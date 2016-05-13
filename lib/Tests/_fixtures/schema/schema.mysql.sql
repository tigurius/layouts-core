DROP TABLE IF EXISTS `ngbm_block_collection`;
DROP TABLE IF EXISTS `ngbm_collection_item`;
DROP TABLE IF EXISTS `ngbm_collection_query`;
DROP TABLE IF EXISTS `ngbm_collection`;
DROP TABLE IF EXISTS `ngbm_block`;
DROP TABLE IF EXISTS `ngbm_zone`;
DROP TABLE IF EXISTS `ngbm_layout`;
DROP TABLE IF EXISTS `ngbm_rule_value`;
DROP TABLE IF EXISTS `ngbm_rule_condition`;
DROP TABLE IF EXISTS `ngbm_rule`;

CREATE TABLE `ngbm_layout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  PRIMARY KEY (`id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ngbm_zone` (
  `identifier` varchar(255) NOT NULL,
  `layout_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`identifier`, `layout_id`, `status`),
  FOREIGN KEY (`layout_id`, `status`)
    REFERENCES ngbm_layout (`id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ngbm_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL,
  `zone_identifier` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `definition_identifier` varchar(255) NOT NULL,
  `view_type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parameters` text NOT NULL,
  PRIMARY KEY (`id`, `status`),
  FOREIGN KEY (`layout_id`, `status`)
    REFERENCES ngbm_layout (`id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ngbm_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ngbm_collection_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `value_id` varchar(255) NOT NULL,
  `value_type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`, `status`),
  FOREIGN KEY (`collection_id`, `status`)
    REFERENCES ngbm_collection (`id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ngbm_collection_query` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `parameters` text NOT NULL,
  PRIMARY KEY (`id`, `status`),
  FOREIGN KEY (`collection_id`, `status`)
    REFERENCES ngbm_collection (`id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ngbm_block_collection` (
  `block_id` int(11) NOT NULL,
  `block_status` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL,
  `collection_status` int(11) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `start` int(11) NOT NULL,
  `length` int(11),
  PRIMARY KEY (`block_id`, `block_status`, `collection_id`, `collection_status`),
  FOREIGN KEY (`block_id`, `block_status`)
    REFERENCES ngbm_block (`id`, `status`),
  FOREIGN KEY (`collection_id`, `collection_status`)
    REFERENCES ngbm_collection (`id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ngbm_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `layout_id` int(11) NOT NULL,
  `target_identifier` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ngbm_rule_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rule_id` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`rule_id`)
    REFERENCES `ngbm_rule` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ngbm_rule_condition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rule_id` int(11) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `parameters` text NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`rule_id`)
    REFERENCES `ngbm_rule` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
