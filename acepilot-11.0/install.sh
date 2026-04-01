#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
CLAUDE_HOME="${CLAUDE_HOME:-$HOME/.claude}"

RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[0;33m'; BOLD='\033[1m'; NC='\033[0m'

usage() {
  echo -e "${BOLD}AcePilot 11.0 installer${NC}"
  echo ""
  echo "Usage:"
  echo "  $(basename "$0")                    Install user-level files to ~/.claude/"
  echo "  $(basename "$0") --project          Install project-level files to .claude/"
  echo "  $(basename "$0") --activate KEY     Save Pro license key to ~/.claude/acepilot-license"
  echo "  $(basename "$0") --check            Verify installation"
  echo "  $(basename "$0") --uninstall        Remove AcePilot files"
  echo ""
  echo "Options:"
  echo "  --help, -h    Show this help"
}

install_file() {
  local src="$1" dst="$2" label="$3"
  mkdir -p "$(dirname "$dst")"

  if [ ! -f "$dst" ]; then
    cp "$src" "$dst"
    echo -e "  ${GREEN}✓${NC} $label"
  elif diff -q "$src" "$dst" >/dev/null 2>&1; then
    echo -e "  ${GREEN}✓${NC} $label (up to date)"
  else
    local backup="${dst}.bak.$(date +%Y%m%d-%H%M%S)"
    cp "$dst" "$backup"
    cp "$src" "$dst"
    echo -e "  ${YELLOW}~${NC} $label (updated — backup: $(basename "$backup"))"
  fi
}

install_user() {
  echo -e "${BOLD}Installing AcePilot 11.0 — Self-Evolving Autonomy${NC}"
  echo ""

  # Core files
  install_file "$SCRIPT_DIR/user-level/CLAUDE.md" "$CLAUDE_HOME/CLAUDE.md" "CLAUDE.md (trigger)"
  install_file "$SCRIPT_DIR/user-level/settings.json" "$CLAUDE_HOME/settings.json" "settings.json (permissions + status line)"
  install_file "$SCRIPT_DIR/user-level/commands/acepilot.md" "$CLAUDE_HOME/commands/acepilot.md" "commands/acepilot.md (the brain)"

  # Agents — 6 specialists
  install_file "$SCRIPT_DIR/user-level/agents/researcher.md" "$CLAUDE_HOME/agents/researcher.md" "agents/researcher.md (Haiku scanner)"
  install_file "$SCRIPT_DIR/user-level/agents/reviewer.md" "$CLAUDE_HOME/agents/reviewer.md" "agents/reviewer.md (code reviewer)"
  install_file "$SCRIPT_DIR/user-level/agents/designer.md" "$CLAUDE_HOME/agents/designer.md" "agents/designer.md (UX + accessibility)"
  install_file "$SCRIPT_DIR/user-level/agents/security.md" "$CLAUDE_HOME/agents/security.md" "agents/security.md (vulnerability scanner)"
  install_file "$SCRIPT_DIR/user-level/agents/architect.md" "$CLAUDE_HOME/agents/architect.md" "agents/architect.md (architecture + performance)"
  install_file "$SCRIPT_DIR/user-level/agents/strategist.md" "$CLAUDE_HOME/agents/strategist.md" "agents/strategist.md (product + growth)"

  # USER-KNOWLEDGE.md: seed only — never overwrite
  if [ ! -f "$CLAUDE_HOME/USER-KNOWLEDGE.md" ]; then
    cp "$SCRIPT_DIR/user-level/USER-KNOWLEDGE.md" "$CLAUDE_HOME/USER-KNOWLEDGE.md"
    echo -e "  ${GREEN}✓${NC} USER-KNOWLEDGE.md (user model — seeded)"
  else
    echo -e "  ${GREEN}✓${NC} USER-KNOWLEDGE.md (exists — preserved)"
  fi
  echo ""

  # License key setup — saved to ~/.claude/acepilot-license (user-level, applies to all projects)
  if [ ! -f "$CLAUDE_HOME/acepilot-license" ]; then
    echo -e "  ${YELLOW}?${NC} No license key found."
    echo -e "    Pro users: paste your license key to unlock auto, ship, and god modes."
    echo -e "    Get yours at ${BOLD}https://acepilot.app/#pricing${NC}"
    echo -n "    License key (or Enter to skip): "
    read -r license_key
    if [ -n "$license_key" ]; then
      echo "$license_key" > "$CLAUDE_HOME/acepilot-license"
      chmod 600 "$CLAUDE_HOME/acepilot-license"
      echo -e "  ${GREEN}✓${NC} acepilot-license saved to $CLAUDE_HOME/acepilot-license"
      echo -e "  ${GREEN}✓${NC} Pro mode active — works in ALL projects automatically"
      echo -e "    Dashboard: ${BOLD}https://acepilot.app/dashboard/${NC}"
    else
      echo -e "  ${YELLOW}-${NC} acepilot-license (skipped — Starter mode)"
      echo -e "    Activate later: ${BOLD}$(basename "$0") --activate YOUR-KEY${NC}"
    fi
  else
    echo -e "  ${GREEN}✓${NC} acepilot-license (Pro — applies to all projects)"
  fi

  echo ""
  echo -e "${GREEN}Done.${NC} Run ${BOLD}$(basename "$0") --project${NC} in each project to add hooks."
  echo ""
  echo -e "${BOLD}10.0 — Self-Evolving Autonomy:${NC}"
  echo "  Playbooks: proven workflows captured and replayed automatically"
  echo "  Self-calibration: gates, routing, ceremony auto-tune from execution data"
  echo "  Session chains: objectives persist across sessions with handoff state"
  echo "  Deploy pipeline: platform detection → build → deploy → verify"
  echo "  Everything from 9.0: Directive Expansion, IDENTITY.md, Business Framework"
  echo ""
  echo -e "${YELLOW}Note:${NC} If files were backed up (~ above), merge any custom content."
}

install_project() {
  echo -e "${BOLD}Installing project-level files to .claude/${NC}"
  echo ""
  mkdir -p .claude/state .claude/rules
  install_file "$SCRIPT_DIR/project-level/settings.json" ".claude/settings.json" "settings.json (hooks)"
  install_file "$SCRIPT_DIR/project-level/rules/browser.md" ".claude/rules/browser.md" "rules/browser.md (browser verification)"
  echo ""
  echo ""
  echo -e "${GREEN}Done.${NC} Optionally seed the knowledge, patterns, and analytics files:"
  echo "  cp \"$SCRIPT_DIR/KNOWLEDGE-TEMPLATE.md\" .claude/state/KNOWLEDGE.md"
  echo "  cp \"$SCRIPT_DIR/PATTERNS-TEMPLATE.md\" .claude/state/PATTERNS.md"
  echo "  cp \"$SCRIPT_DIR/ANALYTICS-TEMPLATE.md\" .claude/state/ANALYTICS.md"
  echo "  cp \"$SCRIPT_DIR/METRICS-TEMPLATE.md\" .claude/state/METRICS.md"
  echo "  cp \"$SCRIPT_DIR/IDENTITY-TEMPLATE.md\" .claude/state/IDENTITY.md"
  echo "  cp \"$SCRIPT_DIR/PLAYBOOKS-TEMPLATE.md\" .claude/state/PLAYBOOKS.md"
}

check_installation() {
  echo -e "${BOLD}Checking AcePilot 11.0 installation${NC}"
  echo ""
  local ok=0 warn=0 fail=0

  check_file() {
    local path="$1" label="$2" required="${3:-optional}"
    if [ -f "$path" ]; then
      ((ok++)); echo -e "  ${GREEN}✓${NC} $label"
    elif [ "$required" = "required" ]; then
      ((fail++)); echo -e "  ${RED}✗${NC} $label (missing)"
    else
      ((warn++)); echo -e "  ${YELLOW}-${NC} $label (not installed)"
    fi
  }

  echo "User-level ($CLAUDE_HOME/):"
  check_file "$CLAUDE_HOME/CLAUDE.md" "CLAUDE.md" required
  check_file "$CLAUDE_HOME/settings.json" "settings.json" required
  check_file "$CLAUDE_HOME/commands/acepilot.md" "commands/acepilot.md" required
  check_file "$CLAUDE_HOME/agents/researcher.md" "agents/researcher.md" required
  check_file "$CLAUDE_HOME/agents/reviewer.md" "agents/reviewer.md" required
  check_file "$CLAUDE_HOME/agents/designer.md" "agents/designer.md" required
  check_file "$CLAUDE_HOME/agents/security.md" "agents/security.md" required
  check_file "$CLAUDE_HOME/agents/architect.md" "agents/architect.md" required
  check_file "$CLAUDE_HOME/agents/strategist.md" "agents/strategist.md" required
  check_file "$CLAUDE_HOME/USER-KNOWLEDGE.md" "USER-KNOWLEDGE.md (user model)" optional
  check_file "$CLAUDE_HOME/acepilot-license" "acepilot-license (Pro key)" optional
  echo ""
  echo "Project-level (.claude/):"
  check_file ".claude/settings.json" "settings.json (hooks)" optional
  check_file ".claude/rules/browser.md" "rules/browser.md (browser verification)" optional
  check_file ".claude/state/KNOWLEDGE.md" "KNOWLEDGE.md" optional
  check_file ".claude/state/PATTERNS.md" "PATTERNS.md" optional
  check_file ".claude/state/ANALYTICS.md" "ANALYTICS.md" optional
  check_file ".claude/state/IDENTITY.md" "IDENTITY.md (project identity)" optional
  check_file ".claude/state/PLAYBOOKS.md" "PLAYBOOKS.md (proven workflows)" optional

  echo ""
  if [ "$fail" -gt 0 ]; then
    echo -e "${RED}$fail required file(s) missing.${NC} Run $(basename "$0") to install."
    exit 1
  elif [ "$warn" -gt 0 ]; then
    echo -e "${GREEN}All required files present.${NC} $warn optional file(s) not found."
  else
    echo -e "${GREEN}All files present. AcePilot 11.0 is ready.${NC}"
  fi
}

uninstall() {
  echo -e "${BOLD}Removing AcePilot files${NC}"
  echo ""
  local removed=0
  for f in \
    "$CLAUDE_HOME/commands/acepilot.md" \
    "$CLAUDE_HOME/agents/researcher.md" \
    "$CLAUDE_HOME/agents/reviewer.md" \
    "$CLAUDE_HOME/agents/designer.md" \
    "$CLAUDE_HOME/agents/security.md" \
    "$CLAUDE_HOME/agents/architect.md" \
    "$CLAUDE_HOME/agents/strategist.md"; do
    if [ -f "$f" ]; then
      rm "$f"
      ((removed++))
      echo -e "  ${GREEN}✓${NC} Removed $(basename "$f")"
    fi
  done
  echo ""
  [ "$removed" -eq 0 ] && echo "Nothing to remove." && return
  echo -e "${YELLOW}Note:${NC} CLAUDE.md and settings.json were NOT removed (may contain custom config)."
  echo "Remove manually if needed:  rm \"$CLAUDE_HOME/CLAUDE.md\" \"$CLAUDE_HOME/settings.json\""
}

activate_license() {
  local key="${1:-}"
  if [ -z "$key" ]; then
    echo -e "${RED}Error:${NC} No license key provided."
    echo "Usage: $(basename "$0") --activate YOUR-LICENSE-KEY"
    exit 1
  fi
  echo "$key" > "$CLAUDE_HOME/acepilot-license"
  chmod 600 "$CLAUDE_HOME/acepilot-license"
  echo -e "${GREEN}✓${NC} License key saved to $CLAUDE_HOME/acepilot-license"
  echo -e "${GREEN}✓${NC} Pro mode active — all modes unlocked: auto, ship, god"
}

case "${1:-}" in
  --project)    install_project ;;
  --activate)   activate_license "${2:-}" ;;
  --check)      check_installation ;;
  --uninstall)  uninstall ;;
  --help|-h)    usage ;;
  "")           install_user ;;
  *)            echo "Unknown option: $1" >&2; usage; exit 1 ;;
esac
