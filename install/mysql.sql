
CREATE TABLE `accessory` (
  `acc_id` int(10) NOT NULL AUTO_INCREMENT,
  `acc_type` int(10) NOT NULL DEFAULT '0',
  `acc_class` int(10) NOT NULL DEFAULT '0',
  `acc_accessory` char(60) NOT NULL DEFAULT '',
  `acc_name` char(60) NOT NULL DEFAULT '',
  `acc_mount` char(15) NOT NULL DEFAULT '',
  `acc_essence` float(10,2) NOT NULL DEFAULT '0.00',
  `acc_rating` int(10) NOT NULL DEFAULT '0',
  `acc_capacity` int(10) NOT NULL DEFAULT '0',
  `acc_avail` int(10) NOT NULL DEFAULT '0',
  `acc_perm` char(5) NOT NULL DEFAULT '',
  `acc_cost` int(10) NOT NULL DEFAULT '0',
  `acc_book` char(5) NOT NULL DEFAULT '',
  `acc_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`acc_id`)
);  

CREATE TABLE `active` (
  `act_id` int(10) NOT NULL AUTO_INCREMENT,
  `act_type` char(40) NOT NULL DEFAULT '0',
  `act_name` char(50) NOT NULL DEFAULT '',
  `act_group` char(50) NOT NULL DEFAULT '',
  `act_attribute` char(4) NOT NULL DEFAULT '',
  `act_default` int(10) NOT NULL DEFAULT '0',
  `act_book` char(10) NOT NULL DEFAULT '',
  `act_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`act_id`)
);  

CREATE TABLE `adept` (
  `adp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `adp_name` char(60) NOT NULL DEFAULT '',
  `adp_desc` char(100) NOT NULL DEFAULT '',
  `adp_power` float(10,2) unsigned NOT NULL DEFAULT '0.00',
  `adp_level` int(10) NOT NULL DEFAULT '0',
  `adp_book` char(10) NOT NULL DEFAULT '',
  `adp_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`adp_id`)
);  

CREATE TABLE `advance` (
  `adv_id` int(10) NOT NULL AUTO_INCREMENT,
  `adv_runner` int(10) NOT NULL DEFAULT '0',
  `adv_advance` char(60) NOT NULL DEFAULT '',
  `adv_karma` int(10) NOT NULL DEFAULT '0',
  `adv_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`adv_id`)
);

CREATE TABLE `agents` (
  `agt_id` int(10) NOT NULL AUTO_INCREMENT,
  `agt_name` char(30) NOT NULL DEFAULT '',
  `agt_rating` int(10) NOT NULL DEFAULT '0',
  `agt_cost` int(10) NOT NULL DEFAULT '0',
  `agt_avail` int(10) NOT NULL DEFAULT '0',
  `agt_perm` char(5) NOT NULL DEFAULT '',
  `agt_book` int(10) NOT NULL DEFAULT '0',
  `agt_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`agt_id`)
);  

CREATE TABLE `ammo` (
  `ammo_id` int(10) NOT NULL AUTO_INCREMENT,
  `ammo_class` int(10) NOT NULL DEFAULT '0',
  `ammo_name` char(50) NOT NULL DEFAULT '',
  `ammo_rounds` int(10) NOT NULL DEFAULT '0',
  `ammo_rating` int(10) NOT NULL DEFAULT '0',
  `ammo_mod` char(20) NOT NULL DEFAULT '',
  `ammo_ap` int(10) NOT NULL DEFAULT '0',
  `ammo_blast` char(15) NOT NULL DEFAULT '',
  `ammo_armor` char(5) NOT NULL DEFAULT '',
  `ammo_avail` int(10) NOT NULL DEFAULT '0',
  `ammo_perm` char(5) NOT NULL DEFAULT '',
  `ammo_cost` int(10) NOT NULL DEFAULT '0',
  `ammo_book` char(10) NOT NULL DEFAULT '',
  `ammo_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ammo_id`)
);  

CREATE TABLE `armor` (
  `arm_id` int(10) NOT NULL AUTO_INCREMENT,
  `arm_class` int(10) NOT NULL DEFAULT '0',
  `arm_name` char(100) NOT NULL DEFAULT '',
  `arm_rating` int(10) NOT NULL DEFAULT '0',
  `arm_capacity` int(10) NOT NULL DEFAULT '0',
  `arm_avail` int(10) NOT NULL DEFAULT '0',
  `arm_perm` char(5) NOT NULL DEFAULT '',
  `arm_cost` int(10) NOT NULL DEFAULT '0',
  `arm_book` char(5) NOT NULL DEFAULT '',
  `arm_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`arm_id`)
);  

CREATE TABLE `attributes` (
  `att_id` int(10) NOT NULL AUTO_INCREMENT,
  `att_name` char(20) NOT NULL DEFAULT '',
  `att_acronym` char(10) NOT NULL DEFAULT '',
  `att_column` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`att_id`)
);  

CREATE TABLE `bioware` (
  `bio_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bio_class` int(10) NOT NULL DEFAULT '0',
  `bio_name` char(50) NOT NULL DEFAULT '',
  `bio_rating` int(10) NOT NULL DEFAULT '0',
  `bio_essence` float(10,2) NOT NULL DEFAULT '0.00',
  `bio_avail` int(10) unsigned NOT NULL DEFAULT '0',
  `bio_perm` char(2) NOT NULL DEFAULT '',
  `bio_cost` int(10) unsigned NOT NULL DEFAULT '0',
  `bio_book` char(10) NOT NULL DEFAULT '',
  `bio_page` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`bio_id`)
);  

CREATE TABLE `bugs_detail` (
  `bug_id` int(10) NOT NULL AUTO_INCREMENT,
  `bug_bug_id` int(10) NOT NULL DEFAULT '0',
  `bug_text` text NOT NULL,
  `bug_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bug_user` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bug_id`)
);  

