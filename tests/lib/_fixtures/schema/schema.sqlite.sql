DROP TABLE IF EXISTS `ngbm_layout`;
CREATE TABLE `ngbm_layout` (
  `id` integer NOT NULL,
  `status` integer NOT NULL,
  `type` text(255) NOT NULL,
  `name` text(255) NOT NULL,
  `created` integer NOT NULL,
  `modified` integer NOT NULL,
  `shared` integer NOT NULL,
  PRIMARY KEY (`id`, `status`)
);

DROP TABLE IF EXISTS `ngbm_zone`;
CREATE TABLE `ngbm_zone` (
  `identifier` text(255) NOT NULL,
  `layout_id` integer NOT NULL,
  `status` integer NOT NULL,
  `root_block_id` integer NOT NULL,
  `linked_layout_id` integer,
  `linked_zone_identifier` text(255),
  PRIMARY KEY (`identifier`, `layout_id`, `status`),
  FOREIGN KEY (`layout_id`, `status`)
    REFERENCES `ngbm_layout` (`id`, `status`)
);

DROP TABLE IF EXISTS `ngbm_block`;
CREATE TABLE `ngbm_block` (
  `id` integer NOT NULL,
  `status` integer NOT NULL,
  `layout_id` integer NOT NULL,
  `depth` integer NOT NULL,
  `path` text(255) NOT NULL,
  `parent_id` integer DEFAULT NULL,
  `placeholder` text(255) DEFAULT NULL,
  `position` integer DEFAULT NULL,
  `definition_identifier` text(255) NOT NULL,
  `view_type` text(255) NOT NULL,
  `item_view_type` text(255) NOT NULL,
  `name` text(255) NOT NULL,
  `placeholder_parameters` text NOT NULL,
  `parameters` text NOT NULL,
  PRIMARY KEY (`id`, `status`),
  FOREIGN KEY (`layout_id`, `status`)
    REFERENCES `ngbm_layout` (`id`, `status`)
);

DROP TABLE IF EXISTS `ngbm_collection`;
CREATE TABLE `ngbm_collection` (
  `id` integer NOT NULL,
  `status` integer NOT NULL,
  `type` integer NOT NULL,
  `shared` integer NOT NULL,
  `name` text(255) DEFAULT NULL,
  PRIMARY KEY (`id`, `status`)
);

DROP TABLE IF EXISTS `ngbm_collection_item`;
CREATE TABLE `ngbm_collection_item` (
  `id` integer NOT NULL,
  `status` integer NOT NULL,
  `collection_id` integer NOT NULL,
  `position` integer NOT NULL,
  `type` integer NOT NULL,
  `value_id` text(255) NOT NULL,
  `value_type` text(255) NOT NULL,
  PRIMARY KEY (`id`, `status`),
  FOREIGN KEY (`collection_id`, `status`)
    REFERENCES `ngbm_collection` (`id`, `status`)
);

DROP TABLE IF EXISTS `ngbm_collection_query`;
CREATE TABLE `ngbm_collection_query` (
  `id` integer NOT NULL,
  `status` integer NOT NULL,
  `collection_id` integer NOT NULL,
  `position` integer NOT NULL,
  `identifier` text(255) NOT NULL,
  `type` text(255) NOT NULL,
  `parameters` text NOT NULL,
  PRIMARY KEY (`id`, `status`),
  FOREIGN KEY (`collection_id`, `status`)
    REFERENCES `ngbm_collection` (`id`, `status`)
);

DROP TABLE IF EXISTS `ngbm_block_collection`;
CREATE TABLE `ngbm_block_collection` (
  `block_id` integer NOT NULL,
  `block_status` integer NOT NULL,
  `collection_id` integer NOT NULL,
  `collection_status` integer NOT NULL,
  `identifier` text(255) NOT NULL,
  `start` integer NOT NULL,
  `length` integer DEFAULT NULL,
  PRIMARY KEY (`block_id`, `block_status`, `identifier`),
  FOREIGN KEY (`block_id`, `block_status`)
    REFERENCES `ngbm_block` (`id`, `status`),
  FOREIGN KEY (`collection_id`, `collection_status`)
    REFERENCES `ngbm_collection` (`id`, `status`)
);

DROP TABLE IF EXISTS `ngbm_rule_data`;
CREATE TABLE `ngbm_rule_data` (
  `rule_id` integer NOT NULL,
  `enabled` integer NOT NULL,
  `priority` integer NOT NULL,
  PRIMARY KEY (`rule_id`)
);

DROP TABLE IF EXISTS `ngbm_rule_target`;
CREATE TABLE `ngbm_rule_target` (
  `id` integer NOT NULL,
  `status` integer NOT NULL,
  `rule_id` integer NOT NULL,
  `type` text(255) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`, `status`),
  FOREIGN KEY (`rule_id`, `status`)
    REFERENCES `ngbm_rule` (`id`, `status`)
);

DROP TABLE IF EXISTS `ngbm_rule_condition`;
CREATE TABLE `ngbm_rule_condition` (
  `id` integer NOT NULL,
  `status` integer NOT NULL,
  `rule_id` integer NOT NULL,
  `type` text(255) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`, `status`),
  FOREIGN KEY (`rule_id`, `status`)
    REFERENCES `ngbm_rule` (`id`, `status`)
);

DROP TABLE IF EXISTS `ngbm_rule`;
CREATE TABLE `ngbm_rule` (
  `id` integer NOT NULL,
  `status` integer NOT NULL,
  `layout_id` integer DEFAULT NULL,
  `comment` text(255) DEFAULT NULL,
  PRIMARY KEY (`id`, `status`)
);
