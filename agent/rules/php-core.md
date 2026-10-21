# PHP codex: core rules

Always loaded. IDs match headings in the human codex (README.md of php-dev-codex). Cite IDs in reviews.
Stack: PHP 8.3, Symfony 6.4. Do not use PHP 8.4+ syntax (property hooks, asymmetric visibility, `new` without parentheses).

## Types and comparisons

- **TC1 Enable strict typing**: `declare(strict_types=1);` is the first statement of every PHP file.
- **TC2 Use strict comparison operators**: only `===` / `!==`; cast both operands first if types differ. `in_array(..., strict: true)` always.
- **TC3 Comparison order**: variable on the left, constant on the right: `$value === null`, never `null === $value`.
- **TC4 Compare booleans directly**: `return $valid;` not `return $valid === true;`. Exception: non-bool values (`strpos(...) === false`).
- **TC5 Check things explicitly**: never `empty()`. Use `$value === null`, `$array !== []`, `$string !== ''`, `$object !== null`, `count($countable) > 0`.
- **TC6 Avoid mixed and untyped arrays**: no `mixed`; every `array` gets a PHPDoc shape (`list<T>`, `array<string, T>`, `array{...}`); a method that always throws returns `never`.
- **TC7 Pass static analysis**: code passes PHPStan at the project level (9 by default) with zero errors. Fix the code, never add `@phpstan-ignore` or `/** @var */` casts to silence a real problem.

## Control flow

- **CF1 Prefer match expressions**: `match` over `switch` when branches produce a value. Add `default` unless matching an enum exhaustively. One expression per arm; extract a method if a branch needs statements. Converting `switch` to `match` must keep every side effect.
- **CF2 Avoid assignments in conditions**: assign on its own line, then test. Exception: `while` condition.
- **CF3 Avoid unnecessary nesting**: `if ($a && $b)` instead of nested `if`s.
- **CF4 Keep if statements simple**: value-only `if/else` → ternary or `match`; 3+ single-expression branches → `match (true)`; guard clauses return early; invert when the `if` body is longer than the `else`; never nest deeper than 2 levels, extract a method.

## Variables and parameters

- **VP1 Avoid unnecessary variables**: no `$result = ...; return $result;` and no `$found` flags in loops. Keep a variable only when it names a complex condition.
- **VP2 Don't reuse variables for different states**: each transformation gets a new descriptive name (`$name`, `$fullName`, `$filteredFullName`).
- **VP3 Reduce function parameters**: max 3 parameters; group more into a Model. Exception: constructors with promoted dependencies (split the class above 5).
- **VP4 Use named arguments**: required for multiline calls and for every boolean argument.
- **VP5 Name numbers and complex regexes**: magic numbers and regexes become `self::` constants (`GRAMS_IN_KILOGRAM`, `E164_PHONE_PATTERN`).
- **VP6 Avoid flag arguments**: no `bool` parameter that switches behaviour; split into two methods or pass an enum. Exception: a boolean that is data (`active: true`) passed as a named argument.
- **VP7 Avoid output arguments**: return results; no `&` references, no "pass a model in to fill it". Exception: built-ins like `preg_match`.
- **VP8 Use null-safe operators**: `$user?->getEmail()`, `$options['limit'] ?? 10` instead of `isset`/ternary/`if` for null. Max two `?->` in a chain.

## Strings and formatting

- **SF1 Use quotes properly**: single quotes by default; double only for `'` inside or escapes like `\n`; never interpolate variables, use `sprintf`. Exception: PSR-3 log messages use `{placeholder}` + context array.
- **SF2 Prefer sprintf for string concatenation**: `sprintf('/v1/%s?%s', $id, $query)` instead of `.` chains.
- **SF3 Add trailing comma**: in every multiline array, argument list, and parameter list.

## Classes and methods

