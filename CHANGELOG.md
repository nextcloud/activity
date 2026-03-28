<!--
  - SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: CC0-1.0
-->
# Changelog

## v33.0.0 — 2026-02-15

### Added
- feat(activity): add bulk activity endpoint (#2089)
- feat: use full date and time in activity emails (#2078)
- enh: add link on emails to notification settings (#2112)

### Changed
- refactor: adjust for Nextcloud 33 new files sidebar API (#2300)
- test: upgrade to PHPUnit 11 (#2202)

### Fixed
- fix: rework attributes (#2355)
- fix(emails): catch server connection exceptions (#2221)
- fix: activity insert (#2218)
- fix(sendDigests): catch the case that a specified user is not existing and log instead (#2064)
- fix: remove deprecated execute method for cronjob calls (#2161)
- fix(dashboard): enable rounded icons (#2097)
- fix: use target path for unshare-from-self events (#2103)
- fix(notification): deduplicate the settings check (#2041)
- fix(FilesHooks): catch all exceptions when looking up unrelated users (#2052)
- perf: pass the node of shares around instead of getting the path from id each time (#2098)

## v32.0.6 — 2026-02-06

### Fixed
- fix: rework attributes (#2355)

## v32.0.5 — 2026-01-14

No user-facing changes.

## v32.0.4 — 2026-01-14

No user-facing changes.

## v32.0.3 — 2025-12-03

### Fixed
- fix(emails): catch server connection exceptions (#2221)

## v32.0.2 — 2025-11-16

### Added
- feat(activity): add bulk activity endpoint (#2089)

### Fixed
- fix: activity insert (#2218)

## v32.0.1 — 2025-10-20

No user-facing changes.

## v32.0.0 — 2025-09-27

### Added
- feat: use full date and time in activity emails (#2078)
- enh: add link on emails to notification settings (#2112)

### Changed
- chore: migrate to Vue 3 (#2033, #2035)
- refactor: adjust nextcloud-vue props to latest version (#2034)
- test(cypress): enable test isolation (#2023)

### Fixed
- fix: use target path for unshare-from-self events (#2103)
- fix(dashboard): enable rounded icons (#2097)
- fix(FilesHooks): catch all exceptions when looking up unrelated users (#2052)
- fix(notification): deduplicate the settings check (#2041)
- fix: don't setup the filesystem to check if a user is blocked by ACLs (#1976)
- fix: fix valid parsed check (#1981)
- fix: improving logging of invalid parameters for activity subject (#1975)
- perf: pass the node of shares around instead of getting the path from id each time (#2098)
