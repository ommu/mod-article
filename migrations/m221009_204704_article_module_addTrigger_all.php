<?php
/**
 * m221009_204704_article_module_addTrigger_all
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 October 2022, 20:47 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use yii\db\Schema;

class m221009_204704_article_module_addTrigger_all extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP TRIGGER IF EXISTS `articleBeforeUpdateCategory`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterDeleteCategory`');
		$this->execute('DROP TRIGGER IF EXISTS `articleBeforeUpdate`');
		$this->execute('DROP TRIGGER IF EXISTS `articleBeforeUpdateFiles`');
		$this->execute('DROP TRIGGER IF EXISTS `articleBeforeUpdateMedia`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterInsertDownloads`');
		$this->execute('DROP TRIGGER IF EXISTS `articleBeforeUpdateDownloads`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterUpdateDownloads`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterInsertViews`');
		$this->execute('DROP TRIGGER IF EXISTS `articleBeforeUpdateViews`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterUpdateViews`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterInsertLikes`');
		$this->execute('DROP TRIGGER IF EXISTS `articleBeforeUpdateLikes`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterUpdateLikes`');

		// add view articleBeforeUpdateCategory
		$addTriggerArticleBeforeUpdateCategory = <<< SQL
CREATE
    TRIGGER `articleBeforeUpdateCategory` BEFORE UPDATE ON `ommu_article_category` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
		$this->execute($addTriggerArticleBeforeUpdateCategory);

		// add view articleAfterDeleteCategory
		$addTriggerArticleAfterDeleteCategory = <<< SQL
CREATE
    TRIGGER `articleAfterDeleteCategory` AFTER DELETE ON `ommu_article_category` 
    FOR EACH ROW BEGIN	
	/*
	DELETE FROM `source_message` WHERE `id`=OLD.name;
	DELETE FROM `source_message` WHERE `id`=OLD.desc;
	*/
	UPDATE `source_message` SET `message`=CONCAT(message,'_DELETED') WHERE `id`=OLD.name;
	UPDATE `source_message` SET `message`=CONCAT(message,'_DELETED') WHERE `id`=OLD.desc;
    END;
SQL;
		$this->execute($addTriggerArticleAfterDeleteCategory);

		// add view articleBeforeUpdate
		$addTriggerArticleBeforeUpdate = <<< SQL
CREATE
    TRIGGER `articleBeforeUpdate` BEFORE UPDATE ON `ommu_articles` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
	
	IF (NEW.headline <> OLD.headline AND NEW.headline = 1) THEN
		SET NEW.headline_date = NOW();
	END IF;
    END;
SQL;
		$this->execute($addTriggerArticleBeforeUpdate);

		// add view articleBeforeUpdateFiles
		$addTriggerArticleBeforeUpdateFiles = <<< SQL
CREATE
    TRIGGER `articleBeforeUpdateFiles` BEFORE UPDATE ON `ommu_article_files` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
		$this->execute($addTriggerArticleBeforeUpdateFiles);

		// add view articleBeforeUpdateMedia
		$addTriggerArticleBeforeUpdateMedia = <<< SQL
CREATE
    TRIGGER `articleBeforeUpdateMedia` BEFORE UPDATE ON `ommu_article_media` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
		$this->execute($addTriggerArticleBeforeUpdateMedia);

		// add view articleAfterInsertDownloads
		$addTriggerArticleAfterInsertDownloads = <<< SQL
CREATE
    TRIGGER `articleAfterInsertDownloads` AFTER INSERT ON `ommu_article_downloads` 
    FOR EACH ROW BEGIN
	INSERT `ommu_article_download_history` (`download_id`, `download_date`, `download_ip`)
	VALUE (NEW.id, NEW.download_date, NEW.download_ip);
    END;
SQL;
		$this->execute($addTriggerArticleAfterInsertDownloads);

		// add view articleBeforeUpdateDownloads
		$addTriggerArticleBeforeUpdateDownloads = <<< SQL
CREATE
    TRIGGER `articleBeforeUpdateDownloads` BEFORE UPDATE ON `ommu_article_downloads` 
    FOR EACH ROW BEGIN
	IF (NEW.downloads <> OLD.downloads) THEN
		SET NEW.download_date = NOW();
	END IF;
    END;
SQL;
		$this->execute($addTriggerArticleBeforeUpdateDownloads);

		// add view articleAfterUpdateDownloads
		$addTriggerArticleAfterUpdateDownloads = <<< SQL
