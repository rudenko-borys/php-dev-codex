# PHP codex: architecture (ADRs)

Always loaded. IDs match the human codex. Service-Oriented Architecture: one service family per entity or unit of functionality.

## Layers

- **AD1 Layered architecture**: five layers, dependencies point inwards only:
  `Presentation → Application → Domain ← Infrastructure`, `Framework` wires everything.
  - `Application`: flows/orchestration (`*Service`, `*Puller`, `*Handler`, `*Processor`); called only from Presentation; depends on Domain only.
  - `Domain`: Models, Enums, Exceptions, Interfaces (`*ManagerInterface`, `*RepositoryInterface`); depends on nothing.
  - `Infrastructure`: implements Domain interfaces with concrete tools (Doctrine, HTTP clients). `*Manager` implements `get`/`create`/`update`/`delete`; `get` throws not-found, never returns `null`.
  - `Framework`: CompilerPass, Bundles, Kernel, DI wiring.
  - `Presentation`: `Cli` (Commands), `Ui` (Controllers, Forms, DataTables, Validators), `Api` (API controllers).
  - Application and Presentation never `use` an Infrastructure class; they depend on Domain interfaces, DI injects the implementation.
- **AD2 Directory tree**: monolith = one bundle per domain, each bundle holds `Application/ Domain/ Infrastructure/ Framework/ Presentation/`; microservice = the five layers at `src/` top level, each holding one folder per domain (`Domain/Gps/Service/GpsManagerInterface.php`, `Infrastructure/Gps/Service/GpsManager.php`). Shared classes go in `Platform/` (monolith) or `Shared/` (microservice). Folder names are singular (`Model`, `Repository`, `DataTransformer`). Tool-specific models stay beside the tool (`Infrastructure/Doctrine/Model`).

## Models and services

- **AD3 Model without defaults**: Model (DTO) properties have no default values and no `= null`; an uninitialised property means "not provided", `null` means "clear it". Services check `isset`/initialisation, never overwrite with defaults.
- **AD4 Model without logic**: Models hold public typed properties only. No methods, no static factories, no property hooks. Built in exactly two places: Presentation maps external input into a Model (form, serializer, command args), a `*Filler` builds one from entities/internal data. Managers and services consume Models, never build one from a raw array.
- **AD5 Fetch-only repository**: repositories only read (`find*`, `count*`, `sum*`), return `null`/empty when nothing matches, never persist, never contain business logic. `Manager::get()` is the one place that converts "missing" into a not-found exception.
- **AD6 Composite service**: `UserService` delegates to specific `UserManager`, `UserSynchronizer`, ...; the composite is the only service other classes (controllers, subscribers) inject. No business logic in the composite itself. Split is required once an entity has 2+ specific services; a lone specific service stands alone under its job name (`OrderManager`). A class named `<Entity>Service` with logic inside violates this rule: move the logic to a specific service.
- **AD7 Service interaction**: services of different entities never inject each other; they communicate through events (`$this->eventDispatcher->dispatch(new UserCreatedEvent($user), UserCreatedEvent::NAME)`).
- **AD8 Inject dependencies through the constructor**: typed against Domain interfaces; never setter injection, `#[Required]`, service locator, container access, or `new` for a service.
- **AD9 Wrap third-party code**: vendor SDKs and HTTP clients are called only from Infrastructure, behind a Domain interface shaped around your needs (`GpsProviderInterface` ← `SamsaraGpsProvider`); add a boundary test for the implementation.
- **AD10 Validate at the presentation layer**: controllers/commands map raw input to a Model and validate it (`#[Assert\*]`); services receive valid typed Models and never re-check shape.
- **AD11 Bind query parameters**: never interpolate values into SQL/DQL/QueryBuilder strings; always `:name` placeholders + `setParameter`, even for values from your own code.
