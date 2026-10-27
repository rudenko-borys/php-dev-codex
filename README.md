# PHP developer codex 📔

[![PHP](https://img.shields.io/badge/v8.5-blue?logo=php&labelColor=grey&logoColor=white)](https://www.php.net)
[![Symfony](https://img.shields.io/badge/v7.4-blue?logo=symfony&labelColor=grey)](https://symfony.com/doc/7.4/index.html)

<a href="https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md">
  <img src="https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-no-action.svg" alt="Stand With Ukraine" />
</a>

---

## About

A set of opinionated rules and conventions for writing consistent, readable, and maintainable PHP code.

PHP code style extends [PSR-1](https://www.php-fig.org/psr/psr-1) and [PSR-12](https://www.php-fig.org/psr/psr-12),
so code must also follow these standards to be compatible with this codex.

For AI coding agents there is a dedicated version of this codex: see [agent/README.md](agent/README.md). To let
agents and reviews point at a specific rule, every rule also carries a stable ID in an invisible comment under its
heading (`<!-- rule: XX -->`). [RULE_FORMAT](RULE_FORMAT.md) describes how to write a rule.

---

## Table of contents

- [Types and comparisons](#types-and-comparisons)
    * [Enable strict typing](#enable-strict-typing)
    * [Use strict comparison operators](#use-strict-comparison-operators)
    * [Comparison order](#comparison-order)
    * [Compare booleans directly](#compare-booleans-directly)
    * [Check things explicitly](#check-things-explicitly)
    * [Avoid mixed and untyped arrays](#avoid-mixed-and-untyped-arrays)
    * [Pass static analysis](#pass-static-analysis)
- [Control flow](#control-flow)
    * [Prefer match expressions](#prefer-match-expressions)
    * [Avoid assignments in conditions](#avoid-assignments-in-conditions)
    * [Avoid unnecessary nesting](#avoid-unnecessary-nesting)
    * [Keep if statements simple](#keep-if-statements-simple)
- [Variables and parameters](#variables-and-parameters)
    * [Avoid unnecessary variables](#avoid-unnecessary-variables)
    * [Don't reuse variables for different states](#dont-reuse-variables-for-different-states)
    * [Reduce function parameters](#reduce-function-parameters)
    * [Use named arguments](#use-named-arguments)
    * [Name numbers and complex regexes](#name-numbers-and-complex-regexes)
    * [Avoid flag arguments](#avoid-flag-arguments)
    * [Avoid output arguments](#avoid-output-arguments)
    * [Use null-safe operators](#use-null-safe-operators)
    * [Use array search functions](#use-array-search-functions)
- [Strings and formatting](#strings-and-formatting)
    * [Use quotes properly](#use-quotes-properly)
    * [Prefer sprintf for string concatenation](#prefer-sprintf-for-string-concatenation)
    * [Add trailing comma](#add-trailing-comma)
- [Classes and methods](#classes-and-methods)
    * [Control scope via visibility](#control-scope-via-visibility)
    * [Write small and understandable methods](#write-small-and-understandable-methods)
    * [Use immutable dates](#use-immutable-dates)
    * [Use readonly classes and properties](#use-readonly-classes-and-properties)
    * [Make classes final](#make-classes-final)
    * [Use constructor property promotion](#use-constructor-property-promotion)
    * [Order class members](#order-class-members)
    * [Separate commands from queries](#separate-commands-from-queries)
    * [Inject the clock](#inject-the-clock)
    * [Use attributes instead of annotations](#use-attributes-instead-of-annotations)
    * [Chain on new without extra parentheses](#chain-on-new-without-extra-parentheses)
- [Enums](#enums)
    * [Use backed enums for persisted values](#use-backed-enums-for-persisted-values)
    * [Convert enums at the boundaries](#convert-enums-at-the-boundaries)
- [Exceptions](#exceptions)
    * [Throw specific exceptions](#throw-specific-exceptions)
    * [Catch specific exceptions](#catch-specific-exceptions)
    * [Extract try catch blocks](#extract-try-catch-blocks)
    * [Create a base exception per domain](#create-a-base-exception-per-domain)
    * [Never swallow exceptions](#never-swallow-exceptions)
    * [Write exception messages consistently](#write-exception-messages-consistently)
- [Comments](#comments)
    * [Eliminate redundant PHPDoc](#eliminate-redundant-phpdoc)
    * [Document array element types with PHPDoc](#document-array-element-types-with-phpdoc)
    * [Follow commenting standards](#follow-commenting-standards)
    * [Refactor instead of explaining](#refactor-instead-of-explaining)
    * [Don't commit TODO comments](#dont-commit-todo-comments)
    * [Remove dead code](#remove-dead-code)
- [General naming](#general-naming)
    * [Favor full names](#favor-full-names)
    * [Avoid uppercased abbreviations](#avoid-uppercased-abbreviations)
    * [Context duplication in naming](#context-duplication-in-naming)
    * [Avoid double plural constructions](#avoid-double-plural-constructions)
    * [Constant and enum case naming](#constant-and-enum-case-naming)
- [Type naming](#type-naming)
    * [Class naming](#class-naming)
    * [Interface naming](#interface-naming)
    * [Abstract class naming](#abstract-class-naming)
    * [Trait naming](#trait-naming)
    * [Enum naming](#enum-naming)
    * [Property naming](#property-naming)
- [Method and symbol naming](#method-and-symbol-naming)
    * [Method naming](#method-naming)
    * [Repository method naming](#repository-method-naming)
    * [Filler and provider method naming](#filler-and-provider-method-naming)
    * [Event naming](#event-naming)
    * [Event class naming](#event-class-naming)
    * [Event subscriber naming](#event-subscriber-naming)
- [Routing naming](#routing-naming)
    * [Route path naming](#route-path-naming)
    * [HTTP methods for actions](#http-methods-for-actions)
    * [Nest resources one level deep](#nest-resources-one-level-deep)
    * [Filter, sort and paginate through query parameters](#filter-sort-and-paginate-through-query-parameters)
    * [Name non-CRUD actions as sub-resources](#name-non-crud-actions-as-sub-resources)
- [Symfony naming](#symfony-naming)
    * [Console command naming](#console-command-naming)
    * [Message handler naming](#message-handler-naming)
- [ADRs](#adrs)
    * [Layered architecture](#layered-architecture)
    * [Directory tree](#directory-tree)
    * [Model without defaults](#model-without-defaults)
    * [Model without logic](#model-without-logic)
    * [Fetch-only repository](#fetch-only-repository)
    * [Composite service](#composite-service)
    * [Service interaction](#service-interaction)
    * [Inject dependencies through the constructor](#inject-dependencies-through-the-constructor)
    * [Wrap third-party code](#wrap-third-party-code)
    * [Validate at the presentation layer](#validate-at-the-presentation-layer)
    * [Bind query parameters](#bind-query-parameters)
- [Testing](#testing)
    * [Mirror the source tree](#mirror-the-source-tree)
    * [Name tests by scenario](#name-tests-by-scenario)
    * [Structure tests as Arrange, Act, Assert](#structure-tests-as-arrange-act-assert)
    * [Test one action per test](#test-one-action-per-test)
    * [Use data providers for variants](#use-data-providers-for-variants)
    * [Mock only boundaries](#mock-only-boundaries)
    * [Build fixtures with builders](#build-fixtures-with-builders)
    * [Use intent-revealing test data](#use-intent-revealing-test-data)
- [Git](#git)
    * [Branch naming](#branch-naming)
    * [Meaningful commits](#meaningful-commits)
    * [Pull request naming](#pull-request-naming)
    * [Use rebase](#use-rebase)
    * [Squash](#squash)

---

## Types and comparisons

### Enable strict typing
<!-- rule: TC1 -->

Every PHP script must include the `declare(strict_types=1);` directive as the very first statement in the file.

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
<?php

namespace App\Entity;

class User
{
    // ...
}
```

</td>
<td>

```php
<?php

declare(strict_types=1);

namespace App\Entity;

class User
{
    // ...
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** To ensure type integrity across the application and to prevent type coercion bugs that are hard to detect.

### Use strict comparison operators
<!-- rule: TC2 -->

Always use strict comparison operators (`===` and `!==`) instead of loose operators (`==` and `!=`). If a type-agnostic
comparison is required, perform an explicit cast on both operands (e.g., `(string)$a === (string)$b`) before comparing
with the strict operator.

For `in_array()`, always set the third parameter (`$strict`) to `true` to ensure elements are checked for both value
and type.

| ❌ Wrong:                       | ✅ Right:                                     |
|--------------------------------|----------------------------------------------|
| `$value == null`               | `$value === null`                            |
| `in_array($needle, $haystack)` | `in_array($needle, $haystack, strict: true)` |

> **Why?** To prevent bugs caused by unexpected type conversion and to ensure the application is secure against
> type-juggling attacks.

### Comparison order
<!-- rule: TC3 -->

Put static value (`true`, `false`, `null`, `enum`) in the right of comparison operator.

| ❌ Wrong:          | ✅ Right:          |
|-------------------|-------------------|
| `null === $value` | `$value === null` |

> **Why?** "Yoda conditions" exist to catch an accidental `=` instead of `==`, which strict types and the rule
> [Avoid assignments in conditions](#avoid-assignments-in-conditions) already prevent. Natural reading order
> (`$value === null`) is what the sentence "value is null" sounds like.

### Compare booleans directly
<!-- rule: TC4 -->

Don't perform identity comparisons against `true` or `false` when a variable is already of type `boolean`.

| ❌ Wrong:                   | ✅ Right:          |
|----------------------------|-------------------|
| `return $valid === true;`  | `return $valid;`  |
| `return $valid !== false;` | `return !$valid;` |

**Exception:** when a variable is not strictly `boolean`.

```php
return strpos($haystack, $needle) === false;
```

> **Why?** To reduce complexity.

### Check things explicitly
<!-- rule: TC5 -->

Use only functions and conditions that match the exact intent of the check. Never use `empty()`: it treats `null`,
`false`, `0`, `0.0`, `'0'`, `''`, and `[]` as the same thing. Compare against the exact value you mean.

| ❌ Wrong:             | ✅ Right:                                  |
|----------------------|-------------------------------------------|
| `empty($value)`      | `$value === null` (or `$value === ''`)     |
| `!empty($array)`     | `$array !== []`                           |
| `!empty($items)`     | `count($items) > 0` (for `Countable`)     |
| `strlen($value) > 0` | `$value !== ''`                           |
| `if ($user)`         | `if ($user !== null)`                     |

Apply this even when the variable can only ever hold one of the conflated values.

> **Why?** Loose checks hide which case the code expects (`'0'` is a valid value, `0` is a valid count). Explicit
> checks document the intent, survive a type change of the variable, and are what static analysis can verify.

### Avoid mixed and untyped arrays
<!-- rule: TC6 -->

Declare a precise type for every parameter, property, and return value. Never use `mixed`, and never use a bare
`array` without a PHPDoc shape (`list<T>`, `array<string, T>`, `array{id: int, name: string}`). Use `never` as the
return type of a method that always throws.

| ❌ Wrong:                           | ✅ Right:                                                |
|------------------------------------|---------------------------------------------------------|
| `function handle(mixed $payload)`  | `function handle(OrderModel $payload)`                  |
| `function ids(): array`            | `/** @return list<int> */ function ids(): array`        |
| `function fail(string $message): void` | `function fail(string $message): never`             |

**Exception:** `mixed` in a generic boundary that really accepts anything (a serializer, a cache adapter), documented
with a PHPDoc that narrows it where possible.

> **Why?** Static analysis can only verify what is declared. `mixed` and shapeless arrays turn every consumer into a
> guess and hide type errors until runtime.

### Pass static analysis
<!-- rule: TC7 -->

Code must pass [PHPStan](https://phpstan.org) 2 at the level configured in the project (`max`, i.e. level `10`,
for new projects) with zero errors and zero new baseline entries. Resolve a reported error in this order:

1. Fix the code (add the missing type, the `null` check, the return type).
2. If the untyped value comes from vendor code, narrow it with a real check that PHPStan understands:
   `if (!$result instanceof Product) { throw new UnexpectedValueException(...); }`. Fall back to `/** @var */`
   only when a check is impossible (see [Follow commenting standards](#follow-commenting-standards)).
3. If the vendor's own PHPDoc is wrong, add a stub (`phpstan/stubs/*.stub`) for that class instead of ignoring
   every call site.
4. `@phpstan-ignore` is allowed only for a confirmed PHPStan or vendor bug, with the identifier and a link to the
   issue on the same line: `// @phpstan-ignore method.notFound (https://github.com/phpstan/phpstan/issues/1234)`.

| ❌ Wrong:                                                    | ✅ Right:                                                       |
|-------------------------------------------------------------|----------------------------------------------------------------|
| `/** @phpstan-ignore-next-line */ $order->ship();`          | `if ($order !== null) { $order->ship(); }`                      |
| `/** @var Product $product */ $product = $client->fetch();` | `$product = $client->fetch(); if (!$product instanceof Product) { throw ...; }` |

> **Why?** The analyser is the only reviewer that checks every call site on every change. A suppressed error is a
> bug with a comment on it, while a runtime check narrows the type for PHPStan and protects production at the same
> time.

## Control flow

### Prefer match expressions
<!-- rule: CF1 -->

Use `match` instead of `switch` to simplify conditional logic and reduce the "cognitive load" required to read the code.
Unlike the `switch` statement, which is used for executing blocks of code, `match` is an expression that returns a
value.

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
switch ($statusCode) {
    case 200:
    case 300:
        $message = null;

        break;
    case 400:
        $error = $this->getErrorMessage();

        if ($error !== null) {
            $message = $error;
        } else {
            $message = 'bad request';

            $this->logger->error('bad request');
        }

        break;
    case 500:
        $message = 'server error';

        break;
    default:
        $message = 'unknown status code';

        break;
}
```

</td>
<td>

```php
$message = match ($statusCode) {
    200, 300 => null,
    400 => $this->getErrorMessage(),
    500 => 'server error',
    default => 'unknown status code'
};
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** `match` compares strictly, returns a value instead of assigning in every branch, and throws
> `UnhandledMatchError` for an unexpected subject instead of silently falling through, so a missing case cannot go
> unnoticed.

### Avoid assignments in conditions
<!-- rule: CF2 -->

Don't perform assignments within conditional statements. All variable assignments must be completed as independent
statements before the condition is evaluated.

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
if (
    ($action = $process->getAction()) !== null 
    && ($result = $action->getResult()) !== null
) {
    return $result->getData();
}

// ...
```

</td>
<td>

```php
$action = $process->getAction();

if ($action === null) {
    return [];
}

$result = $action->getResult();

if ($result !== null) {
    return $result->getData();
}

// ...
```

</td>
        </tr>
    </tbody>
</table>

**Exception:** in a while loop condition.

> **Why?** Assignments in conditions perform two distinct actions (assignment and comparison) in a single line, making
> the code harder to read, maintain, and debug.

### Avoid unnecessary nesting
<!-- rule: CF3 -->

Avoid nested conditions when a single condition is enough.

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
if ($first) {
    if ($second) {
        do();
    }
}
```

</td>
<td>

```php
if ($first && $second) {
    do();
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** To reduce code complexity. Nested conditions are harder to read and reason about.

### Keep if statements simple
<!-- rule: CF4 -->

Keep every `if` short and flat. Apply these in order:

- Replace `if`/`else` that only picks a value with a ternary or a `match`.
- Use `match (true)` instead of an `if`/`elseif` chain when there are three or more branches and every branch is a
  single expression.
- Return early from guard clauses (`if (!$condition) { return ...; }`) instead of wrapping the rest of the method in
  the `if`.
- Invert heavy blocks: when the `if` body is longer than its `else`, reverse the condition so the long path is the
  top-level code.
- Never nest `if` deeper than two levels; extract a method instead.

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
if ($condition) {
    return $value;
} else {
    return null;
}
```

</td>
<td>

```php
return $condition ? $value : null;
```

</td>
        </tr>
        <tr>
<td>

```php
if ($first) {
    // a lot of code
    // ...
    // ...
} elseif ($second) {
    // a lot of code
    // ...
    // ...
} else {
    // a lot of code
    // ...
    // ...
}
```

</td>
<td>

```php
match (true) {
    $first => $this->doFirst(),
    $second => $this->doSecond(),
    default => $this->doDefault()
};
```

</td>
        </tr>
        <tr>
<td>

```php
if ($condition) {
    // a lot of code
    // ...
    // ...

    return $result;
}

return null;
```

</td>
<td>

```php
if (!$condition) {
    return null;
}

// a lot of code
// ...
// ...

return $result;
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** Simpler control flow is easier to read, test, and maintain.

## Variables and parameters

### Avoid unnecessary variables
<!-- rule: VP1 -->

Avoid temporary variables when they don't clarify the code.

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
function find(int $needle, array $haystack): bool
{
    $found = false;

    foreach ($haystack as $item) {
        if ($needle === $item) {
            $found = true;

            break;
        }
    }

    return $found;
}

function getValue(): int
{
    // ...

    $value = $this->get();

    return $value;
}
```

</td>
<td>

```php
function find(int $needle, array $haystack): bool
{
    foreach ($haystack as $item) {
        if ($needle === $item) {
            return true;
        }
    }

    return false;
}

function getValue(): int
{
    // ...

    return $this->get();
}
```

</td>
        </tr>
    </tbody>
</table>

**Exception:** use variables when they improve readability or help explain a complex condition.

```php
function canModify(Product $product): bool
{
    $rightsGranted = $this->isAdmin() || $this->isOwner($product);
    $productEditable = $this->isNew($product) && !$this->isLocked($product);

    return $rightsGranted && $productEditable;
}
```

> **Why?** Extra variables add noise, but useful variables can make the code easier to understand.

### Don't reuse variables for different states
<!-- rule: VP2 -->

Don't assign different states or representations of a value to the same variable. Introduce a new, descriptive variable
for each state.

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
$name = 'John';
$name = sprintf('%s %s', $name, $lastName);
$name = preg_replace($pattern, '', $name);
```

</td>
<td>

```php
$name = 'John';
$fullName = sprintf('%s %s', $name, $lastName);
$filteredFullName = preg_replace($pattern, '', $fullName);
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** Reusing a variable for different states hides the data flow and makes the code harder to follow and debug.

### Reduce function parameters
<!-- rule: VP3 -->

The maximum number of function parameters should be three. Every parameter you add to a function signature makes that
function harder to understand. If more parameters are needed, group them into a dedicated object.

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
function createUser(
    string $name,
    string $email,
    int $age,
    string $country,
): User {
    // ...
}
```

</td>
<td>

```php
function createUser(CreateUserModel $model): User {
    // ...
}
```

</td>
        </tr>
    </tbody>
</table>

**Exception:** constructors that receive injected dependencies through promoted properties. Don't wrap dependencies
into a holder object. A specific service with more than five dependencies is doing too much and must be split; a
[composite service](#composite-service) is exempt from that limit, because its only job is to hold the specific
services it delegates to.

> **Why?** Every parameter is one more thing the caller must order and remember. A named object turns the list into
> a documented structure, and the limit keeps the signature readable at the call site.

### Use named arguments
<!-- rule: VP4 -->

Use named arguments for:

1. Multiline method calls.
2. Any boolean argument.

You can skip named arguments when the method can be called on a single line and has no boolean argument.

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
$this->createUser($name, $email, true);
```

</td>
<td>

```php
$this->createUser(
    name: $name,
    email: $email,
    active: true,
);
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** Named arguments document the meaning of each value at the call site, especially for booleans and long
> argument lists.

### Name numbers and complex regexes
<!-- rule: VP5 -->

Extract magic numbers used in formulas into named constants. Apply the same approach to complex regular expressions,
unless the variable name already explains the regex purpose.

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
$weightKg = $weightGrams / 1000;
```

</td>
<td>

```php
$weightKg = $weightGrams / self::GRAMS_IN_KILOGRAM;
```

</td>
        </tr>
        <tr>
<td>

```php
preg_match('/^\+?[1-9]\d{7,14}$/', $phone);
```

</td>
<td>

```php
preg_match(self::E164_PHONE_PATTERN, $phone);
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** Named constants make formulas self-explanatory and prevent the meaning of a value from being lost.

### Avoid flag arguments
<!-- rule: VP6 -->

Never add a `bool` parameter that switches a method between two behaviours. Split the method in two, or pass an enum
when there are more than two modes.

| ❌ Wrong:                                      | ✅ Right:                                              |
|-----------------------------------------------|-------------------------------------------------------|
| `render(Template $template, bool $asPdf)`     | `renderHtml(Template $template)` / `renderPdf(Template $template)` |
| `export(Report $report, bool $compressed)`    | `export(Report $report, ExportFormatEnum $format)`    |

**Exception:** a boolean that is data, not a mode (`active: true` in a create model), passed as a named argument.

> **Why?** A flag means the method does two things. The caller has to read the implementation to learn which, and
> every new flag doubles the paths to test.

### Avoid output arguments
<!-- rule: VP7 -->

Return results; never mutate an argument to pass data back to the caller. Don't use `&` references, and don't pass a
model into a method only to have it filled.

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
function fillTotals(Order $order, array &$totals): void
{
    $totals['net'] = $order->getNet();
}
```

</td>
<td>

```php
function calculateTotals(Order $order): OrderTotals
{
    return new OrderTotals(net: $order->getNet());
}
```

</td>
        </tr>
    </tbody>
</table>

**Exception:** `preg_match()` and other built-ins that have no alternative; wrap them if used more than once.

> **Why?** A reader expects data to flow in through arguments and out through the return value. An argument that
> changes after the call is a hidden second return.

### Use null-safe operators
<!-- rule: VP8 -->

Use `?->` to call through a nullable value and `??` to supply a default, instead of nested `if` checks or the
ternary with `isset`.

| ❌ Wrong:                                                       | ✅ Right:                               |
|----------------------------------------------------------------|----------------------------------------|
| `$user !== null ? $user->getEmail() : null`                    | `$user?->getEmail()`                   |
| `isset($options['limit']) ? $options['limit'] : 10`            | `$options['limit'] ?? 10`              |
| `if ($order !== null) { $id = $order->getId(); } else { ... }` | `$id = $order?->getId() ?? self::NONE` |

**Exception:** don't chain `?->` more than twice; a long nullable chain is a sign that the data should be fetched
by a dedicated method instead.

> **Why?** The operators express "maybe absent" in one token and remove a branch that exists only for `null`.

### Use array search functions
<!-- rule: VP9 -->

Use `array_find()`, `array_find_key()`, `array_any()`, `array_all()`, `array_first()`, and `array_last()` instead of
a `foreach` that searches and breaks, or `reset()`/`end()` tricks to reach the first or last element.

| ❌ Wrong:                                                      | ✅ Right:                                                   |
|---------------------------------------------------------------|------------------------------------------------------------|
| `foreach ($items as $item) { if ($item->isActive()) { return $item; } } return null;` | `return array_find($items, static fn (Item $item) => $item->isActive());` |
| `foreach ($ids as $id) { if ($id === $needle) { return true; } } return false;` | `return array_any($ids, static fn (int $id) => $id === $needle);` |
| `$first = reset($rows);`                                      | `$first = array_first($rows);`                              |

Use `in_array($needle, $haystack, strict: true)` for a plain membership test; `array_any` is for a predicate.

> **Why?** The function name states the intent (find one, check any, check all) that a loop with a `break` forces the
> reader to reconstruct, and there is no loop variable or flag left behind.

## Strings and formatting

### Use quotes properly
<!-- rule: SF1 -->

Use single quotes for strings by default. Use double quotes only when the string contains single quotes or requires
escape characters such as `\n`. Never embed variables directly in strings - use `sprintf` instead.

| ❌ Wrong:                    | ✅ Right:                      |
|-----------------------------|-------------------------------|
| `"Hello, World!"`           | `'Hello, World!'`             |
| `'It\'s a beautiful day'`   | `"It's a beautiful day"`      |
| `'First line\nSecond line'` | `"First line\nSecond line"`   |
| `"Hello $name!"`            | `sprintf('Hello %s!', $name)` |

**Exception:** PSR-3 log messages use `{placeholder}` tokens with a context array, never `sprintf`:
`$this->logger->error('User {id} not found', ['id' => $id]);`.

> **Why?** To keep quotes usage consistent. Single quotes signal that the string is plain text - no variables, no
> escape sequences - making it faster to read and safer to edit. Log placeholders keep the message searchable and the
> context structured.

### Prefer sprintf for string concatenation
<!-- rule: SF2 -->

Use formatted templates (`sprintf`) to ensure that data injection is explicit and easy to trace during debugging.

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
$url = '/v1/templates/' . $template->getTemplateFamily()->getId() . '/ingredients?' . http_build_query($queryParams);
```

</td>
<td>

```php
$url = sprintf(
    '/v1/templates/%s/ingredients?%s',
    $template->getTemplateFamily()->getId(),
    http_build_query($queryParams),
);
```

</td>
        </tr>
    </tbody>
</table>

**Exception:** PSR-3 log messages (see [Use quotes properly](#use-quotes-properly)).

> **Why?** To separate the string template from its values, making long strings easier to read.

### Add trailing comma
<!-- rule: SF3 -->

Always add a trailing comma in multiline arrays, objects, functions, etc.

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
public function create(
    ProductCreateModel $model,
    User $user
): Product {
    $data = [
        'name' => $model->name,
        'type' => $model->type
    ];

    // ...
}
```

</td>
<td>

```php
public function create(
    ProductCreateModel $model,
    User $user,
): Product {
    $data = [
        'name' => $model->name,
        'type' => $model->type,
    ];

    // ...
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** This leads to cleaner git diffs and simplifies adding and removing items.

## Classes and methods

### Control scope via visibility
<!-- rule: CM1 -->

Adhere to the principle of least privilege by applying the most restrictive access modifier to all class members.

- Use `private` for all internal implementation details.
- Use `protected` only when a member is intended to be accessible by, or overridable in, subclasses.
- Use `public` exclusively for members called from outside the class.

> **Why?** Restrictive visibility limits the scope of changes and allows the IDE to detect unused methods and
> properties.

### Write small and understandable methods
<!-- rule: CM2 -->

To minimize a cognitive load, favor small, encapsulated methods that are easy to reason about. Implement
self-explanatory logic by utilizing expressive naming conventions that communicate the method's intent clearly.

To prevent "God Methods", maintain a maximum threshold of `50` lines per function.

**Exception:** in cases of high-density declarative configuration (such as schema definitions or form layouts), where
line count increases due to structural complexity rather than procedural logic.

> **Why?** Small, well-named methods are easier to read, test, and reuse.

### Use immutable dates
<!-- rule: CM3 -->

All date and time operations must utilize `DateTimeImmutable` instead of `DateTime`.

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
$startedAt = new DateTime();

$finishedAt = clone $startedAt;
$finishedAt->modify('+1 day');
```

</td>
<td>

```php
$startedAt = new DateTimeImmutable();

$finishedAt = $startedAt->modify('+1 day');
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** To ensure that the date remains constant across different layers of the application and to eliminate the
> need for "defensive cloning".

### Use readonly classes and properties
<!-- rule: CM4 -->

Prefer using `readonly` classes and properties.

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
class UserModel
{
    private string $name;

    private string $email;
    
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
    
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
```

</td>
<td>

```php
readonly class UserModel
{
    public function __construct(
        public string $name,
        public string $email,
    ) {
    }
}
```

</td>
        </tr>
    </tbody>
</table>

**Exception:** when a class requires internal state mutation (e.g., for memoization, caching, or tracking internal
lifecycle changes), and Models (DTOs) whose properties are filled from outside after construction (see
[Model without defaults](#model-without-defaults)): a `readonly` property can only be initialised from inside its
class, so such models stay non-readonly.

When only one property needs internal mutation, keep the rest `readonly` and declare that property with asymmetric
visibility (`public private(set) int $hits = 0;`) instead of dropping `readonly` from the whole class.

> **Why?** Readonly modifier prevents accidental mutation after initialization and disallows dynamic property creation.

### Make classes final
<!-- rule: CM5 -->

Declare every class `final` unless it is explicitly designed for extension (an `Abstract*` base or a Doctrine
entity). Prefer composition and interfaces over inheritance.

| ❌ Wrong:                      | ✅ Right:                              |
|-------------------------------|---------------------------------------|
| `class ProductManager`        | `final class ProductManager`          |
| `readonly class UserService`  | `final readonly class UserService`    |

**Exception:** Doctrine entities (proxies extend them) and classes a framework must subclass.

> **Why?** A class that nobody extends has no hidden subclasses to break when it changes. Removing `final` later is
> free; adding it later breaks downstream code.

### Use constructor property promotion
<!-- rule: CM6 -->

Declare dependencies and constructor-initialised fields as promoted constructor properties. Don't duplicate a property
declaration and an assignment in the constructor body.

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
final class ProductService
{
    private ProductManager $manager;

    public function __construct(ProductManager $manager)
    {
        $this->manager = $manager;
    }
}
```

</td>
<td>

```php
final readonly class ProductService
{
    public function __construct(
        private ProductManager $manager,
    ) {
    }
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** One declaration instead of three keeps the dependency list in one place and makes a wrong assignment
> impossible.

### Order class members
<!-- rule: CM7 -->

Declare class members in this order, each group separated by one blank line:

1. Constants (`public`, then `protected`, then `private`)
2. Properties (non-promoted, same visibility order)
3. Constructor
4. Public methods
5. Protected methods
6. Private methods

Within a group, place a method directly below the first method that calls it (the "stepdown" order), so a reader
can go top to bottom.

> **Why?** A fixed order lets a reader find the public API without scanning, and the stepdown order reads like a
> newspaper: headline first, detail below.

### Separate commands from queries
<!-- rule: CM8 -->

A method either changes state and returns `void` (a command), or returns a value and changes nothing (a query).
Never both.

| ❌ Wrong:                                        | ✅ Right:                                                  |
|-------------------------------------------------|-----------------------------------------------------------|
| `function activate(User $user): bool` (saves, returns success) | `function activate(User $user): void` (throws on failure) |
| `function getOrCreate(string $key): Item`       | `find(string $key): ?Item` + `create(string $key): Item`  |

**Exception:** `create()` methods return the created entity, because the caller needs the generated identifier. This
covers `Manager::create()` and the composite service method that delegates to it; nothing else returns from a
command.

> **Why?** A caller of a query can call it any number of times without side effects, and a caller of a command knows
> that nothing else needs to be checked in the return value.

### Inject the clock
<!-- rule: CM9 -->

Never call `new DateTimeImmutable()`, `time()`, or `date()` inside a service. Inject `Psr\Clock\ClockInterface`
(Symfony `Clock` component) and call `$this->clock->now()`.

| ❌ Wrong:                                 | ✅ Right:                                  |
|------------------------------------------|-------------------------------------------|
| `$expiresAt = new DateTimeImmutable('+1 day');` | `$expiresAt = $this->clock->now()->modify('+1 day');` |
| `if (time() > $token->getExpiry())`      | `if ($this->clock->now() > $token->getExpiresAt())` |

**Exception:** timestamps set inside Doctrine lifecycle callbacks (`#[ORM\PrePersist]` in the entity itself), and
console output that only displays the time. A `createdAt` assigned to a model inside a service is not an exception;
it uses the clock.

> **Why?** A service that reads the system clock cannot be tested for "yesterday" or "at midnight". A `MockClock`
> makes every time-dependent test deterministic.

### Use attributes instead of annotations
<!-- rule: CM10 -->

Configure routes, Doctrine mappings, validation, and serialization with PHP 8 attributes (`#[Route]`, `#[ORM\Column]`,
`#[Assert\NotBlank]`). Never use `@Route` / `@ORM` docblock annotations.

| ❌ Wrong:                                 | ✅ Right:                                      |
|------------------------------------------|-----------------------------------------------|
| `/** @ORM\Column(type="string") */`      | `#[ORM\Column(type: Types::STRING)]`          |
| `/** @Route("/v1/products", methods={"GET"}) */` | `#[Route('/v1/products', methods: ['GET'])]` |

> **Why?** Attributes are real PHP syntax: the parser, the IDE, and static analysis validate them, while an
> annotation is a string that fails at runtime.

### Chain on new without extra parentheses
<!-- rule: CM12 -->

Call a method or access a property on a freshly created object directly: `new Foo()->bar()`. Never wrap the
constructor call in parentheses, and always keep the constructor's own parentheses even without arguments.

| ❌ Wrong:                                   | ✅ Right:                               |
|--------------------------------------------|----------------------------------------|
| `(new DateTimeImmutable())->format('Y')`   | `new DateTimeImmutable()->format('Y')` |
| `(new Builder)->withName($name)->build()`  | `new Builder()->withName($name)->build()` |

> **Why?** Since PHP 8.4 the wrapping parentheses are noise, and the constructor parentheses keep `new Foo()` visually
> distinct from a constant or class reference.

## Enums

### Use backed enums for persisted values
<!-- rule: EN1 -->

Any value stored in a database, sent over an API, or read from configuration must be a backed enum. Choose the
backing type per enum: `string` when people or external systems read the value (API payloads, logs, configuration,
raw SQL), `int` when the value is numeric by nature (priority, level) or the column sits in a hot path where index
size matters. In both cases the enum is the single map between the case name and the stored value.

| ❌ Wrong:                                   | ✅ Right:                                                  |
|--------------------------------------------|-----------------------------------------------------------|
| `public const STATUS_ACTIVE = 'active';`   | `enum StatusEnum: string { case Active = 'active'; }`     |
| `enum StatusEnum { case Active; }` (stored) | `enum StatusEnum: string { case Active = 'active'; }`     |
| `public const PRIORITY_HIGH = 3;`          | `enum PriorityEnum: int { case High = 3; }`               |

> **Why?** A backed enum is both a closed set the type system checks and a stable external representation.
> Constants give you neither. A `string` value stays readable outside the code; an `int` value is smaller and
> faster to index but means nothing without the enum, so it is reserved for the cases where that cost pays off.

### Convert enums at the boundaries
<!-- rule: EN2 -->

Let the framework turn raw input into the enum at the entrypoint: an enum-typed property on the request Model
mapped with `#[MapRequestPayload]` / `#[MapQueryString]`, `EnumType` in forms, `EnumRequirement` in routes (in
Laravel: enum casts and the `Enum` validation rule). Call `tryFrom()` yourself only where no mapping exists (console
arguments, third-party payloads) and treat `null` as a validation error. Inside the application pass the enum, never
its backing value. Match on enums exhaustively, without `default`.

| ❌ Wrong:                                              | ✅ Right:                                                    |
|-------------------------------------------------------|-------------------------------------------------------------|
| `$this->service->update($id, $request->get('status'))` | `public StatusEnum $status;` on the request Model, mapped by `#[MapRequestPayload]` |
| `$this->service->update($id, $input->getArgument('status'))` | `StatusEnum::tryFrom($input->getArgument('status')) ?? throw new InvalidArgumentException(...)` |
| `$this->repository->findByStatus($status->value)`     | `$this->repository->findByStatus($status)`                  |
| `match ($status) { ..., default => '' }`               | `match ($status) { StatusEnum::Active => ..., StatusEnum::Archived => ... }` |

> **Why?** Converting once at the edge means every inner method receives a valid case, and the framework already
> reports a wrong value as a validation error. An exhaustive `match` makes adding a case a compile-time to-do list
> (static analysis reports every unhandled match).

## Exceptions

### Throw specific exceptions
<!-- rule: EX1 -->

Never throw the base `Exception` class.

- For domain logic: Create custom exceptions that extend the appropriate SPL exception. They may remain empty; their
  value lies in their type.
- For standard errors: Utilize existing SPL exceptions for common error states.

| ❌ Wrong:                                | ✅ Right:                                            |
|-----------------------------------------|-----------------------------------------------------|
| `throw new Exception('Invalid token.')` | `throw new InvalidTokenException('Invalid token.')` |
| `throw new Exception('Bad input.')`     | `throw new InvalidArgumentException('Bad input.')`  |

> **Why?** Specific exceptions make it easier to identify the error type, handle it precisely, and keep error handling
> organized.

### Catch specific exceptions
<!-- rule: EX2 -->

Always catch the most specific exception type available. Never catch base `Exception` or `Throwable` classes.

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
try {
    $this->someDeepMethod();
} catch (Exception) {
    return null;
}
```

</td>
<td>

```php
try {
    $this->someDeepMethod();
} catch (EntityNotFoundException) {
    return null;
} catch (UnknownProductTypeException $exception) {
    $this->productLogger->error($exception->getMessage());

    return null;
}
```

</td>
        </tr>
    </tbody>
</table>

**Exception:** only catch the base `Exception` class when it comes from vendor code.

> **Why?** Catching specific exceptions makes it clear what error is expected and where it comes from.

### Extract try catch blocks
<!-- rule: EX3 -->

Extract the bodies of the `try` and `catch` blocks out into functions of their own. Try/catch blocks confuse the
structure of the code and mix error processing with normal processing.

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
try {
    // a lot of code
    // ...
    // ...
} catch (UnknownTypeException $exception) {
    // a lot of code
    // ...
    // ...
}
```

</td>
<td>

```php
try {
    $this->someDeepMethod();
} catch (UnknownTypeException $exception) {
    $this->handleError($exception);
}
```

</td>
        </tr>
    </tbody>
</table>

**Exception:** a `catch` body that is a single `return` of a fallback value, or a single `throw` of a wrapping
exception, needs no extraction. An assignment of one call (`$order = $this->manager->create($model);`) counts as a
single call in the `try` body.

> **Why?** To provide a nice separation that makes the code easier to understand and modify.

### Create a base exception per domain
<!-- rule: EX4 -->

Each domain (bundle or domain folder) declares one abstract base exception (`ImportException`) that extends the
matching SPL exception. Every concrete exception of that domain extends the base.

```php
abstract class ImportException extends RuntimeException
{
}

final class ImportFileNotFoundException extends ImportException
{
}
```

> **Why?** A caller can catch everything one domain can throw with a single type, and the exception hierarchy
> mirrors the module structure.

### Never swallow exceptions
<!-- rule: EX5 -->

A `catch` block must do at least one of: rethrow (wrapped in a domain exception), log at `error` level with the
exception in context, or return a fallback value that is part of the method's contract (a `?T` return type on a
`find*` method, an empty collection). An empty `catch`, a `catch` that only logs at `debug` level, or `return null`
from a method whose name promises a result, is forbidden.

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
try {
    $this->client->send($message);
} catch (TransportException) {
}
```

</td>
<td>

```php
try {
    $this->client->send($message);
} catch (TransportException $exception) {
    throw new NotificationFailedException(
        message: 'Failed to send notification.',
        previous: $exception,
    );
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** A swallowed exception turns a failure into silent wrong behaviour that surfaces far from its cause.

### Write exception messages consistently
<!-- rule: EX6 -->

Write the message as a full sentence: capitalised, ending with a period, naming the failing value in quotes or
with `sprintf`. Pass structured data (IDs, codes) through constructor arguments or dedicated getters, not only
through the message.

| ❌ Wrong:                                    | ✅ Right:                                                             |
|---------------------------------------------|----------------------------------------------------------------------|
| `throw new ProductNotFoundException('not found')` | `throw new ProductNotFoundException(sprintf('Product "%d" was not found.', $id))` |
| `throw new InvalidArgumentException('bad status')` | `throw new InvalidArgumentException(sprintf('Unknown status "%s".', $status))` |

> **Why?** The message is what ends up in logs and error reports. A sentence with the value in it makes the log entry
> searchable and the failure reproducible.

## Comments

### Eliminate redundant PHPDoc
<!-- rule: CO1 -->

Don't add PHPDoc to methods that are already fully type-hinted.

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
/**
 * @param string $email
 *
 * @return object
 */
function send(string $email): object {
    // ...
}
```

</td>
<td>

```php
function send(string $email): object {
    // ...
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** To reduce maintenance debt and cognitive load. If PHPDoc does not add any additional information, it merely
> duplicates the information already provided.

### Document array element types with PHPDoc
<!-- rule: CO2 -->

Add PHPDoc to describe element types when using `arrays` or `Collections` as arguments, properties, or return
types. Use `list<T>` for sequential arrays, `array<K, V>` for maps, and `array{...}` shapes for fixed keys.

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
public function getProducts(): Collection;

public function findByIds(array $ids): array;
```

</td>
<td>

```php
/**
 * @return Collection<int, Product>
 */
public function getProducts(): Collection;

/**
 * @param list<int> $ids
 *
 * @return array<int, Product>
 */
public function findByIds(array $ids): array;
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** IDEs use this information to provide auto-completion and type warnings.

### Follow commenting standards
<!-- rule: CO3 -->

Use the right comment syntax depending on what you are documenting.

- Use multi-line `/** */` comments for method, property, and class annotations.
- Use single-line `/** @var Class $object */` comments for local variables if type is missing in vendor code.
- Use `//` for single line comments.
- Don't use `/* */` or `#` comments at all.
- Never leave commented-out code. If code is no longer needed, delete it. Use VCS to revert if needed.

> **Why?** Consistent comment styles make code easier to read and process with tooling.

### Refactor instead of explaining
<!-- rule: CO4 -->

Prefer code refactoring over lazy commenting to explain its behavior. Code that explains itself needs no comments.

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
// Check if the user is a product manager
if (
    $user->getRole() === RoleEnum::Manager
    && $user->hasSpecialAccess()
    && $user->isAllowedToManageProducts()
    && $user->isActive()
) {
    // ...
}
```

</td>
<td>

```php
if ($this->isProductManager($user)) {
    // ...
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** Comments that explain "how" code works are technical debt. They create a maintenance burden where every
> future change to the logic requires a corresponding update to the comment to prevent misleading information.

### Don't commit TODO comments
<!-- rule: CO5 -->

Never commit `TODO`, `FIXME`, or `XXX` comments. Either do the work in the same change, or create an issue and
reference nothing in the code.

| ❌ Wrong:                                    | ✅ Right:                                   |
|---------------------------------------------|--------------------------------------------|
| `// TODO: handle timeouts`                  | handle timeouts, or open `JIRA-456`        |

> **Why?** A `TODO` is a bug report that no tracker shows, no sprint plans, and no one is assigned to.

### Remove dead code
<!-- rule: CO6 -->

Delete unused classes, methods, parameters, and properties in the same change that makes them unused. Don't keep
code "in case", don't guard it with a feature flag that is never enabled, and don't deprecate what has no external
consumer.

> **Why?** Dead code is read, searched, refactored, and tested like live code, but delivers nothing. Version control
> keeps it if it is ever needed again.

---

## General naming

### Favor full names
<!-- rule: GN1 -->

Always use full, descriptive names instead of abbreviations.

| ❌ Wrong:               | ✅ Right:                                                   |
|------------------------|------------------------------------------------------------|
| `$em`                  | `$entityManager`                                           |
| `$e`                   | `$exception`                                               |
| `function delMsg($id)` | `function deleteMessage($id)` (`del` could also mean delay) |

**Exception:** established acronyms like `GPS`, `SQL`, `URL`, etc.

> **Why?** To reduce cognitive load and prevent ambiguity. Explicit names ensure that the intent of the code is clear
> to future maintainers.

### Avoid uppercased abbreviations
<!-- rule: GN2 -->

Don't fully uppercase abbreviations in class, method, property, or variable names. Treat the abbreviation as a regular
word and only capitalize its first letter.

| ❌ Wrong:          | ✅ Right:          |
|-------------------|-------------------|
| `getGPSSQL()`     | `getGpsSql()`     |
| `$userID`         | `$userId`         |
| `class APIClient` | `class ApiClient` |

> **Why?** Consistent casing keeps multi-word names readable and avoids ambiguous boundaries between stacked
> abbreviations.

### Context duplication in naming
<!-- rule: GN3 -->

Don't repeat the class context in property, folder, or method names. Keep full names for classes, but drop the
redundant context from members. This avoids unnecessary line breaks and improves readability.

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
readonly class FileCategoryService
{
    public function __construct(
        private FileCategoryManagerInterface $fileCategoryManager,
        private FileCategoryRepositoryInterface $fileCategoryRepository,
    ) {
    }
}
```

</td>
<td>

```php
readonly class FileCategoryService
{
    public function __construct(
        private FileCategoryManagerInterface $manager,
        private FileCategoryRepositoryInterface $repository,
    ) {
    }
}
```

</td>
        </tr>
    </tbody>
</table>

The same applies to methods. For example, in `LoadManagerInterface` use `get()`, `create(LoadModel $model)` and
`update(LoadModel $model)` instead of `getLoad()`, `createNewLoad()` and `updateLoadByModel()`.

Drop only the prefix that repeats the class entity and keep the role: inside `OrderService` the
`OrderRepositoryInterface` is `$repository` and the `OrderManagerInterface` is `$manager`, but a
`UserRepositoryInterface` stays `$userRepository`, because `User` is not this class's context.

> **Why?** The class already provides the context, so repeating it in members only adds noise and unnecessary line
> breaks.

### Avoid double plural constructions
<!-- rule: GN4 -->

Don't pluralize both parts of a compound name. Pluralize only the trailing noun.

| ❌ Wrong:        | ✅ Right:       |
|-----------------|----------------|
| `$driversLoads` | `$driverLoads` |

> **Why?** Pluralizing only the trailing noun reads as natural English and keeps the meaning unambiguous.

### Constant and enum case naming
<!-- rule: GN5 -->

Constants are `UPPER_SNAKE_CASE` and start with the shared prefix, followed by the specific part. Enum cases are
`PascalCase`, like class names.

| ❌ Wrong:                      | ✅ Right:                      |
|-------------------------------|-------------------------------|
| `DEFAULT_LIMIT`, `MAX_LIMIT`  | `LIMIT_DEFAULT`, `LIMIT_MAX`  |
| `case ACTIVE = 'active';`     | `case Active = 'active';`     |
| `case in_progress;`           | `case InProgress;`            |

> **Why?** A shared prefix groups related constants visually and in auto-complete. An enum case is not a constant:
> it is a typed singleton object of its enum, so it is named like a class, and the different casing shows at the
> call site that `StatusEnum::Active` is an object while `self::LIMIT_MAX` is a scalar.

## Type naming

### Class naming
<!-- rule: TN1 -->

Use nouns for class names, following the kind of class:

- **Entity / Model** - the bare domain noun: `Product`, `ProductCreateModel`.
- **Specific service** - the entity plus an `er` noun that names its single job: `ProductManager`,
  `ProductNormalizer`, `ProductProvider`, `ProductUpdater`, `ProductResolver`, `ProductRegistry`.
- **Composite service** (the single entry point that delegates to specific services, see
  [Composite service](#composite-service)) - the entity plus `Service`: `ProductService`.

| ❌ Wrong:              | ✅ Right:          |
|-----------------------|-------------------|
| `class ProductLogic`  | `class ProductService` |
| `class SyncProducts`  | `class ProductSynchronizer` |
| `class ProductHelper` | `class ProductPriceCalculator` |

> **Why?** A noun names the thing, and the `er` suffix communicates the job a service performs at a glance. Vague
> suffixes like `Helper`, `Util`, or `Logic` hide what the class does.

### Interface naming
<!-- rule: TN2 -->

Add suffix `Interface` to interfaces, even if interface name would be adjective.

> **Why?** If there is a base class that implements the interface, then there will be a name conflict.
> For example, `ContainerAware` and `ContainerAwareInterface`.

### Abstract class naming
<!-- rule: TN3 -->

Add the prefix `Abstract` to abstract classes.

> **Why?** If there is a base class that extends an abstract class, then there will be a name conflict.
> For example, `Controller` and `AbstractController`.

### Trait naming
<!-- rule: TN4 -->

Add the suffix `Trait` to traits, even if the trait name would be an adjective.

> **Why?** To make it clear from the name that it is a trait.

### Enum naming
<!-- rule: TN5 -->

Add the suffix `Enum` to enums.

> **Why?** To make it clear from the name that it is an enum.

### Property naming
<!-- rule: TN6 -->

Use nouns or adjectives for property names, not verbs or questions.

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
class Entity
{
    private bool $isValid;

    private bool $check;
}
```

</td>
<td>

```php
class Entity
{
    private bool $valid;

    private bool $checkNeeded;
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** Properties hold state, not actions. Nouns and adjectives describe that state, while verbs and questions
> belong to methods.

## Method and symbol naming

### Method naming
<!-- rule: MN1 -->

Use verbs for methods that perform action and/or return something, questions only for methods which return boolean.

Questions must start with `has`, `is`, `can`, `must` - these cannot make any side effect and always return boolean.

For entities use `is` or `are` for boolean getters, `get` for other getters, `set` for setters, `add` for adders
and `remove` for removers.

Make proper english phrase out of method names, it's more important than calling a method `'is' + propertyName.`

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
interface EntityInterface
{
    public function getIsValid(): bool;

    public function isCheck(): bool;

    public function isNeedsChecking(): bool;

    public function isTaxesIncluded(): bool;
}
```

</td>
<td>

```php
interface EntityInterface
{
    public function isValid(): bool;

    public function isCheckNeeded(): bool;

    public function canBeChecked(): bool;

    public function areTaxesIncluded(): bool;
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** Verb and question prefixes make a method's intent and return type predictable, and signal whether it has
> side effects.

### Repository method naming
<!-- rule: MN2 -->

Public repository methods must start with `find`, `count`, or `sum` only. A method that returns a single entity must
be named `findOne`.

| ❌ Wrong:              | ✅ Right:           |
|-----------------------|--------------------|
| `getProduct(int $id)` | `findOne(int $id)` |
| `getActiveProducts()` | `findActive()`     |
| `getTotalAmount()`    | `sumAmount()`      |

> **Why?** A consistent verb set makes it clear that the repository only fetches data and returns nothing else.

### Filler and provider method naming
<!-- rule: MN3 -->

Public filler methods must start with `fill` only. Public provider methods must start with `provide` only.

| ❌ Wrong:                                              | ✅ Right:                                                |
|-------------------------------------------------------|---------------------------------------------------------|
| `ImportHistoryFiller::createModel(ImportHistory $entity)` | `ImportHistoryFiller::fill(ImportHistory $entity)`   |
| `ImportHistoryFiller::buildFromEntity(ImportHistory $entity)` | `ImportHistoryFiller::fillFromEntity(ImportHistory $entity)` |
| `ProductProvider::getActiveProducts()`                | `ProductProvider::provideActive()`                      |
| `ProductProvider::loadByCategory(Category $category)` | `ProductProvider::provideByCategory(Category $category)` |

> **Why?** The prefix communicates the single responsibility of the class directly from the method name.

### Event naming
<!-- rule: MN4 -->

Name events in past-tense verbs, prefixed by resource for which some action happened. Separate the resource and action
with a dot.

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
class UserEvent extends Event
{
    public const USER_CREATE = 'user_create';
}
```

</td>
<td>

```php
class UserCreatedEvent extends Event
{
    public const NAME = 'user.created';
}
```

</td>
        </tr>
    </tbody>
</table>

An event must have only one name to comply with the Single Responsibility Principle.

> **Why?** Events should be used only after something happened. Present tense verb would indicate that event should
> make something, which would be a misuse of event system.

### Event class naming
<!-- rule: MN5 -->

Use the event name for the event class name and add the `Event` suffix. See example above.

| ❌ Wrong:                                   | ✅ Right:                                       |
|--------------------------------------------|------------------------------------------------|
| `class UserCreated` (`'user.created'`)     | `class UserCreatedEvent` (`'user.created'`)    |
| `class CreatedUserEvent` (`'user.created'`) | `class UserCreatedEvent` (`'user.created'`)   |
| `class UserEvent` (`'user.created'`, `'user.deleted'`) | `class UserCreatedEvent` + `class UserDeletedEvent` |

> **Why?** Since an event can only have one concrete class, its name must match the `NAME` constant.

### Event subscriber naming
<!-- rule: MN6 -->

The subscriber must be named according to the action it will perform with the suffix `Subscriber`.

| ❌ Wrong:                                   | ✅ Right:                                       |
|--------------------------------------------|------------------------------------------------|
| `class UserCreatedSubscriber`              | `class SendWelcomeEmailSubscriber`             |
| `class UserEventListener`                  | `class ResetPasswordSubscriber`                |
| `class UserSubscriber` (sends email, resets password) | `class SendWelcomeEmailSubscriber` + `class ResetPasswordSubscriber` |

> **Why?** A subscriber can receive many events, but with the same required action, so its name must describe the
> action it performs.

## Routing naming

### Route path naming
<!-- rule: RN1 -->

1. Use nouns
2. Use plural nouns for collections; a single item is the collection path plus its ID (`/products`,
   `/products/{id}`)
3. Use lowercase letters
4. Use hyphens (`-`) instead of underscores (`_`)
5. Don't use trailing forward slash
6. Don't use file extensions
7. Don't use CRUD function names
8. Version the API: a `/v1` path prefix, or a version header (`Accept: application/vnd.app.v1+json`,
   `Api-Version: 1`) when the path must stay stable; pick one per API

| ❌ Wrong:                  | ✅ Right:                       |
|---------------------------|--------------------------------|
| `/store/GetProducts/`     | `/v1/store/products`           |
| `/store/products.json`    | `/v1/store/products`           |
| `/v1/store/product/{id}`  | `/v1/store/products/{id}`      |
| `/template_Families/{ID}` | `/v1/template-families/{id}`   |
| `/templateFamilies/{id}`  | `/v1/template-families/{id}`   |

> **Why?** Nouns are used to specify the resource (**1**), the URIs shouldn't indicate any CRUD operations (**7**).
> Plural nouns are what every major API and routing tool expects, and they make the collection and the item differ
> only by the ID (**2**). URL paths are case-sensitive, so `/Product` and `/product` are two different resources;
> lowercase removes the ambiguity (**3**). Hyphens improve readability (**4**). A forward slash as last character
> adds no semantic value (**5**). File extensions are unnecessary, the `Accept` header selects the format (**6**). A
> version lets you change the contract without breaking existing clients; a path prefix is visible and easy to route,
> a header keeps one URL per resource across versions (**8**).

### HTTP methods for actions
<!-- rule: RN2 -->

Use the correct HTTP method for each action so that clients and servers stay interoperable.

| Method   | Action                                                            |
|----------|-------------------------------------------------------------------|
| `GET`    | Retrieves a resource or collection of resources.                  |
| `POST`   | Creates a new resource or submits data for processing.            |
| `PUT`    | Updates an existing resource fully by replacing it with new data. |
| `PATCH`  | Partially updates a resource with specific changes.               |
| `DELETE` | Removes a resource.                                               |

`GET`, `PUT`, and `DELETE` are idempotent: repeating the request gives the same result and no extra side effect.
`POST` is not; a retried `POST` creates a second resource unless the endpoint accepts an idempotency key.

Return the status code that matches the outcome:

| Code  | When                                                                                   |
|-------|----------------------------------------------------------------------------------------|
| `200` | `GET` succeeded; `PUT`/`PATCH` succeeded and returns the resource.                     |
| `201` | `POST` created a resource; return the resource in the body.                            |
| `204` | `DELETE` succeeded, or `PUT`/`PATCH` succeeded with nothing to return.                 |
| `400` | The request is malformed (invalid JSON, wrong types).                                  |
| `401` | No or invalid credentials.                                                             |
| `403` | Valid credentials, no permission for this action.                                      |
| `404` | The resource does not exist, or exists but must stay hidden from this client.          |
| `409` | The request conflicts with the current state (duplicate, stale version).               |
| `422` | The request is well-formed but fails validation (what `#[MapRequestPayload]` returns). |
| `429` | Rate limit exceeded.                                                                   |
| `5xx` | The server failed on a valid request; never for a client mistake.                      |

> **Why?** Standard methods and status codes give a uniform interface and consistent, predictable feedback to
> clients. A client can retry an idempotent request blindly, and can tell from `422` vs `400` vs `409` whether to fix
> the data, the request, or wait.

### Nest resources one level deep
<!-- rule: RN3 -->

Nest a collection under its parent only one level: `/v1/orders/{orderId}/items`. Anything deeper becomes a top-level
collection with a filter. Name the parent's parameter `{<entity>Id}` and the last resource's parameter `{id}`.

| ❌ Wrong:                                                       | ✅ Right:                                         |
|----------------------------------------------------------------|--------------------------------------------------|
| `/v1/customers/{customerId}/orders/{orderId}/items/{itemId}`   | `/v1/orders/{orderId}/items/{id}`                |
| `/v1/customers/{customerId}/orders/{orderId}/items`            | `/v1/items?order_id={orderId}`                   |
| `/v1/orders/{id}/items/{id}`                                   | `/v1/orders/{orderId}/items/{id}`                |

> **Why?** Every nesting level is one more ID the client must know and one more lookup the server must do before
> reaching the resource. One level keeps the ownership visible without turning the URL into a query.

### Filter, sort and paginate through query parameters
<!-- rule: RN4 -->

Filtering, sorting, pagination, and field selection go in the query string, never in the path. Query parameter names
are `snake_case`. Every collection endpoint is paginated; there is no "return everything" mode.

| ❌ Wrong:                                   | ✅ Right:                                                       |
|--------------------------------------------|----------------------------------------------------------------|
| `/v1/products/active`                      | `/v1/products?status=active`                                   |
| `/v1/products/sorted-by-price`             | `/v1/products?sort=-price`                                     |
| `/v1/products?createdAfter=...`            | `/v1/products?created_after=...`                               |
| `/v1/products` (returns all rows)          | `/v1/products?page=1&per_page=50` (or `cursor=`)               |

> **Why?** The path identifies a resource, the query describes a view of it. Separating the two keeps one URL per
> collection, and pagination keeps response size and query time bounded as the table grows.

### Name non-CRUD actions as sub-resources
<!-- rule: RN5 -->

An action that does not map to create, read, update, or delete is a `POST` to a verb sub-resource of the item:
`POST /v1/orders/{id}/cancel`. This is the only place a verb appears in a path, and it is always `POST`.

| ❌ Wrong:                                   | ✅ Right:                                      |
|--------------------------------------------|-----------------------------------------------|
| `POST /v1/cancel-order/{id}`               | `POST /v1/orders/{id}/cancel`                 |
| `PATCH /v1/orders/{id}` with `{"status": "cancelled"}` | `POST /v1/orders/{id}/cancel`      |
| `GET /v1/invoices/{id}/send`               | `POST /v1/invoices/{id}/send`                 |

> **Why?** Cancelling, sending, or approving is a transition with its own rules, not a field update; a dedicated
> endpoint gives it its own validation and permissions, and `POST` signals that it changes state and is not safe to
> retry.

## Symfony naming

### Console command naming
<!-- rule: SN1 -->

Name commands `app:<domain>:<action>` in lowercase with hyphens, where `<domain>` is the bundle or domain folder and
`<action>` is an imperative verb phrase. The class is `<Action><Domain>Command`.

| ❌ Wrong:                     | ✅ Right:                                                  |
|------------------------------|-----------------------------------------------------------|
| `import-templates`           | `app:import:pull-templates` (`PullTemplatesImportCommand`) |
| `app:ImportTemplates`        | `app:import:pull-templates`                               |

> **Why?** The prefix groups commands by domain in `bin/console list`, and the verb says what running it does.

### Message handler naming
<!-- rule: SN2 -->

Name a Messenger message after the thing that should happen (`SendInvoiceMessage`) and its handler after the message
with the `Handler` suffix (`SendInvoiceHandler`). One handler per message; the handler only delegates to an
`Application` service.

| ❌ Wrong:                                     | ✅ Right:                                             |
|----------------------------------------------|------------------------------------------------------|
| `InvoiceMessage` + `InvoiceMessageHandler`   | `SendInvoiceMessage` + `SendInvoiceHandler`          |
| handler with business logic inside           | handler calls `$this->invoiceService->send($id)`     |

> **Why?** The pairing is visible from the name alone, and keeping logic out of the handler makes the same action
> available synchronously.

## ADRs

It stands for **Architectural Decision Record**, but in this case it's just **Any Decision Record** to make it simple.
It's the rules you decide to apply to your project.

I work on projects based on Service-Oriented Architecture (SOA), so ADRs are directly related to it.
Services are responsible for the business logic of one specific entity or discrete unit of functionality.
For example, `UserService` is responsible for all the logic related to the user entity, while `SecurityService` is
responsible for all the logic related to the authentication functionality.

### Layered architecture
<!-- rule: AD1 -->

We follow a Domain-Driven Design (DDD) approach with five layers:

- **Application** - holds business and complex logic flows. It is a high-level layer called only from the
  `Presentation` layer, and it may call services from the `Domain` layer. Classes usually end in
  `Service`/`Puller`/`Decider`/`Pusher`/`Handler`/`Processor`/`<anything>er`. Their goal is to trigger a chain of
  business-connected actions and act as an entrypoint for the `Presentation` layer.
- **Domain** - holds `Models`/`Enums`/`Exceptions`/`Interfaces`. These classes know only the business aspects of the
  domain, nothing about the framework or specific tools. Service interfaces here (`*ManagerInterface`,
  `*RepositoryInterface`) are the API that the `Infrastructure` layer implements. This layer depends on nothing else.
- **Infrastructure** - holds the implementation of everything that requires a specific tool, and implements the
  interfaces provided by the `Domain` layer. A service responsible for CRUD actions on a domain entity must always be
  called `Manager` and implements `get`/`create`/`update`/`delete`, where `get` throws a not-found exception and
  never returns `null`.
- **Framework** - holds framework-specific logic like `CompilerPass`/`Extensions`/`Bundles`/etc.
- **Presentation** - holds the entrypoints for accessing the application from the outside, split into sublayers:
    - **Cli** - `Commands` and anything callable from the terminal.
    - **Ui** - `Controllers`/`Ui Services`/`Content Preparators`/`Frontend Resources`/`Datatables`/`Forms`/`Validators`.
    - **Api** - the API layer of the application.

Dependencies point inwards only. A class may `use` classes from its own layer or from a layer to the right of it:

```
Presentation → Application → Domain ← Infrastructure
                                 ↑
                             Framework (wires everything, depends on all)
```

`Application` and `Presentation` never reference `Infrastructure` classes directly; they depend on `Domain`
interfaces and get the implementation through dependency injection.

> **Why?** Clear layer boundaries keep business logic independent from frameworks and tools, which makes the code
> easier to test, replace, and reason about. A single dependency direction makes a violation visible in the `use`
> block.

### Directory tree
<!-- rule: AD2 -->

There are two structures depending on the project type.

**1. Monolith (bundle-based).** A large monolith that may be split into microservices later treats each bundle as a
domain:

```
📂 /ImportBundle
├── 📂 /Application
│   └── 📂 /Service
│       └── 📄 ImportTemplateService.php
├── 📂 /Domain
│   ├── 📂 /Model
│   │   └── 📄 ImportHistoryModel.php
│   ├── 📂 /Enum
│   │   └── 📄 ImportTypeEnum.php
│   ├── 📂 /Exception
│   │   └── 📄 ImportException.php
│   ├── 📂 /Service
│   │   └── 📄 ImportManagerInterface.php
│   └── 📂 /Repository
│       ├── 📄 ImportHistoryRepositoryInterface.php
│       └── 📄 ImportTemplateRepositoryInterface.php
├── 📂 /Infrastructure
│   ├── 📂 /Doctrine
│   │   ├── 📂 /Entity
│   │   │   └── 📄 ImportTemplate.php
│   │   ├── 📂 /Model
│   │   │   └── 📄 ImportHistoryFillerModel.php
│   │   ├── 📂 /Repository
│   │   │   ├── 📄 ImportHistoryRepository.php
│   │   │   └── 📄 ImportTemplateRepository.php
│   │   └── 📂 /Service
│   │       ├── 📄 ImportManager.php
│   │       └── 📄 ImportHistoryFiller.php
│   └── 📂 /Messenger
│       └── 📂 /OpenSpout
├── 📂 /Framework
│   ├── 📂 /CompilerPass
│   ├── 📂 /EventSubscriber
│   ├── 📂 /Kernel
│   └── 📄 ImportBundle.php
└── 📂 /Presentation
    ├── 📂 /Cli
    │   └── 📂 /Command
    ├── 📂 /Ui
    │   ├── 📂 /Controller
    │   ├── 📂 /Validator
    │   ├── 📂 /Form
    │   │   ├── 📂 /DataTransformer
    │   │   └── 📄 ImportTemplateType.php
    │   ├── 📂 /Resources
    │   │   ├── 📂 /assets
    │   │   └── 📂 /views
    │   ├── 📂 /Service
    │   │   └── 📄 UiImportTemplatePreparator.php
    │   └── 📂 /DataTable
    │       └── 📄 ImportHistoryDataTable.php
    └── 📂 /Api
        └── 📂 /Controller
```

Classes shared between domains live in a `Platform` folder. Tool-specific models that never leave the
`Infrastructure` layer (such as a filler model built from a Doctrine entity) live next to their tool
(`Infrastructure/Doctrine/Model`), while models that cross layers live in `Domain/Model`.

**2. Microservice.** There are no bundles, so the five layers sit at the top and contain the domain folders:

```
📂 /src
├── 📂 /Application
│   ├── 📂 /Gps
│   │   └── 📄 GpsService.php
│   └── 📂 /Shared
│       ├── 📄 FeedPuller.php
│       └── 📄 HistoryFeedPuller.php
├── 📂 /Domain
│   ├── 📂 /DataProvider
│   │   ├── 📂 /Model
│   │   ├── 📂 /Repository
│   │   │   └── 📄 DataProviderRepositoryInterface.php
│   │   ├── 📂 /Service
│   │   │   └── 📄 DataProviderService.php
│   │   └── 📂 /Strategy
│   │       └── 📄 DataProviderPullStrategyInterface.php
│   └── 📂 /Gps
│       ├── 📂 /Repository
│       │   └── 📄 GpsRepositoryInterface.php
│       └── 📂 /Service
│           └── 📄 GpsManagerInterface.php
├── 📂 /Infrastructure
│   ├── 📂 /DataProvider
│   │   └── 📂 /Samsara
│   │       └── 📄 SamsaraPullStrategy.php
│   ├── 📂 /Gps
│   │   ├── 📂 /Clickhouse
│   │   │   └── 📄 GpsRepository.php
│   │   └── 📂 /Service
│   │       └── 📄 GpsManager.php
│   └── 📂 /Shared
├── 📂 /Framework
│   ├── 📂 /CompilerPass
│   ├── 📂 /EventListener
│   └── 📂 /Kernel
└── 📂 /Presentation
    ├── 📂 /DataProvider
    │   └── 📂 /Cli
    │       └── 📄 LatestPullCommand.php
    ├── 📂 /Gps
    │   └── 📂 /Api
    │       └── 📄 GpsApiController.php
    └── 📂 /Shared
        └── 📂 /Api
            └── 📂 /Controller
                └── 📄 TmsApiController.php
```

> **Why?** A predictable directory layout makes the layer of any class obvious from its path and keeps domains
> isolated from each other.

### Model without defaults
<!-- rule: AD3 -->

Model[^1] properties mustn't contain default values. Service must handle the model properties.

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
class UserUpdateModel
{
    public string $name;

    public bool $active = true;

    public ?UploadedFile $image = null;
}
```

</td>
<td>

```php
class UserUpdateModel
{
    public string $name;

    public bool $active;

    public ?UploadedFile $image;
}
```

</td>
        </tr>
    </tbody>
</table>

**Background**

For example, we have a service that updates a user, which depends on the data in the model that it receives.
Let's consider two cases that lead to problems.

**In the first case**, the application will have several sources for updating the user: the endpoint, the admin panel,
and the console command. When updating a user through the admin panel, there are no problems, because the model
receives all the data that the user entity already had. But a console command that updates one particular field will
overwrite other fields with default values. Exactly the same situation for the endpoint, the client application may
simply not pass some field that will be overwritten by the default value.

**In the second case**, the developer who needed to make an optional field simply made it nullable and assigned `null`
by default. In the service, he added an `isset` check that will never set the value to `null`. But how can a user who
wants to remove an avatar do it now? Some developers add a separate endpoint for such purposes, which is fundamentally
wrong. The correct solution in this case would be to check if this property is initialised in the model. We can
conditionally divide the `$image` property into 3 states:

1. Not initialised - service mustn't do anything
2. Initialised with `null` value - service must assign `null` and delete the image
3. Initialised with a new value - service must assign a new value and delete the old image

> **Why?** Without defaults the service can distinguish "not provided" from "set to empty", which prevents partial
> updates from silently overwriting existing data.

### Model without logic
<!-- rule: AD4 -->

Model[^1] mustn't contain any logic, including static factory methods and property hooks (`get`/`set` blocks on a
property are logic). A model is built in exactly two places: the
`Presentation` layer maps external input into it (form, serializer, command arguments), and a filler service (see
[Filler and provider method naming](#filler-and-provider-method-naming)) builds it from entities or other internal
data. Managers and composite services consume models; they never build one from a raw array.

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
class RenderQueueModel
{
    public string $format;

    public string $videoCodec;

    public string $audioCodec;
    
    public static function createFromTemplate(Template $template): self
    {
        // ...
    }
}
```

</td>
<td>

```php
class RenderQueueModel
{
    public string $format;

    public string $videoCodec;

    public string $audioCodec;
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** A model is a plain data carrier that many services may build and consume differently. Logic inside it
> would tie every consumer to one construction path and cannot be replaced per context.

### Fetch-only repository
<!-- rule: AD5 -->

Use the repository class only for fetching objects from the database. The repository mustn't manage entities and
mustn't contain business logic, this is the responsibility of services.

Repository methods return `null` or an empty collection when nothing matches. The `get` method of the entity's
`Manager` is the single place that converts a missing entity into a not-found exception.

| ❌ Wrong:                                                | ✅ Right:                                      |
|---------------------------------------------------------|-----------------------------------------------|
| `$repository->findOneOrFail($id)`                       | `$manager->get($id)` (throws)                 |
| `$repository->save($product)`                           | `$manager->create($model)`                    |
| `$repository->findActiveAndNotify()`                    | `$repository->findActive()` + service logic   |

> **Why?** Any logic not related to fetching objects violates the Single Responsibility Principle and Service-Oriented
> Architecture. One throwing entry point (`get`) keeps the "missing entity" decision in one place.

### Composite service
<!-- rule: AD6 -->

Don't concentrate all business logic in one service. Create multiple services to perform specific tasks and combine
them into one composite service. For example, the composite `UserService` would be composed of the specific
`UserManager` and `UserSynchronizer` services.

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
readonly class UserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function create(UserCreateModel $model): User
    {
        $user = new User();
        $user->name = $model->name;
        // ...
    }

    public function synchronize(User $user): void
    {
        $products = $user->getProducts();
        // ...
    }
}
```

</td>
<td>

```php
readonly class UserService
{
    public function __construct(
        private UserManager $manager,
        private UserSynchronizer $synchronizer,
    ) {
    }

    public function create(UserCreateModel $model): User
    {
        return $this->manager->create($model);
    }

    public function synchronize(User $user): void
    {
        $this->synchronizer->synchronize($user);
    }
}
```

</td>
        </tr>
    </tbody>
</table>

**Background**

All business logic in one service is a bad idea. Such a service would contain a huge amount of code and would be
difficult to maintain. It also violates the Single Responsibility Principle.

But using a huge number of specific services in controllers, normalizers, event subscribers, etc. is also not
convenient. The classes will have a lot of dependencies, and it will be hard to keep track of the usage of the
business logic.

Creating a composite service that will contain several specific services solves these problems. Such a service simply
delegates tasks to specific services and serves as an interface for interacting with other classes.

The split is required as soon as an entity has more than one specific service. A single specific service may stand
alone under its job name (`OrderManager`); a class named `<Entity>Service` must always be a composite that holds no
logic of its own.

> **Why?** Splitting logic into focused services and exposing them through a composite keeps each class small and
> respects the Single Responsibility Principle.

### Service interaction
<!-- rule: AD7 -->

Services responsible for the business logic of different entities must interact with each other through the event
system. The service responsible for the business logic of the user mustn't contain the service responsible for the
authorization logic and vice versa. Otherwise, you will get one interconnected service instead of two independent ones.

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
readonly class UserManager
{
    public function __construct(
        private SecurityService $securityService,
    ) {
    }

    public function create(UserCreateModel $model): User
    {
        // ...

        $this->securityService->generateResetToken($user);

        return $user;
    }
}
```

</td>
<td>

```php
readonly class UserManager
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function create(UserCreateModel $model): User
    {
        // ...

        $event = new UserCreatedEvent($user);
        $this->eventDispatcher->dispatch($event, UserCreatedEvent::NAME);

        return $user;
    }
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** To avoid circular dependency and to keep services loosely coupled.

### Inject dependencies through the constructor
<!-- rule: AD8 -->

Pass every dependency as a constructor argument typed against the `Domain` interface. Never use setter injection,
`#[Required]`, a service locator, or the container inside a service. Never instantiate a service with `new`.

| ❌ Wrong:                                         | ✅ Right:                                       |
|--------------------------------------------------|------------------------------------------------|
| `$this->container->get(ProductManager::class)`   | `private ProductManagerInterface $manager`     |
| `public function setLogger(LoggerInterface $logger)` | `private LoggerInterface $logger` in constructor |
| `$mailer = new SmtpMailer($dsn);`                | `private MailerInterface $mailer`              |

> **Why?** A constructor signature is the complete, type-checked list of what a class needs. Anything else hides a
> dependency and makes the class impossible to construct in a test.

### Wrap third-party code
<!-- rule: AD9 -->

Never call a vendor SDK, HTTP client, or library API from `Application` or `Domain`. Define an interface in
`Domain` that describes what you need, implement it in `Infrastructure` around the vendor code, and write a boundary
test for the implementation.

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
// Application layer
final readonly class GpsService
{
    public function __construct(
        private SamsaraClient $client,
    ) {
    }
}
```

</td>
<td>

```php
// Domain layer
interface GpsProviderInterface
{
    /** @return list<GpsPointModel> */
    public function fetchLatest(): array;
}

// Infrastructure layer
final readonly class SamsaraGpsProvider implements GpsProviderInterface
{
    public function __construct(
        private SamsaraClient $client,
    ) {
    }
    // ...
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** The vendor can change its API, pricing, or existence. An interface shaped around your needs localises
> that change to one class.

### Validate at the presentation layer
<!-- rule: AD10 -->

Validate and convert raw input (request bodies, CLI arguments, form data) in the `Presentation` layer, before it
becomes a Model. Services receive fully typed, already valid models and never re-check basic shape.

| ❌ Wrong:                                                      | ✅ Right:                                                           |
|---------------------------------------------------------------|--------------------------------------------------------------------|
| `$service->create($request->toArray())`                       | controller maps request to `ProductCreateModel`, validates, then calls `$service->create($model)` |
| `if (!is_string($model->name)) throw ...` inside a service    | `public string $name` on the model, validated by `#[Assert\NotBlank]` |

> **Why?** One validation point per entrypoint keeps services free of defensive checks and makes every invalid input
> fail with the same error shape.

### Bind query parameters
<!-- rule: AD11 -->

Never concatenate or interpolate a value into SQL, DQL, or a query builder expression. Use parameter binding
(`setParameter`, `?` or `:name` placeholders) for every value, including values that come from your own code.

| ❌ Wrong:                                                  | ✅ Right:                                                                 |
|-----------------------------------------------------------|--------------------------------------------------------------------------|
| `"SELECT * FROM product WHERE id = $id"`                  | `'SELECT * FROM product WHERE id = :id'` + `['id' => $id]`               |
| `->where(sprintf("p.status = '%s'", $status))`           | `->where('p.status = :status')->setParameter('status', $status->value)`   |

> **Why?** Binding is the only reliable defence against SQL injection, and it lets the database cache the query plan.

---

## Testing

### Mirror the source tree
<!-- rule: TS1 -->

Place every test under `tests/` at the same path as the class it tests, with the `Test` suffix:
`src/Domain/Gps/Service/GpsService.php` → `tests/Domain/Gps/Service/GpsServiceTest.php`. Split by kind at the top
level only when the project needs it (`tests/Unit`, `tests/Integration`, `tests/Functional`).

> **Why?** The location of a test is derivable from the class name, so nobody searches for it and nobody writes a
> second one.

### Name tests by scenario
<!-- rule: TS2 -->

Name a test method after the behaviour it proves: `test<Method><Scenario><Expectation>` in camelCase, or a
`#[Test]` attribute with a snake-case sentence. Never name a test after the method alone.

| ❌ Wrong:                     | ✅ Right:                                                    |
|------------------------------|-------------------------------------------------------------|
| `testCreate()`               | `testCreateWithDuplicateEmailThrowsException()`             |
| `testCreate2()`              | `testCreateAssignsDefaultRole()`                            |
| `testItWorks()`              | `#[Test] public function it_rejects_expired_token(): void`  |

> **Why?** The test name is the failure message. A scenario in the name tells the reader what broke without opening
> the test.

### Structure tests as Arrange, Act, Assert
<!-- rule: TS3 -->

Write every test in three visible blocks separated by a blank line: set up the inputs, perform exactly one call,
assert the outcome. Put shared setup in `setUp()` or a builder, not in a helper that also asserts.

```php
public function testGetThrowsWhenProductIsMissing(): void
{
    $repository = $this->createStub(ProductRepositoryInterface::class);
    $repository->method('findOne')->willReturn(null);
    $manager = new ProductManager($repository);

    $this->expectException(ProductNotFoundException::class);

    $manager->get(42);
}
```

> **Why?** A fixed shape makes a test readable at a glance and makes it obvious when a test is doing too much.

### Test one action per test
<!-- rule: TS4 -->

Each test calls the method under test once and asserts the result of that one call. Don't chain "create, then
update, then delete" in one test; write three tests.

> **Why?** When a multi-step test fails, the name no longer says what broke. One action per test keeps the failure
> and the name aligned.

### Use data providers for variants
<!-- rule: TS5 -->

When the same assertion holds for several inputs, write one test with a `#[DataProvider]` that yields named cases.
Never copy a test and change one literal.

```php
#[DataProvider('provideInvalidEmails')]
public function testValidateRejectsInvalidEmail(string $email): void
{
    $this->expectException(InvalidEmailException::class);

    $this->validator->validate($email);
}

/** @return iterable<string, array{string}> */
public static function provideInvalidEmails(): iterable
{
    yield 'missing at sign' => ['user.example.com'];
    yield 'missing domain' => ['user@'];
}
```

> **Why?** Named cases document the edge cases in one place, and a new case is one line instead of a new method.

### Mock only boundaries
<!-- rule: TS6 -->

Replace with a test double only what crosses a boundary: repositories, HTTP clients, the clock, the mailer, the
event dispatcher. Use real instances of models, enums, and pure services. Prefer `createStub()` to
`createMock()` unless the test is about the interaction itself.

| ❌ Wrong:                                                | ✅ Right:                                      |
|---------------------------------------------------------|-----------------------------------------------|
| `$this->createMock(ProductCreateModel::class)`          | `new ProductCreateModel()` with properties set |
| `$this->createMock(UserCreatedEvent::class)`            | `new UserCreatedEvent($user)`                 |
| mock of a pure `PriceCalculator`                        | the real `PriceCalculator`                    |

> **Why?** A mock of a value or a pure class tests the mock's configuration instead of the code, and breaks on every
> refactor.

### Build fixtures with builders
<!-- rule: TS7 -->

Create test data through small builder or factory helpers (`ProductBuilder::active()->withPrice(100)->build()`)
instead of repeating constructor calls or array literals in every test. One builder per model, in `tests/Builder`.

> **Why?** When a model gains a field, one builder changes instead of every test, and each test shows only the
> values that matter to it.

### Use intent-revealing test data
<!-- rule: TS8 -->

Choose values that say why they were chosen: `'user@example.com'`, `'expired-token'`, `self::MISSING_PRODUCT_ID`.
Never copy production records, real names, or real identifiers into tests.

| ❌ Wrong:                               | ✅ Right:                                  |
|----------------------------------------|-------------------------------------------|
| `$id = 48213;`                         | `$id = self::MISSING_PRODUCT_ID;`         |
| `'john.smith@acme-corp.com'`           | `'owner@example.com'`                     |
| `new DateTimeImmutable('2021-03-14')`  | `$clock->now()->modify('-1 day')` (expired) |

> **Why?** The reader should not have to guess whether a number is significant, and production data leaks private
> information into the repository.

---

## Git

### Branch naming
<!-- rule: GT1 -->

Name the branch `<ISSUE-KEY>-<slug>`:

1. The issue key exactly as the tracker writes it (`JIRA-123`), never lowercased or re-prefixed
2. The slug is the issue title: lowercase, every run of non-alphanumeric characters becomes one hyphen, no
   leading or trailing hyphen
3. Keep the whole name under 80 characters; shorten the slug, never the key

For example, there is an issue "JIRA-123 Add the ability to assign access to the Product for the User from the admin
panel".

| ❌ Wrong:                                                                             | ✅ Right:                                                   |
|--------------------------------------------------------------------------------------|------------------------------------------------------------|
| `JIRA-123_Add-the-ability-to-assign-access-to-the-Product-for-the-User-from-the-admin` | `JIRA-123-add-product-access-assignment-for-user-in-admin` |
| `jira-123-add-product-access`                                                          | `JIRA-123-add-product-access`                              |
| `feature/add-product-access`                                                           | `JIRA-123-add-product-access`                              |

> **Why?** This is the shape Jira, Bitbucket, GitLab, and GitHub generate from "Create branch", so the tracker links
> the branch to the issue automatically. The key in its original case is what the tracker matches on, and a length
> cap keeps the name usable in CI and on the filesystem.

### Meaningful commits
<!-- rule: GT2 -->

Write the subject line as `<ISSUE-KEY>: <Imperative subject>`:

1. Start with the issue key, exactly as in the branch name, followed by a colon
2. Capitalise the subject and write it in the imperative: `Add`, `Remove`, `Fix`, `Refactor`
3. Keep the whole subject line under 72 characters, no period at the end
4. Add a body only when the *why* is not obvious from the diff; separate it from the subject by a blank line
5. Make small, specific commits: one logical change each

Your commit subject should be able to end the phrase "If applied, this commit will...".

| ❌ Wrong:                      | ✅ Right:                                             |
|-------------------------------|------------------------------------------------------|
| fixed bug                     | JIRA-123: Fix bug within login screen                |
| refactored due to PR comments | JIRA-123: Refactor registration page for performance |
| fixing previous commit        | JIRA-123: Fix validation tests for login form        |
| made tests pass               | JIRA-123: Update login tests for forgotten password  |
| jira-123 Some changes         | JIRA-123: Add product access service                 |

> **Why?** Clear, imperative commit messages make the history readable and let reviewers understand each change without
> opening the diff.

### Pull request naming
<!-- rule: GT3 -->

1. Use issue ID and name
2. Replace long and obscure names with short and descriptive summary
3. Must be capitalized and written in imperative present tense
4. Open the PR as a **draft** while it is not ready for review; never use a `WIP:` title prefix
5. Don't put a dot at the end

For example, there is an issue "JIRA-321 add new API endpoint: GET /product/{productId}/status".

| ❌ Wrong:                                                          | ✅ Right:                               |
|-------------------------------------------------------------------|----------------------------------------|
| `JIRA-321 add new API endpoint: GET /product/{productId}/status` | `JIRA-321 Add product status endpoint` |
| `WIP: JIRA-321 Add product status endpoint`                       | draft PR `JIRA-321 Add product status endpoint` |

> **Why?** A concise, descriptive title tells reviewers what the PR does at a glance, and the draft state blocks
> merging in the tool itself instead of relying on a prefix nobody enforces.

### Use rebase
<!-- rule: GT4 -->

Always use `git rebase` when working on your separate branch.

> **Why?** This will allow you to get a clean branch with a linear history without unnecessary merge commits.

### Squash
<!-- rule: GT5 -->

Use the squash option to merge a PR when there is more than one commit. Delete branch after merge.

> **Why?** This significantly reduces the number of commits in the target branch, which in turn allows you to move
> through the history of the branch faster.

[^1]:_Model_ - aka DTO, used for moving data, that enters the application, around functionality.
