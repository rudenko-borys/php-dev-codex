# PHP developer codex :notebook_with_decorative_cover:

[![PHP](https://img.shields.io/badge/v8.3-blue?logo=php&labelColor=grey&logoColor=white)](https://www.php.net)
[![Symfony](https://img.shields.io/badge/v6.4-blue?logo=symfony&labelColor=grey)](https://symfony.com/doc/6.4/index.html)

<a href="https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md">
  <img src="https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-no-action.svg" alt="Stand With Ukraine" />
</a>

---

## Table of contents

- [Code style and basics](#code-style-and-basics)
    * [Enable strict typing](#enable-strict-typing)
    * [Use strict comparison operators](#use-strict-comparison-operators)
    * [Comparison order](#comparison-order)
    * [Compare booleans directly](#compare-booleans-directly)
    * [Check things explicitly](#check-things-explicitly)
    * [Prefer match expressions](#prefer-match-expressions)
    * [Add trailing comma](#add-trailing-comma)
    * [Avoid assignments in conditions](#avoid-assignments-in-conditions)
    * [Avoid unnecessary variables](#avoid-unnecessary-variables)
    * [Avoid unnecessary nesting](#avoid-unnecessary-nesting)
    * [Keep if statements simple](#keep-if-statements-simple)
    * [Use quotes properly](#use-quotes-properly)
    * [Prefer sprintf for string concatenation](#prefer-sprintf-for-string-concatenation)
    * [Control scope via visibility](#control-scope-via-visibility)
    * [Write small and understandable methods](#write-small-and-understandable-methods)
    * [Use immutable dates](#use-immutable-dates)
    * [Use readonly classes and properties](#use-readonly-classes-and-properties)
    * [Reduce function parameters](#reduce-function-parameters)
- [Exceptions](#exceptions)
    * [Throw specific exceptions](#throw-specific-exceptions)
    * [Catch specific exceptions](#catch-specific-exceptions)
    * [Extract try catch blocks](#extract-try-catch-blocks)
- [Comments](#comments)
    * [Eliminate redundant PHPDoc](#eliminate-redundant-phpdoc)
    * [Document array element types with PHPDoc](#document-array-element-types-with-phpdoc)
    * [Follow commenting standards](#follow-commenting-standards)
    * [Refactor instead of explaining](#refactor-instead-of-explaining)
- [Naming conventions](#naming-conventions)
    * [Favor full names](#favor-full-names)
    * [Class naming](#class-naming)
    * [Interface naming](#interface-naming)
    * [Property naming](#property-naming)
    * [Method naming](#method-naming)
    * [Event naming](#event-naming)
    * [Event class naming](#event-class-naming)
    * [Event subscriber naming](#event-subscriber-naming)
    * [Route path naming](#route-path-naming)
- [ADRs](#adrs)
    * [Model without defaults](#model-without-defaults)
    * [Model without logic](#model-without-logic)
    * [Fetch-only repository](#fetch-only-repository)
    * [Composite service](#composite-service)
    * [Service interaction](#service-interaction)
- [Git](#git)
    * [Branch naming](#branch-naming)
    * [Meaningful commits](#meaningful-commits)
    * [Pull request naming](#pull-request-naming)
    * [Use rebase](#use-rebase)
    * [Squash](#squash)

---

## Code style and basics

PHP code style extends [PSR-1](https://www.php-fig.org/psr/psr-1) and [PSR-12](https://www.php-fig.org/psr/psr-12),
so code must also follow these standards to be compatible with this codex.

### Enable strict typing

Every PHP script must include the `declare(strict_types=1);` directive as the very first statement in the file.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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

Always use strict comparison operators (`===` and `!==`) instead of loose operators (`==` and `!=`). If a type-agnostic
comparison is required, perform an explicit cast on both operands (e.g., `(string)$a === (string)$b`) before comparing
with the strict operator.

For `in_array()`, always set the third parameter (`$strict`) to `true` to ensure elements are checked for both value
and type.

| :x: Wrong:                     | :white_check_mark: Right:                    |
|--------------------------------|----------------------------------------------|
| `$value == null`               | `$value === null`                            |
| `in_array($needle, $haystack)` | `in_array($needle, $haystack, strict: true)` |

> **Why?** To prevent bugs caused by unexpected type conversion and to ensure the application is secure against
> type-juggling attacks.

### Comparison order

Put static value (`true`, `false`, `null`, `enum`) in the right of comparison operator.

| :x: Wrong:        | :white_check_mark: Right: |
|-------------------|---------------------------|
| `null === $value` | `$value === null`         |

> **Why?** Speak clearly to be understood correctly, you must. Yes, hmmm.

### Compare booleans directly

Don't perform identity comparisons against `true` or `false` when a variable is already of type `boolean`.

| :x: Wrong:                 | :white_check_mark: Right: |
|----------------------------|---------------------------|
| `return $valid === true;`  | `return $valid;`          |
| `return $valid !== false;` | `return !$valid;`         |

**Exception:** when a variable is not strictly `boolean`.

```php
return strpos('needle', 'haystack') === false;
```

> **Why?** To reduce complexity.

### Check things explicitly

Use only functions and conditions that match the exact intent of the check. Avoid unrelated constructs, even if they
produce the desired result with less code.

| :x: Wrong:           | :white_check_mark: Right: |
|----------------------|---------------------------|
| `empty($value)`      | `isset($value)`           |
| `!empty($array)`     | `$array !== []`           |
| `strlen($value) > 0` | `$value !== ''`           |

Use all of the above even in cases where side effects are almost impossible.

> **Why?** To avoid side effects. Explicit checks are easier to read, less surprising, and safer if the surrounding
> code changes later.

### Prefer match expressions

Use `match` instead of `switch` to simplify conditional logic and reduce the "cognitive load" required to read the code.
Unlike the `switch` statement, which is used for executing blocks of code, `match` is an expression that returns a
value.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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
    400 => $this->getValidationMessage(),
    500 => 'server error',
    default => 'unknown status code'
};
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** For strict type checks and to avoid huge obscure and hard to read structures.

### Add trailing comma

Always add a trailing comma in multiline arrays, objects, functions, etc.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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

### Avoid assignments in conditions

Don't perform assignments within conditional statements. All variable assignments must be completed as independent
statements before the condition is evaluated.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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

### Avoid unnecessary variables

Avoid temporary variables when they don't clarify the code.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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
    $value = get();

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
    return get();
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
    
    return $rightsGranted && $profileEditable;
}
```

> **Why?** Extra variables add noise, but useful variables can make the code easier to understand.

### Avoid unnecessary nesting

Avoid nested conditions when a single condition is enough.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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

Reduce `if` usage as much as possible.

- Prefer simplifying the logic or extracting it into a separate method.
- Use `match` for multiple conditions where possible.
- Apply the "Early Return" pattern.
- Avoid nested `if` statements.
- Invert heavy blocks: reverse the condition of the large `if` block to keep the main logic at the top level.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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

### Use quotes properly

Use single quotes for strings by default. Use double quotes only when the string contains single quotes or requires
escape characters such as `\n`. Never embed variables directly in strings - use `sprintf` instead.

| :x: Wrong:                  | :white_check_mark: Right:     |
|-----------------------------|-------------------------------|
| `"Hello, World!"`           | `'Hello, World!'`             |
| `'It\'s a beautiful day'`   | `"It's a beautiful day"`      |
| `'First line\nSecond line'` | `"First line\nSecond line"`   |
| `"Hello $name!"`            | `sprintf('Hello %s!', $name)` |

> **Why?** To keep quotes usage consistent. Single quotes signal that the string is plain text - no variables, no
> escape sequences - making it faster to read and safer to edit.

### Prefer sprintf for string concatenation

Use formatted templates (`sprintf`) to ensure that data injection is explicit and easy to trace during debugging.

:x: Wrong:

```php
$url = '/v1/templates/' . $template->getTemplateFamily()->getId() . '/ingredients?' . http_build_query($queryParams);
```

:white_check_mark: Right:

```php
$url = sprintf(
    '/v1/templates/%s/ingredients?%s',
    $template->getTemplateFamily()->getId(),
    http_build_query($queryParams),
);
```

> **Why?** To separate the string template from its values, making long strings easier to read.

### Control scope via visibility

Adhere to the principle of least privilege by applying the most restrictive access modifier to all class members.

- Use `private` for all internal implementation details.
- Use `protected` only when a member is intended to be accessible by, or overridable in, subclasses.
- Use `public` exclusively for members called from outside the class.

> **Why?** Restrictive visibility limits the scope of changes and allows the IDE to detect unused methods and
> properties.

### Write small and understandable methods

To minimize a cognitive load, favor small, encapsulated methods that are easy to reason about. Implement
self-explanatory logic by utilizing expressive naming conventions that communicate the method's intent clearly.

To prevent "God Methods", maintain a maximum threshold of `50` lines per function.

**Exception:** in cases of high-density declarative configuration (such as schema definitions or form layouts), where
line count increases due to structural complexity rather than procedural logic.

> **Why?** Small, well-named methods are easier to read, test, and reuse.

### Use immutable dates

All date and time operations must utilize `DateTimeImmutable` instead of `DateTime`.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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

Prefer using `readonly` classes and properties.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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
lifecycle changes).

> **Why?** Readonly modifier prevents accidental mutation after initialization and disallows dynamic property creation.

### Reduce function parameters

The maximum number of function parameters should be three. Every parameter you add to a function signature makes that
function harder to understand. If more parameters are needed, group them into a dedicated object.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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

> **Why?** To make code more maintainable, scalable, and easier to read.

## Exceptions

### Throw specific exceptions

Never throw the base `Exception` class.

- For domain logic: Create custom exceptions that extend the appropriate SPL exception. They may remain empty; their
  value lies in their type.
- For standard errors: Utilize existing SPL exceptions for common error states.

| :x: Wrong:                               | :white_check_mark: Right:                           |
|------------------------------------------|-----------------------------------------------------|
| `throw new Exception('Invalid token.')`  | `throw new InvalidTokenException('Invalid token.')` |
| `throw new Exception('Bad input.')`      | `throw new InvalidArgumentException('Bad input.')`  |

> **Why?** Specific exceptions make it easier to identify the error type, handle it precisely, and keep error handling
> organized.

### Catch specific exceptions

Always catch the most specific exception type available. Never catch base `Exception` or `Throwable` classes.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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

Extract the bodies of the `try` and `catch` blocks out into functions of their own. Try/catch blocks confuse the
structure of the code and mix error processing with normal processing.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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
} catch (Exception) {
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
} catch (UnknownProductTypeException $exception) {
    $this->handleError($exception);
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** To provide a nice separation that makes the code easier to understand and modify.

## Comments

### Eliminate redundant PHPDoc

Don't add PHPDoc to methods that are already fully type-hinted.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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

> **Why?** To reduce maintenance dept and cognitive load. If PHPDoc does not add any additional information, it merely
> duplicates the information already provided.

### Document array element types with PHPDoc

Add PHPDoc to describe element types when using `arrays` or `Collections` as arguments or return types.

```php
/**
 * @return Collection<int, Product>
 */
public function getProducts(): Collection;
```

> **Why?** IDEs use this information to provide auto-completion and type warnings.

### Follow commenting standards

Use the right comment syntax depending on what you are documenting.

- Use multi-line `/** */` comments for method, property, and class annotations.
- Use single-line `/** @var Class $object */` comments for local variables if type is missing in vendor code.
- Use `//` for single line comments.
- Don't use `/* */` or `#` comments at all.
- Never leave commented-out code. If code is no longer needed, delete it. Use VCS to revert if needed.

> **Why?** Consistent comment styles make code easier to read and process with tooling.

### Refactor instead of explaining

Prefer code refactoring over lazy commenting to explain its behavior. Code that explains itself needs no comments.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
        </tr>
    </thead>
    <tbody>
        <tr>
<td>

```php
// Check if the user is a product manager
if (
    $user->getRole() === ROLE::MANAGER
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

---

## Naming conventions

### Favor full names

Always use full, descriptive names instead of abbreviations.

| :x: Wrong:             | :white_check_mark: Right:    |
|------------------------|------------------------------|
| `$em`                  | `$entityManager`             |
| `$e`                   | `$exception`                 |
| `function delMsg($id)` | `function delayMessage($id)` |

**Exception:** established acronyms like `GPS`, `SQL`, `URL`, etc.

> **Why?** To reduce cognitive load and prevent ambiguity. Explicit names ensure that the intent of the code is clear
> to future maintainers.

### Name constants and enums consistently 

Constant and enum case names must be uppercase. Start the name with the shared prefix, followed by the specific part. :x: Wrong: :white_check_mark: Right: SUCCEEDED_STATUS STATUS_SUCCEEDED FAILED_STATUS STATUS_FAILED Why? Shared prefixes group related constants visually, making them easier to scan and auto-complete

### Class naming

Use nouns for class names. Use object names only for entities, for example `Product`.

Use `er` suffix for services to represent the job of that service, for example:
`manager`, `normalizer`, `provider`, `updater`, `controller`, `registry`, `resolver`, etc.

**Exception:** main service class.

### Interface naming

Add suffix `Interface` to interfaces, even if interface name would be adjective.

> **Why?** If there is a base class that implements the interface, then there will be a name conflict.
> For example, `ContainerAware` and `ContainerAwareInterface`.

### Property naming

Use nouns or adjectives for property names, not verbs or questions.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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

### Method naming

Use verbs for methods that perform action and/or return something, questions only for methods which return boolean.

Questions must start with `has`, `is`, `can` - these cannot make any side effect and always return boolean.

For entities use `is` or `are` for boolean getters, `get` for other getters, `set` for setters, `add` for adders
and `remove` for removers.

Make proper english phrase out of method names, it's more important than calling a method `'is' + propertyName.`

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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

### Event naming

Name events in past-tense verbs, prefixed by resource for which some action happened. Separate the resource and action
with a dot.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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

Use the event name for the event class name and add the `Event` suffix. See example above.

> **Why?** Since an event can only have one concrete class, its name must match the `NAME` constant.

### Event subscriber naming

The subscriber must be named according to the action it will perform with the suffix `Subscriber`.
Example: `ResetPasswordSubscriber`.

> **Why?** A subscriber can receive many events, but with the same required action, so its name must describe the
> action it performs.

### Route path naming

1. Use nouns
2. Use lowercase letters
3. Use hyphens (`-`) instead of underscores (`_`)
4. Don't use trailing forward slash
5. Don't use file extensions
6. Don't use CRUD function names
7. Use versions

| :x: Wrong:              | :white_check_mark: Right:  |
|-------------------------|----------------------------|
| `/store/GetProducts/`   | `/v1/store/product`        |
| `/store/products.json`  | `/v1/store/product`        |
| `/template_Family/{ID}` | `/v1/template-family/{id}` |
| `/templateFamily/{id}`  | `/v1/template-family/{id}` |

> **Why?** Nouns are used to specify the resource (**1**), the URIs shouldn't indicate any CRUD operations (**6**).
> Lowercase letters often need to be used for SEO purposes, search engines are case-sensitive (**2**).
> Hyphens improves the readability (**3**). A forward slash as last character adds no semantic value (**4**).
> File extensions are unnecessary and add length and complexity (**5**). By versioning your APIs, you can provide an
> upgrade path without making any fundamental changes to existing APIs (**7**).

## ADRs

It stands for **Architectural Decision Record**, but in this case it's just **Any Decision Record** to make it simple.
It's the rules you decide to apply to your project.

I work on projects based on Service-Oriented Architecture (SOA), so ADRs are directly related to it.
Services are responsible for the business logic of one specific entity or discrete unit of functionality.
For example, `UserService` is responsible for all the logic related to the user entity, while `SecurityService` is
responsible for all the logic related to the authentication functionality.

### Model without defaults

Model[^1] properties mustn't contain default values. Service must handle the model properties.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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

### Model without logic

Model[^1] mustn't contain any logic. All logic is handled in services.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
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

> **Why?** This allows to change and configure behaviour in different context.

### Fetch-only repository

Use the repository class only for fetching objects from the database. The repository mustn't manage entities and
mustn't contain business logic, this is the responsibility of services.

> **Why?** Any logic not related to fetching objects violates the Single Responsibility Principle and Service-Oriented
> Architecture.

### Composite service

Don't concentrate all business logic in one service. Create multiple services to perform specific tasks and combine
them into one composite service. For example, the composite `UserService` would be composed of the specific
`UserManager` and `UserSynchronizer` services.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
        </tr>
    </thead>
    <tbody>
        <tr>
<td>

```php
class UserService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
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
class UserService
{
    public function __construct(
        private readonly UserManager $userManager,
        private readonly UserSynchronizer $userSynchronizer,
    ) {
    }

    public function create(UserCreateModel $model): User
    {
        return $this->userManager->create($model);
    }

    public function synchronize(User $user): void
    {
        $this->userSynchronizer->synchronize($user);
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

### Service interaction

Services responsible for the business logic of different entities must interact with each other through the event
system. The service responsible for the business logic of the user mustn't contain the service responsible for the
authorization logic and vice versa. Otherwise, you will get one interconnected service instead of two independent ones.

<table>
    <thead>
        <tr>
            <th>:x: Wrong</th>
            <th>:white_check_mark: Right</th>
        </tr>
    </thead>
    <tbody>
        <tr>
<td>

```php
class UserManager
{
    public function __construct(
        private readonly SecurityService $securityService,
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
class UserManager
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
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

---

## Git

### Branch naming

1. Use only lowercase letters, numbers, and hyphens
2. Use a unique ID based on issue ID
3. Use the issue name for the branch name
4. Name must not exceed 8 words (excluding ID)

For example, there is an issue "JIRA-123 Add the ability to assign access to the Product for the User from the admin
panel".

:x: Wrong:

JIRA-123_Add-the-ability-to-assign-access-to-the-Product-for-the-User-from-the-admin

:white_check_mark: Right:

jira-123-add-product-access-assignment-for-user-in-admin

### Meaningful commits

When committing your code, it's helpful to write useful commit messages.

1. Start each message with issue ID
2. Capitalise subject
3. Use imperative commands such as: `add`, `remove`, `fix`, `refactor`, etc.
4. Keep it brief. Message mustn't exceed 50 characters (excluding ID)
5. Make small, specific commits
6. Don't put a dot at the end

Your commit message should be able to end the phrase "If applied, this code will...".

| :x: Wrong:                    | :white_check_mark: Right:                           |
|-------------------------------|-----------------------------------------------------|
| fixed bug                     | JIRA-123 Fix bug within login screen                |
| refactored due to PR comments | JIRA-123 Refactor registration page for performance |
| fixing previous commit        | JIRA-123 Fix validation tests for login form        |
| made tests pass               | JIRA-123 Update login tests for forgotten password  |
| jira-123 Some changes         | JIRA-123 Add product access service                 |

### Pull request naming

1. Use issue ID and name
2. Replace long and obscure names with short and descriptive summary
3. Must be capitalized and written in imperative present tense
4. Always use `WIP:` prefix if PR is not ready
5. Don't put a dot at the end

For example, there is an issue "JIRA-321 add new API endpoint: GET /product/{productId}/status".

:x: Wrong:

JIRA-321 add new API endpoint: GET /product/{productId}/status

:white_check_mark: Right:

JIRA-321 Add product status endpoint

### Use rebase

Always use `git rebase` when working on your separate branch.

> **Why?** This will allow you to get a clean branch with a linear history without unnecessary merge commits.

### Squash

Use the squash option to merge a PR when there is more than one commit. Delete branch after merge.

> **Why?** This significantly reduces the number of commits in the target branch, which in turn allows you to move
> through the history of the branch faster.

[^1]:_Model_ - aka DTO, used for moving data, that enters the application, around functionality.
