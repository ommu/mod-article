<?php
/**
 * m221010_072116_article_module_insertRow_categoryGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 10 October 2022, 07:22 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use yii\db\Schema;

class m221010_072116_article_module_insertRow_categoryGrid extends \yii\db\Migration
{
	public function up()
	{
		$insertRowCategoryGrid = <<< SQL
INSERT INTO `ommu_article_category_grid` (`id`, `article`) 

SELECT 
	a.id AS id,
	case when a.all is null then 0 else a.all end AS `article`
FROM _article_category AS a
LEFT JOIN ommu_article_category_grid AS b
	ON b.id = a.id
WHERE
	b.id IS NULL;
SQL;
		$this->execute($insertRowCategoryGrid);
	}
}
