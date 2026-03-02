# PHP developer codex :notebook_with_decorative_cover:

[![PHP](https://img.shields.io/badge/v8.3-blue?logo=php&labelColor=grey&logoColor=white)](https://www.php.net)
[![Symfony](https://img.shields.io/badge/v6.4-blue?logo=symfony&labelColor=grey)](https://symfony.com/doc/6.4/index.html)

<a href="https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md">
  <img src="https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-no-action.svg" alt="Stand With Ukraine" />
</a>

---

## Table of contents

- [Code style and basics](#code-style-and-basics)
    * [Strict typing](#strict-typing)
    * [Strict comparison operators](#strict-comparison-operators)
    * [Comparison order](#comparison-order)
    * [Comparing to boolean](#comparing-to-boolean)
    * [Checking things explicitly](#checking-things-explicitly)
    * [Match expression](#match-expression)
    * [Trailing comma](#trailing-comma)
    * [Assignments in conditions](#assignments-in-conditions)
    * [Unnecessary variables](#unnecessary-variables)
    * [Unnecessary structures](#unnecessary-structures)
    * [If usage](#if-usage)
    * [Double quotes](#double-quotes)
    * [String concatenation](#string-concatenation)
    * [Method visibility](#method-visibility)
    * [Small and understandable methods](#small-and-understandable-methods)
    * [Subtyping exceptions](#subtyping-exceptions)
    * [Catching exceptions](#catching-exceptions)
    * [Extract try catch blocks](#extract-try-catch-blocks)
    * [Immutable dates](#immutable-dates)
    * [Readonly modifier](#readonly-modifier)
    * [Function arguments](#function-arguments)
    * [Redundant PhpDoc](#redundant-phpdoc)
    * [PhpDoc on arrays](#phpdoc-on-arrays)
    * [Comment styles](#comment-styles)
    * [Lazy comments](#lazy-comments)
- [Naming conventions](#naming-conventions)
    * [Full names](#full-names)
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

### Strict typing

Always enable strict typing mode by placing the `declare(strict_types=1);` directive at the beginning of the script
file, before any other statements.

> **Why?** It helps to prevent bugs.

### Strict comparison operators

Use `===` (`!==`) instead of `==` (`!=`) everywhere. To compare without type checking type, cast both of the arguments
(for example to a string) and compare with `===`.

Same applies for `in_array` - always pass third argument as `true` for strict checking.

> **Why?** For security reasons and to avoid bugs.

### Comparison order

Put static value (`true`, `false`, `null`, `enum`) in the right of comparison operator.

| :x: Wrong:        | :white_check_mark: Right: |
|-------------------|---------------------------|
| `null === $value` | `$value === null`         |

> **Why?** Speak clearly to be understood correctly, you must. Yes, hmmm.

### Comparing to boolean

Don't use `true`/`false` keywords when checking variable which is already boolean.

| :x: Wrong:                 | :white_check_mark: Right: |
|----------------------------|---------------------------|
| `return $valid === true;`  | `return $valid;`          |
| `return $valid !== false;` | `return !$valid;`         |

**Exception:** when variable can be not only `boolean`, but also `int` or `null`.

```php
return strpos('needle', 'haystack') === false;
```

### Checking things explicitly

Use only functions or conditions that are designed for specific task. Don’t use unrelated features, even if they give
required result with less code.

- use `isset` instead of `empty` to check if array element is defined

- use `count($array) > 0` instead of `!empty($array)` to check if array is not empty, as we do not want to check
  whether `$array` is `0`, `false`, `''` or even not defined at all (in which case IDE would possibly hide some warnings
  that could help noticing possible bugs)

- use `$x !== ''` instead of `strlen($x) > 0` - length of `$x` has nothing to do with what we are trying to check here,
  even if it gives the needed result

Use all of the above even in cases where side effects are almost impossible.

> **Why?** To avoid side effects if any related code changes. The code is much easier to understand when you see
> specific checks or function calls instead of something unrelated that happens to give the needed result.

### Match expression

The `match` expression is a powerful feature that will often be the better choice to using `switch`.

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
    default => 'unknown status code',
};
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** For strict type checks and to avoid huge obscure and hard to read structures.

### Trailing comma

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
        'name' => $modle->name,
        'type' => $modle->type
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
        'name' => $modle->name,
        'type' => $modle->type,
    ];

    // ...
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** This leads to cleaner git diffs and simplify adding and removing items.

### Assignments in conditions

Don't use assignments inside conditional statements.

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
    ($b = $a->get()) !== null 
    && ($c = $b->get()) !== null
) {
    return $c->do();
}

if ($product = $this->findProduct()) {
    return $product->isActive();
}
```

</td>
<td>

```php
$b = $a->get();

if ($b !== null) {
    $c = $b->get();

    if ($c !== null) {
       return $c->do();
    }
}

$product = $this->findProduct();

if ($product !== null) {
    return $product->isActive();
}
```

</td>
        </tr>
    </tbody>
</table>

**Exception:** in a while loop condition.

> **Why?** By saving a few lines of code, the code becomes less clear - several actions occur at once.
> Furthermore, when explicitly comparing to `null`, the conditional assignment statements become more complicated.

### Unnecessary variables

Avoid unnecessary variables.

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
function find($needle, $haystack): bool
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
function find($needle, $haystack): bool
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

**Exception:** use variables to make code more understandable. For example:

```php
function canModify($object): bool
{
    $rightsGranted = isAdmin() || isOwner($object);
    $objectEditable = isNew($object) && !isLocked($object);
    
    return $rightsGranted && $objectEditable;
}
```

### Unnecessary structures

Avoid unnecessary structures.

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

> **Why?** To reduce code complexity.

### If usage

Reduce `if` usage as much as possible.

- Don’t use `if-else` construction at all. Simplify or extract that logic to a separate method.
- If there are several `if`s with different conditions - it is better to consider using `match`.
- Good `if` construction usually will have `return` inside of `if`.
- Don’t use nested `if`s in any case.
- If there is a lot of code inside `if` construction - you should reverse the condition body, make `return` inside of the `if` and write logic after this `if`.

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
    // ...
} elseif ($second) {
    // ...
} else {
    // ...
}
```

</td>
<td>

```php
match (true) {
    $first => doFirst(),
    $second => doSecond(),
    default => doDefault(),
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

> **Why?** To reduce code complexity and improve readability.

### Double quotes

Don't use double quotes in simple strings.

**Exception:**

- Single quote is used repeatedly inside the string
- Some special symbols are used, like `"\n"`

Don't use auto variable includes in double quotes (`"Hello $name!"` or `"Hello {$object->name}!"`).

> **Why?** To keep quotes usage consistent.

### String concatenation

Use `sprintf` function to concatenate strings.

:x: Wrong:

```php
$url = '/v1/template-family/' . $template->getTemplateFamily()->getId() . '/ingredient?' . http_build_query($queryParams);
```

:white_check_mark: Right:

```php
$url = sprintf(
    '/v1/template-family/%s/ingredient?%s',
    $template->getTemplateFamily()->getId(),
    http_build_query($queryParams),
);
```

> **Why?** It improves the readability of the code.

### Method visibility

Prefer `private` over `protected` over `public` as it constraints the scope.

Use `protected` when intend some property or method to be overwritten if necessary in extending classes.

Use `public` visibility only when method is called from outside of class.

> **Why?** It's easier to refactor, find usages, plan possible changes in code. Also, IDE can warn about unused methods
> or properties.

### Small and understandable methods

Try (hard) to write code that is self-explanatory. This implies writing small methods by looking at which you can
understand what the method does. Also try to name methods by their meaning, which also helps to understand the code.
Maximum `50` lines, except forms, data tables and other similar methods that requires a lot of configuration.

### Subtyping exceptions

You should never throw the `Exception` class directly. Instead, you must create custom exception extended from the SPL
library exception.

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
public function checkToken(string $token): string
{
    if ($token === 'not valid token') {
        throw new Exception('Invalid token.');
    }

    return $token;
}
```

</td>
<td>

```php
public function checkToken(string $token): string
{
    if ($token === 'not valid token') {
        throw new InvalidTokenException('Invalid token.');
    }

    return $token;
}
```

</td>
        </tr>
    </tbody>
</table>

Custom exception does not need to contain any code. It just needs to extend the SPL exception class. For common cases
(e.g. wrong type of variable in validator) you can just use an exception from the SPL library.

> **Why?** This greatly simplifies debugging and troubleshooting because you can easily determine the type of error
> that occurred. Additionally, creating custom exception classes also helps keep code organised and maintainable.

### Catching exceptions

Never catch base `Exception` class except where it’s thrown from vendor code.

In any case, never catch exception if there are few throwing places possible and only one is expected.

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

> **Why?** It is easier to find an issue if there is only one place where it happens.

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

### Immutable dates

Instead of manually applying defensive techniques to prevent unexpected mutation when passing around
date/time objects, use `DateTimeImmutable` that encapsulates those techniques, making your code more reliable.

> **Why?** To avoid object cloning and bugs.

### Readonly modifier

Prefer using readonly classes and properties. The only exception is a class that needs internal caching via property.

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

> **Why?** Readonly class prevents the creation of dynamic properties. Readonly property prevents modification of the
> property after initialization.

### Function arguments

The maximum number of function parameters should be three. Every argument you add to a function signature makes that
function harder to understand. If more than three arguments are needed - put them into an object.

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
function createUser(string $name, string $email, int $age, string $country) {
    // ...
}
```

</td>
<td>

```php
function createUser(CreateUserModel $model) {
    // ...
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** To make code more maintainable, scalable and easier to read.

### Redundant PhpDoc

Don't add PhpDoc to fully strictly typed methods.

> **Why?** Any comment needs to be maintained - if you change parameters or return type, then you must also update
> PhpDoc. If PhpDoc does not add any additional information, it merely duplicates the information already provided.

### PhpDoc on arrays

If an array (or Doctrine Collection) of strictly typed elements is used as an argument or return type,
then PhpDoc must be added to describe the type of elements in the array. Example:

```php
/**
 * @return Collection<int, Product>
 */
public function getProducts(): Collection;
```

> **Why?** Most IDEs, such as PhpStorm, can apply auto-completion or warn you of non-existing methods by reading this
> information and inferring the types of variables, properties and even method return values.

### Comment styles

Use multi-line `/** */` comments for method, property and class annotations.

Use single-line `/** @var Class $object */` annotation for local variables. Try to avoid this if possible - usually
PhpDoc is enough (sometimes it's missing, for example in vendor code).

Use `//` for single line comments in the code.

Don't use `/* */` or `#` comments at all.

If you want to comment-out something - just delete it (and use VCS to revert if needed).

> **Why no multi-line comment?** Try to keep functions small and comment them in PhpDoc. If some things are to be
> explained in function body, single line comments should be enough.

### Lazy comments

Prefer code refactoring over lazy commenting to explain its behavior. In most cases, the code should be clear enough
not to need comments at all.

> **Why?** They are technical debt - if you cannot keep it updated, it gets stale real quick. When you can't trust some
> comments, you don't know which ones you can really trust.

---

## Naming conventions

### Full names

Use full names, not abbreviations: `$entityManager` instead of `$em`, `$exception` instead of `$e`, etc.

> **Why?** It improves the readability of the code.

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

Use the repository class only for fetching objets from the database. The repository mustn't manage entities and
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

1. Use only lowercase letters, numbers and hyphens
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
