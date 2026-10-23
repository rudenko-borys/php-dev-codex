---
paths:
  - "tests/**/*.php"
---

# PHP codex: testing

Loaded when editing tests. IDs match the human codex. PHPUnit with attributes (`#[Test]`, `#[DataProvider]`).

- **TS1 Mirror the source tree**: `src/Domain/Gps/Service/GpsService.php` → `tests/Domain/Gps/Service/GpsServiceTest.php` (optionally under `tests/Unit|Integration|Functional`).
- **TS2 Name tests by scenario**: `test<Method><Scenario><Expectation>` (`testCreateWithDuplicateEmailThrowsException`) or `#[Test] it_rejects_expired_token`. Never `testCreate`, `testCreate2`, `testItWorks`.
- **TS3 Structure tests as Arrange, Act, Assert**: three blocks separated by blank lines; shared setup in `setUp()` or builders; helpers never assert.
- **TS4 Test one action per test**: one call to the method under test per test method; "create then update then delete" is three tests.
- **TS5 Use data providers for variants**: same assertion for several inputs → one test + `#[DataProvider]` with named `yield 'case name' => [...]`; never copy a test and change a literal. Provider is `public static`, PHPDoc `@return iterable<string, array{...}>`.
- **TS6 Mock only boundaries**: doubles only for repositories, HTTP clients, clock, mailer, event dispatcher. Real instances for Models, enums, pure services. `createStub()` unless the test is about the interaction.
- **TS7 Build fixtures with builders**: one builder per Model in `tests/Builder` (`ProductBuilder::active()->withPrice(100)->build()`); no repeated constructor calls or array literals.
- **TS8 Use intent-revealing test data**: `'owner@example.com'`, `self::MISSING_PRODUCT_ID`, `$clock->now()->modify('-1 day')`; never production records, real names, or unexplained literals.

Use `MockClock` (Symfony Clock) for time (CM9).