CREATE TABLE `bugs` (
  `bug_id` int(10) NOT NULL AUTO_INCREMENT,
  `bug_module` int(10) NOT NULL DEFAULT '0',
  `bug_severity` int(10) NOT NULL DEFAULT '0',
  `bug_priority` int(10) NOT NULL DEFAULT '0',
  `bug_discovered` date NOT NULL DEFAULT '0000-00-00',
  `bug_closed` date NOT NULL DEFAULT '0000-00-00',
  `bug_subject` char(255) NOT NULL DEFAULT '',
  `bug_openby` int(10) NOT NULL DEFAULT '0',
  `bug_closeby` int(10) NOT NULL DEFAULT '0',
  `bug_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`bug_id`)
);  

CREATE TABLE `cande` (
  `ce_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ce_name` int(10) unsigned NOT NULL DEFAULT '0',
  `ce_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ce_text` text,
  PRIMARY KEY (`ce_id`)
);  

CREATE TABLE `cities` (
  `ct_id` int(10) NOT NULL AUTO_INCREMENT,
  `ct_city` char(255) NOT NULL DEFAULT '',
  `ct_state` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ct_id`)
);  

CREATE TABLE `class` (
  `class_id` int(10) NOT NULL AUTO_INCREMENT,
  `class_subjectid` int(10) NOT NULL DEFAULT '0',
  `class_name` char(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`class_id`)
);  

CREATE TABLE `command` (
  `cmd_id` int(10) NOT NULL AUTO_INCREMENT,
  `cmd_brand` char(30) NOT NULL DEFAULT '',
  `cmd_model` char(30) NOT NULL DEFAULT '',
  `cmd_rating` int(10) NOT NULL DEFAULT '0',
  `cmd_data` int(10) NOT NULL DEFAULT '0',
  `cmd_firewall` int(10) NOT NULL DEFAULT '0',
  `cmd_programs` int(10) NOT NULL DEFAULT '0',
  `cmd_access` char(15) NOT NULL DEFAULT '',
  `cmd_avail` int(10) NOT NULL DEFAULT '0',
  `cmd_perm` char(2) NOT NULL DEFAULT '',
  `cmd_cost` int(10) NOT NULL DEFAULT '0',
  `cmd_book` int(10) NOT NULL DEFAULT '0',
  `cmd_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cmd_id`)
);  

CREATE TABLE `commlink` (
  `link_id` int(10) NOT NULL AUTO_INCREMENT,
  `link_brand` char(30) NOT NULL DEFAULT '',
  `link_model` char(30) NOT NULL DEFAULT '',
  `link_rating` int(10) NOT NULL DEFAULT '0',
  `link_data` int(10) NOT NULL DEFAULT '0',
  `link_firewall` int(10) NOT NULL DEFAULT '0',
  `link_access` char(15) NOT NULL DEFAULT '',
  `link_avail` int(10) NOT NULL DEFAULT '0',
  `link_perm` char(5) NOT NULL DEFAULT '',
  `link_cost` int(10) NOT NULL DEFAULT '0',
  `link_book` char(10) NOT NULL DEFAULT '',
  `link_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`link_id`)
);  

CREATE TABLE `complexform` (
  `form_id` int(10) NOT NULL AUTO_INCREMENT,
  `form_name` char(40) NOT NULL DEFAULT '',
  `form_target` char(10) NOT NULL DEFAULT '',
  `form_duration` char(10) NOT NULL DEFAULT '',
  `form_fading` int(10) NOT NULL DEFAULT '0',
  `form_book` int(10) NOT NULL DEFAULT '0',
  `form_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`form_id`)
);  

CREATE TABLE `contact_notes` (
  `note_id` int(10) NOT NULL AUTO_INCREMENT,
  `note_notes` text NOT NULL,
  `note_contact` int(10) NOT NULL DEFAULT '0',
  `note_user` int(10) NOT NULL DEFAULT '0',
  `note_approved` int(10) NOT NULL DEFAULT '0',
  `note_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`note_id`)
);

CREATE TABLE `contact` (
  `con_id` int(10) NOT NULL AUTO_INCREMENT,
  `con_name` char(60) NOT NULL DEFAULT '',
  `con_location` char(255) NOT NULL DEFAULT '',
  `con_archetype` char(60) NOT NULL DEFAULT '',
  `con_character` int(10) NOT NULL DEFAULT '0',
  `con_book` char(10) NOT NULL DEFAULT '',
  `con_page` int(10) NOT NULL DEFAULT '0',
  `con_owner` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`con_id`)
);  

CREATE TABLE `country` (
  `cn_id` int(10) NOT NULL AUTO_INCREMENT,
  `cn_acronym` char(10) NOT NULL DEFAULT '',
  `cn_country` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`cn_id`)
);  

CREATE TABLE `cyberdeck` (
  `deck_id` int(10) NOT NULL AUTO_INCREMENT,
  `deck_brand` char(30) NOT NULL DEFAULT '',
  `deck_model` char(30) NOT NULL DEFAULT '',
  `deck_rating` int(10) NOT NULL DEFAULT '0',
  `deck_attack` int(10) NOT NULL DEFAULT '0',
  `deck_sleaze` int(10) NOT NULL DEFAULT '0',
  `deck_data` int(10) NOT NULL DEFAULT '0',
  `deck_firewall` int(10) NOT NULL DEFAULT '0',
  `deck_programs` int(10) NOT NULL DEFAULT '0',
  `deck_access` char(15) NOT NULL DEFAULT '',
  `deck_avail` int(10) NOT NULL DEFAULT '0',
  `deck_perm` char(2) NOT NULL DEFAULT '',
  `deck_cost` int(10) NOT NULL DEFAULT '0',
  `deck_book` char(10) NOT NULL DEFAULT '',
  `deck_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`deck_id`)
);  

CREATE TABLE `cyberware` (
  `ware_id` int(10) NOT NULL AUTO_INCREMENT,
  `ware_class` int(10) NOT NULL DEFAULT '0',
  `ware_name` char(40) NOT NULL DEFAULT '',
  `ware_rating` int(10) NOT NULL DEFAULT '0',
  `ware_multiply` int(10) NOT NULL DEFAULT '0',
  `ware_essence` float(4,2) NOT NULL DEFAULT '0.00',
  `ware_capacity` int(10) NOT NULL DEFAULT '0',
  `ware_avail` int(10) NOT NULL DEFAULT '0',
  `ware_perm` char(2) NOT NULL DEFAULT '',
  `ware_cost` int(10) NOT NULL DEFAULT '0',
  `ware_book` char(10) NOT NULL DEFAULT '',
  `ware_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ware_id`)
);  

