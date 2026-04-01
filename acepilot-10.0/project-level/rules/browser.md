---
paths:
  [
    "src/components/**/*",
    "src/app/**/*",
    "src/pages/**/*",
    "**/*.css",
    "**/*.scss",
    "**/*.tsx",
    "**/*.jsx",
    "**/*.vue",
    "**/*.svelte",
  ]
---

After any UI/CSS/layout change, verify via browser MCP:

1. Navigate to the dev server URL
2. Take an accessibility snapshot — verify the DOM rendered correctly
3. Check browser console — catch runtime errors
4. If interactive: fill inputs, click buttons, wait for response, snapshot again
5. Report: `✓ Browser: [what verified]`

Code compiling does not equal rendering correctly. Connection drop → `/chrome` reconnect. Login/CAPTCHA/dialog → stop, ask operator.
