CREATE TABLE IF NOT EXISTS migration (
    id INTEGER NOT NULL PRIMARY KEY,
    connection TEXT NOT NULL,
    name TEXT NOT NULL UNIQUE,
    status INTEGER NOT NULL DEFAULT 0,
    created_at INTEGER,
    executed_at INTEGER
);
CREATE INDEX IF NOT EXISTS migration_status ON migration (status);

-- @todo
-- input to the created_at column first time a migration appears
-- means using hooks to` install and uninstall modules/migrations