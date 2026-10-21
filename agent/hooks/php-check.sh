#!/usr/bin/env bash
# PostToolUse hook: run php-cs-fixer and PHPStan on the PHP file Claude just edited.
# Exit 2 feeds stderr back to Claude so it fixes the problem itself.
# Install: copy to .claude/hooks/php-check.sh in your project, chmod +x, and register it
# in .claude/settings.json (see agent/settings.hooks.json).

set -u

input="$(cat)"
file="$(printf '%s' "$input" | jq -r '.tool_input.file_path // empty')"

case "$file" in
    *.php) ;;
    *) exit 0 ;;
esac

[ -f "$file" ] || exit 0

project="${CLAUDE_PROJECT_DIR:-$(pwd)}"
cd "$project" || exit 0

errors=""

if [ -x vendor/bin/php-cs-fixer ]; then
    # Fix style in place; report only if the fixer itself fails (syntax error, config error).
    if ! output="$(vendor/bin/php-cs-fixer fix --quiet "$file" 2>&1)"; then
        errors+="php-cs-fixer failed for ${file}:"$'\n'"${output}"$'\n'
    fi
fi

if [ -x vendor/bin/phpstan ]; then
    if ! output="$(vendor/bin/phpstan analyse --no-progress --error-format=raw "$file" 2>&1)"; then
        errors+="PHPStan errors in ${file}:"$'\n'"${output}"$'\n'
    fi
fi

if [ -n "$errors" ]; then
    printf '%s\nFix these before continuing (codex TC7).\n' "$errors" >&2
    exit 2
fi

exit 0
