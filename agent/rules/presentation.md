---
paths:
  - "src/**/Presentation/**/*.php"
  - "src/**/Controller/**/*.php"
  - "src/**/Command/**/*.php"
  - "src/**/MessageHandler/**/*.php"
  - "src/**/Messenger/Handler/**/*.php"
  - "config/routes*"
---

# PHP codex: presentation layer

Loaded when editing controllers, commands, handlers, routes. IDs match the human codex.

## Routing

- **RN1 Route path naming**: nouns, **plural** collections with the item as collection + ID (`/v1/store/products`, `/v1/store/products/{id}`, never `/product/{id}`), lowercase, hyphens (`/template-families`), no trailing slash, no extension, no verbs (`/GetProducts`), version as `/v1` prefix or a version header (one per API).
- **RN2 HTTP methods for actions**: `GET` read, `POST` create/process, `PUT` full replace, `PATCH` partial update, `DELETE` remove; `GET`/`PUT`/`DELETE` idempotent, `POST` not. Status: `200` read/update with body, `201` created (+ body), `204` deleted or no body, `400` malformed, `401` no credentials, `403` no permission, `404` missing or hidden, `409` state conflict, `422` validation, `429` rate limit, `5xx` only for server faults.
- **RN3 Nest resources one level deep**: `/v1/orders/{orderId}/items/{id}`, never a second nesting level (`/customers/{customerId}/orders/{orderId}/items`); deeper = top-level collection + filter (`/v1/items?order_id=`). Parent param `{<entity>Id}`, last param `{id}`.
- **RN4 Filter, sort and paginate through query parameters**: filters/sort/pagination/fields only in the query string (`?status=active&sort=-price&page=1&per_page=50`), never in the path (`/products/active`); query params `snake_case`; every collection endpoint paginated.
- **RN5 Name non-CRUD actions as sub-resources**: `POST /v1/orders/{id}/cancel`; the only verb allowed in a path, always `POST`; never `PATCH` with a status field or `GET` for an action.

## Symfony naming

- **SN1 Console command naming**: `app:<domain>:<action>` lowercase with hyphens (`app:import:pull-templates`); class `<Action><Domain>Command` (`PullTemplatesImportCommand`).
- **SN2 Message handler naming**: message named after what should happen (`SendInvoiceMessage`), handler = same stem + `Handler` (`SendInvoiceHandler`); one handler per message; handler lives in `Presentation/Messenger/Handler` and only delegates to an Application service.

## Boundary duties (see architecture rules)

- Enums come from the framework mapping (enum-typed Model property, `EnumType`, `EnumRequirement`); `tryFrom()` only for console arguments and vendor payloads, `null` is a validation error (EN2).
- Map request/CLI input into a Model and validate it here; services receive valid Models (AD10).
- Controllers and handlers inject the composite `*Service`, or the lone specific service when the entity has no composite; never a specific service behind a composite, never a repository (AD6).