CREATE
    TRIGGER `articleAfterUpdateDownloads` AFTER UPDATE ON `ommu_article_downloads` 
    FOR EACH ROW BEGIN
	IF (NEW.download_date <> OLD.download_date) THEN
		INSERT `ommu_article_download_history` (`download_id`, `download_date`, `download_ip`)
		VALUE (NEW.id, NEW.download_date, NEW.download_ip);
	END IF;
    END;
SQL;
		$this->execute($addTriggerArticleAfterUpdateDownloads);

		// add view articleAfterInsertViews
		$addTriggerArticleAfterInsertViews = <<< SQL
CREATE
    TRIGGER `articleAfterInsertViews` AFTER INSERT ON `ommu_article_views` 
    FOR EACH ROW BEGIN
	IF (NEW.publish = 1 AND NEW.views <> 0) THEN
		INSERT `ommu_article_view_history` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.id, NEW.view_date, NEW.view_ip);
	END IF;
    END;
SQL;
		$this->execute($addTriggerArticleAfterInsertViews);

		// add view articleBeforeUpdateViews
		$addTriggerArticleBeforeUpdateViews = <<< SQL
CREATE
    TRIGGER `articleBeforeUpdateViews` BEFORE UPDATE ON `ommu_article_views` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	ELSE
		IF (NEW.publish = 1 AND (NEW.views <> OLD.views AND NEW.views > OLD.views)) THEN
			SET NEW.view_date = NOW();
		END IF;
	END IF;
    END;
SQL;
		$this->execute($addTriggerArticleBeforeUpdateViews);

		// add view articleAfterUpdateViews
		$addTriggerArticleAfterUpdateViews = <<< SQL
CREATE
    TRIGGER `articleAfterUpdateViews` AFTER UPDATE ON `ommu_article_views` 
    FOR EACH ROW BEGIN
	IF (NEW.view_date <> OLD.view_date) THEN
		INSERT `ommu_article_view_history` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.id, NEW.view_date, NEW.view_ip);
	END IF;
    END;
SQL;
		$this->execute($addTriggerArticleAfterUpdateViews);

		// add view articleAfterInsertLikes
		$addTriggerArticleAfterInsertLikes = <<< SQL
CREATE
    TRIGGER `articleAfterInsertLikes` AFTER INSERT ON `ommu_article_likes` 
    FOR EACH ROW BEGIN
	INSERT `ommu_article_like_history` (`publish`, `like_id`, `likes_date`,`likes_ip`)
	VALUE (NEW.publish, NEW.id, NEW.likes_date, NEW.likes_ip);
    END;
SQL;
		$this->execute($addTriggerArticleAfterInsertLikes);

		// add view articleBeforeUpdateLikes
		$addTriggerArticleBeforeUpdateLikes = <<< SQL
CREATE
    TRIGGER `articleBeforeUpdateLikes` BEFORE UPDATE ON `ommu_article_likes` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
		$this->execute($addTriggerArticleBeforeUpdateLikes);

		// add view articleAfterUpdateLikes
		$addTriggerArticleAfterUpdateLikes = <<< SQL
CREATE
    TRIGGER `articleAfterUpdateLikes` AFTER UPDATE ON `ommu_article_likes` 
    FOR EACH ROW BEGIN
	IF (NEW.updated_date <> OLD.updated_date) THEN
		INSERT `ommu_article_like_history` (`publish`, `like_id`, `likes_date`,`likes_ip`)
		VALUE (NEW.publish, NEW.id, NEW.updated_date, NEW.likes_ip);
	END IF;
    END;
SQL;
		$this->execute($addTriggerArticleAfterUpdateLikes);
	}

	public function down()
	{
		$this->execute('DROP TRIGGER IF EXISTS `articleBeforeUpdateCategory`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterDeleteCategory`');
		$this->execute('DROP TRIGGER IF EXISTS `articleBeforeUpdate`');
		$this->execute('DROP TRIGGER IF EXISTS `articleBeforeUpdateFiles`');
		$this->execute('DROP TRIGGER IF EXISTS `articleBeforeUpdateMedia`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterInsertDownloads`');
		$this->execute('DROP TRIGGER IF EXISTS `articleBeforeUpdateDownloads`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterUpdateDownloads`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterInsertViews`');
		$this->execute('DROP TRIGGER IF EXISTS `articleBeforeUpdateViews`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterUpdateViews`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterInsertLikes`');
		$this->execute('DROP TRIGGER IF EXISTS `articleBeforeUpdateLikes`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterUpdateLikes`');
    }
}
