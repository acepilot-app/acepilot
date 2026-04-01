# AcePilot — Product Metrics

Paste your analytics numbers here before each session. Takes 2-3 minutes from your dashboard. AcePilot uses this to prioritize tasks by real impact, gate high-traffic changes more carefully, and route specialists to business-critical paths.

**Last refreshed:** YYYY-MM-DD HH:MM

## Route Traffic (last 30 days)

From Google Analytics / PostHog / your analytics tool.

| Route      | Pageviews | Daily Avg | Users | Bounce | Conversion   | Notes        |
| ---------- | --------- | --------- | ----- | ------ | ------------ | ------------ |
| /          |           |           |       |        | N/A          | homepage     |
| /pricing   |           |           |       |        | % → checkout |              |
| /checkout  |           |           |       |        | % → confirm  | **critical** |
| /docs      |           |           |       |        | N/A          |              |
| /dashboard |           |           |       |        | N/A          | logged-in    |

## Transactions (last 30 days)

From Stripe / payment provider.

| Flow       | Count | Revenue | Avg Value | Notes |
| ---------- | ----- | ------- | --------- | ----- |
| New signup |       |         |           |       |
| Upgrade    |       |         |           |       |

## Error Rates (last 7 days)

From Sentry / server logs / uptime monitor. Only endpoints with >0.1% error rate.

| Endpoint     | 5xx Count | 5xx Rate | Status | Notes |
| ------------ | --------- | -------- | ------ | ----- |
| /api/example |           |          |        |       |

## Performance (Core Web Vitals)

From PageSpeed Insights / Lighthouse. Only routes with >1k DAU.

| Page | LCP | FID | CLS | Status |
| ---- | --- | --- | --- | ------ |
| /    |     |     |     |        |

## Notes

Any recent changes? Traffic spikes? Incidents? Launches?
