-- tables
CREATE TABLE IF NOT EXISTS content (
    id INTEGER NOT NULL PRIMARY KEY,
    name TEXT,
    image TEXT,
    description TEXT,
    identifier TEXT NOT NULL UNIQUE,
    url TEXT,
    owner INTEGER NOT NULL,
    visibility INTEGER NOT NULL DEFAULT 0,
    props BLOB,
    created_at INTEGER NOT NULL,
    FOREIGN KEY (owner) REFERENCES account (id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS content_meta (
    id INTEGER NOT NULL PRIMARY KEY,
    content_id INTEGER NOT NULL,
    description TEXT,
    status INTEGER,
    agent INTEGER,
    data BLOB,
    executed_at INTEGER NOT NULL,
    FOREIGN KEY (content_id) REFERENCES content (id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (agent) REFERENCES account (id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS content_permission (
    id INTEGER NOT NULL,
    content_id INTEGER NOT NULL,
    account_id INTEGER NOT NULL,
    permissions BLOB NOT NULL,
    PRIMARY KEY (id, content_id, account_id),
    FOREIGN KEY (content_id) REFERENCES content (id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (account_id) REFERENCES account (id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS content_tag (
    id INTEGER NOT NULL,
    content_id INTEGER NOT NULL,
    data BLOB NOT NULL,
    PRIMARY KEY (id, content_id),
    FOREIGN KEY (content_id) REFERENCES content (id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS content_type (
    id INTEGER NOT NULL,
    content_id INTEGER NOT NULL,
    data BLOB NOT NULL,
    PRIMARY KEY (id, content_id),
    FOREIGN KEY (content_id) REFERENCES content (id) ON UPDATE CASCADE ON DELETE CASCADE
);

-- indexes
-- content
CREATE INDEX IF NOT EXISTS content_composite ON content (identifier, owner, visibility, url);

-- content meta
CREATE INDEX IF NOT EXISTS content_meta_composite ON content_meta (content_id, status, agent);
