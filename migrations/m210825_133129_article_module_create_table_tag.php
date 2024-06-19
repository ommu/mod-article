<?php
/**
 * m210825_133129_article_module_create_table_tag
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 25 August 2021, 13:31 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use yii\db\Schema;

class m210825_133129_article_module_create_table_tag extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_article_tag';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'article_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'tag_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_article_tag_ibfk_1 FOREIGN KEY ([[article_id]]) REFERENCES ommu_articles ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

            $this->createIndex(
                'tag_id',
                $tableName,
                'tag_id'
            );
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_article_tag';
		$this->dropTable($tableName);
	}
}
