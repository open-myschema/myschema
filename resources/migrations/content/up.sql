-- tables
CREATE TABLE content_tag (
    id BIGSERIAL NOT NULL PRIMARY KEY,
    name VARCHAR NOT NULL,
    link_target VARCHAR NOT NULL,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE content_type (
    id BIGSERIAL NOT NULL PRIMARY KEY,
    name VARCHAR NOT NULL,
    description VARCHAR,
    identifier VARCHAR NOT NULL,
    link_target VARCHAR NOT NULL,
    properties JSONB,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE content_item (
    id BIGSERIAL NOT NULL PRIMARY KEY,
    name VARCHAR,
    image VARCHAR,
    description VARCHAR,
    url VARCHAR,
    owner BIGINT NOT NULL,
    status SMALLINT NOT NULL DEFAULT 0,
    attributes JSONB,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT content_item_owner_fk FOREIGN KEY (owner)
        REFERENCES account (id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE content_item_meta (
    id BIGSERIAL NOT NULL,
    content_item_id BIGINT NOT NULL,
    description VARCHAR,
    status VARCHAR,
    agent BIGINT,
    data JSONB,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id, content_item_id),
    CONSTRAINT content_item_meta_content_item_id_fk FOREIGN KEY (content_item_id)
        REFERENCES content_item (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT content_meta_agent_fk FOREIGN KEY (agent)
        REFERENCES account (id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE content_item_tag (
    id BIGSERIAL NOT NULL,
    content_item_id BIGINT NOT NULL,
    tag_id BIGINT NOT NULL,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id, content_item_id, tag_id),
    CONSTRAINT content_item_tag_content_item_id_fk FOREIGN KEY (content_item_id)
        REFERENCES content_item (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT content_item_tag_tag_id_fk FOREIGN KEY (tag_id)
        REFERENCES content_tag (id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE content_item_type (
    id BIGSERIAL NOT NULL,
    content_item_id BIGINT NOT NULL,
    content_type_id BIGINT NOT NULL,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id, content_item_id, content_type_id),
    CONSTRAINT content_item_type_content_item_id_fk FOREIGN KEY (content_item_id)
        REFERENCES content_item (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT content_item_type_content_type_id_fk FOREIGN KEY (content_type_id)
        REFERENCES content_type (id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE content_item_permission (
    id BIGSERIAL NOT NULL,
    content_item_id BIGINT NOT NULL,
    account_id BIGINT NOT NULL,
    permission VARCHAR NOT NULL,
    is_granted BOOLEAN NOT NULL,
    PRIMARY KEY (id, content_item_id, account_id),
    CONSTRAINT content_item_permission_content_item_id_fk FOREIGN KEY (content_item_id)
        REFERENCES content_item (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT content_item_permission_account_id_fk FOREIGN KEY (account_id)
        REFERENCES account (id) ON UPDATE CASCADE ON DELETE CASCADE
);

-- indexes
CREATE INDEX content_tag_link_target ON content_tag (link_target);
CREATE INDEX content_type_link_target ON content_type (link_target);
CREATE INDEX content_item_url ON content_item (url);
CREATE INDEX content_item_owner ON content_item (owner);
CREATE INDEX content_item_status ON content_item (status);