# How to write a rule

This guide defines the canonical format for every rule in the [README](README.md).

---

## Anatomy of a rule

Every rule is built from the same parts, always in this order:

```
### <Heading>            ← required

<Description>            ← required

<List / Exception>       ← optional

<Wrong/Right example>    ← optional

> **Why?** <rationale>   ← required
```

### 1. Heading (required)

- Start with `### ` (H3). Rules are never H2 — H2 is reserved for sections.
- Use a short **imperative** phrase, verb first: `Enable strict typing`, `Throw specific exceptions`,
  `Avoid assignments in conditions`.
- Sentence case: capitalize only the first word and proper nouns (`Use immutable dates`, not `Use Immutable Dates`).
- A **noun phrase** is allowed only when the action is obvious (`Comparison order`, `Class naming`). Prefer the
  imperative when in doubt.
- The heading must produce a unique anchor and be added to the Table of contents.

### 2. Description (required)

- One to three sentences, present tense, **prescriptive**: state what to do, not a discussion.
- Open with a directive verb: `Always…`, `Never…`, `Don't…`, `Use…`, `Prefer…`, `Avoid…`.
- Name the exact construct in `inline code` (`===`, `match`, `DateTimeImmutable`).

### 3. List / Exception (optional)

- Use a bullet or numbered list when the rule has several distinct parts (see `Follow commenting standards`,
  `Route path naming`).
- Use a bold **`Exception:`** inline note for carve-outs (see `Compare booleans directly`, `Catch specific exceptions`).
- Reserve a bold **`Background`** block for deep rationale, and only in the ADRs section.

### 4. Wrong/Right example (optional but expected)

Pick the variant that fits the content:

- **Short, one-line snippets → Markdown table.** Header is always `| ❌ Wrong: | ✅ Right: |`.
- **Multi-line or full code → HTML `<table>`** with `❌ Wrong` / `✅ Right` headers and fenced
  ```` ```php ```` blocks inside each `<td>`.
- **A single illustrative snippet with no contrast → a plain fenced code block** (see `Document array element types
  with PHPDoc`).
- Some rules need no example (`Control scope via visibility`, `Write small and understandable methods`). That is fine —
  the example is the only optional-by-default part.

### 5. Why blockquote (required)

- Always close the rule with `> **Why?** …`.
- One short paragraph stating the benefit or the bug it prevents. Wrap long lines with a leading `> `.
- This is the **only mandatory non-heading element**. A rule without a `Why?` is incomplete.

---

## Conventions

- Wrap prose at ~120 columns.
- All code fences are ` ```php ` unless showing a directory tree or shell.
- Inline code (backticks) for any identifier, operator, keyword, or path.
- Emojis are exactly `❌` (wrong) and `✅` (right) — never swap or restyle them.
- Keep examples minimal: show only the lines that demonstrate the rule, use `// ...` for elided code.

---

## Copy-paste templates

### Short snippets (Markdown table)

```markdown
### <Imperative heading>

<One- to three-sentence prescriptive description.>

| ❌ Wrong:        | ✅ Right:                  |
|-----------------|---------------------------|
| `bad_example()` | `good_example()`          |

> **Why?** <Benefit or bug prevented.>
```

### Multi-line code (HTML table)

~~~markdown
### <Imperative heading>

<One- to three-sentence prescriptive description.>

<table>
    <thead>
        <tr>
            <th>❌ Wrong</th>
            <th>✅ Right</th>
        </tr>
    </thead>
    <tbody>
        <tr>
<td>

```php
// wrong code
```

</td>
<td>

```php
// right code
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** <Benefit or bug prevented.>
~~~

---

## Author checklist

- [ ] `### ` heading, imperative, sentence case, unique
- [ ] Added to the Table of contents
- [ ] Prescriptive description starting with a directive verb
- [ ] `Exception:` / list used only when needed
- [ ] Wrong/Right example in the correct variant (Markdown vs HTML table), identifiers in `inline code`
- [ ] Closes with a `> **Why?**` blockquote
- [ ] Lines wrapped ~120 cols, `❌` / `✅` used correctly
