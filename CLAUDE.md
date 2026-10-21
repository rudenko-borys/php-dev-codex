# PHP developer codex

This repository is a coding style guide, not an application. Two deliverables must stay in sync:

- `README.md`: the human codex. Every rule follows `RULE_FORMAT.md` exactly (heading, `<!-- rule: ID -->`,
  description, optional example, `> **Why?**`).
- `agent/rules/*.md`: the Claude Code version, one line per rule with the same ID. `agent/README.md` explains the
  layout and the writing style for agent rules.

## Working on rules

- Read `RULE_FORMAT.md` before adding or editing a rule.
- A new rule needs: README entry with the next free ID in its section, table-of-contents line, and a matching
  `- **ID Heading**: ...` line in the right `agent/rules/` file.
- Never renumber or reuse IDs.
- Examples must be behaviour-preserving: the Right side keeps every side effect of the Wrong side.
- Target stack for all examples: PHP 8.5, Symfony 8.1 (7.4 LTS equivalent), PHPUnit 13, PHPStan 2. Examples use
  current syntax (`new Foo()->bar()`, `private(set)`, `array_find()`), never workarounds for older PHP.

## Verify before finishing

```bash
php bin/check-sync.php
```

Must print `OK`. It checks IDs, duplicates, the table of contents, and README ↔ agent parity.

## Not part of the repo

`BOOK.md` and the Clean Code PDF are local working notes (untracked, PDF is gitignored). Don't commit them.