- **CM1 Control scope via visibility**: `private` by default, `protected` only for subclass access, `public` only for external callers.
- **CM2 Write small and understandable methods**: max 50 lines per method. Exception: declarative config (schemas, form layouts).
- **CM3 Use immutable dates**: `DateTimeImmutable` only, never `DateTime`.
- **CM4 Use readonly classes and properties**: services and value objects are `readonly`. Exception: classes with internal mutable state (cache, memoization) and Models filled from outside (readonly props can only be set inside the class).
- **CM5 Make classes final**: `final` on every class except `Abstract*` bases, Doctrine entities, and classes a framework subclasses.
- **CM6 Use constructor property promotion**: dependencies and fields as promoted constructor properties; no separate declaration + assignment.
- **CM7 Order class members**: constants → properties → constructor → public → protected → private; callee directly below its first caller.
- **CM8 Separate commands from queries**: a method either mutates and returns `void`, or returns a value with no side effects. Exception: `create()` returns the created entity (in `Manager::create()` and in the composite service method that delegates to it); never return a nullable id or bool as a success signal.
- **CM9 Inject the clock**: never `new DateTimeImmutable()`, `time()`, `date()` in services; inject `Psr\Clock\ClockInterface` and use `$this->clock->now()`. Exception: timestamps set inside Doctrine lifecycle callbacks in the entity, display-only console output. A `createdAt` set on a model in a service uses the clock.
- **CM10 Use attributes instead of annotations**: `#[Route]`, `#[ORM\Column]`, `#[Assert\NotBlank]`; never `@Route` / `@ORM` docblocks.
- **CM11 Use value objects for validated primitives**: email, money, percentage, formatted IDs are `final readonly` classes validating in the constructor; pass the object through Domain and Application. Value objects are not Models: immutable, no setters, no business logic beyond validation.

## Enums

- **EN1 Use backed enums for persisted values**: anything stored, sent, or configured is `enum XEnum: string` (int only when numeric by nature). Never string constants for closed sets.
- **EN2 Convert enums at the boundaries**: `XEnum::tryFrom($raw)` in Presentation, `null` = validation error; inside the app pass the enum. `match` on an enum is exhaustive, no `default`.

## Exceptions

- **EX1 Throw specific exceptions**: never `throw new Exception`; domain exceptions extend the fitting SPL exception (may be empty), standard errors use SPL directly (`InvalidArgumentException`).
- **EX2 Catch specific exceptions**: never catch `Exception` / `Throwable`. Exception: wrapping vendor code that throws the base class.
- **EX3 Extract try catch blocks**: `try` body and `catch` body are each a single statement: one method call (an assignment of one call counts), one `return` of a fallback, or one `throw` of a wrapping exception.
- **EX4 Create a base exception per domain**: `abstract class ImportException extends RuntimeException`; every exception of that domain extends it.
- **EX5 Never swallow exceptions**: every `catch` rethrows (wrapped, with `previous`), logs at `error` with the exception in context, or returns a fallback that is part of the method's contract (`?T` on a `find*`, an empty collection). No empty catch; no `return null` from a method whose name promises a result.
- **EX6 Write exception messages consistently**: full sentence, capitalised, ends with a period, value in quotes: `sprintf('Product "%d" was not found.', $id)`. Structured data goes through constructor args/getters too.

## Comments

- **CO1 Eliminate redundant PHPDoc**: no `@param`/`@return` that repeats the native type.
- **CO2 Document array element types with PHPDoc**: `@return Collection<int, Product>`, `@param list<int> $ids`, `array{id: int}` shapes.
- **CO3 Follow commenting standards**: `/** */` for class/method/property docs and inline `/** @var */` for untyped vendor values; `//` for single lines; never `/* */` or `#`; never commented-out code.
- **CO4 Refactor instead of explaining**: a comment that explains *how* becomes a well-named method (`isProductManager($user)`).
- **CO5 Don't commit TODO comments**: no `TODO`/`FIXME`/`XXX`; do it now or open an issue.
- **CO6 Remove dead code**: delete unused classes, methods, parameters, properties in the same change; no "keep in case", no never-enabled flags.
