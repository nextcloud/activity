<!--
  - SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

# Activity database configuration

The Activity app stores its events in two tables (`activity` and `activity_mq`).
By default these live in the same database as the rest of Nextcloud.

On large or busy installations the activity log can grow into hundreds of
millions of rows and dominate the size and write load of the main database. To
keep that traffic off the main database, the Activity app supports an
**optional, dedicated database connection**. When configured, only Activity's
two tables are read/written there; everything else continues to use the main
Nextcloud database.

## When to use a dedicated database

Consider a separate connection if you are seeing one or more of:

- The `activity` and `activity_mq` tables are larger than the rest of the
  schema combined (check the **Activity** admin section, "Database" panel).
- The main DB is the bottleneck and you want to offload sustained activity
  writes to a different host.
- Compliance / retention requirements differ between activity events and the
  rest of your Nextcloud data.

For most installations the default (shared connection) is the right choice.

## Configuration keys

All keys go into `config/config.php`. The activity app uses the same connection
factory as the main DB, so the keys mirror the standard ones with an
`activity_` prefix.

If **all** of `activity_dbuser`, `activity_dbpassword`, `activity_dbname`,
`activity_dbhost`, `activity_dbport` and `activity_dbdriveroptions` are unset,
the app falls back to the main Nextcloud connection. As soon as **any one** of
them is set, the dedicated connection is used and the others must be filled in.

| Key | Type | Default | Description |
|-----|------|---------|-------------|
| `activity_dbtype` | string | falls back to `dbtype` | `mysql`, `pgsql`, `oci`, or `sqlite3` |
| `activity_dbhost` | string | – | Hostname (and optional `:port`) of the server |
| `activity_dbport` | int | – | Port (alternative to embedding it in `dbhost`) |
| `activity_dbname` | string | – | Database / schema name |
| `activity_dbuser` | string | – | Username |
| `activity_dbpassword` | string | – | Password |
| `activity_dbtableprefix` | string | falls back to `dbtableprefix` | Table prefix (usually `oc_`) |
| `activity_dbdriveroptions` | array | – | Driver-specific PDO options, see below |
| `activity_dbpersistent` | bool | follows main `dbpersistent` | Use persistent connections |

The `dbtype` key is **not** prefix-required: if `activity_dbtype` is omitted,
the app uses the same engine as the main DB. You can mix engines (e.g. main DB
on PostgreSQL, activity DB on MySQL) but it is rarely worth the operational
cost.

## Examples

### Same engine, separate host

```php
<?php
$CONFIG = [
    // …existing main-DB config…
    'dbtype'   => 'mysql',
    'dbhost'   => 'main-db.internal',
    'dbname'   => 'nextcloud',
    'dbuser'   => 'nextcloud',
    'dbpassword' => 'secret',

    // Dedicated activity database on a different host:
    'activity_dbhost'     => 'activity-db.internal',
    'activity_dbname'     => 'nextcloud_activity',
    'activity_dbuser'     => 'nextcloud_activity',
    'activity_dbpassword' => 'another-secret',
    // dbtype, tableprefix, etc. inherited from the main DB
];
```

### Driver options (TLS to MySQL)

```php
'activity_dbdriveroptions' => [
    PDO::MYSQL_ATTR_SSL_CA   => '/etc/ssl/certs/db-ca.pem',
    PDO::MYSQL_ATTR_SSL_CERT => '/etc/ssl/certs/db-client.pem',
    PDO::MYSQL_ATTR_SSL_KEY  => '/etc/ssl/private/db-client.key',
],
```

## Migrations

Schema migrations for the two activity tables run against whichever connection
is active for this app. If you switch from the shared connection to a
dedicated one (or back), make sure the target database is up to date — the
easiest path is to enable / disable the app via `occ` so that the migrator
runs against the new connection.

## Backup and restore

When using a dedicated connection, your normal Nextcloud database backup will
**not** include the activity tables. Back up the dedicated database
separately, on the same schedule as the main one.

You can verify what each backup contains with:

```sql
-- Should be empty in the main DB if a dedicated connection is configured:
SHOW TABLES LIKE 'oc_activity%';
```

## Disk size visibility

The size of the activity tables on disk is shown on the **Activity** admin
settings page under "Database". Use this to decide whether moving activity to
a dedicated database is worthwhile.

## Troubleshooting

- **The app can't connect.** Run `occ config:system:get activity_dbhost` etc.
  to confirm what is actually loaded — typos in `config.php` fail silently.
- **Migrations did not run on the dedicated database.** Disable and re-enable
  the app, or run `occ maintenance:repair`.
- **You see activity data in *both* databases.** This usually means the app
  was used with a shared connection first, then switched. Either drop the
  duplicated tables from the main DB after verifying the dedicated DB has the
  data, or revert to the shared connection.
