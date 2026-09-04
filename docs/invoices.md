# Invoices and Nylon Pay

## Workflow

1. Open **Billing → Invoices** in the admin workspace.
2. Enter the client name/email, optional business/contact/address, title, due date, notes and item quantities/prices.
3. Save and review the draft. Totals are calculated on the server; submitted totals and statuses are ignored.
4. Issue the invoice. Client/line-item details become immutable and a private, unguessable share URL becomes available. Copy it to share with the client. The app does not send email or messages automatically.
5. The client opens the link and chooses **Pay with Nylon Pay**. A hosted invoice is created by the official PHP SDK and its URL is reused for repeat clicks. The hosted page determines available payment methods.
6. A signed webhook or server-side reconciliation confirms the exact reference, amount, currency and charge type before marking the invoice paid. Returning from checkout or passing a status in the URL never marks an invoice paid.

The first version bills in whole UGX with integer quantities. Invoice totals must be UGX 500–1,000,000,000; each invoice supports up to 50 items. A due date marks an invoice overdue but does not automatically cancel it. Voiding is available only before any checkout starts, because disabling a local link cannot revoke a provider checkout already in circulation.

## Configuration

Set these only in the server's ignored `apps/web/.env` or hosting secret manager:

- `NYLONPAY_API_KEY`
- `NYLONPAY_API_SECRET`
- `NYLONPAY_WEBHOOK_SECRET`
- `NYLONPAY_BASE_URL` (default: official Nylon Pay services URL)
- `INVOICE_BUSINESS_NAME`, `INVOICE_BUSINESS_EMAIL`, `INVOICE_BUSINESS_ADDRESS`
- `APP_URL` (the real public HTTPS URL in deployment)

Run `php artisan config:clear` after local changes, or rebuild the configuration cache during deployment. Set `APP_DEBUG=false` in production. Keys supplied during development have been stored locally; deploy them through your hosting secret mechanism.

In the Nylon Pay dashboard, configure this URL on the same API key that creates the invoices:

```text
https://YOUR_PUBLIC_HOST/api/v1/webhooks/nylonpay
```

Use the matching webhook secret. The local `127.0.0.1` address cannot receive provider notifications. No dashboard webhook was configured during development because a public host was not provided.

## Deployment and checks

1. Install Composer dependencies and run `php artisan migrate` against the intended database.
2. Set the business identity, public URL and payment secrets; configure the webhook in Nylon Pay.
3. Run `php artisan nylonpay:check` for a read-only connection check.
4. Schedule Laravel's scheduler every minute (`php artisan schedule:run`). The invoice reconciliation command runs every five minutes. You can also run `php artisan invoices:reconcile` manually. It returns a nonzero exit code if any status could not be confirmed.
5. Complete an end-to-end sandbox invoice: verify that the SDK's hosted checkout reference matches the transaction/webhook reference, the correct total appears, confirmation updates the invoice, repeat callbacks are harmless, and failed payments remain unpaid. No real charge was made during development.

The installed SDK is `nile-squad/nylonpay-php` v0.1.0. Its current API requires `customerEmail` and uses `merchantReference` / `paymentLink`. Older invoice documentation also describes `reference`, `redirectUrl` and `url`. The adapter sends the same 14-character reference in both reference fields and accepts either documented URL response field. This compatibility handling must be verified against your account's hosted flow before live rollout; a read-only credential check does not establish the hosted flow's reference correlation.

The existing Laravel dependency lock reports security advisories. Review `composer audit` and upgrade the affected existing dependencies before production rollout; the invoice implementation does not claim to remediate the inherited framework stack.

## Recovery

A checkout claim is committed before contacting Nylon Pay. If creation times out or returns an invalid response, the invoice stays `uncertain` and the app refuses to generate a duplicate checkout. A process crash can similarly leave `creating`. Admins see the reference that needs reconciliation.

Check that reference in Nylon Pay and contact provider support if necessary. Do not reset the local checkout state or issue a replacement until the provider confirms whether a payable invoice exists. An operator must recover a confirmed existing checkout URL from Nylon Pay or explicitly establish that none exists. There is deliberately no automatic retry with a new invoice/reference after an ambiguous failure.

Payment amounts/reference/type mismatches return HTTP 422 and do not change invoice status. Invalid or stale webhook signatures return 401. Delivery IDs are deduplicated transactionally; paid status never regresses on a late failure event. Unknown references are acknowledged without changing local invoices, since the API key may serve other products. Monitor unresolved invoices with the reconciliation command.

## API

See `packages/api-contract/openapi.json` for request and response schemas.

