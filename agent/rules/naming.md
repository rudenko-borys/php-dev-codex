# PHP codex: naming rules

Always loaded. IDs match the human codex.

## General naming

- **GN1 Favor full names**: `$entityManager` not `$em`, `$exception` not `$e`, `deleteMessage` not `delMsg`. Exception: established acronyms (`GPS`, `SQL`, `URL`).
- **GN2 Avoid uppercased abbreviations**: `getGpsSql()`, `$userId`, `ApiClient`; never `getGPSSQL()`, `$userID`, `APIClient`.
- **GN3 Context duplication in naming**: drop the entity prefix, keep the role: inside `FileCategoryService` the property is `$manager`, not `$fileCategoryManager`; inside `OrderService` it is `$repository`, not `$orderRepository`; inside `LoadManagerInterface` the method is `create()`, not `createLoad()`.
- **GN4 Avoid double plural constructions**: `$driverLoads`, not `$driversLoads`; pluralise only the last noun.
- **GN5 Name constants and enums consistently**: UPPERCASE, shared prefix first: `STATUS_SUCCEEDED`, `STATUS_FAILED`.

## Type naming

- **TN1 Class naming**: entity/model = bare noun (`Product`, `ProductCreateModel`); specific service = entity + job `er` noun (`ProductManager`, `ProductNormalizer`, `ProductResolver`); composite service = entity + `Service` (`ProductService`). Never `Helper`, `Util`, `Logic`.
- **TN2 Interface naming**: suffix `Interface`, always (`ContainerAwareInterface`).
- **TN3 Abstract class naming**: prefix `Abstract` (`AbstractController`).
- **TN4 Trait naming**: suffix `Trait`.
- **TN5 Enum naming**: suffix `Enum` (`ImportTypeEnum`).
- **TN6 Property naming**: nouns or adjectives only: `$valid`, `$checkNeeded`; never `$isValid`, `$check`.

## Method and symbol naming

- **MN1 Method naming**: verbs for actions; boolean methods start with `is`/`are`/`has`/`can`/`must`, have no side effects. Entities: `is`/`are` for bool getters, `get`, `set`, `add`, `remove`. Make a real English phrase: `areTaxesIncluded()`, `canBeChecked()`, never `getIsValid()`, `isNeedsChecking()`.
- **MN2 Repository method naming**: public repository methods start with `find`, `count`, or `sum` only; single entity = `findOne`. Never `get*`/`save*` in a repository (responsibility rule: AD5).
- **MN3 Filler and provider method naming**: fillers expose only `fill*`, providers only `provide*`.
- **MN4 Event naming**: past tense, `resource.action` string: `'user.created'`; one name per event class.
- **MN5 Event class naming**: class = event name + `Event`: `UserCreatedEvent` with `NAME = 'user.created'`.
- **MN6 Event subscriber naming**: named after the action it performs + `Subscriber`: `ResetPasswordSubscriber`.
