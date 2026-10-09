<?php

declare(strict_types=1);

/**
 * Errorlog viewer — log parsing & classification.
 *
 * Pure functions: no output, no globals, no side effects. tool.php feeds raw
 * log lines in, gets normalised records back, and twig/tool.twig renders them.
 *
 * Record shape (one associative array per entry):
 *   ts      string  ISO-8601 timestamp as written to the log
 *   unix    int     ts as a Unix timestamp (0 if unparseable)
 *   type    string  raw PHP severity ("Warning", "User Deprecated", "Exception", …)
 *   file    string  file that raised the error
 *   line    string  line in `file`
 *   from    string  calling file ("" for exceptions / visitor lines)
 *   fline   string  line in `from`
 *   call    string  class->method / function context ("" when unknown)
 *   msg     string  the error message
 * After collapse() each record additionally carries:
 *   count   int     number of consecutive identical entries folded together
 *   line_lo string  lowest source line in the group
 *   line_hi string  highest source line in the group
 *
 * @author  Ruud Eisinga (original log format) · Christian M. Stefan (parser)
 * @license GNU GPL2
 */
final class ErrorlogParser
{
    /** Line types written by initialize.php that never carry file/line info. */
    private const TS_RE =
        '/^(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}[+\-]\d{2}:\d{2})\s+\[([^\]]+)\]\s?(.*)$/s';

    /**
     * Parse the raw lines returned by file() into normalised records.
     *
     * Handles the three shapes initialize.php writes:
     *   <ts> [<Type>] <file>:[<line>]  from <file2>:[<line2>] <call> "<message>"
     *   <ts> [Exception] … <message> in line (<n>) of <file>
     *   <ts> [Visitor Request] <url>          — dropped (context marker, not an error)
     * A line without a timestamp prefix is a continuation of the previous
     * message (multi-line errors / stack traces) and is appended to it.
     *
     * @param  list<string> $lines
     * @return list<array<string,mixed>>
     */
    public static function parse(array $lines): array
    {
        $out = [];

        foreach ($lines as $raw) {
            $raw = rtrim($raw, "\r\n");
            if ($raw === '') {
                continue;
            }

            if (!preg_match(self::TS_RE, $raw, $m)) {
                if ($out) {
                    $out[count($out) - 1]['msg'] .= "\n" . $raw;
                }
                continue;
            }

            $type = trim($m[2]);
            $rest = trim($m[3]);
            if ($type === 'Visitor Request') {
                continue;
            }

            $rec = [
                'ts'    => $m[1],
                'unix'  => strtotime($m[1]) ?: 0,
                'type'  => $type,
                'file'  => '',
                'line'  => '',
                'from'  => '',
                'fline' => '',
                'call'  => '',
                'msg'   => $rest,
            ];

            if ($type === 'Exception') {
                $rec['msg'] = preg_replace('/^There was an unknown exception:\s*/', '', $rest);
                // Greedy: anchor on the LAST " in line (N) of FILE" so a message
                // that itself mentions "on line X" does not throw the match off.
                if (preg_match('/^(.*) in line \((\d+)\) of (\S.*)$/s', $rec['msg'], $x)) {
                    $rec['msg']  = trim($x[1]);
                    $rec['line'] = $x[2];
                    $rec['file'] = trim($x[3]);
                }
            } elseif (preg_match('#^(.*?):\[([^\]]*)\]\s+from\s+(.*?):\[([^\]]*)\]\s*(.*)$#s', $rest, $n)) {
                $rec['file']  = trim($n[1]);
                $rec['line']  = trim($n[2]);
                $rec['from']  = trim($n[3]);
                $rec['fline'] = trim($n[4]);
                $tail = trim($n[5]);
                if (preg_match('/^(.*?)\s*"(.*)"\s*$/s', $tail, $q)) {
                    $rec['call'] = trim($q[1]);
                    $rec['msg']  = $q[2];
                } else {
                    $rec['msg'] = $tail;
                }
            }

            $out[] = $rec;
        }

        return $out;
    }

    /**
     * Collapse runs of the "same" consecutive error into one row with a count.
     *
     * Same = identical type + message + file + from-file + call. The source line
     * number is deliberately NOT part of the key (the same bug hit in a loop
     * logs one line per iteration); a collapsed group that spans several lines
     * keeps its lowest/highest line in line_lo / line_hi.
     *
     * @param  list<array<string,mixed>> $recs
     * @return list<array<string,mixed>>
     */
    public static function collapse(array $recs): array
    {
        $res = [];
        foreach ($recs as $r) {
            $r['count']   = 1;
            $r['line_lo'] = $r['line'];
            $r['line_hi'] = $r['line'];

            $i = count($res) - 1;
            if ($i >= 0
                && $res[$i]['type'] === $r['type'] && $res[$i]['msg']  === $r['msg']
                && $res[$i]['file'] === $r['file'] && $res[$i]['from'] === $r['from']
                && $res[$i]['call'] === $r['call']
            ) {
                $res[$i]['count']++;
                $res[$i]['ts']   = $r['ts'];      // keep the most recent occurrence
                $res[$i]['unix'] = $r['unix'];
                if ($r['line'] !== '' && ctype_digit((string) $r['line'])) {
                    $lo = $res[$i]['line_lo'];
                    $hi = $res[$i]['line_hi'];
                    $res[$i]['line_lo'] = ($lo === '' || (int) $r['line'] < (int) $lo) ? $r['line'] : $lo;
                    $res[$i]['line_hi'] = ($hi === '' || (int) $r['line'] > (int) $hi) ? $r['line'] : $hi;
                }
            } else {
                $res[] = $r;
            }
        }
        return $res;
    }

