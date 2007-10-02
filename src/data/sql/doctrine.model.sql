CREATE TABLE cf_workflow(
 id BIGINT AUTO_INCREMENT,
 template_id BIGINT,
 sender_id BIGINT,
 description TEXT,
 end_action BIGINT,
 is_archived BIGINT,
 is_deleted BIGINT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_user_substitute(
 id BIGINT AUTO_INCREMENT,
 user_id BIGINT,
 substitute_id BIGINT,
 delay BIGINT,
 position BIGINT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_user_index(
 id BIGINT AUTO_INCREMENT,
 user_id BIGINT,
 `index` TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_user_filter_value(
 id BIGINT AUTO_INCREMENT,
 user_filter_id BIGINT,
 `key` TEXT,
 `value` TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_user_filter(
 id BIGINT AUTO_INCREMENT,
 user_id BIGINT,
 description TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_user(
 id BIGINT AUTO_INCREMENT,
 project_id BIGINT,
 access_level BIGINT,
 description TEXT,
 username TEXT,
 password TEXT,
 firstname TEXT,
 lastname TEXT,
 email TEXT,
 street TEXT,
 country TEXT,
 zipcode BIGINT,
 city TEXT,
 telephone1 TEXT,
 telephone2 TEXT,
 mobilephone TEXT,
 fax TEXT,
 organisation TEXT,
 department TEXT,
 cost_center TEXT,
 userdefined1 TEXT,
 userdefined2 TEXT,
 created_at BIGINT,
 updated_at BIGINT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_trigger_time(
 id BIGINT AUTO_INCREMENT,
 breakpoint_id BIGINT,
 `value` TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_trigger_database_value(
 id BIGINT AUTO_INCREMENT,
 breakpoint_id BIGINT,
 trigger_id BIGINT,
 required_value TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_trigger(
 id BIGINT AUTO_INCREMENT,
 `value` TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_template_slot(
 id BIGINT AUTO_INCREMENT,
 template_id BIGINT,
 description TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_template(
 id BIGINT AUTO_INCREMENT,
 project_id BIGINT,
 description TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_revision(
 id BIGINT AUTO_INCREMENT,
 workflow_id BIGINT,
 description TEXT,
 revision BIGINT,
 created_at BIGINT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_project(
 id BIGINT AUTO_INCREMENT,
 description TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_process(
 id BIGINT AUTO_INCREMENT,
 workflow_id BIGINT,
 revision_id BIGINT,
 node_id BIGINT,
 user_id BIGINT,
 substitute_id BIGINT,
 decission_state BIGINT,
 decission_date BIGINT,
 start_date BIGINT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_node_condition(
 id BIGINT AUTO_INCREMENT,
 node_id BIGINT,
 destination_node_id BIGINT,
 `condition` TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_node(
 id BIGINT AUTO_INCREMENT,
 receiver_id BIGINT,
 workflow_id BIGINT,
 template_id BIGINT,
 template_slot_id BIGINT,
 description TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_field_value(
 id BIGINT AUTO_INCREMENT,
 field_id BIGINT,
 revision_id BIGINT,
 `value` TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_field_definition(
 id BIGINT AUTO_INCREMENT,
 field_id BIGINT,
 `value` TEXT,
 position BIGINT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_field(
 id BIGINT AUTO_INCREMENT,
 description TEXT,
 default_value TEXT,
 type BIGINT,
 is_read_only BIGINT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_event_action_value(
 id BIGINT AUTO_INCREMENT,
 event_action_id BIGINT,
 `key` TEXT,
 `value` TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_event_action_php(
 id BIGINT AUTO_INCREMENT,
 event_id BIGINT,
 script_url TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_event_action_pdo(
 id BIGINT AUTO_INCREMENT,
 event_id BIGINT,
 dsn TEXT,
 username TEXT,
 password TEXT,
 connection_string TEXT,
 table_name TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_event_action_odbc(
 id BIGINT AUTO_INCREMENT,
 event_id BIGINT,
 dsn TEXT,
 table_name TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_event_action_breakpoint(
 id BIGINT AUTO_INCREMENT,
 event_id BIGINT,
 trigger_type BIGINT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_event(
 id BIGINT AUTO_INCREMENT,
 node_id BIGINT,
 type BIGINT,
 action_type BIGINT,
 position BIGINT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_cross_trigger_user(
 id BIGINT AUTO_INCREMENT,
 trigger_id BIGINT,
 user_id BIGINT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_cross_project_user(
 id BIGINT AUTO_INCREMENT,
 project_id BIGINT,
 user_id BIGINT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_cross_field_slot(
 id BIGINT AUTO_INCREMENT,
 field_id BIGINT,
 template_slot_id BIGINT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_config(
 id BIGINT AUTO_INCREMENT,
 description TEXT,
 `key` TEXT,
 `value` TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;

CREATE TABLE cf_attachment(
 id BIGINT AUTO_INCREMENT,
 revision_id BIGINT,
 path TEXT,
 PRIMARY KEY(id))
ENGINE = INNODB;
