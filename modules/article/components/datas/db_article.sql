/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 10.1.16-MariaDB : Database - ommu_db_article
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`ommu_db_article` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `ommu_db_article`;

/*Table structure for table `ommu_article_category` */

DROP TABLE IF EXISTS `ommu_article_category`;

CREATE TABLE `ommu_article_category` (
  `cat_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `publish` tinyint(1) NOT NULL DEFAULT '1',
  `parent` smallint(5) NOT NULL DEFAULT '0',
  `name` int(11) unsigned NOT NULL COMMENT 'trigger[delete]',
  `desc` int(11) unsigned NOT NULL COMMENT 'trigger[delete]',
  `single_photo` tinyint(1) NOT NULL DEFAULT '0',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'trigger',
  `creation_id` int(11) unsigned NOT NULL,
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'trigger',
  `modified_id` int(11) unsigned NOT NULL,
  `slug` varchar(32) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ommu_article_download_detail` */

DROP TABLE IF EXISTS `ommu_article_download_detail`;

CREATE TABLE `ommu_article_download_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'trigger',
  `download_id` int(11) unsigned NOT NULL,
  `download_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `download_ip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `download_id` (`download_id`),
  CONSTRAINT `ommu_article_download_detail_ibfk_1` FOREIGN KEY (`download_id`) REFERENCES `ommu_article_downloads` (`download_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ommu_article_downloads` */

DROP TABLE IF EXISTS `ommu_article_downloads`;

CREATE TABLE `ommu_article_downloads` (
  `download_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `downloads` int(11) NOT NULL DEFAULT '1',
  `download_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `download_ip` varchar(20) NOT NULL,
  PRIMARY KEY (`download_id`),
  KEY `article_id` (`article_id`),
  CONSTRAINT `ommu_article_downloads_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `ommu_articles` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ommu_article_like_detail` */

DROP TABLE IF EXISTS `ommu_article_like_detail`;

CREATE TABLE `ommu_article_like_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'trigger',
  `publish` tinyint(1) NOT NULL,
  `like_id` int(11) unsigned NOT NULL,
  `likes_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `likes_ip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `download_id` (`like_id`),
  CONSTRAINT `ommu_article_like_detail_ibfk_1` FOREIGN KEY (`like_id`) REFERENCES `ommu_article_likes` (`like_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ommu_article_likes` */

DROP TABLE IF EXISTS `ommu_article_likes`;

CREATE TABLE `ommu_article_likes` (
  `like_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `publish` tinyint(1) NOT NULL DEFAULT '1',
  `article_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `likes_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'trigger',
  `likes_ip` varchar(20) NOT NULL,
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'trigger',
  PRIMARY KEY (`like_id`),
  KEY `FK_ommu_pose_likes` (`article_id`),
  CONSTRAINT `FK_ommu_article_likes` FOREIGN KEY (`article_id`) REFERENCES `ommu_articles` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ommu_article_media` */

DROP TABLE IF EXISTS `ommu_article_media`;

CREATE TABLE `ommu_article_media` (
  `media_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `publish` tinyint(1) NOT NULL DEFAULT '1',
  `cover` tinyint(1) NOT NULL DEFAULT '0',
  `article_id` int(11) unsigned NOT NULL,
  `media` text NOT NULL,
  `caption` varchar(150) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'trigger',
  `creation_id` int(11) unsigned NOT NULL,
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'trigger',
  `modified_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`media_id`),
  KEY `FK_ommu_pose_media` (`article_id`),
  CONSTRAINT `FK_ommu_article_media` FOREIGN KEY (`article_id`) REFERENCES `ommu_articles` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ommu_article_setting` */

DROP TABLE IF EXISTS `ommu_article_setting`;

CREATE TABLE `ommu_article_setting` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `license` varchar(32) NOT NULL,
  `permission` tinyint(1) NOT NULL,
  `meta_keyword` text NOT NULL,
  `meta_description` text NOT NULL,
  `type_active` text NOT NULL,
  `gridview_column` text NOT NULL,
  `headline` tinyint(1) NOT NULL COMMENT '1=enable, 0=disable',
  `headline_limit` smallint(3) NOT NULL,
  `headline_category` text NOT NULL,
  `media_limit` smallint(5) NOT NULL,
  `media_resize` tinyint(1) NOT NULL,
  `media_resize_size` text NOT NULL,
  `media_view_size` text NOT NULL,
  `media_file_type` text NOT NULL,
  `upload_file_type` text NOT NULL,
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'trigger',
  `modified_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `ommu_article_tag` */

DROP TABLE IF EXISTS `ommu_article_tag`;

CREATE TABLE `ommu_article_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(11) unsigned NOT NULL,
  `tag_id` int(11) unsigned NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'trigger',
  `creation_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_ommu_publication_journal_tag` (`article_id`),
  KEY `FK_ommu_publication_journal_tag_info` (`tag_id`),
  CONSTRAINT `FK_ommu_article_tag` FOREIGN KEY (`article_id`) REFERENCES `ommu_articles` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ommu_article_view_detail` */

DROP TABLE IF EXISTS `ommu_article_view_detail`;

CREATE TABLE `ommu_article_view_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'trigger',
  `view_id` int(11) unsigned NOT NULL,
  `view_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `view_ip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `view_id` (`view_id`),
  CONSTRAINT `ommu_article_view_detail_ibfk_1` FOREIGN KEY (`view_id`) REFERENCES `ommu_article_views` (`view_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ommu_article_views` */

DROP TABLE IF EXISTS `ommu_article_views`;

CREATE TABLE `ommu_article_views` (
  `view_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `publish` tinyint(1) NOT NULL DEFAULT '1',
  `article_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `views` int(11) NOT NULL DEFAULT '1',
  `view_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'trigger',
  `view_ip` varchar(20) NOT NULL,
  `deleted_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'trigger',
  PRIMARY KEY (`view_id`),
  KEY `FK_ommu_pose_likes` (`article_id`),
  CONSTRAINT `ommu_article_views_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `ommu_articles` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ommu_articles` */

DROP TABLE IF EXISTS `ommu_articles`;

CREATE TABLE `ommu_articles` (
  `article_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `publish` tinyint(1) NOT NULL DEFAULT '0',
  `cat_id` smallint(5) unsigned NOT NULL,
  `article_type` enum('standard','video','quote') NOT NULL,
  `title` varchar(128) NOT NULL,
  `body` text NOT NULL,
  `quote` text NOT NULL,
  `media_file` text NOT NULL,
  `published_date` datetime NOT NULL,
  `headline` tinyint(1) NOT NULL DEFAULT '0',
  `comment_code` tinyint(1) NOT NULL DEFAULT '1',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'trigger',
  `creation_id` int(11) unsigned NOT NULL,
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'trigger',
  `modified_id` int(11) unsigned NOT NULL,
  `headline_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'trigger',
  `slug` text NOT NULL,
  PRIMARY KEY (`article_id`),
  KEY `FK_ommu_article_category` (`cat_id`),
  CONSTRAINT `FK_ommu_article_category` FOREIGN KEY (`cat_id`) REFERENCES `ommu_article_category` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Trigger structure for table `ommu_article_category` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `articleAfterDeleteCategory` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `articleAfterDeleteCategory` AFTER DELETE ON `ommu_article_category` FOR EACH ROW BEGIN	
	DELETE FROM `ommu_core_system_phrase` WHERE `phrase_id`=OLD.name;
	DELETE FROM `ommu_core_system_phrase` WHERE `phrase_id`=OLD.desc;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_article_downloads` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `articleAfterInsertDownloads` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `articleAfterInsertDownloads` AFTER INSERT ON `ommu_article_downloads` FOR EACH ROW BEGIN
	INSERT `ommu_article_download_detail` (`download_id`, `download_date`, `download_ip`)
	VALUE (NEW.download_id, NEW.download_date, NEW.download_ip);
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_article_likes` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `articleAfterInsertLikes` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `articleAfterInsertLikes` AFTER INSERT ON `ommu_article_likes` FOR EACH ROW BEGIN
	INSERT `ommu_article_like_detail` (`publish`, `like_id`, `likes_date`,`likes_ip`)
	VALUE (NEW.publish, NEW.like_id, NEW.likes_date, NEW.likes_ip);
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_article_views` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `articleAfterInsertViews` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `articleAfterInsertViews` AFTER INSERT ON `ommu_article_views` FOR EACH ROW BEGIN
	IF ((NEW.publish = 1) AND (NEW.views = 1)) THEN
		INSERT `ommu_article_view_detail` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.view_id, NEW.view_date, NEW.view_ip);
	END IF;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_article_downloads` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `articleAfterUpdateDownloads` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `articleAfterUpdateDownloads` AFTER UPDATE ON `ommu_article_downloads` FOR EACH ROW BEGIN
	IF (NEW.downloads <> OLD.downloads) THEN
		INSERT `ommu_article_download_detail` (`download_id`, `download_date`, `download_ip`)
		VALUE (NEW.download_id, NEW.download_date, NEW.download_ip);
	END IF;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_article_likes` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `articleAfterUpdateLikes` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `articleAfterUpdateLikes` AFTER UPDATE ON `ommu_article_likes` FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		INSERT `ommu_article_like_detail` (`publish`, `like_id`, `likes_date`,`likes_ip`)
		VALUE (NEW.publish, NEW.like_id, NEW.updated_date, NEW.likes_ip);
	END IF;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_article_views` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `articleAfterUpdateViews` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `articleAfterUpdateViews` AFTER UPDATE ON `ommu_article_views` FOR EACH ROW BEGIN
	IF (NEW.views <> OLD.views) THEN
		INSERT `ommu_article_view_detail` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.view_id, NEW.view_date, NEW.view_ip);
	END IF;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_article_setting` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `articleBeforeInsertSetting` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `articleBeforeInsertSetting` BEFORE INSERT ON `ommu_article_setting` FOR EACH ROW BEGIN
	SET NEW.modified_date = NOW();
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_articles` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `articleBeforeUpdate` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `articleBeforeUpdate` BEFORE UPDATE ON `ommu_articles` FOR EACH ROW BEGIN
	DECLARE column_update_count INT;
	SET column_update_count = 0;
	
	IF (NEW.publish <> OLD.publish) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.cat_id <> OLD.cat_id) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.article_type <> OLD.article_type) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.title <> OLD.title) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.body <> OLD.body) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.quote <> OLD.quote) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.media_file <> OLD.media_file AND ((NEW.modified_id <> OLD.modified_id) OR (NEW.modified_id = OLD.modified_id AND NEW.modified_id <> 0))) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.published_date <> OLD.published_date) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.comment_code <> OLD.comment_code) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.creation_date <> OLD.creation_date) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.creation_id <> OLD.creation_id) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	
	IF (column_update_count > 0) THEN
		SET NEW.modified_date = NOW();
	END IF;
	
	IF (NEW.headline <> OLD.headline AND NEW.headline = 1) THEN
		SET NEW.headline_date = NOW();
	END IF;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_article_category` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `articleBeforeUpdateCategory` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `articleBeforeUpdateCategory` BEFORE UPDATE ON `ommu_article_category` FOR EACH ROW BEGIN
	DECLARE column_update_count INT;
	SET column_update_count = 0;
	
	IF (NEW.publish <> OLD.publish) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.parent <> OLD.parent) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.name <> OLD.name) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.desc <> OLD.desc) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.creation_date <> OLD.creation_date) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.creation_id <> OLD.creation_id) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	
	IF (column_update_count > 0) THEN
		SET NEW.modified_date = NOW();
	END IF;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_article_downloads` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `articleBeforeUpdateDownloads` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `articleBeforeUpdateDownloads` BEFORE UPDATE ON `ommu_article_downloads` FOR EACH ROW BEGIN
	IF (NEW.downloads <> OLD.downloads) THEN
		SET NEW.download_date = NOW();
	END IF;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_article_likes` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `articleBeforeUpdateLikes` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `articleBeforeUpdateLikes` BEFORE UPDATE ON `ommu_article_likes` FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_article_media` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `articleBeforeUpdateMedia` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `articleBeforeUpdateMedia` BEFORE UPDATE ON `ommu_article_media` FOR EACH ROW BEGIN
	DECLARE column_update_count INT;
	SET column_update_count = 0;	
	
	IF (NEW.publish <> OLD.publish) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.article_id <> OLD.article_id) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.media <> OLD.media) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.caption <> OLD.caption) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.creation_date <> OLD.creation_date) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.creation_id <> OLD.creation_id) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	
	IF (column_update_count > 0) THEN
		SET NEW.modified_date = NOW();
	END IF;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_article_setting` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `articleBeforeUpdateSetting` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `articleBeforeUpdateSetting` BEFORE UPDATE ON `ommu_article_setting` FOR EACH ROW BEGIN
	DECLARE column_update_count INT;
	SET column_update_count = 0;
	
	IF (NEW.license <> OLD.license) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.permission <> OLD.permission) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.meta_keyword <> OLD.meta_keyword) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.meta_description <> OLD.meta_description) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.type_active <> OLD.type_active) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.headline <> OLD.headline) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.media_limit <> OLD.media_limit) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.media_resize <> OLD.media_resize) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.media_resize_size <> OLD.media_resize_size) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.media_view_size <> OLD.media_view_size) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.media_file_type <> OLD.media_file_type) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.upload_file_type <> OLD.upload_file_type) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	
	IF (column_update_count > 0) THEN
		SET NEW.modified_date = NOW();
	END IF;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_article_views` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `articleBeforeUpdateViews` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `articleBeforeUpdateViews` BEFORE UPDATE ON `ommu_article_views` FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		IF (NEW.publish = 0) THEN
			SET NEW.deleted_date = NOW();
		END IF;
	ELSE
		IF ((NEW.views <> OLD.views) AND (NEW.views > OLD.views) AND (NEW.publish = 1)) THEN
			SET NEW.view_date = NOW();
		END IF;
	END IF;
    END */$$


DELIMITER ;

/* Procedure structure for procedure `getArticleSetting` */

/*!50003 DROP PROCEDURE IF EXISTS  `getArticleSetting` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `getArticleSetting`(OUT `headline_sp` TINYINT)
BEGIN
	/**
	 * articleAfterUpdate
	 */
	SELECT `headline` INTO headline_sp FROM `ommu_article_setting` WHERE `id`=1;
    END */$$
DELIMITER ;

/*Table structure for table `_view_article_category` */

DROP TABLE IF EXISTS `_view_article_category`;

/*!50001 DROP VIEW IF EXISTS `_view_article_category` */;
/*!50001 DROP TABLE IF EXISTS `_view_article_category` */;

/*!50001 CREATE TABLE  `_view_article_category`(
 `cat_id` smallint(5) unsigned ,
 `articles` decimal(23,0) ,
 `article_pending` decimal(23,0) ,
 `article_unpublish` decimal(23,0) ,
 `article_all` bigint(21) ,
 `article_id` bigint(11) unsigned 
)*/;

/*Table structure for table `_view_article_likes` */

DROP TABLE IF EXISTS `_view_article_likes`;

/*!50001 DROP VIEW IF EXISTS `_view_article_likes` */;
/*!50001 DROP TABLE IF EXISTS `_view_article_likes` */;

/*!50001 CREATE TABLE  `_view_article_likes`(
 `like_id` int(11) unsigned ,
 `article_id` int(11) unsigned ,
 `likes` decimal(23,0) ,
 `unlikes` decimal(23,0) ,
 `like_all` bigint(21) 
)*/;

/*Table structure for table `_view_article_statistic_download` */

DROP TABLE IF EXISTS `_view_article_statistic_download`;

/*!50001 DROP VIEW IF EXISTS `_view_article_statistic_download` */;
/*!50001 DROP TABLE IF EXISTS `_view_article_statistic_download` */;

/*!50001 CREATE TABLE  `_view_article_statistic_download`(
 `article_id` int(11) unsigned ,
 `downloads` decimal(32,0) 
)*/;

/*Table structure for table `_view_article_statistic_like` */

DROP TABLE IF EXISTS `_view_article_statistic_like`;

/*!50001 DROP VIEW IF EXISTS `_view_article_statistic_like` */;
/*!50001 DROP TABLE IF EXISTS `_view_article_statistic_like` */;

/*!50001 CREATE TABLE  `_view_article_statistic_like`(
 `article_id` int(11) unsigned ,
 `likes` decimal(23,0) ,
 `like_all` bigint(21) 
)*/;

/*Table structure for table `_view_article_statistic_media_cover` */

DROP TABLE IF EXISTS `_view_article_statistic_media_cover`;

/*!50001 DROP VIEW IF EXISTS `_view_article_statistic_media_cover` */;
/*!50001 DROP TABLE IF EXISTS `_view_article_statistic_media_cover` */;

/*!50001 CREATE TABLE  `_view_article_statistic_media_cover`(
 `article_id` int(11) unsigned ,
 `media_id` int(11) unsigned ,
 `media_cover` text ,
 `media_caption` varchar(150) 
)*/;

/*Table structure for table `_view_article_statistic_tag` */

DROP TABLE IF EXISTS `_view_article_statistic_tag`;

/*!50001 DROP VIEW IF EXISTS `_view_article_statistic_tag` */;
/*!50001 DROP TABLE IF EXISTS `_view_article_statistic_tag` */;

/*!50001 CREATE TABLE  `_view_article_statistic_tag`(
 `article_id` int(11) unsigned ,
 `tags` bigint(21) 
)*/;

/*Table structure for table `_view_article_statistic_view` */

DROP TABLE IF EXISTS `_view_article_statistic_view`;

/*!50001 DROP VIEW IF EXISTS `_view_article_statistic_view` */;
/*!50001 DROP TABLE IF EXISTS `_view_article_statistic_view` */;

/*!50001 CREATE TABLE  `_view_article_statistic_view`(
 `article_id` int(11) unsigned ,
 `views` decimal(32,0) ,
 `view_all` decimal(32,0) 
)*/;

/*Table structure for table `_view_article_tag` */

DROP TABLE IF EXISTS `_view_article_tag`;

/*!50001 DROP VIEW IF EXISTS `_view_article_tag` */;
/*!50001 DROP TABLE IF EXISTS `_view_article_tag` */;

/*!50001 CREATE TABLE  `_view_article_tag`(
 `tag_id` int(11) unsigned ,
 `articles` decimal(23,0) ,
 `article_all` bigint(21) 
)*/;

/*Table structure for table `_view_articles` */

DROP TABLE IF EXISTS `_view_articles`;

/*!50001 DROP VIEW IF EXISTS `_view_articles` */;
/*!50001 DROP TABLE IF EXISTS `_view_articles` */;

/*!50001 CREATE TABLE  `_view_articles`(
 `article_id` int(11) unsigned ,
 `media_id` int(11) unsigned ,
 `media_cover` text ,
 `media_caption` varchar(150) ,
 `medias` decimal(23,0) ,
 `media_all` bigint(21) ,
 `likes` decimal(23,0) ,
 `like_all` bigint(21) ,
 `views` decimal(32,0) ,
 `view_all` decimal(32,0) ,
 `downloads` decimal(32,0) ,
 `tags` bigint(21) 
)*/;

/*View structure for view _view_article_category */

/*!50001 DROP TABLE IF EXISTS `_view_article_category` */;
/*!50001 DROP VIEW IF EXISTS `_view_article_category` */;

/*!50001 CREATE ALGORITHM=TEMPTABLE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `_view_article_category` AS select `a`.`cat_id` AS `cat_id`,sum((case when ((`b`.`publish` = '1') and (`b`.`published_date` <= curdate())) then 1 else 0 end)) AS `articles`,sum((case when ((`b`.`publish` = '1') and (`b`.`published_date` > curdate())) then 1 else 0 end)) AS `article_pending`,sum((case when (`b`.`publish` = '0') then 1 else 0 end)) AS `article_unpublish`,count(`b`.`cat_id`) AS `article_all`,max((case when ((`b`.`publish` = '1') and (`b`.`published_date` <= curdate())) then `b`.`article_id` end)) AS `article_id` from (`ommu_article_category` `a` left join `ommu_articles` `b` on((`a`.`cat_id` = `b`.`cat_id`))) group by `a`.`cat_id` */;

/*View structure for view _view_article_likes */

/*!50001 DROP TABLE IF EXISTS `_view_article_likes` */;
/*!50001 DROP VIEW IF EXISTS `_view_article_likes` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `_view_article_likes` AS select `a`.`like_id` AS `like_id`,`a`.`article_id` AS `article_id`,sum((case when (`b`.`publish` = '1') then 1 else 0 end)) AS `likes`,sum((case when (`b`.`publish` = '0') then 1 else 0 end)) AS `unlikes`,count(`b`.`like_id`) AS `like_all` from (`ommu_article_likes` `a` left join `ommu_article_like_detail` `b` on((`a`.`like_id` = `b`.`like_id`))) group by `a`.`like_id` */;

/*View structure for view _view_article_statistic_download */

/*!50001 DROP TABLE IF EXISTS `_view_article_statistic_download` */;
/*!50001 DROP VIEW IF EXISTS `_view_article_statistic_download` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `_view_article_statistic_download` AS select `a`.`article_id` AS `article_id`,sum(`a`.`downloads`) AS `downloads` from `ommu_article_downloads` `a` group by `a`.`article_id` */;

/*View structure for view _view_article_statistic_like */

/*!50001 DROP TABLE IF EXISTS `_view_article_statistic_like` */;
/*!50001 DROP VIEW IF EXISTS `_view_article_statistic_like` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `_view_article_statistic_like` AS select `a`.`article_id` AS `article_id`,sum((case when (`a`.`publish` = '1') then 1 else 0 end)) AS `likes`,count(`a`.`article_id`) AS `like_all` from `ommu_article_likes` `a` group by `a`.`article_id` */;

/*View structure for view _view_article_statistic_media_cover */

/*!50001 DROP TABLE IF EXISTS `_view_article_statistic_media_cover` */;
/*!50001 DROP VIEW IF EXISTS `_view_article_statistic_media_cover` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `_view_article_statistic_media_cover` AS select `a`.`article_id` AS `article_id`,`a`.`media_id` AS `media_id`,`a`.`media` AS `media_cover`,`a`.`caption` AS `media_caption` from `ommu_article_media` `a` where ((`a`.`publish` = 1) and (`a`.`cover` = 1)) group by `a`.`article_id` */;

/*View structure for view _view_article_statistic_tag */

/*!50001 DROP TABLE IF EXISTS `_view_article_statistic_tag` */;
/*!50001 DROP VIEW IF EXISTS `_view_article_statistic_tag` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `_view_article_statistic_tag` AS select `a`.`article_id` AS `article_id`,count(`a`.`article_id`) AS `tags` from `ommu_article_tag` `a` group by `a`.`article_id` */;

/*View structure for view _view_article_statistic_view */

/*!50001 DROP TABLE IF EXISTS `_view_article_statistic_view` */;
/*!50001 DROP VIEW IF EXISTS `_view_article_statistic_view` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `_view_article_statistic_view` AS select `a`.`article_id` AS `article_id`,sum((case when (`a`.`publish` = '1') then `a`.`views` else 0 end)) AS `views`,sum(`a`.`views`) AS `view_all` from `ommu_article_views` `a` group by `a`.`article_id` */;

/*View structure for view _view_article_tag */

/*!50001 DROP TABLE IF EXISTS `_view_article_tag` */;
/*!50001 DROP VIEW IF EXISTS `_view_article_tag` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `_view_article_tag` AS select `a`.`tag_id` AS `tag_id`,sum((case when (`b`.`publish` = '1') then 1 else 0 end)) AS `articles`,count(`a`.`article_id`) AS `article_all` from (`ommu_article_tag` `a` left join `ommu_articles` `b` on((`a`.`article_id` = `b`.`article_id`))) group by `a`.`tag_id` */;

/*View structure for view _view_articles */

/*!50001 DROP TABLE IF EXISTS `_view_articles` */;
/*!50001 DROP VIEW IF EXISTS `_view_articles` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `_view_articles` AS select `a`.`article_id` AS `article_id`,`g`.`media_id` AS `media_id`,`g`.`media_cover` AS `media_cover`,`g`.`media_caption` AS `media_caption`,sum((case when (`b`.`publish` = '1') then 1 else 0 end)) AS `medias`,count(`b`.`article_id`) AS `media_all`,`c`.`likes` AS `likes`,`c`.`like_all` AS `like_all`,`d`.`views` AS `views`,`d`.`view_all` AS `view_all`,`e`.`downloads` AS `downloads`,`f`.`tags` AS `tags` from ((((((`ommu_articles` `a` left join `ommu_article_media` `b` on((`a`.`article_id` = `b`.`article_id`))) left join `_view_article_statistic_like` `c` on((`a`.`article_id` = `c`.`article_id`))) left join `_view_article_statistic_view` `d` on((`a`.`article_id` = `d`.`article_id`))) left join `_view_article_statistic_download` `e` on((`a`.`article_id` = `e`.`article_id`))) left join `_view_article_statistic_tag` `f` on((`a`.`article_id` = `f`.`article_id`))) left join `_view_article_statistic_media_cover` `g` on((`a`.`article_id` = `g`.`article_id`))) group by `a`.`article_id` */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
