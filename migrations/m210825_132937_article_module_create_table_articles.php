<?php
/**
 * m210825_132937_article_module_create_table_articles
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 25 August 2021, 13:29 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use yii\db\Schema;

class m210825_132937_article_module_create_table_articles extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_articles';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'cat_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED NOT NULL',
				'title' => Schema::TYPE_STRING . '(128) NOT NULL',
				'body' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'redactor\'',
				'published_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
				'headline' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'headline_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_articles_ibfk_1 FOREIGN KEY ([[cat_id]]) REFERENCES ommu_article_category ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

            $this->createIndex(
                'getArticleWithCategory',
                $tableName,
                ['publish', 'cat_id', 'published_date']
            );
    
            $this->createIndex(
                'getArticleWithCategoryAndNotIn',
                $tableName,
                ['id', 'publish', 'cat_id', 'published_date']
            );
    
            $this->createIndex(
                'getArticleWithHeadline',
                $tableName,
                ['publish', 'published_date', 'headline']
            );
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_articles';
		$this->dropTable($tableName);
	}
}