CREATE TABLE `features_detail` (
  `feat_id` int(10) NOT NULL AUTO_INCREMENT,
  `feat_feat_id` int(10) NOT NULL DEFAULT '0',
  `feat_text` text NOT NULL,
  `feat_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `feat_user` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`feat_id`)
);  

CREATE TABLE `features` (
  `feat_id` int(10) NOT NULL AUTO_INCREMENT,
  `feat_module` int(10) NOT NULL DEFAULT '0',
  `feat_severity` int(10) NOT NULL DEFAULT '0',
  `feat_priority` int(10) NOT NULL DEFAULT '0',
  `feat_discovered` date NOT NULL DEFAULT '0000-00-00',
  `feat_closed` date NOT NULL DEFAULT '0000-00-00',
  `feat_subject` char(255) NOT NULL DEFAULT '',
  `feat_openby` int(10) NOT NULL DEFAULT '0',
  `feat_closeby` int(10) NOT NULL DEFAULT '0',
  `feat_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`feat_id`)
);  

CREATE TABLE `finance` (
  `fin_id` int(10) NOT NULL AUTO_INCREMENT,
  `fin_character` int(10) NOT NULL DEFAULT '0',
  `fin_funds` int(10) NOT NULL DEFAULT '0',
  `fin_date` date NOT NULL DEFAULT '0000-00-00',
  `fin_notes` text NOT NULL,
  PRIMARY KEY (`fin_id`)
);  

CREATE TABLE `firearms` (
  `fa_id` int(10) NOT NULL AUTO_INCREMENT,
  `fa_class` int(10) NOT NULL DEFAULT '0',
  `fa_name` char(100) NOT NULL DEFAULT '',
  `fa_acc` int(10) NOT NULL DEFAULT '0',
  `fa_damage` int(10) NOT NULL DEFAULT '0',
  `fa_type` char(2) NOT NULL DEFAULT '',
  `fa_flag` char(2) NOT NULL DEFAULT '',
  `fa_ap` int(10) NOT NULL DEFAULT '0',
  `fa_mode1` char(4) NOT NULL DEFAULT '',
  `fa_mode2` char(4) NOT NULL DEFAULT '',
  `fa_mode3` char(4) NOT NULL DEFAULT '',
  `fa_rc` int(10) NOT NULL DEFAULT '0',
  `fa_fullrc` int(10) NOT NULL,
  `fa_ammo1` int(10) NOT NULL DEFAULT '0',
  `fa_clip1` char(2) NOT NULL DEFAULT '',
  `fa_ammo2` int(10) NOT NULL DEFAULT '0',
  `fa_clip2` char(2) NOT NULL DEFAULT '',
  `fa_avail` int(10) NOT NULL DEFAULT '0',
  `fa_perm` char(2) NOT NULL DEFAULT '',
  `fa_cost` int(10) NOT NULL DEFAULT '0',
  `fa_book` char(10) NOT NULL DEFAULT '',
  `fa_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fa_id`)
);  

CREATE TABLE `gear` (
  `gear_id` int(10) NOT NULL AUTO_INCREMENT,
  `gear_class` char(60) NOT NULL DEFAULT '',
  `gear_name` char(100) NOT NULL DEFAULT '',
  `gear_rating` int(10) NOT NULL DEFAULT '0',
  `gear_capacity` int(10) NOT NULL DEFAULT '0',
  `gear_avail` int(10) NOT NULL DEFAULT '0',
  `gear_perm` char(5) NOT NULL DEFAULT '',
  `gear_cost` int(10) NOT NULL DEFAULT '0',
  `gear_book` int(10) NOT NULL DEFAULT '0',
  `gear_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gear_id`)
);  

CREATE TABLE `grades` (
  `grade_id` int(10) NOT NULL AUTO_INCREMENT,
  `grade_name` char(60) NOT NULL DEFAULT '',
  `grade_essence` float(10,2) NOT NULL DEFAULT '0.00',
  `grade_avail` int(10) NOT NULL DEFAULT '0',
  `grade_cost` float(10,2) NOT NULL DEFAULT '0.00',
  `grade_book` int(10) NOT NULL DEFAULT '0',
  `grade_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`grade_id`)
);  

CREATE TABLE `group_books` (
  `book_id` int(10) NOT NULL AUTO_INCREMENT,
  `book_group` int(10) NOT NULL DEFAULT '0',
  `book_bookid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`book_id`)
);

CREATE TABLE `group_notes` (
  `note_id` int(10) NOT NULL AUTO_INCREMENT,
  `note_notes` text NOT NULL,
  `note_group` int(10) NOT NULL DEFAULT '0',
  `note_user` int(10) NOT NULL DEFAULT '0',
  `note_approved` int(10) NOT NULL DEFAULT '0',
  `note_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`note_id`)
);

CREATE TABLE `groups` (
  `grp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `grp_disabled` int(1) NOT NULL DEFAULT '0',
  `grp_name` char(60) NOT NULL DEFAULT '',
  `grp_email` char(255) NOT NULL DEFAULT '',
  `grp_owner` int(10) NOT NULL DEFAULT '0',
  `grp_visible` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`grp_id`)
);  

CREATE TABLE `help` (
  `help_id` int(10) NOT NULL AUTO_INCREMENT,
  `help_user` int(10) NOT NULL DEFAULT '0',
  `help_screen` char(100) NOT NULL DEFAULT '',
  `help_seen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`help_id`)
);  

CREATE TABLE `history` (
  `his_id` int(10) NOT NULL AUTO_INCREMENT,
  `his_character` int(10) NOT NULL DEFAULT '0',
  `his_date` date NOT NULL DEFAULT '0000-00-00',
  `his_notes` text NOT NULL,
  PRIMARY KEY (`his_id`)
);  

CREATE TABLE `karma` (
  `kar_id` int(10) NOT NULL AUTO_INCREMENT,
  `kar_character` int(10) NOT NULL DEFAULT '0',
  `kar_karma` int(10) NOT NULL DEFAULT '0',
  `kar_date` date NOT NULL DEFAULT '0000-00-00',
  `kar_notes` text NOT NULL,
  PRIMARY KEY (`kar_id`)
);  

CREATE TABLE `knowledge` (
  `know_id` int(10) NOT NULL AUTO_INCREMENT,
  `know_name` char(50) NOT NULL DEFAULT '',
  `know_attribute` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`know_id`)
);  

CREATE TABLE `language` (
  `lang_id` int(10) NOT NULL AUTO_INCREMENT,
  `lang_name` char(50) NOT NULL DEFAULT '',
  `lang_attribute` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lang_id`)
);  

