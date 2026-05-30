<?php
/**
 * AltchaLib — ALTCHA SHA-256 v1 proof-of-work for WBCE.
 *
 * Implements the original ALTCHA v1 protocol (SHA-256 hash matching).
 * Compatible with altcha widget v0.x through v3.x (all versions).
 *
 * No Composer, no extensions beyond standard PHP (hash, hash_hmac,
 * hash_equals, random_bytes, random_int — all built-in since PHP 5.6).
 *
 * How it works
 * ────────────
 * Server (createChallenge):
 *   salt      = random hex + ?expires=TIMESTAMP
 *   number    = random integer in [1 .. maxNumber]   (the secret answer)
 *   challenge = sha256(salt . number)
 *   signature = hmac-sha256(challenge, hmacKey)
 *   → return JSON {algorithm, challenge, maxNumber, salt, signature}
 *
 * Widget (client-side, altcha.min.js):
 *   for n = 0 .. maxNumber:
 *     if sha256(salt . n) == challenge → found, submit n
 *   → POST field 'altcha' = base64(JSON {algorithm, challenge, number, salt, signature, took})
 *
 * Server (verifySolution):
 *   1. base64-decode + json-decode the payload
 *   2. check expiry in salt
 *   3. verify hmac(challenge, hmacKey) == signature  (proves our server made this challenge)
 *   4. verify sha256(salt . number) == challenge     (proves the client solved it)
 *
 * Fully stateless — no session, no DB needed.
 *
 * @license MIT
 */
class AltchaLib
{
    /** @var string HMAC secret — server-side only */
    private string $hmacKey;

    /**
     * Upper bound for the random answer.
     * Widget tries 0 … maxNumber sequentially; avg solve time ≈ (maxNumber/2) hashes.
     * SHA-256 is fast (~0.01 ms/hash in browser), so 50 000 ≈ 0.25 s.
     *
     *   Low-risk forms (contact, newsletter):  20 000 – 50 000
     *   Login / registration:                  50 000 – 100 000
     *   API endpoints under abuse:            100 000 – 200 000
     */
    private int $maxNumber;

    /** Seconds before a challenge expires (default 10 min) */
    private int $ttl;

    public function __construct(
        string $hmacKey,
        int    $maxNumber = 50000,
        int    $ttl       = 600
    ) {
        $this->hmacKey   = $hmacKey;
        $this->maxNumber = $maxNumber;
        $this->ttl       = $ttl;
    }


    // ── Public API ────────────────────────────────────────────────────────────

    /**
     * Generate a challenge. json_encode() the return value and send it.
     *
     * @return array  {algorithm, challenge, maxNumber, salt, signature}
     */
    public function createChallenge(): array
    {
        $nonce     = bin2hex(random_bytes(12));
        $expires   = time() + $this->ttl;
        $salt      = $nonce . '?expires=' . $expires;
        $number    = random_int(1, $this->maxNumber);
        $challenge = hash('sha256', $salt . $number);
        $signature = hash_hmac('sha256', $challenge, $this->hmacKey);

        return [
            'algorithm' => 'SHA-256',
            'challenge' => $challenge,
            'maxNumber' => $this->maxNumber,
            'salt'      => $salt,
            'signature' => $signature,
        ];
    }

    /**
     * Verify a solution submitted by the widget.
     *
     * @param  string $payload  Raw value of $_POST['altcha'] — base64-encoded JSON.
     * @return bool
     */
    public function verifySolution(string $payload): bool
    {
        if (empty($payload)) {
            return false;
        }

        $json = base64_decode($payload, true);
        if ($json === false) {
            return false;
        }

        $data = json_decode($json, true);
        if (!is_array($data)) {
            return false;
        }

        // Required fields
        foreach (['algorithm', 'challenge', 'number', 'salt', 'signature'] as $f) {
            if (!isset($data[$f])) {
                return false;
            }
        }

        if ($data['algorithm'] !== 'SHA-256') {
            return false;
        }

        // Expiry — embedded as ?expires=N in the salt
        if (preg_match('/[?&]expires=(\d+)/', (string) $data['salt'], $m)) {
            if ((int) $m[1] < time()) {
                return false;
            }
        }

        // HMAC — proves the challenge was issued by this server
        $expectedSig = hash_hmac('sha256', (string) $data['challenge'], $this->hmacKey);
        if (!hash_equals($expectedSig, (string) $data['signature'])) {
            return false;
        }

        // PoW — proves the client solved it
        $expected = hash('sha256', (string) $data['salt'] . (int) $data['number']);
        return hash_equals($expected, (string) $data['challenge']);
    }

    /**
     * Generate a secure HMAC key.
     * Call once on module install and store in the settings table.
     *
     * @return string  64-character hex string.
     */
    public static function generateHmacKey(): string
    {
        return bin2hex(random_bytes(32));
    }
}
