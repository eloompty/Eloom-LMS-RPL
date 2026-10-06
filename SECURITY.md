# Security Policy

## Reporting a vulnerability

**Don't open a public issue for a security vulnerability.**

Report it privately through GitHub instead: on the repository's **Security** tab, choose
**Report a vulnerability**. Include:
- a description of the issue and its impact
- steps to reproduce it, or a proof of concept
- the affected version or commit

We'll acknowledge your report, keep you updated while we work on it, and credit you in the fix unless
you'd rather stay anonymous. Please give us reasonable time to release a fix before you disclose the
issue publicly.

## Supported versions

Only the latest commit on `main` receives security fixes.

## Hardening checklist for deployments

- Set `APP_ENV=production` and `APP_DEBUG=false`.
- Serve the app over HTTPS only, and point the web server's document root at `public/`.
- Complete the first-run registration straight after deploying. Registration closes as soon as the
  first account exists.
- Use your own Firebase project, socket server and converter service. Restrict API keys to your domain.
- Keep dependencies up to date. Run `composer audit` regularly.
