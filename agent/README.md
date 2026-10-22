# PHP codex for Claude Code

The agent version of the [PHP developer codex](../README.md): the same rules, rewritten for an AI coding agent.
One line per rule, keyed by the same IDs, split into files that Claude Code loads always or only when it touches
matching paths.

## What is here

| Path | Purpose | Loaded |
|------|---------|--------|
| `CLAUDE.md` | Template for a project's root `CLAUDE.md`: stack, pointer to the rules, five hard constraints, finish checklist | every session |
| `rules/php-core.md` | Types, control flow, parameters, strings, classes, enums, exceptions, comments | every session |
| `rules/naming.md` | General, type, method, event naming | every session |
| `rules/architecture.md` | Layers, directory tree, models, repositories, services, DI | every session |
| `rules/git.md` | Branch, commit, PR conventions | every session |
| `rules/presentation.md` | Routes, commands, handlers, boundary validation | when editing `Presentation/`, controllers, commands |
| `rules/tests.md` | Test structure, naming, doubles, fixtures | when editing `tests/` |
| `skills/codex-review/SKILL.md` | `/codex-review`: review a diff against the rules, report by rule ID | on demand |

Always-on files total about 140 lines; the docs recommend keeping `CLAUDE.md` under 200 lines and moving
everything else into `.claude/rules/` and skills, which is what this layout does.

## Install into a project

```bash
CODEX=/path/to/php-dev-codex          # this repository
PROJECT=/path/to/your-project

mkdir -p "$PROJECT/.claude/rules" "$PROJECT/.claude/skills"
cp "$CODEX"/agent/rules/*.md            "$PROJECT/.claude/rules/"
cp -R "$CODEX"/agent/skills/codex-review "$PROJECT/.claude/skills/"
cp "$CODEX"/agent/CLAUDE.md             "$PROJECT/CLAUDE.md"      # then fill in the placeholders
```

Commit all of it.

To stay on the latest codex instead of copying, add this repository as a git submodule and symlink:

```bash
git submodule add git@github.com:rudenko-borys/php-dev-codex.git tools/codex
ln -s ../../tools/codex/agent/rules .claude/rules/codex
```

Claude Code follows symlinks inside `.claude/rules/`. Keep project-specific rules in separate files next to the
symlink (`.claude/rules/project.md`).

## Adjust per project

- `CLAUDE.md`: replace every `<placeholder>`, keep the "Before finishing a task" list, add only what Claude cannot
  read from the code (env vars, running services, gotchas).
- `rules/presentation.md` and `rules/tests.md`: edit the `paths:` globs if the project layout differs.
- PHPStan level: the rules say "project level, `max` for new projects"; set the real one in `phpstan.neon` and run
  it in the project's quality gate (CI, pre-commit, or your agent framework's gates).
- Drop a rule file you don't need (a project without Messenger can delete `SN2` lines); run
  `php bin/check-sync.php` in this repository only, not in the project.

## How the agent version is written

- **One line per rule**, `- **ID Heading**: instruction.` The ID lets reviews cite the human codex.
- **Numbers over adjectives**: "max 3 parameters", "level max", "never nest deeper than 2".
- **Every carve-out inline** as `Exception:`; agents don't infer exceptions.
- **Prohibition + replacement**: "never `empty()`; use `$value === null`".
- **No Why paragraphs, no TOC, no HTML tables**: each of those costs tokens on every turn and adds nothing an agent
  acts on. The first sentence of the human `Why` is kept only where it changes behaviour in unseen cases.
- **Enforce with tools where possible**: php-cs-fixer, PHPStan, and tests run in the project's quality gate; the
  prose covers only what tools can't check (naming semantics, architecture, test design).
- **Emphasis is rare**: a single `IMPORTANT` line is noticed, ten are ignored. The template keeps only the five
  most-violated constraints in `CLAUDE.md`.

## Keep in sync

The README is the source of truth. After changing a rule there, change the matching line here and run:

```bash
php bin/check-sync.php
```

It fails when an ID exists on one side only, appears twice, or the README table of contents is stale.
