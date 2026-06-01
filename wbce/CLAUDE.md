# Claude Project Instructions — WBCE CMS

## Markdown Tables

Always write Markdown tables with **padded, aligned columns** so they are
readable in plain text without a Markdown renderer.

Rules:
- Every column width is determined by its longest cell (including header).
- Add **one space** of padding on each side of every cell.
- The separator row uses dashes that exactly match the column width.
- All rows in the same column must have identical total character width.

**Do this:**

```
| Variable           | Example value              | Description                         |
|--------------------|----------------------------|-------------------------------------|
| `$fp_locale_key`   | `'de'`, `'default'`        | Locale key for the active language  |
| `$fp_use_time`     | `true` / `false`           | Whether time picking is enabled     |
```

**Not this:**

```
| Variable | Example value | Description |
|---|---|---|
| `$fp_locale_key` | `'de'`, `'default'` | Locale key for the active language |
```

This rule applies to every `.md` file written or edited in this project.
