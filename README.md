# Digito Move

Monorepo for the Digito Move website, shared Laravel backend, and future Flutter mobile app.

- `apps/web`: existing Laravel website, admin workspace, billing services and versioned JSON API.
- `apps/mobile`: reserved for the future Flutter iOS and Android app; see its README.
- `packages/api-contract`: OpenAPI contract for mobile/admin integrations.
- `docs/invoices.md`: invoice workflow, Nylon Pay configuration, deployment and reconciliation.

The Git root is now this directory. The original web repository history and remote are preserved. The initial path relocation is staged; feature edits remain available for review. The previously tracked `apps/web/.env` is retained locally and removed from the current index (this does not rewrite older Git history).

## Development

Use PHP 8.4 (the version used for verification), Composer, and MySQL or SQLite. No frontend rebuild is required for the invoice screens; they use the existing CSS assets and a standalone JavaScript editor.

```sh
cd apps/web
composer install
# On a new checkout only: cp .env.example .env
# On a new checkout only: php artisan key:generate
# Set database credentials in .env before migrating.
php artisan migrate
php artisan serve
```

Log in with an existing administrator and open `/admin/invoices`. Invoices support whole UGX amounts, up to 50 line items, client details, drafts, issue/share, void before checkout, hosted payment and print/save as PDF using the browser.

```sh
cd apps/web
php artisan test
php artisan nylonpay:check
```

`nylonpay:check` performs a read-only lookup; it never creates a charge. Automated tests use an in-memory SQLite database and mocked payment transport, with real SDK signature verification for webhook tests. A successful connection check does not replace an end-to-end sandbox checkout test.
