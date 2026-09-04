# Future Flutter app — iOS and Android

This directory reserves the mobile workspace. No mobile UI is implemented yet.

When mobile development begins, initialize Flutter here with the chosen organization/bundle identifier:

```sh
flutter create --platforms=ios,android --project-name digitomove .
```

Use the Laravel backend in `../web`; do not create a separate billing database or embed Nylon Pay credentials in Dart, platform files, or app build variables. The API contract is at `../../packages/api-contract/openapi.json`.

For an admin mobile workflow:

1. Exchange the admin email/password and a device name at `POST /api/v1/auth/token`.
2. Store the returned bearer token in the platform's secure storage. Use HTTPS.
3. Call `/api/v1/admin/invoices` to list/create drafts and `/issue` to issue invoices.
4. Share the returned `share_url` through the operating system share sheet.
5. Open the public invoice in a browser for payment. The server owns checkout creation and payment verification.
6. Revoke the device token with `DELETE /api/v1/auth/token` on sign-out.

The current API supports administrator billing. Client account history, client authentication, push notifications, deep links and mobile UI are future work. Invoice recipients can already view and pay by private link without an account.
