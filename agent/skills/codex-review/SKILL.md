---
name: codex-review
description: Use when asked to review PHP changes, a diff, a branch, or a pull request against the project coding rules (the PHP developer codex), or before opening a PR in a project that uses the codex.
---

# Codex review

Review changed PHP code against the codex rules in `.claude/rules/` and report violations by rule ID.

## Procedure

1. Collect the scope: `git diff --name-only <base>...HEAD` (or the files the user named). Review only changed files;
   mention pre-existing problems in touched lines only.
2. Read every changed file in full. Read every file in `.claude/rules/` directly, including the path-scoped ones
   (`tests.md`, `presentation.md`); they do not load by themselves unless you open a matching file.
3. Walk each file against the checklist below. For every violation record `path:line`, rule ID, what is wrong, and
   the concrete fix.
4. Run what the project provides and include failures verbatim: `vendor/bin/phpstan analyse`, `vendor/bin/php-cs-fixer
   fix --dry-run --diff`, `vendor/bin/phpunit` for the touched test classes. If a tool is not installed, write
   `not run` for it in the Tools line and mark any static-analysis finding as unverified; never claim it is clean.
5. Report in the format below. No praise, no restating the diff. If nothing is wrong, say so in one line.

## Checklist (highest-impact first)

| Area | Look for | Rules |
|------|----------|-------|
| Layers | `use` of Infrastructure from Application/Presentation; vendor SDK outside Infrastructure | AD1, AD9 |
| Repositories/managers | `get*`/`save` in a repository; `null` returned from `Manager::get()` | MN2, AD5 |
| Models | default values, `= null`, methods, static factories on a Model | AD3, AD4 |
| Services | logic in composite service; service of entity A injecting service of entity B | AD6, AD7 |
| Types | missing `strict_types`, `mixed`, bare `array`, `empty()`, `==` | TC1, TC5, TC6, TC2 |
| Classes | non-final class, non-readonly service, missing promotion, member order | CM5, CM4, CM6, CM7 |
| Methods | > 50 lines, > 3 params, bool flag param, output param, command that returns data | CM2, VP3, VP6, VP7, CM8 |
| Control flow | `switch`, assignment in `if`, nesting > 2, `if/else` returning values | CF1, CF2, CF4 |
| Exceptions | `throw new Exception`, `catch (Exception)`, empty catch, message without the value | EX1, EX2, EX5, EX6 |
| Time | `new DateTimeImmutable()`/`time()` in a service; `DateTime` | CM9, CM3 |
| Enums | string constants for a closed set; `default` arm on an enum match; raw string passed inward | EN1, EN2 |
| Naming | abbreviations, `$userID`, repeated context, `Helper`/`Util`, bool method not `is/has/can` | GN1, GN2, GN3, TN1, MN1 |
| Routes/commands | plural path, verb in path, command not `app:<domain>:<action>` | RN1, SN1 |
| Tests | no test for a behaviour change, scenario missing from name, mocked value object, copied test instead of provider | TS2, TS6, TS5 |
| Comments | redundant PHPDoc, TODO, commented-out code | CO1, CO5, CO3 |
| Strings/SQL | `.` concatenation, interpolated variables, value interpolated into a query | SF2, SF1, AD11 |

## Report format

```
## Codex review: <branch or PR>

Tools: phpstan <clean | N errors>, php-cs-fixer <clean | N files>, phpunit <passed | N failed>

### Must fix
- src/Application/Gps/GpsService.php:12 — AD1: imports `Infrastructure\Gps\Clickhouse\GpsRepository`. Inject `Domain\Gps\Repository\GpsRepositoryInterface` instead.
- src/Domain/Gps/Model/GpsPointModel.php:9 — AD3: `public ?string $note = null`. Remove the default; let the filler set it.

### Should fix
- tests/Domain/Gps/Service/GpsServiceTest.php:31 — TS6: `createMock(GpsPointModel::class)`. Use `new GpsPointModel()`.

### Notes
- <pre-existing issue in a touched line, or a judgement call the author should confirm>
```

Severity: **Must fix** = any `AD*` rule, `TC7`, `AD11`, `EX5`, and anything that changes behaviour incorrectly
(`==` on non-booleans, `catch (Exception)` hiding errors, a missing `default` arm). **Should fix** = naming,
structure, style, and everything else. Never report formatting that php-cs-fixer fixes automatically.

## Common mistakes

- Reviewing the whole repository instead of the diff.
- Citing a rule without the `path:line` and the concrete replacement.
- Flagging a documented `Exception:` (constructor with 5 dependencies, `preg_match` output argument, log placeholders) as a violation. Read the rule line fully before reporting.
- Skipping the tool run and guessing that PHPStan is clean.
