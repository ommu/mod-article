<?php
/**
 * m210825_133032_article_module_create_table_media
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 25 August 2021, 13:30 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use yii\db\Schema;

class m210825_133032_article_module_create_table_media extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_article_media';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'cover' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'orders' => Schema::TYPE_TINYINT . '(3) NOT NULL',
				'article_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'media_filename' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'file\'',
				'caption' => Schema::TYPE_STRING . '(150) NOT NULL',
				'description' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'redactor\'',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_article_media_ibfk_1 FOREIGN KEY ([[article_id]]) REFERENCES ommu_articles ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

            $this->createIndex(
                'idWithPublishArticle',
                $tableName,
                ['id', 'publish', 'article_id']
            );
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_article_media';
		$this->dropTable($tableName);
	}
}
