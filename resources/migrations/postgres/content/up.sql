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
    id BIGSERIAL NOT NULL,
    content_id BIGINT NOT NULL,
    account_id BIGINT NOT NULL,
    permissions JSONB NOT NULL,
    PRIMARY KEY (id, content_id, account_id),
    CONSTRAINT content_permission_content_id_fk FOREIGN KEY (content_id)
        REFERENCES content (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT content_permission_account_id_fk FOREIGN KEY (account_id)
        REFERENCES account (id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE content_tag (
    id BIGSERIAL NOT NULL,
    content_id BIGINT NOT NULL,
    data JSONB NOT NULL,
    PRIMARY KEY (id, content_id),
    CONSTRAINT content_tag_content_id_fk FOREIGN KEY (content_id)
        REFERENCES content (id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE content_type (
    id BIGSERIAL NOT NULL,
    content_id BIGINT NOT NULL,
    data JSONB NOT NULL,
    PRIMARY KEY (id, content_id),
    CONSTRAINT content_type_content_id_fk FOREIGN KEY (content_id)
        REFERENCES content (id) ON UPDATE CASCADE ON DELETE CASCADE
);

-- indexes
-- content
CREATE INDEX content_composite ON content (identifier, owner, visibility, url);
CREATE INDEX content_props ON content USING GIN (props);

-- content meta
CREATE INDEX content_meta_composite ON content_meta (content_id, status, agent);
CREATE INDEX content_meta_data ON content_meta USING GIN (data);

-- content permission
CREATE INDEX content_permission_permissions ON content_permission USING GIN (permissions);

-- content tag
CREATE INDEX content_tag_data ON content_tag USING GIN (data);

-- content type
CREATE INDEX content_type_data ON content_type USING GIN (data);