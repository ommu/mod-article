<?php
/**
 * m221010_072656_article_module_addTriggerArticle_categoryGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 10 October 2022, 07:28 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use yii\db\Schema;

class m221010_072656_article_module_addTriggerArticle_categoryGrid extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `articleAfterInsert`');
        $this->execute('DROP TRIGGER IF EXISTS `articleAfterUpdate`');

        // create trigger articleAfterInsert
        $articleAfterInsert = <<< SQL
CREATE
    TRIGGER `articleAfterInsert` AFTER INSERT ON `ommu_articles` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_article_category_grid` SET `article` = `article` + 1 WHERE `id` = NEW.cat_id;
    END;
SQL;
        $this->execute($articleAfterInsert);

        // create trigger articleAfterUpdate
        $articleAfterUpdate = <<< SQL
CREATE
    TRIGGER `articleAfterUpdate` AFTER UPDATE ON `ommu_articles` 
    FOR EACH ROW BEGIN
	IF (NEW.updated_date <> OLD.updated_date) THEN
		IF (NEW.publish <> OLD.publish AND NEW.publish = 2) THEN
			UPDATE `ommu_article_category_grid` SET `article` = `article` - 1 WHERE `id` = OLD.cat_id;
		END IF;
	END IF;
    END;
SQL;
        $this->execute($articleAfterUpdate);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `articleAfterInsert`');
        $this->execute('DROP TRIGGER IF EXISTS `articleAfterUpdate`');
    }
}
