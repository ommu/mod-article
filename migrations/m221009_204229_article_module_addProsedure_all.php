<?php
/**
 * m221009_204229_article_module_addProsedure_all
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 October 2022, 20:43 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use yii\db\Schema;

class m221009_204229_article_module_addProsedure_all extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP PROCEDURE IF EXISTS `articleGetSetting`');

		// add view _article_category
		$createProsedureArticleGetSetting = <<< SQL
CREATE PROCEDURE `articleGetSetting`(OUT `headline_sp` TINYINT)
BEGIN
	/**
	 * articleAfterUpdate
	 */
	SELECT `headline` INTO headline_sp FROM `ommu_article_setting` WHERE `id`=1;
END;
SQL;
		$this->execute($createProsedureArticleGetSetting);
	}

	public function down()
	{
        $this->execute('DROP PROCEDURE IF EXISTS `articleGetSetting`');
    }
}