CREATE TABLE `levels` (
  `lvl_id` int(8) NOT NULL AUTO_INCREMENT,
  `lvl_name` varchar(255) NOT NULL,
  `lvl_level` int(1) NOT NULL,
  `lvl_disabled` tinyint(1) NOT NULL DEFAULT '0',
  `lvl_changedby` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lvl_id`)
);  

CREATE TABLE `lifestyle` (
  `life_id` int(10) NOT NULL AUTO_INCREMENT,
  `life_style` char(60) NOT NULL DEFAULT '',
  `life_cost` int(10) NOT NULL DEFAULT '0',
  `life_comforts` int(10) NOT NULL DEFAULT '0',
  `life_security` int(10) NOT NULL DEFAULT '0',
  `life_neighborhood` int(10) NOT NULL DEFAULT '0',
  `life_entertainment` int(10) NOT NULL DEFAULT '0',
  `life_book` char(10) NOT NULL DEFAULT '',
  `life_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`life_id`)
);  

CREATE TABLE `log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_user` char(30) NOT NULL DEFAULT '',
  `log_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `log_source` char(60) NOT NULL DEFAULT '',
  `log_detail` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`)
);  

CREATE TABLE `melee` (
  `melee_id` int(10) NOT NULL AUTO_INCREMENT,
  `melee_class` char(30) NOT NULL DEFAULT '',
  `melee_name` char(50) NOT NULL DEFAULT '',
  `melee_acc` int(10) NOT NULL DEFAULT '0',
  `melee_reach` int(10) NOT NULL DEFAULT '0',
  `melee_damage` int(10) NOT NULL DEFAULT '0',
  `melee_type` char(2) NOT NULL DEFAULT '',
  `melee_flag` char(2) DEFAULT NULL,
  `melee_strength` int(10) NOT NULL DEFAULT '0',
  `melee_ap` int(10) NOT NULL DEFAULT '0',
  `melee_avail` int(10) NOT NULL DEFAULT '0',
  `melee_perm` char(2) NOT NULL DEFAULT '',
  `melee_cost` int(10) NOT NULL DEFAULT '0',
  `melee_book` char(10) NOT NULL DEFAULT '',
  `melee_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`melee_id`)
);  

CREATE TABLE `members` (
  `mem_id` int(10) NOT NULL AUTO_INCREMENT,
  `mem_owner` int(10) NOT NULL DEFAULT '0',
  `mem_runner` int(10) NOT NULL DEFAULT '0',
  `mem_group` int(10) NOT NULL DEFAULT '0',
  `mem_invite` int(10) NOT NULL DEFAULT '0',
  `mem_active` date NOT NULL DEFAULT '0000-00-00',
  `mem_visible` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`mem_id`)
);  

CREATE TABLE `mentor` (
  `mentor_id` int(10) NOT NULL AUTO_INCREMENT,
  `mentor_name` char(30) NOT NULL DEFAULT '',
  `mentor_all` char(100) NOT NULL DEFAULT '',
  `mentor_mage` char(100) NOT NULL DEFAULT '',
  `mentor_adept` char(100) NOT NULL DEFAULT '',
  `mentor_disadvantage` char(100) NOT NULL DEFAULT '',
  `mentor_book` char(10) NOT NULL DEFAULT '',
  `mentor_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`mentor_id`)
);  

CREATE TABLE `metatypes` (
  `meta_id` int(10) NOT NULL AUTO_INCREMENT,
  `meta_name` char(60) NOT NULL DEFAULT '',
  `meta_walk` int(10) NOT NULL DEFAULT '0',
  `meta_run` int(10) NOT NULL DEFAULT '0',
  `meta_swim` int(10) NOT NULL DEFAULT '0',
  `meta_notes` char(100) NOT NULL DEFAULT '',
  `meta_book` char(10) NOT NULL DEFAULT '',
  `meta_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`meta_id`)
);  

CREATE TABLE `modules` (
  `mod_id` int(10) NOT NULL AUTO_INCREMENT,
  `mod_name` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`mod_id`)
);  

CREATE TABLE `notoriety` (
  `not_id` int(10) NOT NULL AUTO_INCREMENT,
  `not_character` int(10) NOT NULL DEFAULT '0',
  `not_notoriety` int(10) NOT NULL DEFAULT '0',
  `not_date` date NOT NULL DEFAULT '0000-00-00',
  `not_notes` text NOT NULL,
  PRIMARY KEY (`not_id`)
);  

CREATE TABLE `powers` (
  `pow_id` int(10) NOT NULL AUTO_INCREMENT,
  `pow_name` char(60) NOT NULL DEFAULT '',
  `pow_type` char(10) NOT NULL DEFAULT '',
  `pow_range` char(10) NOT NULL DEFAULT '',
  `pow_action` char(10) NOT NULL DEFAULT '',
  `pow_duration` char(20) NOT NULL DEFAULT '',
  `pow_description` char(60) NOT NULL DEFAULT '',
  `pow_book` int(10) NOT NULL DEFAULT '0',
  `pow_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pow_id`)
);  

CREATE TABLE `present` (
  `pre_id` int(10) NOT NULL AUTO_INCREMENT,
  `pre_character_number` int(10) NOT NULL DEFAULT '0',
  `pre_name` char(60) NOT NULL DEFAULT '',
  `pre_metatype` int(10) NOT NULL DEFAULT '0',
  `pre_age` int(10) NOT NULL DEFAULT '0',
  `pre_sex` int(10) NOT NULL DEFAULT '0',
  `pre_height` int(10) NOT NULL DEFAULT '0',
  `pre_weight` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pre_id`)
);

CREATE TABLE `program` (
  `pgm_id` int(10) NOT NULL AUTO_INCREMENT,
  `pgm_name` char(50) NOT NULL DEFAULT '',
  `pgm_type` char(2) NOT NULL DEFAULT '',
  `pgm_desc` char(100) NOT NULL DEFAULT '',
  `pgm_avail` int(10) NOT NULL DEFAULT '0',
  `pgm_perm` char(2) NOT NULL DEFAULT '',
  `pgm_cost` int(10) NOT NULL DEFAULT '0',
  `pgm_book` char(10) NOT NULL DEFAULT '',
  `pgm_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pgm_id`)
);  