- `POST /api/v1/auth/token`: admin login with `email`, `password`, `device_name`; five requests/minute/IP.
- `DELETE /api/v1/auth/token`: revoke the current bearer token.
- `GET /api/v1/admin/invoices`: paginated list; optional `q` and `status` (`draft`, `issued`, `overdue`, `paid`, `void`).
- `POST /api/v1/admin/invoices`: create draft.
- `GET /api/v1/admin/invoices/{id}`: invoice detail.
- `PUT /api/v1/admin/invoices/{id}`: replace draft details/items.
- `POST /api/v1/admin/invoices/{id}/issue`: issue and expose share URL.
- `POST /api/v1/admin/invoices/{id}/void`: void before checkout begins.

Admin endpoints require both an admin role and an `invoices:manage` bearer-token ability. API amounts are integers in UGX. Validation returns 422, unauthorized requests 401/403, and invalid lifecycle transitions 409. Tokens can be revoked individually and should be stored only in secure device storage. The API currently uses Sanctum's configured token lifetime; define a rotation/expiry policy before distributing a mobile app.

## Provider references

- [Official PHP SDK](https://github.com/nile-squad/nylonpay-php)
- [Current request/response types](https://docs.nylonpay.nilesquad.com/docs/api-reference/types)
- [Hosted payment links](https://docs.nylonpay.nilesquad.com/docs/guides/payment-links)
- [Signed webhooks](https://docs.nylonpay.nilesquad.com/docs/guides/webhooks)

## Verification in this workspace

- 18 automated tests / 109 assertions passed on PHP 8.4, including the existing site tests.
- Blade compilation and Laravel route caching succeeded.
- Browser checks covered creating a two-item invoice, issuing it, viewing the share page, and a 390px phone viewport.
- The supplied local credentials received a `not_found` result for an intentionally nonexistent transaction in a read-only API lookup. No hosted invoice or payment was created in the provider account.
- The configured MySQL connection refused connections. Migrations were verified in an isolated SQLite preview database; the configured application's database still needs `php artisan migrate` when MySQL is available.

## Downloadable receipts

Verified payment now automatically creates one receipt with a unique receipt number. Receipt issuance is part of the payment database transaction; duplicate notifications reuse the same receipt. The stored receipt preserves the client, business, line items, amount, payment reference and confirmation date even if business configuration later changes. Previously paid invoices receive their receipt on first download.

Clients can download the PDF from the paid invoice screen. Administrators have a download button on the invoice detail screen. The mobile API exposes `receipt_url`, served by `GET /api/v1/admin/invoices/{id}/receipt` with the same admin/token protection. Unpaid invoices return 404. PDFs are generated locally with remote content disabled and sent as private, non-cacheable attachments.

Apply the new `2026_09_04_000002_create_invoice_receipts_table` migration before enabling this change. Receipt rendering and payment tests pass (21 tests, 141 assertions); the PDF and paid screen were visually reviewed using isolated demo data. No actual customer payment was simulated in the configured MySQL database or Nylon Pay account.

## Administrator setup

The administrator is configured from private `ADMIN_EMAIL` / `ADMIN_PASSWORD` environment values. Run `php artisan admin:provision` after migrations to create or update that administrator with a hashed password. The main seeder uses the same configuration instead of the former fixed demo login. The local preview administrator was provisioned separately in its isolated SQLite database. Existing receipt snapshots keep the contact details recorded when they were issued; new receipts include both company email addresses and phone numbers.

## Mark an invoice paid manually

Administrators can open a draft or issued invoice, choose **Mark as paid**, select cash/bank transfer/mobile money/other, enter an optional reference and a required internal note, then confirm the full amount was received. This sets the invoice to paid and creates its receipt in one database transaction. It records the administrator ID and payment details. The internal note is never included on the client receipt. Voided invoices cannot be marked paid, and repeat submissions preserve the first payment and receipt.

Paid invoices offer **Print receipt** (opens the PDF inline; use the browser PDF viewer's print button) and **Download receipt · PDF**. Manually recorded receipts identify the payment method and business confirmation rather than claiming Nylon Pay processed them. A started provider checkout must be resolved with Nylon Pay to avoid a second collection; the admin form displays this reminder.

Deploy the code and run `php artisan migrate --force` from `apps/web` to apply `2026_09_04_000003_add_manual_invoice_payments`. The scoped mobile endpoint is `POST /api/v1/admin/invoices/{id}/mark-paid`. Verification: 24 tests / 171 assertions pass, including permissions, required confirmation, void rejection, idempotent receipts and both PDF response modes.
