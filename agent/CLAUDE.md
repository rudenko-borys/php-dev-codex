# <Project name>

<!-- Template from php-dev-codex/agent/CLAUDE.md: copy to the root of a PHP project and replace the placeholders. Add the project's own commands and environment notes below; keep the whole file under 200 lines. -->

<One sentence: what this application does.>

## Stack

- PHP 8.5, Symfony 7.4 LTS, Doctrine ORM, PHPUnit 13, PHPStan 2 at level `max`.
- Use current PHP syntax: `private(set)`, `new Foo()->bar()`, `array_find()`/`array_any()`/`array_all()`,
  `clone($obj, [...])`. Don't write PHP 8.3-era workarounds for them.

## Code rules

Coding rules live in `.claude/rules/` (PHP developer codex). They apply to every PHP file you write or change.
When you review code or explain a change, cite the rule ID (`CM5`, `AD1`).

Hard constraints, repeated here because they are violated most often:

- `declare(strict_types=1);` first in every file; `final` classes; promoted `readonly` constructor properties (TC1, CM5, CM6).
- Dependencies point inwards: Presentation → Application → Domain ← Infrastructure. Application never imports Infrastructure (AD1).
- Repositories only `find*`/`count*`/`sum*`; `Manager::get()` throws not-found (MN2, AD5).
- No `empty()`, no `mixed`, no bare `array` without a PHPDoc shape (TC5, TC6).
- Models are plain DTOs: no defaults, no logic (AD3, AD4).

## Before finishing a task

1. Run the project's quality gate (code style, PHPStan, tests) and leave it green.
2. Add or update tests for every behaviour change (see `.claude/rules/tests.md`).
3. Check `use` statements of changed files for layer violations (AD1).
4. Commit message: `<ISSUE-ID> <Imperative subject>` (GT2).