CREATE TABLE `projectile` (
  `proj_id` int(10) NOT NULL AUTO_INCREMENT,
  `proj_class` int(10) NOT NULL DEFAULT '0',
  `proj_name` char(60) NOT NULL DEFAULT '',
  `proj_rating` int(10) NOT NULL DEFAULT '0',
  `proj_acc` int(10) NOT NULL DEFAULT '0',
  `proj_damage` int(10) NOT NULL DEFAULT '0',
  `proj_type` char(2) NOT NULL DEFAULT '',
  `proj_strength` int(10) NOT NULL,
  `proj_ap` int(10) NOT NULL DEFAULT '0',
  `proj_avail` int(10) NOT NULL DEFAULT '0',
  `proj_perm` char(2) NOT NULL DEFAULT '',
  `proj_cost` int(10) NOT NULL DEFAULT '0',
  `proj_book` int(10) NOT NULL DEFAULT '0',
  `proj_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`proj_id`)
);  

CREATE TABLE `publicity` (
  `pub_id` int(10) NOT NULL AUTO_INCREMENT,
  `pub_character` int(10) NOT NULL DEFAULT '0',
  `pub_publicity` int(10) NOT NULL DEFAULT '0',
  `pub_date` date NOT NULL DEFAULT '0000-00-00',
  `pub_notes` text NOT NULL,
  PRIMARY KEY (`pub_id`)
);

CREATE TABLE `qualities` (
  `qual_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qual_name` char(50) NOT NULL DEFAULT '',
  `qual_value` int(10) NOT NULL DEFAULT '0',
  `qual_desc` char(160) NOT NULL DEFAULT '',
  `qual_book` char(10) NOT NULL DEFAULT '',
  `qual_page` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`qual_id`)
);  

CREATE TABLE `r_accessory` (
  `r_acc_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_acc_character` int(10) NOT NULL DEFAULT '0',
  `r_acc_number` int(10) NOT NULL DEFAULT '0',
  `r_acc_amount` int(10) NOT NULL DEFAULT '1',
  `r_acc_parentid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_acc_id`)
);  

CREATE TABLE `r_active` (
  `r_act_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_act_character` int(10) NOT NULL DEFAULT '0',
  `r_act_number` int(10) NOT NULL DEFAULT '0',
  `r_act_rank` int(10) NOT NULL DEFAULT '0',
  `r_act_group` int(10) NOT NULL DEFAULT '0',
  `r_act_specialize` char(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`r_act_id`)
);  

CREATE TABLE `r_adept` (
  `r_adp_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_adp_character` int(10) NOT NULL DEFAULT '0',
  `r_adp_number` int(10) NOT NULL DEFAULT '0',
  `r_adp_level` int(10) NOT NULL DEFAULT '0',
  `r_adp_specialize` char(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`r_adp_id`)
);  

CREATE TABLE `r_agents` (
  `r_agt_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_agt_character` int(10) NOT NULL DEFAULT '0',
  `r_agt_cyberdeck` int(10) NOT NULL DEFAULT '0',
  `r_agt_number` int(10) NOT NULL DEFAULT '0',
  `r_agt_active` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_agt_id`)
);

CREATE TABLE `r_alchemy` (
  `r_alc_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_alc_character` int(10) NOT NULL DEFAULT '0',
  `r_alc_number` int(10) NOT NULL DEFAULT '0',
  `r_alc_special` char(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`r_alc_id`)
);  

CREATE TABLE `r_ammo` (
  `r_ammo_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_ammo_character` int(10) NOT NULL DEFAULT '0',
  `r_ammo_number` int(10) NOT NULL DEFAULT '0',
  `r_ammo_rounds` int(10) NOT NULL DEFAULT '0',
  `r_ammo_parentid` int(10) NOT NULL DEFAULT '0',
  `r_ammo_parentveh` int(10) NOT NULL DEFAULT '0',
  `r_ammo_parentware` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_ammo_id`)
);  

CREATE TABLE `r_armor` (
  `r_arm_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_arm_character` int(10) NOT NULL DEFAULT '0',
  `r_arm_number` int(10) NOT NULL DEFAULT '0',
  `r_arm_details` char(60) NOT NULL DEFAULT '',
  `r_arm_active` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_arm_id`)
);  

CREATE TABLE `r_bioware` (
  `r_bio_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_bio_character` int(10) NOT NULL DEFAULT '0',
  `r_bio_number` int(10) NOT NULL DEFAULT '0',
  `r_bio_grade` int(10) NOT NULL DEFAULT '0',
  `r_bio_specialize` char(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`r_bio_id`)
);  

CREATE TABLE `r_command` (
  `r_cmd_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_cmd_character` int(10) NOT NULL DEFAULT '0',
  `r_cmd_number` int(10) NOT NULL DEFAULT '0',
  `r_cmd_noise` int(10) NOT NULL DEFAULT '0',
  `r_cmd_sharing` int(10) NOT NULL DEFAULT '0',
  `r_cmd_active` int(10) NOT NULL DEFAULT '0',
  `r_cmd_access` char(35) NOT NULL DEFAULT '',
  `r_cmd_conmon` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_cmd_id`)
);  

CREATE TABLE `r_commlink` (
  `r_link_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_link_character` int(10) NOT NULL DEFAULT '0',
  `r_link_number` int(10) NOT NULL DEFAULT '0',
  `r_link_active` int(10) NOT NULL DEFAULT '0',
  `r_link_access` char(35) NOT NULL DEFAULT '',
  `r_link_conmon` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_link_id`)
);  

