# Laravel codex

[![PHP](https://img.shields.io/badge/v8.5-blue?logo=php&labelColor=grey&logoColor=white)](https://www.php.net)
[![Laravel](https://img.shields.io/badge/v12_|_v13-blue?logo=laravel&labelColor=grey)](https://laravel.com/docs)

---

## About

Rules for writing Laravel applications: how to use the framework's own features and how to structure an application
as modules. This file extends the [PHP developer codex](README.md); every rule there applies here too, and where
both speak about the same thing the Laravel form in this file wins.

Rules follow [RULE_FORMAT](RULE_FORMAT.md). IDs use the prefixes `LV` (framework) and `MA` (module architecture).
This file has no agent version yet and is not covered by `bin/check-sync.php`.

Target stack for all examples: PHP 8.5, Laravel 12 and 13, PHPUnit. A feature that needs Laravel 13 says so.

**Where the Laravel form replaces a codex rule**

| Codex rule | In a Laravel application |
|---|---|
| [Layered architecture](README.md#layered-architecture), [Directory tree](README.md#directory-tree) | [Module architecture](#module-architecture) |
| [Composite service](README.md#composite-service) | the module service: [Expose a module through its service](#expose-a-module-through-its-service) |
| [Fetch-only repository](README.md#fetch-only-repository) | reads plus set-based writes (`upsert`, bulk `update`); single-entity writes stay in managers |
| [Inject the clock](README.md#inject-the-clock) | [Read time from the framework](#read-time-from-the-framework) |
| [Convert enums at the boundaries](README.md#convert-enums-at-the-boundaries) | [Map enums through the framework](#map-enums-through-the-framework) |
| [Validate at the presentation layer](README.md#validate-at-the-presentation-layer) | [Validate with form requests](#validate-with-form-requests) |
| [Build fixtures with builders](README.md#build-fixtures-with-builders) | [Build data with factories](#build-data-with-factories) |
| [Event subscriber naming](README.md#event-subscriber-naming) | suffix `Listener`: [Name jobs, listeners, and commands](#name-jobs-listeners-and-commands) |

---

## Table of contents

- [HTTP boundary](#http-boundary)
    * [Keep controllers thin](#keep-controllers-thin)
    * [Validate with form requests](#validate-with-form-requests)
    * [Read typed input](#read-typed-input)
    * [Write rules as arrays of rule objects](#write-rules-as-arrays-of-rule-objects)
    * [Map enums through the framework](#map-enums-through-the-framework)
    * [Authorize with policies](#authorize-with-policies)
    * [Shape responses with API resources](#shape-responses-with-api-resources)
    * [Return status codes by constant](#return-status-codes-by-constant)
    * [Bind models in routes](#bind-models-in-routes)
    * [Define routes declaratively](#define-routes-declaratively)
    * [Paginate every collection](#paginate-every-collection)
- [Eloquent](#eloquent)
    * [Keep models to mapping](#keep-models-to-mapping)
    * [Use model attributes instead of boilerplate](#use-model-attributes-instead-of-boilerplate)
    * [Cast dates to immutable](#cast-dates-to-immutable)
    * [Guard mass assignment](#guard-mass-assignment)
    * [Prevent N+1 queries](#prevent-n1-queries)
    * [Let the database do the work](#let-the-database-do-the-work)
    * [Stream large result sets](#stream-large-result-sets)
    * [Prefer collections and helpers](#prefer-collections-and-helpers)
    * [Query through the model](#query-through-the-model)
- [Transactions and side effects](#transactions-and-side-effects)
    * [Wrap the use case in a transaction](#wrap-the-use-case-in-a-transaction)
    * [Dispatch side effects after commit](#dispatch-side-effects-after-commit)
    * [Queue slow and external work](#queue-slow-and-external-work)
    * [Decouple modules with events](#decouple-modules-with-events)
    * [Name jobs, listeners, and commands](#name-jobs-listeners-and-commands)
    * [Keep the schedule in one file](#keep-the-schedule-in-one-file)
- [Container, config, and infrastructure](#container-config-and-infrastructure)
    * [Inject through the constructor](#inject-through-the-constructor)
    * [Read config, never env](#read-config-never-env)
    * [Inject values with contextual attributes](#inject-values-with-contextual-attributes)
    * [Pass the current user down](#pass-the-current-user-down)
    * [Call external APIs through the HTTP client](#call-external-apis-through-the-http-client)
    * [Cache with the framework primitives](#cache-with-the-framework-primitives)
    * [Defer and parallelize deliberately](#defer-and-parallelize-deliberately)
    * [Read time from the framework](#read-time-from-the-framework)
    * [Map errors outside controllers](#map-errors-outside-controllers)
    * [Log with placeholders and context](#log-with-placeholders-and-context)
- [Migrations and generators](#migrations-and-generators)
    * [Write safe migrations](#write-safe-migrations)
    * [Generate only global files](#generate-only-global-files)
    * [Generate model PHPDoc](#generate-model-phpdoc)
- [Testing](#testing)
    * [Test features through HTTP](#test-features-through-http)
    * [Build data with factories](#build-data-with-factories)
    * [Fake the edges, not the application](#fake-the-edges-not-the-application)
    * [Control time in tests](#control-time-in-tests)
    * [Test both sides of authorization](#test-both-sides-of-authorization)
    * [Write PHPUnit tests](#write-phpunit-tests)
- [Module architecture](#module-architecture)
    * [Group code by module](#group-code-by-module)
    * [Use the closed folder vocabulary](#use-the-closed-folder-vocabulary)
    * [Follow the call direction](#follow-the-call-direction)
    * [Return domain types from managers](#return-domain-types-from-managers)
    * [Type manager signatures](#type-manager-signatures)
    * [Name managers by use case](#name-managers-by-use-case)
    * [Write interfaces only at a boundary](#write-interfaces-only-at-a-boundary)
    * [Expose a module through its service](#expose-a-module-through-its-service)
    * [Import only the public surface](#import-only-the-public-surface)
    * [Read models of lower modules only](#read-models-of-lower-modules-only)
    * [Keep dependencies one-way](#keep-dependencies-one-way)
    * [Publish events as completed facts](#publish-events-as-completed-facts)
    * [Let the writer own the table](#let-the-writer-own-the-table)
    * [Keep the shared module tiny](#keep-the-shared-module-tiny)
    * [Keep support classes pure](#keep-support-classes-pure)
    * [Wire a module in its provider](#wire-a-module-in-its-provider)
    * [Keep schema and tests global](#keep-schema-and-tests-global)
    * [Move without changing behaviour](#move-without-changing-behaviour)
    * [Respect size targets](#respect-size-targets)
- [Sources](#sources)

---

## HTTP boundary

### Keep controllers thin
<!-- rule: LV1 -->

Always limit a controller action to three steps: take a form request, call one manager, wrap the result in a
resource. Max 10 lines per action. Never put queries, business `if`s, or `try/catch` in a controller.

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
public function store(Request $request): JsonResponse
{
    $data = $request->validate(['name' => 'required']);

    if (Tag::where('name', $data['name'])->exists()) {
        return response()->json(['error' => 'Exists.'], 409);
    }

    return response()->json(Tag::create($data), 201);
}
```

</td>
<td>

```php
public function store(TagCreateRequest $request): JsonResponse
{
    $tag = $this->createManager->handle(
        $request->string('name')->toString(),
    );

    return TagResource::make($tag)
        ->response()
        ->setStatusCode(Response::HTTP_CREATED);
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** A thin action can be read in one glance and its logic can be reused from a job or a command, because none of
> it lives in the HTTP layer.

### Validate with form requests
<!-- rule: LV2 -->

Always validate and authorize input in a `FormRequest`. Never call `Validator::make()` or `$request->validate()` in a
controller, and never read `$request->all()` or `$request->input()` past the boundary.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `$request->validate(['name' => 'required'])` | `public function store(TagCreateRequest $request)` |
| `$manager->handle($request->all())` | `$manager->handle($request->string('name')->toString())` |

> **Why?** A form request gives validation one home, runs before the action body, and keeps unvalidated keys from
> leaking into the application.

### Read typed input
<!-- rule: LV3 -->

Always read validated input with `validated()`, `safe()->only([...])`, or a typed accessor: `string()`, `integer()`,
`boolean()`, `date()`, `enum()`, `collect()`. Pass typed values or a data class to the manager, never the raw array.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `$request->input('per_page')` | `$request->integer('per_page')` |
| `$request->get('status')` | `$request->enum('status', StatusEnum::class)` |
| `$manager->handle($request->validated())` | `new TagFilter(search: $request->string('search')->toString())` |

> **Why?** Typed accessors convert once at the boundary, so inner code works with `int`, `bool`, and enums instead of
> strings that happen to look like them.

### Write rules as arrays of rule objects
<!-- rule: LV4 -->

Always write validation rules as arrays, never pipe strings. Use `Rule::enum()`, `Rule::in()`, `Rule::unique()`,
`Rule::exists()`, `Rule::anyOf()`, and `Password::defaults()`; never build an `in:` string by hand. Put a reusable
check into a class in `Rules/`.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `'status' => 'in:' . implode(',', Status::values())` | `'status' => ['required', Rule::enum(StatusEnum::class)]` |
| `'email' => 'unique:users,email,' . $id` | `'email' => [Rule::unique('users')->ignore($id)]` |

> **Why?** Rule objects are checked by the IDE and static analysis and cannot be broken by a value that contains `,` or
> `|`.

### Map enums through the framework
<!-- rule: LV5 -->

Always let Laravel convert enums: validate with `Rule::enum()`, read with `$request->enum()`, cast on the model, and
type-hint the enum in a route for binding. Pass enum cases straight into `where`, `whereIn`, `update`, and `insert`.
Never map to `->value` by hand and never keep a `values()` helper for queries.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `->whereIn('status', array_column(StatusEnum::cases(), 'value'))` | `->whereIn('status', StatusEnum::cases())` |
| `in_array($value, StatusEnum::values(), strict: true)` | `StatusEnum::tryFrom($value) !== null` |
| `->where('status', StatusEnum::Active->value)` | `->where('status', StatusEnum::Active)` |

> **Why?** The framework already unwraps backed enums everywhere, so hand-written conversion is duplicate code that can
> drift from the enum.

### Authorize with policies
<!-- rule: LV6 -->

Always express permission checks as a `Policy` and call it through `Gate::authorize()`, the form request's
`authorize()`, or `->can()` on the route. Never write an inline role or permission `if` in a controller or manager.
Link a module model to its policy with `#[UsePolicy]`.

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
if (!$user->hasRole('editor') && $post->user_id !== $user->id) {
    abort(403);
}
```

</td>
<td>

```php
Gate::authorize('update', $post);

// or on the route
Route::put('/posts/{post}', UpdatePostController::class)
    ->can('update', 'post');
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** A policy keeps the rule for one entity in one class, so a change in who may do what is one edit instead of a
> search through every controller.

### Shape responses with API resources
<!-- rule: LV7 -->

Always return JSON through a `JsonResource` or a resource collection. Never return a model, `->toArray()`, or
`response()->json($model)`. Use `whenLoaded()` for relations, `whenCounted()` for counts, and `when()` for optional
fields. A resource never runs a query.

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
return response()->json($top->load('brand'));
```

</td>
<td>

```php
return TopResource::make($top);

// TopResource
'brand' => BrandResource::make($this->whenLoaded('brand')),
'offers_count' => $this->whenCounted('offers'),
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** A resource is the single place where the public shape is decided, so a renamed column or a new hidden
> attribute does not silently change the API.

### Return status codes by constant
<!-- rule: LV8 -->

Always set non-`200` statuses explicitly with `Response::HTTP_*` constants: `201` through
`->response()->setStatusCode(Response::HTTP_CREATED)`, `204` through `response()->noContent()`. Never write a bare
number.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `response()->json($data, 201)` | `->response()->setStatusCode(Response::HTTP_CREATED)` |
| `response()->json(null, 204)` | `response()->noContent()` |

> **Why?** A named constant states the intent and can be searched for; `201` and `204` scattered through controllers
> cannot.

### Bind models in routes
<!-- rule: LV9 -->

Use implicit route model binding for a plain lookup by key instead of `findOrFail($id)` in the action. Use
`scopeBindings()` for nested routes. **Exception:** a lookup that depends on who is asking ("visible to this user")
goes through the repository.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `public function show(int $id) { $tag = Tag::findOrFail($id); }` | `public function show(Tag $tag)` |

> **Why?** Binding removes a query and a `404` branch from every action and guarantees they all behave the same way.

### Define routes declaratively
<!-- rule: LV10 -->

Use `Route::apiResource()` for CRUD, an invokable controller for a single action, named routes, and
`Route::prefix()->middleware()->group()` for groups. Always register middleware aliases and global middleware in
`bootstrap/app.php`.

```php
Route::prefix('v1/tags')
    ->middleware('auth:sso')
    ->name('tags.')
    ->group(function (): void {
        Route::apiResource('/', TagController::class)->only(['index', 'store']);
        Route::post('/{tag}/archive', ArchiveTagController::class)->name('archive');
    });
```

> **Why?** Declarative routes show the whole surface of a module in one screen and keep URL shape, middleware, and names
> consistent.

### Paginate every collection
<!-- rule: LV11 -->

Always return collections through `paginate()` or `cursorPaginate()` wrapped in a resource collection. Never return
an unbounded `get()` from an endpoint.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `TagResource::collection(Tag::query()->get())` | `TagResource::collection(Tag::query()->paginate($perPage))` |

> **Why?** An unbounded list works on day one and takes the endpoint down when the table grows.

---

## Eloquent

### Keep models to mapping
<!-- rule: LV12 -->

A model holds table mapping, a `casts()` method (never the `$casts` property), relations with return types, local
scopes marked `#[Scope]`, and accessors built with `Attribute::make()`. Never put business logic, HTTP concerns, or
queries on other models into a model.

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
protected $casts = ['status' => TopStatusEnum::class];

public function publish(): void
{
    $this->status = TopStatusEnum::Published;
    $this->save();
    Http::post(config('widget.url'), ['id' => $this->id]);
}
```

</td>
<td>

```php
protected function casts(): array
{
    return ['status' => TopStatusEnum::class];
}

/** @return BelongsTo<Brand, $this> */
public function brand(): BelongsTo
{
    return $this->belongsTo(Brand::class);
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** A model that only maps data stays small and safe to load anywhere; behaviour in a manager can differ per use
> case and be tested without the model.

### Use model attributes instead of boilerplate
<!-- rule: LV13 -->

Use `#[UseFactory]`, `#[UsePolicy]`, `#[ObservedBy]`, `#[ScopedBy]`, `#[CollectedBy]`, and `#[UseEloquentBuilder]` on
the model. Never register the same thing in a service provider or override a method for it. On Laravel 13 also use
`#[Table]`, `#[Fillable]`, `#[Hidden]`, and `#[Touches]`.

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
protected static function newFactory(): TagFactory
{
    return TagFactory::new();
}

// AppServiceProvider
Gate::policy(Tag::class, TagPolicy::class);
Tag::observe(TagObserver::class);
```

</td>
<td>

```php
#[UseFactory(TagFactory::class)]
#[UsePolicy(TagPolicy::class)]
#[ObservedBy(TagObserver::class)]
final class Tag extends Model
{
    /** @use HasFactory<TagFactory> */
    use HasFactory;
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** The attribute sits on the class it configures, so opening the model shows everything attached to it; provider
> lines are invisible from there and easy to forget.

### Cast dates to immutable
<!-- rule: LV14 -->

Always cast date columns to `immutable_datetime` or `immutable_date`, and call `Date::use(CarbonImmutable::class)` in
`AppServiceProvider::boot()`. Never use mutable `Carbon`.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `'published_at' => 'datetime'` | `'published_at' => 'immutable_datetime'` |

> **Why?** A mutable date changed by `->addDay()` in one place changes the model attribute everywhere; an immutable one
> cannot.

### Guard mass assignment
<!-- rule: LV15 -->

Always write through `create()`, `fill()`, or `update()` with validated data against an explicit `$fillable` list.
Never call `forceFill()` with request data and never call `Model::unguard()`.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `$tag->forceFill($request->all())->save()` | `$tag->fill($request->validated())->save()` |

> **Why?** An explicit fillable list is what stops a request from setting `is_admin` or `team_id`.

### Prevent N+1 queries
<!-- rule: LV16 -->

Always eager-load what the response needs in the repository with `with()`, `withCount()`, `withExists()`, or
`loadMissing()`, and constrain eager loads with closures. Code must stay correct under `Model::shouldBeStrict()`:
no lazy loading and no access to unselected attributes.

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
$tops = Top::query()->paginate();

// TopResource
'brand' => $this->brand->name,
```

</td>
<td>

```php
$tops = Top::query()
    ->with('brand:id,name')
    ->withCount('offers')
    ->paginate();
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** One lazy relation in a resource turns a page of 50 rows into 51 queries, and nothing fails until production
> load.

### Let the database do the work
<!-- rule: LV17 -->

Prefer a query that returns the answer over loading rows and computing in PHP: `exists()` instead of `count() > 0`,
`value()` and `pluck()` instead of hydrate-then-map, `withCount()` and `withSum()` instead of counting loops,
`whereRelation()`, `whereBelongsTo()`, `whereAny()`, and `whereLike()` instead of hand-written joins, and
`increment()`, `upsert()`, `updateOrCreate()`, `firstOrCreate()`, `insertOrIgnore()` instead of read-then-write.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `Tag::query()->where(...)->count() > 0` | `Tag::query()->where(...)->exists()` |
| `Tag::all()->map(fn (Tag $tag) => $tag->name)` | `Tag::query()->pluck('name')` |
| `$row = Stat::find($id); $row->hits++; $row->save();` | `Stat::query()->whereKey($id)->increment('hits')` |

> **Why?** The database does it in one round trip and atomically; the PHP version is slower and has a race between the
> read and the write.

### Stream large result sets
<!-- rule: LV18 -->

Always use `chunkById()`, `lazyById()`, or `cursor()` for anything unbounded: commands, jobs, exports. Never call
`all()` or `get()` and then loop.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `foreach (Domain::all() as $domain)` | `foreach (Domain::query()->lazyById() as $domain)` |

> **Why?** `all()` loads the whole table into memory, so the command works in development and dies with the production
> data set.

### Prefer collections and helpers
<!-- rule: LV19 -->

Transform Eloquent results with collection methods (`map`, `filter`, `keyBy`, `groupBy`, `partition`, `sum`) and use
`Str::`, `Arr::`, `Number::`, and `Uri` instead of manual string and array plumbing.

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
$byGeo = [];

foreach ($offers as $offer) {
    $byGeo[$offer->geo_id][] = $offer;
}
```

</td>
<td>

```php
$byGeo = $offers->groupBy('geo_id');
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** A named collection method says what happens to the data; a loop makes the reader work it out.

### Query through the model
<!-- rule: LV20 -->

Always start a query with `Model::query()`. Never use `DB::table()` for a table that has a model. Use raw SQL only
through `selectRaw()` or `whereRaw()` with bindings.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `DB::table('tags')->where('name', $name)->first()` | `Tag::query()->where('name', $name)->first()` |
| `->whereRaw("name = '$name'")` | `->whereRaw('LOWER(name) = ?', [$name])` |

> **Why?** Going through the model keeps casts, scopes, and soft deletes applied, and bindings keep user input out of
> the SQL string.

---

## Transactions and side effects

### Wrap the use case in a transaction
<!-- rule: LV21 -->

Always open the transaction in the manager with the closure form `DB::transaction(function () { ... })`. Never call
`beginTransaction()` and `commit()` by hand. Use `lockForUpdate()` or `Cache::lock()` for row races.

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
DB::beginTransaction();

try {
    $top = Top::create($attributes);
    $top->offers()->createMany($offers);
    DB::commit();
} catch (Throwable $exception) {
    DB::rollBack();

    throw $exception;
}
```

</td>
<td>

```php
$top = DB::transaction(function () use ($attributes, $offers): Top {
    $top = Top::create($attributes);
    $top->offers()->createMany($offers);

    return $top;
});
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** The closure form cannot forget the rollback or the commit, and it retries on deadlock when asked to.

### Dispatch side effects after commit
<!-- rule: LV22 -->

Always dispatch an event, job, mail, or notification raised inside a transaction after the commit: implement
`ShouldDispatchAfterCommit` on the event, `ShouldQueueAfterCommit` on the listener, or call `->afterCommit()` on the
job.

```php
final class TopPublishedEvent implements ShouldDispatchAfterCommit
{
    public function __construct(
        public readonly int $topId,
    ) {
    }
}
```

> **Why?** A job that starts before the commit reads rows that are not there yet, and one dispatched from a transaction
> that rolls back announces something that never happened.

### Queue slow and external work
<!-- rule: LV23 -->

Always run outbound HTTP, mail, exports, and fan-out in a `ShouldQueue` job, never inline in a request. A job is
idempotent, takes models or IDs (never large arrays), declares `tries`, `backoff`, and `timeout`, implements
`failed()`, and uses `ShouldBeUnique` or `WithoutOverlapping` when a duplicate run is harmful. Use `Bus::chain()` and
`Bus::batch()` for multi-step work.

```php
final class SyncSiteMetricsJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public readonly int $siteId,
    ) {
    }

    /** @return list<int> */
    public function backoff(): array
    {
        return [10, 60, 300];
    }

    public function uniqueId(): string
    {
        return (string) $this->siteId;
    }

    public function handle(SiteSyncManager $manager): void
    {
        $manager->handle($this->siteId);
    }
}
```

> **Why?** A queued job survives a slow or failing third party with retries; the same call inline makes the user wait
> and turns the third party's outage into yours.

### Decouple modules with events
<!-- rule: LV24 -->

Publish a past-tense event class when another module must react to a change, and put the listener in the reacting
module. Call the other module's service when you need an answer now. Always register module listeners in the module
provider with `Event::listen()`, not through global discovery.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `$this->widgetCacheManager->clear($top->id)` | `TopPublishedEvent::dispatch($top->id)` |

> **Why?** The publisher does not have to know who reacts, so a new reaction is a new listener and not an edit to the
> code that publishes.

### Name jobs, listeners, and commands
<!-- rule: LV25 -->

Name a job as a verb phrase with the `Job` suffix, a listener after the action it performs with the `Listener`
suffix, and a command class as a verb phrase with the `Command` suffix. A command signature is `<module>:<action>` in
kebab-case.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `SiteMetricsJob` | `SyncSiteMetricsJob` |
| `TopPublishedListener` | `ClearWidgetCacheListener` |
| `$signature = 'importTops'` | `$signature = 'tops:import-from-sheet'` |

> **Why?** The name says what will run without opening the class, and the signature prefix groups a module's commands in
> `artisan list`.

### Keep the schedule in one file
<!-- rule: LV26 -->

Always define schedule entries in `routes/console.php`, each with `withoutOverlapping()` and, on a multi-server
deploy, `onOneServer()`. Never schedule from a service provider.

```php
Schedule::command('domains:resync-from-pbn')
    ->hourly()
    ->withoutOverlapping()
    ->onOneServer();
```

> **Why?** One file answers "what runs when" for the whole application; overlap protection stops a slow run from
> stacking on the next one.

---

## Container, config, and infrastructure

### Inject through the constructor
<!-- rule: LV27 -->

Never call `app()`, `resolve()`, or `App::make()` inside controllers, managers, repositories, clients, or a job's
`handle()` body; type-hint the dependency instead. Facades are allowed for framework services: `Log`, `Cache`, `DB`,
`Http`, `Storage`, `Event`, `Gate`.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `$manager = app(TagCreateManager::class);` | `public function __construct(private TagCreateManager $manager)` |

> **Why?** A constructor lists everything a class needs; a `resolve()` call hides a dependency in the middle of a method
> where neither the reader nor static analysis looks for it.

### Read config, never env
<!-- rule: LV28 -->

Never call `env()` outside a config file. Read values with typed getters (`config()->string()`, `integer()`,
`boolean()`, `array()`) or inject them with `#[Config]`. Keep a module's settings in its own config file, merged by
the module provider with `mergeConfigFrom()`.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `env('WIDGET_TOKEN')` | `config()->string('widget.token')` |
| `config('widget.timeout')` | `config()->integer('widget.timeout')` |

> **Why?** Once the configuration is cached, `env()` returns `null` everywhere except in config files, so the code works
> locally and breaks after deploy.

### Inject values with contextual attributes
<!-- rule: LV29 -->

Use `#[CurrentUser]`, `#[Config]`, `#[Storage]`, `#[Cache]`, `#[Log]`, and `#[RouteParameter]` on constructor or
method parameters instead of calling `auth()->user()`, `config()`, or `Storage::disk()` inside the class.

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
public function __construct()
{
    $this->disk = Storage::disk('s3');
    $this->token = config('widget.token');
}
```

</td>
<td>

```php
public function __construct(
    #[Storage('s3')] private Filesystem $disk,
    #[Config('widget.token')] private string $token,
) {
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** The dependency becomes visible in the signature and replaceable in a test without faking a facade.

### Pass the current user down
<!-- rule: LV30 -->

Take the user in the controller from the request or `#[CurrentUser]` and pass it to the manager as a typed argument.
Never call `auth()` or `Auth::` in a manager or a repository.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `$this->repository->findForUser(auth()->id())` | `$this->repository->findForUser($user->id)` |

> **Why?** A manager that reads the session cannot run from a job, a command, or a test without pretending to be an HTTP
> request.

### Call external APIs through the HTTP client
<!-- rule: LV31 -->

Always make outbound calls with `Http::` inside a `Clients/` class, with `timeout()`, `connectTimeout()`, `retry()`,
and `throw()`, and take the base URL and credentials from config. Never use raw Guzzle or cURL.

```php
return Http::baseUrl($this->baseUrl)
    ->withToken($this->token)
    ->connectTimeout(self::CONNECT_TIMEOUT_SECONDS)
    ->timeout(self::TIMEOUT_SECONDS)
    ->retry(times: 3, sleepMilliseconds: 200)
    ->post('/api/clear-cache', ['top_id' => $topId])
    ->throw();
```

> **Why?** The framework client has timeouts, retries, and `Http::fake()` built in; a call without a timeout hangs a
> worker until the remote side gives up.

### Cache with the framework primitives
<!-- rule: LV32 -->

Use `Cache::remember()`, `Cache::flexible()` for stale-while-revalidate, `Cache::lock()` for mutual exclusion, and
`once()` or `Cache::memo()` for per-request memoization. Never memoize in a static property. Keep keys and TTLs in
named constants.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `private static ?array $geos = null;` | `once(fn (): Collection => $this->repository->findAll())` |
| `Cache::remember('geos', 3600, ...)` | `Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, ...)` |

> **Why?** A static property survives between requests under Octane and between tests; the framework primitives are
> scoped and can be flushed.

### Defer and parallelize deliberately
<!-- rule: LV33 -->

Use `defer()` for work that must not delay the response and needs no retry, `Concurrency::run()` for independent slow
calls, and `Http::pool()` for parallel requests. Work that must not be lost is a queued job, not `defer()`.

```php
defer(fn () => $this->metrics->recordView($topId));

[$clicks, $impressions] = Concurrency::run([
    fn (): int => $this->repository->sumClicks($siteId),
    fn (): int => $this->repository->sumImpressions($siteId),
]);
```

> **Why?** Each tool has a different guarantee; picking by name states whether the work may be lost, must be retried, or
> only has to be fast.

### Read time from the framework
<!-- rule: LV34 -->

Always take the current time from `now()` or `Date::now()`. Never use `new DateTimeImmutable()`, `time()`, `date()`,
or `Carbon::now()`.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `new DateTimeImmutable()` | `now()` |
| `date('Y-m-d')` | `now()->toDateString()` |

> **Why?** `now()` is the one clock a test can move with `travelTo()`; every other source makes time-dependent code
> untestable.

### Map errors outside controllers
<!-- rule: LV35 -->

Throw domain exceptions from managers. Put the HTTP mapping on the exception (`render()`, `report()`, or
`ShouldntReport`) or in `withExceptions()` in `bootstrap/app.php`. Never `try/catch` in a controller to build a JSON
error and never call `abort()` below the controller.

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
    $tag = $this->createManager->handle($name);
} catch (DuplicateTagException $exception) {
    return response()->json(
        ['message' => $exception->getMessage()],
        409,
    );
}
```

</td>
<td>

```php
final class DuplicateTagException extends TagException
{
    public function render(): JsonResponse
    {
        return response()->json(
            ['message' => $this->getMessage()],
            Response::HTTP_CONFLICT,
        );
    }
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** The exception is thrown from many places but mapped once, so the same error always produces the same
> response.

### Log with placeholders and context
<!-- rule: LV36 -->

Always log with a `{placeholder}` message and a context array. Put cross-cutting IDs (request, tenant, job) into
`Context::add()` once instead of repeating them in every call.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `Log::info("Site $id synced")` | `Log::info('Site {site} synced.', ['site' => $id])` |

> **Why?** A constant message can be grouped and searched in the log system; an interpolated one is a different string
> every time.

---

## Migrations and generators

### Write safe migrations
<!-- rule: LV37 -->

Always create a migration with `php artisan make:migration` and a descriptive name. Never edit a merged migration.
When modifying a column, repeat every attribute it already has. Give every foreign key an index and an explicit
`onDelete` behaviour.

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
$table->string('name', 100)->change();
```

</td>
<td>

```php
$table->string('name', 100)
    ->nullable()
    ->default(null)
    ->comment('Display name')
    ->change();
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** `change()` rebuilds the column from the new definition only, so an attribute that is not repeated is silently
> dropped.

### Generate only global files
<!-- rule: LV38 -->

Use `php artisan make:migration`, `make:factory`, `make:seeder`, and `make:test` with `--no-interaction`. In a module
application, create controllers, requests, resources, managers, models, jobs, and commands directly in the module
folder by copying the nearest sibling.

> **Why?** `make:controller` and `make:model` write to `app/Http` and `app/Models`, which do not exist in a module
> application; the generated file lands outside every module.

### Generate model PHPDoc
<!-- rule: LV39 -->

Always regenerate model PHPDoc with the project's IDE-helper script after changing a model's columns or relations.
Never hand-write `@property` blocks.

> **Why?** Generated PHPDoc matches the schema; hand-written PHPDoc matches what someone remembered about it.

---

## Testing

### Test features through HTTP
<!-- rule: LV40 -->

Always test an endpoint through its route with `getJson()` or `postJson()` and assert with `assertStatus()`,
`assertJsonPath()`, `assertJsonStructure()`, `assertDatabaseHas()`, and `assertModelMissing()`. One test class per
endpoint or use case, mirrored under `tests/Feature/<Module>/`.

```php
public function testStoreWithDuplicateNameReturnsConflict(): void
{
    Tag::factory()->create(['name' => 'casino']);

    $response = $this->actingAs($this->editor)->postJson('/api/v1/tags', ['name' => 'casino']);

    $response->assertStatus(Response::HTTP_CONFLICT);
    $this->assertDatabaseCount('tags', 1);
}
```

> **Why?** A feature test exercises routing, middleware, validation, the manager, and the database together, which is
> exactly what a structural refactoring must not break.

### Build data with factories
<!-- rule: LV41 -->

Always give every model a factory with named states, and build graphs with `for()`, `has()`, and `recycle()`. Never
insert rows by hand and never repeat attribute arrays across tests.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `Top::factory()->create(['status' => 1, 'published_at' => now()])` | `Top::factory()->published()->for($brand)->create()` |

> **Why?** A named state says why the row looks that way, and a new required column is added in one place instead of in
> every test.

### Fake the edges, not the application
<!-- rule: LV42 -->

Use the framework fakes: `Http::fake()` with `Http::preventStrayRequests()`, `Queue::fake()`, `Bus::fake()`,
`Event::fake()`, `Mail::fake()`, `Notification::fake()`, `Storage::fake()`, `Exceptions::fake()`, `Sleep::fake()`.
Mock only an interface in `Clients/`. Use the real database with the project's refresh trait and factories.

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
$repository = $this->mock(TagRepository::class);
$repository->shouldReceive('findAll')
    ->andReturn(collect([$tag]));
```

</td>
<td>

```php
Http::preventStrayRequests();
Http::fake([
    'widget.test/*' => Http::response(status: 204),
]);

Tag::factory()->create(['name' => 'casino']);
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** A mocked repository proves that the mock works; a real query against a test database proves that the feature
> works.

### Control time in tests
<!-- rule: LV43 -->

Always move the clock with `$this->travelTo()` or `$this->freezeTime()`. Never call `sleep()` and never depend on the
real time.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `sleep(2);` | `$this->travel(2)->seconds();` |

> **Why?** A frozen clock makes the test fast and gives the same result at midnight, on the last day of the month, and
> in CI.

### Test both sides of authorization
<!-- rule: LV44 -->

Always authenticate with `$this->actingAs()` and a factory user that holds the needed role or permission. Write one
test for the allowed case and one that asserts `403` for a user without it.

> **Why?** Only the forbidden case proves that the check exists; a suite of allowed cases still passes when the policy
> is deleted.

### Write PHPUnit tests
<!-- rule: LV45 -->

Always write tests as PHPUnit classes created with `php artisan make:test --phpunit`, using attributes
(`#[DataProvider]`, `#[Test]`). Never use docblock annotations or Pest.

> **Why?** One test framework and one syntax keep every test readable by every developer and every tool in the pipeline.

---

## Module architecture

A module application groups code by business capability under `app/Modules/`. These rules define what a module
contains, what it exposes, and what may depend on what. They follow the practices described in the
[sources](#sources) and name the places where this codex deliberately differs.

### Group code by module
<!-- rule: MA1 -->

Always place application code in `app/Modules/<Module>/`: one PascalCase singular folder per business capability
(`Billing`, `Catalog`). Never create a module per table or per model, and never a module named after a layer
(`Models`, `Repositories`, `Helpers`). Don't keep type-grouped folders at the `app/` root; `app/Providers/` holds
framework-level providers only.

```text
app/
  Modules/
    Shared/
    Brand/
    Top/
    Widget/
  Providers/
```

> **Why?** Code that changes together sits together, so a feature is one folder to read and one folder to review instead
> of a slice through six type folders.

### Use the closed folder vocabulary
<!-- rule: MA2 -->

Inside a module use only these folders, plus `<Module>ServiceProvider.php` at the module root. Create a folder only
when it has a class. Never invent a new folder name locally.

| Folder | Holds |
|---|---|
| `Routes/` | `api.php`, `web.php` |
| `Controllers/`, `Requests/`, `Resources/`, `Middleware/`, `Policies/`, `Rules/` | the HTTP boundary |
| `Managers/` | use cases |
| `Services/` | the module service: the public API for other modules |
| `Repositories/` | queries |
| `Models/`, `Enums/`, `Data/`, `Exceptions/` | entities, enums, data classes, the module's exceptions |
| `Contracts/` | the module service interface and client interfaces |
| `Clients/` | outbound HTTP and SDK wrappers |
| `Support/` | pure stateless helpers |
| `Jobs/`, `Commands/`, `Events/`, `Listeners/` | as named |
| `config/` | the module's config files |

> **Why?** A fixed vocabulary means every module is navigated the same way and a `Services/` or `Helpers/` grab-bag
> cannot start.

### Follow the call direction
<!-- rule: MA3 -->

Always call in one direction inside a module: `Controller → Manager → Repository / Client → Model`. A controller
calls only managers. Only managers and repositories run Eloquent queries; only clients touch the network.

> **Why?** A fixed direction gives every kind of code exactly one place, which is the same separation that layered
> folders give, without the extra nesting.

### Return domain types from managers
<!-- rule: MA4 -->

A manager returns a model, a `Collection`, a paginator, a data class, or a scalar. Never return `Responsable`,
`JsonResource`, `JsonResponse`, or `response()` from a manager; only the controller wraps a result in a resource.

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
public function handle(): AnonymousResourceCollection
{
    return TagResource::collection(
        $this->repository->findAll(),
    );
}
```

</td>
<td>

```php
/** @return Collection<int, Tag> */
public function handle(): Collection
{
    return $this->repository->findAll();
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** A manager that returns an HTTP response cannot be reused from a job, a command, or another module without
> dragging the HTTP layer along.

### Type manager signatures
<!-- rule: MA5 -->

Never accept `array $payload` in a manager. Use typed parameters, and a data class for more than 3 parameters or when
the same arguments travel together.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `public function handle(array $payload): Tag` | `public function handle(string $name, ?int $teamId): Tag` |
| `public function handle(array $filters): Collection` | `public function handle(TagFilter $filter): Collection` |

> **Why?** An untyped array hides the contract from the IDE and static analysis and pushes validation back into the
> method body.

### Name managers by use case
<!-- rule: MA6 -->

Name a manager `<Entity><UseCase>Manager`. Never repeat the module name in front of the entity and never use a bare
`Manager`, `Handler`, or `Processor`. Expose one public `handle()` by default; use several public methods only when
they share dependencies and state.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `Gsc\Managers\GscSiteSyncManager` | `Gsc\Managers\SiteSyncManager` |
| `Dashboard\Managers\Handler` | `Dashboard\Managers\OverviewManager` |

> **Why?** A name built from the use case tells you what the class does and stops it from growing a second, unrelated
> job.

### Write interfaces only at a boundary
<!-- rule: MA7 -->

Write an interface only when two implementations exist today, when it is an outbound boundary a test fakes
(`Clients/`), or when it is the module service that other modules call. Otherwise inject the concrete class. Never
write an interface per manager or per repository.

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
$this->app->bind(TagListManagerInterface::class, TagListManager::class);
$this->app->bind(TagCreateManagerInterface::class, TagCreateManager::class);
$this->app->bind(TagRepositoryInterface::class, TagRepository::class);
```

</td>
<td>

```php
$this->app->bind(TagServiceInterface::class, TagService::class);
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** An interface with one implementation that is never swapped doubles the file count and the cost of every
> signature change while providing no seam.

### Expose a module through its service
<!-- rule: MA8 -->

Other modules call a module only through `Contracts/<Capability>ServiceInterface`, implemented by
`Services/<Capability>Service`. The service holds no logic: every method delegates to one manager or repository.
Create it when the first foreign consumer appears. Name it for a capability and split it beyond 7 methods. Use the
`Service` suffix for this role only; a class with logic is a manager.

```php
final readonly class CatalogService implements CatalogServiceInterface
{
    public function __construct(
        private BrandRepository $repository,
        private BrandMergeManager $mergeManager,
    ) {
    }

    public function findBrand(int $brandId): ?BrandData
    {
        return $this->repository->findOneAsData($brandId);
    }

    public function mergeBrands(int $sourceId, int $targetId): void
    {
        $this->mergeManager->handle($sourceId, $targetId);
    }
}
```

> **Why?** One small entry point is a contract the owning module can keep stable while it changes everything behind it.
> This is the [composite service](README.md#composite-service) of the codex, used as the module's public API.

### Import only the public surface
<!-- rule: MA9 -->

From another module import only `Contracts/`, `Data/`, `Enums/`, `Events/`, and `Exceptions/`. Service methods take
and return data classes, enums, scalars, and IDs. Never import another module's `Controllers/`, `Requests/`,
`Routes/`, `Middleware/`, `Managers/`, `Services/`, `Repositories/`, `Clients/`, `Jobs/`, `Listeners/`, `Policies/`,
or `Support/`.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `use App\Modules\Gsc\Repositories\SiteRepository;` | `use App\Modules\Gsc\Contracts\GscServiceInterface;` |
| `use App\Modules\Gsc\Managers\SiteSyncManager;` | `use App\Modules\Gsc\Data\SiteData;` |

> **Why?** Whatever another module imports becomes an API the owner can no longer change freely; a small public surface
> keeps the rest of the module private.

### Read models of lower modules only
<!-- rule: MA10 -->

A module may type-hint, relate to, and eager-load a model of a module below it in the dependency graph, embed that
module's resources, and reuse its validation rules. Reading through a relation (`$top->brand`, `with('brand')`) is fine. Never write
that model, never query it directly, and never add a scope or accessor to it; anything beyond reading a relation goes through the owner's service. Modules that are not
related in the data model exchange IDs and data classes.

| ❌ Wrong: | ✅ Right: |
|---|---|
| `Brand::query()->where('active', true)->get()` | `$this->catalogService->findActiveBrands()` |
| `$top->brand->update(['name' => $name])` | `$this->catalogService->renameBrand($top->brand_id, $name)` |

> **Why?** Eloquent relations between a parent and a child entity are the application, not leakage; fencing them to
> "downward and read-only" keeps the benefit of the ORM without letting one module change another's data. This
> deliberately relaxes the DTO-only advice of the [sources](#sources).

### Keep dependencies one-way
<!-- rule: MA11 -->

Modules form a graph without cycles: `Shared ← base modules ← integrations ← read models`. Before importing another
module, check that it does not already depend on yours. Call the lower module's service when you need an answer now;
when the lower module needs the upper one to react, it publishes an event.

> **Why?** A cycle makes two modules one module with two folders: neither can be understood, tested, or changed without
> the other.

### Publish events as completed facts
<!-- rule: MA12 -->

A cross-module event is a past-tense class in the publisher's `Events/` that carries IDs, enums, and values, never an
Eloquent model, and is dispatched after commit. Listeners live in the reacting module and are idempotent. Never
publish an event that asks for work and never replace a call whose result the caller needs.

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
final class SyncWidgetRequestedEvent
{
    public function __construct(
        public readonly Top $top,
    ) {
    }
}
```

</td>
<td>

```php
final class TopPublishedEvent implements ShouldDispatchAfterCommit
{
    public function __construct(
        public readonly int $topId,
        public readonly int $geoId,
    ) {
    }
}
```

</td>
        </tr>
    </tbody>
</table>

> **Why?** A fact with IDs stays valid whoever listens; a model in the payload hands the listener another module's
> internals, and a "requested" event is a hidden call that is harder to follow than the call it replaced.

### Let the writer own the table
<!-- rule: MA13 -->

The module that writes a table owns its model, factory, repository, and transaction. Never write another module's
table, and never update another module's rows inside your transaction.

> **Why?** One owner per table means one place where its invariants are enforced; two writers means two sets of rules
> that drift apart.

### Keep the shared module tiny
<!-- rule: MA18 -->

`Shared/` holds only what two or more modules use and what carries no business rule: the base service provider,
shared data classes, pure helpers. Move a helper to `Shared/Support/` when a second module needs it; never import it
sideways. Prefer two copies of similar logic over a shared abstraction in `Shared/`.

A second cross-cutting module (for example audit logging) may be declared shared next to `Shared/`; it then follows
the same terms: every class is public, it depends on no other module, and it holds no business rule.

> **Why?** A shared folder is a dependency magnet: everything depends on it, so every change to it is a change to every
> module.

### Keep support classes pure
<!-- rule: MA19 -->

A class in `Support/` takes values and returns values. Never access the database, cache, network, filesystem, clock,
logger, config, or container from it.

> **Why?** Pure code is unit-tested without booting the framework, and the rule is what stops `Support/` from decaying
> into a grab-bag.

### Wire a module in its provider
<!-- rule: MA14 -->

Every module has `<Module>ServiceProvider` extending `Shared\Providers\BaseServiceProvider`, listed in
`bootstrap/providers.php` in dependency order. It binds the module service and client interfaces, loads the module's
routes, registers commands with `$this->commands()`, listeners with `Event::listen()`, and merges module config.
Never wire a module in `AppServiceProvider`.

```php
final class TagServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/tag.php', 'tag');
        $this->app->bind(TagServiceInterface::class, TagService::class);
    }

    public function boot(): void
    {
        $this->loadApiRoutes(__DIR__ . '/Routes/api.php', ['auth:sso']);
        $this->commands([SyncTagsCommand::class]);
        Event::listen(SiteRemovedEvent::class, DetachSiteTagsListener::class);
    }
}
```

> **Why?** Everything a module adds to the application is visible in one file, and removing the provider line removes
> the module.

### Keep schema and tests global
<!-- rule: MA15 -->

Keep `database/migrations`, `database/factories`, `database/seeders`, the schedule in `routes/console.php`, and
`tests/` at the application root; tests mirror the module path. Name a module model's factory with `#[UseFactory]`.

> **Why?** The schema belongs to the application as a whole, and a migration often touches tables of two modules; there
> is no correct module folder for it.

### Move without changing behaviour
<!-- rule: MA16 -->

A structural move changes no URL, response, table, or queue name, and the feature suite passes unchanged before and
after. Never mix a move with a behaviour change in one commit.

> **Why?** A commit that only moves files can be reviewed by its test result; one that also changes behaviour hides the
> change inside hundreds of renamed lines.

### Respect size targets
<!-- rule: MA17 -->

Keep a controller action within 10 lines, a manager within 150, a repository within 200, a model within 150, and a
`Support/` class within 120. Split by use case or by aggregate when a class goes over.

> **Why?** A class over the target is usually two concerns sharing a file; the limit is the prompt to look for the seam.

---

## Sources

Laravel documents no module structure, so the module rules rest on talks and articles by practitioners.

- Wendell Adriel, [Modular Monoliths: Creating Real Boundaries Before Reaching for Microservices](https://wendelladriel.com/blog/modular-monoliths-creating-real-boundaries-before-reaching-for-microservices)
  (2026): public contracts and internal implementation, calls for answers and events for facts, table and transaction
  ownership, a small shared kernel.
- Steve McDougall, [Building modular systems in Laravel](https://www.juststeveking.com/articles/building-modular-systems-in-laravel-a-practical-guide/)
  (2025): Laravel-style folders inside a module, a service behind an interface as the cross-module contract, modules
  by business domain.
- Mateus Guimarães, [Unveiling the Modular Monolith](https://speakerdeck.com/mateusjatenee/unveiling-the-modular-monolith-laracon-eu-2024)
  (Laracon EU 2024): grouping by context, data classes as contracts, module services and events.
- Mohamed Said, [Modular Monolith in Laravel: Enforcing Bounded Contexts Without a Framework](https://msaied.com/articles/modular-monolith-in-laravel-enforcing-bounded-contexts-without-a-framework)
  (2026): contracts, events, and the container as the only channels between modules.
- [avosalmon/modular-monolith-laravel](https://github.com/avosalmon/modular-monolith-laravel) (Laracon Online 2022):
  a module may depend on other modules' `Contracts/` and nothing else.
- Kirsten Westeinde, [Deconstructing the Monolith](https://shopify.engineering/deconstructing-monolith-designing-software-maximizes-developer-productivity)
  (Shopify, 2019): move files first, isolate behind public APIs second, enforce last.

**Where this codex deliberately differs**

- Eloquent models, resources, and validation rules cross a module boundary downward and read-only
  ([Read models of lower modules only](#read-models-of-lower-modules-only)); the sources allow data classes only.
- Migrations, factories, and tests stay global ([Keep schema and tests global](#keep-schema-and-tests-global));
  several sources keep them inside each module.
- Modules use Laravel's own folder names and a fixed call direction
  ([Follow the call direction](#follow-the-call-direction)) instead of `Application/Domain/Infrastructure/Presentation`
  folders.
