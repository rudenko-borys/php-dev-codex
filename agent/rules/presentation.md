---
paths:
  - "src/**/Presentation/**/*.php"
  - "src/**/Controller/**/*.php"
  - "src/**/Command/**/*.php"
  - "src/**/MessageHandler/**/*.php"
  - "config/routes*"
---

# PHP codex: presentation layer

Loaded when editing controllers, commands, handlers, routes. IDs match the human codex.

## Routing

- **RN1 Route path naming**: nouns, **plural** collections with the item as collection + ID (`/v1/store/products`, `/v1/store/products/{id}`, never `/product/{id}`), lowercase, hyphens (`/template-families`), no trailing slash, no extension, no verbs (`/GetProducts`), version prefix `/v1`.
- **RN2 HTTP methods for actions**: `GET` read, `POST` create/process, `PUT` full replace, `PATCH` partial update, `DELETE` remove. Return accurate status codes (2xx success, 4xx client error, 5xx server error).

## Symfony naming

- **SN1 Console command naming**: `app:<domain>:<action>` lowercase with hyphens (`app:import:pull-templates`); class `<Action><Domain>Command` (`PullTemplatesImportCommand`).
- **SN2 Message handler naming**: message named after what should happen (`SendInvoiceMessage`), handler = same stem + `Handler` (`SendInvoiceHandler`); one handler per message; handler only delegates to an Application service.

## Boundary duties (see architecture rules)

- Enums come from the framework mapping (enum-typed Model property, `EnumType`, `EnumRequirement`); `tryFrom()` only for console arguments and vendor payloads, `null` is a validation error (EN2).
- Map request/CLI input into a Model and validate it here; services receive valid Models (AD10).
- Controllers and handlers inject the composite `*Service`, never a `*Manager` or repository directly (AD6).
