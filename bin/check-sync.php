#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Verifies that README.md and agent/rules/*.md describe the same set of rules.
 *
 * Checks:
 *  1. every `### ` heading in a rule section of README.md is followed by `<!-- rule: ID -->`
 *  2. rule IDs are unique in README.md
 *  3. the README table of contents lists exactly the sections and headings that exist, with correct anchors
 *  4. every README rule ID is defined exactly once across agent/rules/*.md (`- **ID Heading**`)
 *  5. every ID defined in agent/rules/*.md exists in README.md
 *
 * Usage: php bin/check-sync.php   (exit code 1 on any problem)
 */

$root = dirname(__DIR__);
$readmePath = $root . '/README.md';
$rulesDir = $root . '/agent/rules';

$errors = [];

$readme = file_get_contents($readmePath);
if ($readme === false) {
    fwrite(STDERR, sprintf('Cannot read "%s".%s', $readmePath, PHP_EOL));
    exit(1);
}

$lines = explode("\n", $readme);

/** @var array<string, string> $readmeRules ID => heading */
$readmeRules = [];
/** @var list<array{string, list<string>}> $sections [section, headings] */
$sections = [];
$inRuleSection = false;

foreach ($lines as $index => $line) {
    if (preg_match('/^## (.+)$/', $line, $match) === 1) {
        $section = trim($match[1]);
        $inRuleSection = !in_array($section, ['About', 'Table of contents'], true);
        if ($inRuleSection) {
            $sections[] = [$section, []];
        }

        continue;
    }

    if (!$inRuleSection || preg_match('/^### (.+)$/', $line, $match) !== 1) {
        continue;
    }

    $heading = trim($match[1]);
    $sections[count($sections) - 1][1][] = $heading;

    $next = $lines[$index + 1] ?? '';
    if (preg_match('/^<!-- rule: ([A-Z]{2}\d+) -->$/', $next, $idMatch) !== 1) {
        $errors[] = sprintf('README.md:%d "%s" has no "<!-- rule: ID -->" line below the heading.', $index + 1, $heading);

        continue;
    }

    $id = $idMatch[1];
    if (isset($readmeRules[$id])) {
        $errors[] = sprintf('README.md:%d duplicate rule ID "%s" ("%s" and "%s").', $index + 2, $id, $readmeRules[$id], $heading);

        continue;
    }

    $readmeRules[$id] = $heading;
}

// --- table of contents ---------------------------------------------------------------------------------------------

$slug = static function (string $heading): string {
    $slug = strtolower($heading);
    $slug = preg_replace('/[^a-z0-9 \-]/', '', $slug) ?? '';

    return str_replace(' ', '-', $slug);
};

$expectedToc = [];
foreach ($sections as [$section, $headings]) {
    $expectedToc[] = sprintf('- [%s](#%s)', $section, $slug($section));
    foreach ($headings as $heading) {
        $expectedToc[] = sprintf('    * [%s](#%s)', $heading, $slug($heading));
    }
}

if (preg_match('/## Table of contents\n\n(.*?)\n\n---/s', $readme, $tocMatch) !== 1) {
    $errors[] = 'README.md: table of contents block not found.';
} else {
    $actualToc = explode("\n", $tocMatch[1]);
    foreach (array_diff($expectedToc, $actualToc) as $missing) {
        $errors[] = sprintf('README.md: table of contents is missing "%s".', trim($missing));
    }
    foreach (array_diff($actualToc, $expectedToc) as $stale) {
        $errors[] = sprintf('README.md: table of contents has stale entry "%s".', trim($stale));
    }
}

// --- agent rules ---------------------------------------------------------------------------------------------------

/** @var array<string, list<string>> $agentRules ID => files */
$agentRules = [];
$ruleFiles = glob($rulesDir . '/*.md') ?: [];

foreach ($ruleFiles as $file) {
    $content = file_get_contents($file);
    if ($content === false) {
        $errors[] = sprintf('Cannot read "%s".', $file);

        continue;
    }

    preg_match_all('/^\s*- \*\*([A-Z]{2}\d+) /m', $content, $matches);
    foreach ($matches[1] as $id) {
        $agentRules[$id][] = basename($file);
    }
}

foreach ($readmeRules as $id => $heading) {
    $files = $agentRules[$id] ?? [];
    if ($files === []) {
        $errors[] = sprintf('agent/rules: "%s %s" is missing (add "- **%s %s**: ..." to the right file).', $id, $heading, $id, $heading);
    } elseif (count($files) > 1) {
        $errors[] = sprintf('agent/rules: "%s" is defined more than once (%s).', $id, implode(', ', $files));
    }
}

foreach ($agentRules as $id => $files) {
    if (!isset($readmeRules[$id])) {
        $errors[] = sprintf('agent/rules/%s: "%s" does not exist in README.md.', $files[0], $id);
    }
}

// --- report --------------------------------------------------------------------------------------------------------

if ($errors !== []) {
    foreach ($errors as $error) {
        fwrite(STDERR, $error . PHP_EOL);
    }
    fwrite(STDERR, sprintf('%d problem(s).%s', count($errors), PHP_EOL));
    exit(1);
}

printf(
    'OK: %d rules in README.md, all present in %d agent rule file(s).%s',
    count($readmeRules),
    count($ruleFiles),
    PHP_EOL,
);