CREATE TABLE `r_complexform` (
  `r_form_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_form_character` int(10) NOT NULL DEFAULT '0',
  `r_form_number` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_form_id`)
);  

CREATE TABLE `r_contact` (
  `r_con_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_con_character` int(10) NOT NULL DEFAULT '0',
  `r_con_number` int(10) NOT NULL DEFAULT '0',
  `r_con_connection` int(10) NOT NULL DEFAULT '0',
  `r_con_loyalty` int(10) NOT NULL DEFAULT '0',
  `r_con_faction` int(10) NOT NULL DEFAULT '0',
  `r_con_notes` text NOT NULL,
  PRIMARY KEY (`r_con_id`)
);  

CREATE TABLE `r_cyberdeck` (
  `r_deck_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_deck_character` int(10) NOT NULL DEFAULT '0',
  `r_deck_number` int(10) NOT NULL DEFAULT '0',
  `r_deck_attack` int(10) NOT NULL DEFAULT '0',
  `r_deck_sleaze` int(10) NOT NULL DEFAULT '0',
  `r_deck_data` int(10) NOT NULL DEFAULT '0',
  `r_deck_firewall` int(10) NOT NULL DEFAULT '0',
  `r_deck_active` int(10) NOT NULL DEFAULT '0',
  `r_deck_access` char(35) NOT NULL DEFAULT '',
  `r_deck_conmon` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_deck_id`)
);  

CREATE TABLE `r_cyberware` (
  `r_ware_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_ware_character` int(10) NOT NULL DEFAULT '0',
  `r_ware_number` int(10) NOT NULL DEFAULT '0',
  `r_ware_grade` int(10) NOT NULL DEFAULT '1',
  `r_ware_specialize` char(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`r_ware_id`)
);  

CREATE TABLE `r_firearms` (
  `r_fa_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_fa_faid` int(10) NOT NULL DEFAULT '0',
  `r_fa_character` int(10) NOT NULL DEFAULT '0',
  `r_fa_number` int(10) NOT NULL DEFAULT '0',
  `r_fa_parentid` int(10) NOT NULL DEFAULT '0',
  `r_fa_active` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_fa_id`)
);  

CREATE TABLE `r_gear` (
  `r_gear_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_gear_character` int(10) NOT NULL DEFAULT '0',
  `r_gear_number` int(10) NOT NULL DEFAULT '0',
  `r_gear_details` char(60) NOT NULL DEFAULT '',
  `r_gear_active` int(10) NOT NULL DEFAULT '0',
  `r_gear_amount` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`r_gear_id`)
);  

CREATE TABLE `r_identity` (
  `id_id` int(10) NOT NULL AUTO_INCREMENT,
  `id_character` int(10) NOT NULL DEFAULT '0',
  `id_name` char(80) NOT NULL DEFAULT '0',
  `id_rating` int(10) NOT NULL DEFAULT '0',
  `id_background` text NOT NULL,
  `id_type` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_id`)
);  

CREATE TABLE `r_knowledge` (
  `r_know_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_know_character` int(10) NOT NULL DEFAULT '0',
  `r_know_number` int(10) NOT NULL DEFAULT '0',
  `r_know_rank` int(10) NOT NULL DEFAULT '0',
  `r_know_specialize` char(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`r_know_id`)
);  

CREATE TABLE `r_language` (
  `r_lang_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_lang_character` int(10) NOT NULL DEFAULT '0',
  `r_lang_number` int(10) NOT NULL DEFAULT '0',
  `r_lang_rank` int(10) NOT NULL DEFAULT '0',
  `r_lang_specialize` char(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`r_lang_id`)
);  

CREATE TABLE `r_license` (
  `lic_id` int(10) NOT NULL AUTO_INCREMENT,
  `lic_character` int(10) NOT NULL DEFAULT '0',
  `lic_type` char(80) NOT NULL DEFAULT '',
  `lic_rating` int(10) NOT NULL DEFAULT '0',
  `lic_identity` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lic_id`)
);  

CREATE TABLE `r_lifestyle` (
  `r_life_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_life_character` int(10) NOT NULL DEFAULT '0',
  `r_life_number` int(10) NOT NULL DEFAULT '0',
  `r_life_desc` char(100) NOT NULL DEFAULT '',
  `r_life_months` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_life_id`)
);  

CREATE TABLE `r_melee` (
  `r_melee_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_melee_character` int(10) NOT NULL DEFAULT '0',
  `r_melee_number` int(10) NOT NULL DEFAULT '0',
  `r_melee_active` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_melee_id`)
);  

CREATE TABLE `r_mentor` (
  `r_mentor_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_mentor_character` int(10) NOT NULL DEFAULT '0',
  `r_mentor_number` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_mentor_id`)
);  

CREATE TABLE `r_program` (
  `r_pgm_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_pgm_character` int(10) NOT NULL DEFAULT '0',
  `r_pgm_cyberdeck` int(10) NOT NULL DEFAULT '0',
  `r_pgm_command` int(10) NOT NULL DEFAULT '0',
  `r_pgm_number` int(10) NOT NULL DEFAULT '0',
  `r_pgm_active` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_pgm_id`)
);  

CREATE TABLE `r_projectile` (
  `r_proj_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_proj_character` int(10) NOT NULL DEFAULT '0',
  `r_proj_number` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_proj_id`)
);  

CREATE TABLE `r_qualities` (
  `r_qual_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_qual_character` int(10) NOT NULL DEFAULT '0',
  `r_qual_number` int(10) NOT NULL DEFAULT '0',
  `r_qual_details` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`r_qual_id`)
);  

CREATE TABLE `r_spells` (
  `r_spell_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_spell_character` int(10) NOT NULL DEFAULT '0',
  `r_spell_number` int(10) NOT NULL DEFAULT '0',
  `r_spell_special` char(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`r_spell_id`)
);  

CREATE TABLE `r_spirit` (
  `r_spirit_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_spirit_character` int(10) NOT NULL DEFAULT '0',
  `r_spirit_number` int(10) NOT NULL DEFAULT '0',
  `r_spirit_force` int(10) NOT NULL DEFAULT '0',
  `r_spirit_services` int(10) NOT NULL DEFAULT '0',
  `r_spirit_bound` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_spirit_id`)
);  

CREATE TABLE `r_sprite` (
  `r_sprite_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_sprite_character` int(10) NOT NULL DEFAULT '0',
  `r_sprite_number` int(10) NOT NULL DEFAULT '0',
  `r_sprite_level` int(10) NOT NULL DEFAULT '0',
  `r_sprite_tasks` int(10) NOT NULL DEFAULT '0',
  `r_sprite_registered` int(10) NOT NULL DEFAULT '0',
  `r_sprite_conmon` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_sprite_id`)
);  

