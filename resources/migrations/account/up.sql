CREATE TABLE account (
    id BIGSERIAL NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(255),
    password VARCHAR,
    status SMALLINT NOT NULL DEFAULT 0,
    created TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    attributes JSONB
);
CREATE TABLE account_meta (
    id BIGSERIAL NOT NULL,
    account_id BIGINT NOT NULL,
    status SMALLINT,
    agent BIGINT,
    description VARCHAR,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id, account_id),
    CONSTRAINT account_meta_account_id_fk FOREIGN KEY (account_id)
        REFERENCES account (id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE INDEX account_email ON account (email);
CREATE INDEX account_phone ON account (phone);