# PHP codex: git

Always loaded. IDs match the human codex. `<ID>` is the issue key (e.g. `JIRA-123`).

- **GT1 Branch naming**: `<id>-<up-to-8-words>` lowercase, hyphens only: `jira-123-add-product-access-assignment-for-user-in-admin`.
- **GT2 Meaningful commits**: `<ID> <Imperative subject>`; capitalised, max 50 chars after the ID, no trailing period, small focused commits: `JIRA-123 Add product access service`. Subject completes "If applied, this commit will ...".
- **GT3 Pull request naming**: `<ID> <Short imperative summary>`, no trailing period: `JIRA-321 Add product status endpoint`. Open as a draft while not ready; never a `WIP:` prefix.
- **GT4 Use rebase**: rebase the feature branch on the target branch; no merge commits from the target.
- **GT5 Squash**: squash-merge PRs with more than one commit; delete the branch after merge.
