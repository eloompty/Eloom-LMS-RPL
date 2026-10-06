# Contributing

Thanks for helping improve Eloom LMS. Bug reports, fixes, documentation and new features are all welcome.

## Reporting bugs and requesting features

Open a GitHub issue. For a bug, include:
- the steps to reproduce it
- what you expected to happen and what happened instead
- your PHP and MySQL versions
- any relevant lines from `storage/logs/laravel.log`, with personal data removed

Security issues are the exception. Report them privately, as described in [SECURITY.md](SECURITY.md).

## Making changes

1. Fork the repository and create a branch from `main`, e.g. `fix/offer-letter-pdf` or
   `feature/sms-notifications`.
2. Follow the [README](README.md) to set up a local environment.
3. Keep each change focused. One pull request should solve one problem.
4. Match the style of the surrounding code:
   - Put new functionality in the module it belongs to, or create a new module with
     `php artisan module:make <Name>`.
   - Protect admin controllers with `$this->middleware('auth:user')` and check permissions with
     `checkRole('<key>', '<action>')`, as the existing controllers do.
   - Change the schema through a new migration. Never edit an existing one.
   - Read deployment-specific values (URLs, keys, names) from config/`.env`. Don't hardcode them.
5. Run the tests before you push:
   ```bash
   php artisan test
   ```
6. Open a pull request that explains what changed and why. Add screenshots for UI changes.

## Commit messages

Use short, imperative subject lines, e.g. `Add payment details to offer settings`.

## License

By contributing, you agree that your contributions are licensed under the [MIT License](LICENSE).