    /**
     * Short badge label + modifier class for a record.
     *
     * PDO- and SQL-related messages win over the plain PHP severity:
     *   - PDO  → a PDO_CANONICAL_DEBUG nudge ("Database::foo() is deprecated …"),
     *            raised only by framework/Database.php's legacy shims.
     *   - SQL  → a genuine DB-layer error (SQLSTATE, PDO/mysqli, driver text).
     *
     * @return array{0:string,1:string}  [label, modifier]
     */
    public static function badge(array $rec): array
    {
        $type = (string) $rec['type'];
        $lc   = strtolower($type);
        $msg  = (string) $rec['msg'];

        if (str_contains($lc, 'deprecated')
            && preg_match('/^Database::\w+\(\) is deprecated/', $msg)
        ) {
            return ['PDO', 'pdo'];
        }
        if (str_contains($lc, 'deprecated')) {
            return ['Deprecated', 'deprecated'];
        }

        if (preg_match(
            '/SQLSTATE|SQL Query Error|\| SQL:|PDOException|mysqli|SQLite3|'
            . "doesn't exist for query|error in your SQL syntax/i",
            $msg
        )) {
            return ['SQL', 'sql'];
        }

        if (str_contains($lc, 'notice'))  { return ['Notice', 'notice']; }
        if (str_contains($lc, 'warning')) { return ['Warning', 'warning']; }
        if ($type === 'Exception')        { return ['Fatal', 'fatal']; }
        if (str_contains($lc, 'error') || str_contains($lc, 'parse')) {
            return [str_contains($lc, 'recoverable') ? 'Error' : $type, 'fatal'];
        }
        return [$type !== '' ? $type : '?', 'muted'];
    }

    /**
     * Primary-line label for a (possibly collapsed) record: a single number, a
     * "lo–hi" range for a multi-line group, or "" when the line is unknown.
     */
    public static function lineLabel(array $rec): string
    {
        if (($rec['count'] ?? 1) > 1
            && $rec['line_lo'] !== '' && $rec['line_lo'] !== $rec['line_hi']
        ) {
            return $rec['line_lo'] . '–' . $rec['line_hi'];
        }
        return (string) $rec['line'];
    }

    /**
     * CSS class for one raw line in the plain / colour view.
     * Last match wins (a "User Deprecated" line is "deprecated", not "error").
     */
    public static function plainClass(string $line): string
    {
        $map = [
            'Notice]'          => 'lognotice',
            'Warning]'         => 'logwarning',
            'Error]'           => 'logerror',
            'PHP Parse error'  => 'logerror',
            'Exception]'       => 'logerror',
            'Deprecated]'      => 'logdeprecated',
            'Visitor Request]' => 'usedurl',
        ];
        $cls = '';
        foreach ($map as $needle => $c) {
            if (str_contains($line, $needle)) {
                $cls = $c;
            }
        }
        return $cls;
    }

    /**
     * Build the plain/colour view row list from raw lines.
     *
     * @param  list<string> $lines
     * @param  int          $since  Unix time of the viewer's last visit
     * @return list<array{cls:string,text:string}>
     */
    public static function plainRows(array $lines, int $since): array
    {
        $rows = [];
        $zebra = 'even';
        foreach ($lines as $line) {
            $line  = rtrim($line, "\r\n");
            $zebra = $zebra === 'odd' ? 'even' : 'odd';
            $when  = strtotime(str_replace(['[', ']'], '', substr($line, 0, 26)));
            $state = ($when !== false && $when > $since) ? 'newline' : $zebra;
            $type  = self::plainClass($line);
            $rows[] = [
                'cls'  => trim($state . ' ' . $type),
                'text' => $line,
            ];
        }
        return $rows;
    }

    /**
     * Full table-view dataset: parsed, collapsed, and decorated with the badge,
     * the "new since last visit" flag and the line label — everything the
     * template needs without any logic of its own.
     *
     * @param  list<string> $lines
     * @param  int          $since  Unix time of the viewer's last visit
     * @return list<array<string,mixed>>
     */
    public static function tableRows(array $lines, int $since): array
    {
        $rows = self::collapse(self::parse($lines));
        foreach ($rows as &$r) {
            [$label, $mod] = self::badge($r);
            $r['badge_label'] = $label;
            $r['badge_mod']   = $mod;
            $r['is_new']      = $r['unix'] > $since;
            $r['line_label']  = self::lineLabel($r);
            $r['exact']       = $r['unix'] ? date('Y-m-d H:i:s', $r['unix']) : '';
            // call context, normalised to always read as a call
            if ($r['call'] !== '' && !str_contains($r['call'], '(')) {
                $r['call'] .= '()';
            }
        }
        unset($r);
        return $rows;
    }
}
