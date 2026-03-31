# Deploying acepilot.app

## Quick deploy (recommended)

```bash
# Deploy all site files
./site/deploy.sh

# Deploy only files changed in recent commits
./site/deploy.sh --changed

# Deploy specific files
./site/deploy.sh index.html dashboard/index.html
```

Requires SSH key configured for `u460488685@us-bos-web1570.main-hosting.eu` (port 65002).

## What gets deployed

`site/` → `~/domains/acepilot.app/public_html/` on Hostinger.

**Excluded (server-managed):**

- `api/config.php` — database credentials, Stripe keys
- `licenses.json` — license data
- `data/` — user session/objective data
- `.htaccess-licenses` — license validation rules

## Manual alternatives

### File Manager

1. Log in to [hpanel.hostinger.com](https://hpanel.hostinger.com)
2. Websites → acepilot.app → Files → File Manager
3. Navigate to `public_html/`
4. Upload files from `site/`

### SSH direct

```bash
scp -P 65002 site/index.html u460488685@us-bos-web1570.main-hosting.eu:~/domains/acepilot.app/public_html/
```

## After deployment

- [ ] Verify https://acepilot.app loads
- [ ] Check on mobile
- [ ] Test waitlist form
- [ ] Test install copy button

## Server details

- Host: `us-bos-web1570.main-hosting.eu`
- SSH port: `65002`
- User: `u460488685`
- Document root: `~/domains/acepilot.app/public_html/`
- SSL: Let's Encrypt (auto-renew)
