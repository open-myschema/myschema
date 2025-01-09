-- tables
CREATE TABLE content (
    id BIGSERIAL NOT NULL PRIMARY KEY,
    name VARCHAR,
    image VARCHAR,
    description VARCHAR,
    identifier VARCHAR,
    url VARCHAR,
    owner BIGINT NOT NULL,
    visibility SMALLINT NOT NULL DEFAULT 0,
    props JSONB,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT content_owner_fk FOREIGN KEY (owner)
        REFERENCES account (id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE content_meta (
    id BIGSERIAL NOT NULL PRIMARY KEY,
    content_id BIGINT NOT NULL,
    description VARCHAR,
    status SMALLINT,
    agent BIGINT,
    data JSONB,
    executed_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT content_meta_content_id_fk FOREIGN KEY (content_id)
        REFERENCES content (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT content_meta_agent_fk FOREIGN KEY (agent)
        REFERENCES account (id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE content_permission (
    id BIGSERIAL NOT NULL PRIMARY KEY,
    content_id BIGINT NOT NULL,
    account_id BIGINT NOT NULL,
    permission VARCHAR NOT NULL,
    is_granted BOOLEAN NOT NULL,
    CONSTRAINT content_permission_content_id_fk FOREIGN KEY (content_id)
        REFERENCES content (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT content_permission_account_id_fk FOREIGN KEY (account_id)
        REFERENCES account (id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE content_tag (
    id BIGSERIAL NOT NULL PRIMARY KEY,
    content_id BIGINT NOT NULL,
    name VARCHAR NOT NULL,
    CONSTRAINT content_tag_content_id_fk FOREIGN KEY (content_id)
        REFERENCES content (id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE content_type (
    id BIGSERIAL NOT NULL PRIMARY KEY,
    content_id BIGINT NOT NULL,
    name VARCHAR NOT NULL,
    CONSTRAINT content_type_content_id_fk FOREIGN KEY (content_id)
        REFERENCES content (id) ON UPDATE CASCADE ON DELETE CASCADE
);

-- indexes
-- content
CREATE INDEX content_identifier ON content (identifier);
CREATE INDEX content_url ON content (url);
CREATE INDEX content_owner ON content (owner);
CREATE INDEX content_visibility ON content (visibility);

-- content meta
CREATE INDEX content_meta_content_id ON content_meta (content_id);
CREATE INDEX content_meta_status ON content_meta (status);
CREATE INDEX content_meta_agent ON content_meta (agent);

-- content permission
CREATE INDEX content_permission_content_id ON content_permission (content_id);
CREATE INDEX content_permission_account_id ON content_permission (account_id);

-- content tag
CREATE INDEX content_tag_content_id ON content_tag (content_id);
CREATE INDEX content_tag_name ON content_tag (name);

-- content type
CREATE INDEX content_type_content_id ON content_type (content_id);
CREATE INDEX content_type_name ON content_type (name);