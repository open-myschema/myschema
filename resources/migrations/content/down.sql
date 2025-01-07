-- indexes
DROP INDEX IF EXISTS content_tag_link_target;
DROP INDEX IF EXISTS content_type_link_target;
DROP INDEX IF EXISTS content_item_url;
DROP INDEX IF EXISTS content_item_owner;
DROP INDEX IF EXISTS content_item_status;

-- tables
DROP TABLE IF EXISTS content_item_type;
DROP TABLE IF EXISTS content_item_tag;
DROP TABLE IF EXISTS content_item_meta;
DROP TABLE IF EXISTS content_item_permission;
DROP TABLE IF EXISTS content_item;
DROP TABLE IF EXISTS content_type;
DROP TABLE IF EXISTS content_tag;