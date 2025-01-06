CREATE TABLE content_type (
    id BIGSERIAL NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description VARCHAR,
    tag VARCHAR(255) NOT NULL,
    attributes HSTORE,
    link_target VARCHAR(255)
);
CREATE INDEX content_type_tag ON content_type (tag);
CREATE INDEX content_type_link_target ON content_type (link_target);