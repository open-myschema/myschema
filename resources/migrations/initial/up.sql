CREATE EXTENSION IF NOT EXISTS hstore;
CREATE TABLE migration (
    id BIGSERIAL NOT NULL PRIMARY KEY,
    database VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description VARCHAR,
    status SMALLINT NOT NULL DEFAULT 0,
    executed_at TIMESTAMP WITH TIME ZONE
);
CREATE INDEX migration_status ON migration (status);