<?php
/**
 * m221010_080327_article_module_alterTrigger_articleGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 10 October 2022, 08:04 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use yii\db\Schema;

class m221010_080327_article_module_alterTrigger_articleGrid extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `articleAfterInsertFiles`');
        $this->execute('DROP TRIGGER IF EXISTS `artarticleAfterUpdateFiles`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterInsertLikes`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterUpdateLikes`');
        $this->execute('DROP TRIGGER IF EXISTS `articleAfterInsertMedias`');
        $this->execute('DROP TRIGGER IF EXISTS `artarticleAfterUpdateMedias`');
        $this->execute('DROP TRIGGER IF EXISTS `articleAfterInsertTags`');
        $this->execute('DROP TRIGGER IF EXISTS `artarticleAfterDeleteTags`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterInsertViews`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterUpdateViews`');

        // create trigger articleAfterInsertFiles
        $articleAfterInsertFiles = <<< SQL
CREATE
    TRIGGER `articleAfterInsertFiles` AFTER INSERT ON `ommu_article_files` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_article_grid` SET `file` = `file` + 1 WHERE `id` = NEW.article_id;
    END;
SQL;
        $this->execute($articleAfterInsertFiles);

        // create trigger artarticleAfterUpdateFiles
        $artarticleAfterUpdateFiles = <<< SQL
CREATE
    TRIGGER `artarticleAfterUpdateFiles` AFTER UPDATE ON `ommu_article_files` 
    FOR EACH ROW BEGIN
	IF (NEW.updated_date <> OLD.updated_date) THEN
		IF (NEW.publish <> OLD.publish AND NEW.publish = 2) THEN
			UPDATE `ommu_article_grid` SET `file` = `file` - 1 WHERE `id` = NEW.article_id;
		END IF;
	END IF;
    END;
SQL;
        $this->execute($artarticleAfterUpdateFiles);

		// add view articleAfterInsertLikes
		$addTriggerArticleAfterInsertLikes = <<< SQL
CREATE
    TRIGGER `articleAfterInsertLikes` AFTER INSERT ON `ommu_article_likes` 
    FOR EACH ROW BEGIN
	INSERT `ommu_article_like_history` (`publish`, `like_id`, `likes_date`,`likes_ip`)
	VALUE (NEW.publish, NEW.id, NEW.likes_date, NEW.likes_ip);

	UPDATE `ommu_article_grid` SET `like` = `like` + 1 WHERE `id` = NEW.article_id;
    END;
SQL;
		$this->execute($addTriggerArticleAfterInsertLikes);

		// add view articleAfterUpdateLikes
		$addTriggerArticleAfterUpdateLikes = <<< SQL
CREATE
    TRIGGER `articleAfterUpdateLikes` AFTER UPDATE ON `ommu_article_likes` 
    FOR EACH ROW BEGIN
	IF (NEW.updated_date <> OLD.updated_date) THEN
		INSERT `ommu_article_like_history` (`publish`, `like_id`, `likes_date`,`likes_ip`)
		VALUE (NEW.publish, NEW.id, NEW.updated_date, NEW.likes_ip);

		IF (NEW.publish <> OLD.publish AND NEW.publish = 2) THEN
			UPDATE `ommu_article_grid` SET `like` = `like` - 1 WHERE `id` = NEW.article_id;
		END IF;
	END IF;
    END;
SQL;
		$this->execute($addTriggerArticleAfterUpdateLikes);

        // create trigger articleAfterInsertMedias
        $articleAfterInsertMedias = <<< SQL
CREATE
    TRIGGER `articleAfterInsertMedias` AFTER INSERT ON `ommu_article_media` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_article_grid` SET `media` = `media` + 1 WHERE `id` = NEW.article_id;
    END;
SQL;
        $this->execute($articleAfterInsertMedias);

        // create trigger artarticleAfterUpdateMedias
        $artarticleAfterUpdateMedias = <<< SQL
CREATE
    TRIGGER `artarticleAfterUpdateMedias` AFTER UPDATE ON `ommu_article_media` 
    FOR EACH ROW BEGIN
	IF (NEW.updated_date <> OLD.updated_date) THEN
		IF (NEW.publish <> OLD.publish AND NEW.publish = 2) THEN
			UPDATE `ommu_article_grid` SET `media` = `media` - 1 WHERE `id` = NEW.article_id;
		END IF;
	END IF;
    END;
SQL;
        $this->execute($artarticleAfterUpdateMedias);

        // create trigger articleAfterInsertTags
        $articleAfterInsertTags = <<< SQL
CREATE
    TRIGGER `articleAfterInsertTags` AFTER INSERT ON `ommu_article_tag` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_article_grid` SET `tag` = `tag` + 1 WHERE `id` = NEW.article_id;
    END;
SQL;
        $this->execute($articleAfterInsertTags);

        // create trigger artarticleAfterDeleteTags
        $artarticleAfterDeleteTags = <<< SQL
CREATE
    TRIGGER `artarticleAfterDeleteTags` AFTER DELETE ON `ommu_article_tag` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_article_grid` SET `tag` = `tag` - 1 WHERE `id` = OLD.article_id;
    END;
SQL;
        $this->execute($artarticleAfterDeleteTags);

		// add view articleAfterInsertViews
		$addTriggerArticleAfterInsertViews = <<< SQL
CREATE
    TRIGGER `articleAfterInsertViews` AFTER INSERT ON `ommu_article_views` 
    FOR EACH ROW BEGIN
	IF (NEW.publish = 1 AND NEW.views <> 0) THEN
		INSERT `ommu_article_view_history` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.id, NEW.view_date, NEW.view_ip);

		UPDATE `ommu_article_grid` SET `view` = `view` + 1 WHERE `id` = NEW.article_id;
	END IF;
    END;
SQL;
		$this->execute($addTriggerArticleAfterInsertViews);

		// add view articleAfterUpdateViews
		$addTriggerArticleAfterUpdateViews = <<< SQL
CREATE
    TRIGGER `articleAfterUpdateViews` AFTER UPDATE ON `ommu_article_views` 
    FOR EACH ROW BEGIN
	IF (NEW.view_date <> OLD.view_date) THEN
		INSERT `ommu_article_view_history` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.id, NEW.view_date, NEW.view_ip);

		UPDATE `ommu_article_grid` SET `view` = `view` + 1 WHERE `id` = NEW.article_id;
	END IF;
    END;
SQL;
		$this->execute($addTriggerArticleAfterUpdateViews);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `articleAfterInsert`');
        $this->execute('DROP TRIGGER IF EXISTS `articleAfterUpdate`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterInsertLikes`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterUpdateLikes`');
        $this->execute('DROP TRIGGER IF EXISTS `articleAfterInsertMedias`');
        $this->execute('DROP TRIGGER IF EXISTS `artarticleAfterUpdateMedias`');
        $this->execute('DROP TRIGGER IF EXISTS `articleAfterInsertTags`');
        $this->execute('DROP TRIGGER IF EXISTS `artarticleAfterDeleteTags`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterInsertViews`');
		$this->execute('DROP TRIGGER IF EXISTS `articleAfterUpdateViews`');

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
    }
}