CREATE TABLE `r_tradition` (
  `r_trad_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_trad_character` int(10) NOT NULL DEFAULT '0',
  `r_trad_number` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_trad_id`)
);  

CREATE TABLE `runners` (
  `runr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `runr_owner` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_aliases` char(60) NOT NULL DEFAULT '',
  `runr_name` char(60) NOT NULL DEFAULT '',
  `runr_archetype` char(60) NOT NULL DEFAULT '',
  `runr_agility` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_body` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_reaction` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_strength` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_charisma` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_intuition` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_logic` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_willpower` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_metatype` int(10) NOT NULL DEFAULT '0',
  `runr_essence` float(4,2) NOT NULL DEFAULT '0.00',
  `runr_totaledge` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_currentedge` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_magic` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_resonance` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_age` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_sex` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_height` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_weight` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_physicalcon` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_stuncon` int(10) unsigned NOT NULL DEFAULT '0',
  `runr_desc` text NOT NULL,
  `runr_sop` text NOT NULL,
  `runr_available` int(10) NOT NULL DEFAULT '0',
  `runr_version` double(10,1) NOT NULL DEFAULT '0.0',
  PRIMARY KEY (`runr_id`)
);  

CREATE TABLE `r_vehicles` (
  `r_veh_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_veh_vehid` int(10) NOT NULL DEFAULT '0',
  `r_veh_character` int(10) NOT NULL DEFAULT '0',
  `r_veh_number` int(10) NOT NULL DEFAULT '0',
  `r_veh_vehiclecon` int(10) NOT NULL DEFAULT '0',
  `r_veh_conmon` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_veh_id`)
);  

CREATE TABLE `s_knowledge` (
  `s_know_id` int(10) NOT NULL AUTO_INCREMENT,
  `s_know_name` char(30) NOT NULL DEFAULT '',
  `s_know_attribute` int(10) NOT NULL DEFAULT '0',
  `s_know_book` char(10) NOT NULL DEFAULT '',
  `s_know_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`s_know_id`)
);  

CREATE TABLE `s_language` (
  `s_lang_id` int(10) NOT NULL AUTO_INCREMENT,
  `s_lang_name` char(30) NOT NULL DEFAULT '',
  `s_lang_attribute` int(10) NOT NULL DEFAULT '0',
  `s_lang_book` char(10) NOT NULL DEFAULT '',
  `s_lang_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`s_lang_id`)
);  

CREATE TABLE `sp_active` (
  `sp_act_id` int(10) NOT NULL AUTO_INCREMENT,
  `sp_act_creature` int(10) NOT NULL DEFAULT '0',
  `sp_act_number` int(10) NOT NULL DEFAULT '0',
  `sp_act_specialize` char(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`sp_act_id`)
);  

CREATE TABLE `spells` (
  `spell_id` int(10) NOT NULL AUTO_INCREMENT,
  `spell_name` char(30) NOT NULL DEFAULT '',
  `spell_group` char(30) NOT NULL DEFAULT '',
  `spell_class` char(60) NOT NULL DEFAULT '',
  `spell_type` char(10) NOT NULL DEFAULT '',
  `spell_test` char(20) NOT NULL DEFAULT '',
  `spell_range` char(10) NOT NULL DEFAULT '',
  `spell_damage` char(10) NOT NULL DEFAULT '',
  `spell_duration` char(20) NOT NULL DEFAULT '',
  `spell_force` int(10) NOT NULL DEFAULT '0',
  `spell_drain` int(10) NOT NULL DEFAULT '0',
  `spell_book` char(10) NOT NULL DEFAULT '',
  `spell_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`spell_id`)
);  

CREATE TABLE `spirits` (
  `spirit_id` int(10) NOT NULL AUTO_INCREMENT,
  `spirit_name` char(60) NOT NULL DEFAULT '',
  `spirit_body` int(10) NOT NULL DEFAULT '0',
  `spirit_agility` int(10) NOT NULL DEFAULT '0',
  `spirit_reaction` int(10) NOT NULL DEFAULT '0',
  `spirit_strength` int(10) NOT NULL DEFAULT '0',
  `spirit_willpower` int(10) NOT NULL DEFAULT '0',
  `spirit_logic` int(10) NOT NULL DEFAULT '0',
  `spirit_intuition` int(10) NOT NULL DEFAULT '0',
  `spirit_charisma` int(10) NOT NULL DEFAULT '0',
  `spirit_edge` int(10) NOT NULL DEFAULT '0',
  `spirit_essence` int(10) NOT NULL DEFAULT '0',
  `spirit_magic` int(10) NOT NULL DEFAULT '0',
  `spirit_description` char(255) NOT NULL DEFAULT '',
  `spirit_book` int(10) NOT NULL DEFAULT '0',
  `spirit_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`spirit_id`)
);  

CREATE TABLE `sp_powers` (
  `sp_power_id` int(10) NOT NULL AUTO_INCREMENT,
  `sp_power_creature` int(10) NOT NULL DEFAULT '0',
  `sp_power_number` int(10) NOT NULL DEFAULT '0',
  `sp_power_optional` int(10) NOT NULL DEFAULT '0',
  `sp_power_specialize` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`sp_power_id`)
);  

CREATE TABLE `sprite_powers` (
  `pow_id` int(10) NOT NULL AUTO_INCREMENT,
  `pow_name` char(60) NOT NULL DEFAULT '',
  `pow_description` char(255) NOT NULL DEFAULT '',
  `pow_book` int(10) NOT NULL DEFAULT '0',
  `pow_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pow_id`)
);  

CREATE TABLE `sprites` (
  `sprite_id` int(10) NOT NULL AUTO_INCREMENT,
  `sprite_name` char(60) NOT NULL DEFAULT '',
  `sprite_attack` int(10) NOT NULL DEFAULT '0',
  `sprite_sleaze` int(10) NOT NULL DEFAULT '0',
  `sprite_data` int(10) NOT NULL DEFAULT '0',
  `sprite_firewall` int(10) NOT NULL DEFAULT '0',
  `sprite_initiative` int(10) NOT NULL DEFAULT '0',
  `sprite_book` int(10) NOT NULL DEFAULT '0',
  `sprite_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sprite_id`)
);  

CREATE TABLE `sp_weaknesses` (
  `sp_weak_id` int(10) NOT NULL AUTO_INCREMENT,
  `sp_weak_creature` int(10) NOT NULL DEFAULT '0',
  `sp_weak_number` int(10) NOT NULL DEFAULT '0',
  `sp_weak_specialize` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`sp_weak_id`)
);  

CREATE TABLE `states` (
  `st_id` int(10) NOT NULL AUTO_INCREMENT,
  `st_acronym` char(10) NOT NULL DEFAULT '',
  `st_state` char(255) NOT NULL DEFAULT '',
  `st_country` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`st_id`)
);  

CREATE TABLE `s_tradition` (
  `s_trad_id` int(10) NOT NULL AUTO_INCREMENT,
  `s_trad_name` char(30) NOT NULL DEFAULT '',
  `s_trad_attribute` int(10) NOT NULL DEFAULT '0',
  `s_trad_book` char(30) NOT NULL DEFAULT '',
  `s_trad_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`s_trad_id`)
);  

