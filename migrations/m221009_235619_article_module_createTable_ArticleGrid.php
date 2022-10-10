<?php
/**
 * m221009_235619_article_module_createTable_ArticleGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 October 2022, 23:56 WIB
 * @link https://bitbucket.org/ommu/article
 *
 */

use Yii;
use yii\db\Schema;

class m221009_235619_article_module_createTable_ArticleGrid extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_article_grid';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'file' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'like' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'media' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'tag' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'view' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_article_grid_ibfk_1 FOREIGN KEY ([[id]]) REFERENCES ommu_articles ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_article_grid';
		$this->dropTable($tableName);
	}
}


