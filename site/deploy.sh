#!/usr/bin/env bash
set -euo pipefail

# AcePilot deploy — pushes site/ to Hostinger via SCP
# Usage:
#   ./site/deploy.sh              Deploy all site files
#   ./site/deploy.sh --changed    Deploy only git-changed files since last deploy
#   ./site/deploy.sh index.html   Deploy specific file(s)

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
REMOTE="u460488685@us-bos-web1570.main-hosting.eu"
REMOTE_PATH="~/domains/acepilot.app/public_html"
PORT=65002

RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[0;33m'; BOLD='\033[1m'; NC='\033[0m'

# Files that should never be deployed (server-managed)
EXCLUDE=("api/config.php" "licenses.json" "data/" ".htaccess-licenses")

should_exclude() {
  local file="$1"
  for pattern in "${EXCLUDE[@]}"; do
    [[ "$file" == *"$pattern"* ]] && return 0
  done
  return 1
}

deploy_file() {
  local src="$1"
  local rel="${src#$SCRIPT_DIR/}"

  if should_exclude "$rel"; then
    echo -e "  ${YELLOW}⊘${NC} $rel (excluded — server-managed)"
    return
  fi

  local remote_dir
  remote_dir="$(dirname "$rel")"
  if [ "$remote_dir" != "." ]; then
    ssh -p "$PORT" "$REMOTE" "mkdir -p $REMOTE_PATH/$remote_dir" 2>/dev/null
  fi

  scp -P "$PORT" "$src" "$REMOTE:$REMOTE_PATH/$rel" 2>/dev/null
  echo -e "  ${GREEN}✓${NC} $rel"
}

echo -e "${BOLD}Deploying to acepilot.app${NC}"
echo ""

# Test connection
if ! ssh -p "$PORT" -o ConnectTimeout=5 "$REMOTE" "echo ok" >/dev/null 2>&1; then
  echo -e "${RED}Error:${NC} Cannot connect to Hostinger. Check SSH key and network."
  exit 1
fi

if [ "${1:-}" = "--changed" ]; then
  # Deploy only files changed since last deploy tag
  echo -e "Mode: ${YELLOW}changed files only${NC}"
  echo ""

  # Get changed site files from git
  changed=$(cd "$SCRIPT_DIR/.." && git diff --name-only HEAD~3 -- site/ 2>/dev/null || git diff --name-only HEAD~1 -- site/)

  if [ -z "$changed" ]; then
    echo "No site files changed."
    exit 0
  fi

  count=0
  while IFS= read -r file; do
    full_path="$SCRIPT_DIR/../$file"
    if [ -f "$full_path" ]; then
      deploy_file "$full_path"
      count=$((count + 1))
    fi
  done <<< "$changed"

  echo ""
  echo -e "${GREEN}Deployed $count file(s).${NC}"

elif [ $# -gt 0 ]; then
  # Deploy specific files
  echo -e "Mode: ${YELLOW}specific files${NC}"
  echo ""

  for file in "$@"; do
    full_path="$SCRIPT_DIR/$file"
    if [ -f "$full_path" ]; then
      deploy_file "$full_path"
    else
      echo -e "  ${RED}✗${NC} $file (not found)"
    fi
  done

else
  # Deploy all deployable files
  echo -e "Mode: ${YELLOW}full deploy${NC}"
  echo ""

  count=0
  while IFS= read -r file; do
    deploy_file "$file"
    count=$((count + 1))
  done < <(find "$SCRIPT_DIR" -type f \
    ! -name "deploy.sh" \
    ! -name "DEPLOY.md" \
    ! -name ".DS_Store" \
    ! -path "*/data/*" \
    | sort)

  echo ""
  echo -e "${GREEN}Deployed $count file(s).${NC}"
fi

echo -e "Live at: ${BOLD}https://acepilot.app${NC}"
