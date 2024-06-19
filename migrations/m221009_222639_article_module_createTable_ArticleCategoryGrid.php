<?php
/**
 * m221009_222639_article_module_createTable_ArticleCategoryGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 October 2022, 22:27 WIB
 * @link https://bitbucket.org/ommu/article
 *
 */

use Yii;
use yii\db\Schema;

class m221009_222639_article_module_createTable_ArticleCategoryGrid extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_article_category_grid';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED NOT NULL',
				'article' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_article_category_grid_ibfk_1 FOREIGN KEY ([[id]]) REFERENCES ommu_article_category ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_article_category_grid';
		$this->dropTable($tableName);
	}
}


