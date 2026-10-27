# How to write a rule

This guide defines the canonical format for every rule in the [README](README.md) and how it maps to the
[agent version](agent/README.md).

---

## Anatomy of a rule

Every rule is built from the same parts, always in this order:

```
### <Heading>            ← required
<!-- rule: XX1 -->       ← required (stable ID)

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

### 2. Rule ID (required)

- The line directly below the heading is an HTML comment `<!-- rule: XX# -->`. It is invisible when rendered.
- `XX` is the section prefix, `#` is the next free number in that section. Prefixes:

  | Section                  | Prefix | Section                | Prefix |
  |--------------------------|--------|------------------------|--------|
  | Types and comparisons    | `TC`   | General naming         | `GN`   |
  | Control flow             | `CF`   | Type naming            | `TN`   |
  | Variables and parameters | `VP`   | Method and symbol naming | `MN` |
  | Strings and formatting   | `SF`   | Routing naming         | `RN`   |
  | Classes and methods      | `CM`   | Symfony naming         | `SN`   |
  | Enums                    | `EN`   | ADRs                   | `AD`   |
  | Exceptions               | `EX`   | Testing                | `TS`   |
  | Comments                 | `CO`   | Git                    | `GT`   |

- IDs are **never reused or renumbered**. Renaming or moving a rule keeps its ID; deleting a rule retires its ID.
- The same ID must appear in exactly one file under `agent/rules/`. `php bin/check-sync.php` verifies this.

### 3. Description (required)

- One to three sentences, present tense, **prescriptive**: state what to do, not a discussion.
- Open with a directive verb: `Always…`, `Never…`, `Don't…`, `Use…`, `Prefer…`, `Avoid…`.
- Name the exact construct in `inline code` (`===`, `match`, `DateTimeImmutable`).
- Prefer a measurable threshold over an adjective: `max 3 parameters`, not `few parameters`.
- Pair every prohibition with the replacement: "Don't X; do Y instead."

### 4. List / Exception (optional)

- Use a bullet or numbered list when the rule has several distinct parts (see `Follow commenting standards`,
  `Route path naming`).
- Use a bold **`Exception:`** inline note for carve-outs (see `Compare booleans directly`, `Catch specific exceptions`).
  Every carve-out must be written down; readers (and agents) don't infer exceptions.
- Reserve a bold **`Background`** block for deep rationale, and only in the ADRs section.

### 5. Wrong/Right example (optional but expected)

Pick the variant that fits the content:

- **Short, one-line snippets → Markdown table.** Header is always `| ❌ Wrong: | ✅ Right: |`.
- **Multi-line or full code → HTML `<table>`** with `❌ Wrong` / `✅ Right` headers and fenced
  ```` ```php ```` blocks inside each `<td>`.
- **A single illustrative snippet with no contrast → a plain fenced code block** (see `Document array element types
  with PHPDoc`).
- Some rules need no example (`Control scope via visibility`, `Write small and understandable methods`). That is fine —
  the example is the only optional-by-default part.
- Never use bare `❌ Wrong:` / `✅ Right:` paragraphs outside a table.
- The Right example must preserve every behaviour of the Wrong example. Readers copy examples more literally than
  prose; an example that silently drops a side effect teaches the wrong refactoring.

### 6. Why blockquote (required)

- Always close the rule with `> **Why?** …`.
- One short paragraph stating the benefit or the bug it prevents. Wrap long lines with a leading `> `.
- No jokes or quotes in place of a reason: the first sentence of the Why is what the agent version keeps.

---

## Conventions

- Wrap prose at ~120 columns.
- All code fences are ` ```php ` unless showing a directory tree or shell.
- Inline code (backticks) for any identifier, operator, keyword, or path.
- Emojis are exactly `❌` (wrong) and `✅` (right) — never swap or restyle them.
- Keep examples minimal: show only the lines that demonstrate the rule, use `// ...` for elided code.
- Link between rules with their anchor: `[Composite service](#composite-service)`.

---

## Keeping the agent version in sync

The human README is the source of truth. The agent version under `agent/rules/` is hand-written, one line per rule,
keyed by the same ID. When you add, change, or remove a rule:

1. Edit the README rule.
2. Edit the matching `- **XX# Heading**` line in the right file under `agent/rules/`.
3. Run `php bin/check-sync.php`. It fails when an ID is missing on either side, duplicated, or when the table of
   contents does not match the headings.

---

## Copy-paste templates

### Short snippets (Markdown table)

```markdown
### <Imperative heading>
<!-- rule: XX# -->

<One- to three-sentence prescriptive description.>

| ❌ Wrong:        | ✅ Right:                  |
|-----------------|---------------------------|
| `bad_example()` | `good_example()`          |

> **Why?** <Benefit or bug prevented.>
```

### Multi-line code (HTML table)

~~~markdown
### <Imperative heading>
<!-- rule: XX# -->

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
- [ ] `<!-- rule: XX# -->` on the next line, new number in the section
- [ ] Added to the Table of contents
- [ ] Prescriptive description starting with a directive verb, thresholds as numbers
- [ ] `Exception:` / list used only when needed, every carve-out written down
- [ ] Wrong/Right example in the correct variant (Markdown vs HTML table), behaviour-preserving, identifiers in
      `inline code`
- [ ] Closes with a `> **Why?**` blockquote whose first sentence stands alone
- [ ] Lines wrapped ~120 cols, `❌` / `✅` used correctly
- [ ] Matching line added to `agent/rules/*.md`, `php bin/check-sync.php` passes
