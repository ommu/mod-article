<?php
/**
 * m221011_051409_article_module_dropView_media
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

class m221011_051409_article_module_dropView_media extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_article_media`');
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_article_media`');

		// add view _article_media
		$addViewArchiveMedia = <<< SQL
CREATE VIEW `_article_media` AS
select
  `a`.`id` AS `id`,
  (case when `a`.`caption` <> '' then 1 else 0 end) AS `caption`,
  (case when `a`.`description` <> '' then 1 else 0 end) AS `description`
from `ommu_article_media` `a`
group by `a`.`id`;
SQL;
		$this->execute($addViewArchiveMedia);
    }
}
