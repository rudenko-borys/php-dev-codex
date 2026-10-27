# PHP codex: git

Always loaded. IDs match the human codex. `<KEY>` is the issue key exactly as the tracker writes it (e.g. `JIRA-123`).

If the project defines its own git workflow (a skill, `CONTRIBUTING.md`, or framework rules for branches, commits,
worktrees, pushing), that workflow wins over GT1–GT5. Keep from this file only what the project leaves undefined.
Never push, force-push, or bypass hooks unless the project workflow explicitly allows it.

- **GT1 Branch naming**: `<KEY>-<slug>`; key verbatim (never lowercased), slug = issue title lowercased with non-alphanumeric runs → `-`; whole name under 80 chars, shorten the slug not the key: `JIRA-123-add-product-access-assignment-for-user-in-admin`.
- **GT2 Meaningful commits**: subject `<KEY>: <Imperative subject>`; capitalised, under 72 chars total, no trailing period; body only when the why is not in the diff; one logical change per commit: `JIRA-123: Add product access service`. Subject completes "If applied, this commit will ...".
- **GT3 Pull request naming**: `<KEY> <Short imperative summary>`, no trailing period: `JIRA-321 Add product status endpoint`. Open as a draft while not ready; never a `WIP:` prefix.
- **GT4 Use rebase**: rebase the feature branch on the target branch; no merge commits from the target.
- **GT5 Squash**: squash-merge PRs with more than one commit; delete the branch after merge.
