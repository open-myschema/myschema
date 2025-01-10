CREATE TABLE account (
    id BIGSERIAL NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    phone VARCHAR(255) UNIQUE,
    password VARCHAR,
    status SMALLINT NOT NULL DEFAULT 0,
    props JSONB
);
CREATE TABLE account_meta (
    id BIGSERIAL NOT NULL PRIMARY KEY,
    account_id BIGINT NOT NULL,
    status SMALLINT,
    agent BIGINT,
    description VARCHAR,
    executed_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data JSONB,
    CONSTRAINT account_meta_account_id_fk FOREIGN KEY (account_id)
        REFERENCES account (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT account_meta_agent_fk FOREIGN KEY (agent)
        REFERENCES account(id) ON UPDATE CASCADE ON DELETE CASCADE
);

-- indexes
CREATE INDEX account_meta_account_id ON account_meta (account_id);
CREATE INDEX account_meta_status ON account_meta (status);
CREATE INDEX account_meta_agent ON account_meta (agent);