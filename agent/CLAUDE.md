# <Project name>

<!-- Template from php-dev-codex/agent/CLAUDE.md. Replace placeholders, keep it under ~80 lines. HTML comments are stripped before Claude sees the file. -->

<One sentence: what this application does.>

## Stack

- PHP 8.5, Symfony 8.1, Doctrine ORM, PHPUnit 13, PHPStan 2 at level `max`.
- Use current PHP syntax: `private(set)`, property hooks where a rule allows them, `new Foo()->bar()`,
  `array_find()`/`array_any()`/`array_all()`, `clone($obj, [...])`. Don't write PHP 8.3-era workarounds for them.

## Commands

```bash
composer install
vendor/bin/phpunit                                   # all tests
vendor/bin/phpunit --filter <TestClass>              # one test class
vendor/bin/phpstan analyse                           # static analysis, must be clean
vendor/bin/php-cs-fixer fix                          # code style, run before committing
bin/console cache:clear
```

## Code rules

Coding rules live in `.claude/rules/` (PHP developer codex). They apply to every PHP file you write or change.
When you review code or explain a change, cite the rule ID (`CM5`, `AD1`).

Hard constraints, repeated here because they are violated most often:

- `declare(strict_types=1);` first in every file; `final` classes; promoted `readonly` constructor properties (TC1, CM5, CM6).
- Dependencies point inwards: Presentation → Application → Domain ← Infrastructure. Application never imports Infrastructure (AD1).
- Repositories only `find*`/`count*`/`sum*`; `Manager::get()` throws not-found (MN2, AD5).
- No `empty()`, no `mixed`, no bare `array` without a PHPDoc shape (TC5, TC6).
- Models are plain DTOs: no defaults, no logic (AD3, AD4).

## Project specifics

<!-- Only what Claude cannot derive from the code: env vars, services that must be running, gotchas. -->

- <e.g. `DATABASE_URL` points to the dockerised Postgres; run `docker compose up -d` first.>
- <e.g. Fixtures: `bin/console doctrine:fixtures:load --no-interaction`.>

## Before finishing a task

1. `vendor/bin/php-cs-fixer fix` then `vendor/bin/phpstan analyse` — both clean.
2. `vendor/bin/phpunit` — green; add or update tests for every behaviour change (see tests rules).
3. Check `use` statements of changed files for layer violations (AD1).
4. Commit message: `<ISSUE-ID> <Imperative subject>` (GT2).