CREATE TABLE `street` (
  `st_id` int(10) NOT NULL AUTO_INCREMENT,
  `st_character` int(10) NOT NULL DEFAULT '0',
  `st_cred` int(10) NOT NULL DEFAULT '0',
  `st_date` date NOT NULL DEFAULT '0000-00-00',
  `st_notes` text NOT NULL,
  PRIMARY KEY (`st_id`)
);

CREATE TABLE `subjects` (
  `sub_id` int(10) NOT NULL AUTO_INCREMENT,
  `sub_name` char(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`sub_id`)
);  

CREATE TABLE `tags` (
  `tag_id` int(10) NOT NULL AUTO_INCREMENT,
  `tag_character` int(10) NOT NULL DEFAULT '0',
  `tag_name` char(20) NOT NULL DEFAULT '',
  `tag_view` int(10) NOT NULL DEFAULT '0',
  `tag_owner` int(10) NOT NULL DEFAULT '0',
  `tag_group` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tag_id`)
);  

CREATE TABLE `themes` (
  `theme_id` int(10) NOT NULL AUTO_INCREMENT,
  `theme_name` char(40) NOT NULL DEFAULT '',
  `theme_title` char(40) NOT NULL DEFAULT '',
  `theme_disabled` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`theme_id`)
);  

CREATE TABLE `tradition` (
  `trad_id` int(10) NOT NULL AUTO_INCREMENT,
  `trad_name` char(60) NOT NULL DEFAULT '',
  `trad_description` char(255) NOT NULL DEFAULT '',
  `trad_combat` int(10) NOT NULL DEFAULT '0',
  `trad_detection` int(10) NOT NULL DEFAULT '0',
  `trad_health` int(10) NOT NULL DEFAULT '0',
  `trad_illusion` int(10) NOT NULL DEFAULT '0',
  `trad_manipulation` int(10) NOT NULL DEFAULT '0',
  `trad_drainleft` int(10) NOT NULL DEFAULT '0',
  `trad_drainright` int(10) NOT NULL DEFAULT '0',
  `trad_book` char(10) NOT NULL DEFAULT '',
  `trad_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`trad_id`)
);  

CREATE TABLE `users` (
  `usr_id` int(8) NOT NULL AUTO_INCREMENT,
  `usr_level` int(1) NOT NULL DEFAULT '2',
  `usr_disabled` int(1) NOT NULL DEFAULT '0',
  `usr_name` varchar(11) NOT NULL,
  `usr_first` varchar(255) NOT NULL,
  `usr_last` varchar(255) NOT NULL,
  `usr_email` varchar(255) NOT NULL,
  `usr_passwd` varchar(32) NOT NULL,
  `usr_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usr_maillist` int(1) NOT NULL DEFAULT '0',
  `usr_lastlogin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `usr_ipaddress` char(60) NOT NULL DEFAULT '',
  `usr_theme` int(10) NOT NULL DEFAULT '0',
  `usr_reset` int(10) NOT NULL DEFAULT '0',
  `usr_phone` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`usr_id`)
);  

CREATE TABLE `vehicles` (
  `veh_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `veh_class` int(10) NOT NULL DEFAULT '0',
  `veh_type` char(40) NOT NULL DEFAULT '',
  `veh_make` char(40) NOT NULL DEFAULT '',
  `veh_model` char(40) NOT NULL DEFAULT '',
  `veh_onhand` int(10) NOT NULL DEFAULT '0',
  `veh_offhand` int(10) NOT NULL DEFAULT '0',
  `veh_onspeed` int(10) NOT NULL DEFAULT '0',
  `veh_offspeed` int(10) NOT NULL DEFAULT '0',
  `veh_onacc` int(10) NOT NULL DEFAULT '0',
  `veh_offacc` int(10) NOT NULL DEFAULT '0',
  `veh_pilot` int(10) NOT NULL DEFAULT '0',
  `veh_body` int(10) NOT NULL DEFAULT '0',
  `veh_armor` int(10) NOT NULL DEFAULT '0',
  `veh_sensor` int(10) NOT NULL DEFAULT '0',
  `veh_sig` int(10) NOT NULL DEFAULT '0',
  `veh_hardpoints` int(10) NOT NULL DEFAULT '0',
  `veh_firmpoints` int(10) NOT NULL DEFAULT '0',
  `veh_onseats` int(10) NOT NULL DEFAULT '0',
  `veh_offseats` int(10) NOT NULL DEFAULT '0',
  `veh_avail` int(10) NOT NULL DEFAULT '0',
  `veh_perm` char(10) NOT NULL DEFAULT '',
  `veh_cost` int(10) NOT NULL DEFAULT '0',
  `veh_book` char(10) NOT NULL DEFAULT '',
  `veh_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`veh_id`)
);  

CREATE TABLE `versions` (
  `ver_id` int(10) NOT NULL AUTO_INCREMENT,
  `ver_book` char(60) NOT NULL DEFAULT '',
  `ver_short` char(10) NOT NULL DEFAULT '',
  `ver_version` double(10,1) NOT NULL DEFAULT '0.0',
  `ver_year` int(10) NOT NULL DEFAULT '0',
  `ver_active` int(10) NOT NULL DEFAULT '0',
  `ver_admin` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ver_id`)
);  

CREATE TABLE `weakness` (
  `weak_id` int(10) NOT NULL AUTO_INCREMENT,
  `weak_name` char(60) NOT NULL DEFAULT '',
  `weak_description` char(128) NOT NULL DEFAULT '',
  `weak_book` int(10) NOT NULL DEFAULT '0',
  `weak_page` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`weak_id`)
);  

