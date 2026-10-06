## What this changes

## Why

## How to test

## Checklist
- [ ] Schema changes are in new migrations (existing ones aren't edited)
- [ ] New admin controllers use `auth:user` middleware and `checkRole()` permission checks
- [ ] No hardcoded URLs, keys or organisation names. Use config or `.env` instead
- [ ] New environment variables are added to `.env.example` and the README
- [ ] `php artisan test` passes
- [ ] Screenshots attached for UI changes
