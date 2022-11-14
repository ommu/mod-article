<?php
/**
 * m221010_075120_article_module_insertRow_articleGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 10 October 2022, 07:52 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use yii\db\Schema;

class m221010_075120_article_module_insertRow_articleGrid extends \yii\db\Migration
{
	public function up()
	{
		$insertRowArticleGrid = <<< SQL
INSERT INTO `ommu_article_grid` (`id`, `file`, `like`, `media`, `tag`, `view`) 

SELECT 
	a.id AS id,
	case when a.files is null then 0 else a.files end AS `files`,
	case when a.likes is null then 0 else a.likes end AS `likes`,
	case when a.images is null then 0 else a.images end AS `images`,
	case when a.tags is null then 0 else a.tags end AS `tags`,
	case when a.view_all is null then 0 else a.view_all end AS `views`
FROM _articles AS a
LEFT JOIN ommu_article_grid AS b
	ON b.id = a.id  
WHERE
	b.id IS NULL;
SQL;
		$this->execute($insertRowArticleGrid);
	}
}
