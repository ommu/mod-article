<?php
/**
 * m210825_133245_article_module_create_table_view_history
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 25 August 2021, 13:32 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use yii\db\Schema;

class m210825_133245_article_module_create_table_view_history extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_article_view_history';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'trigger\'',
				'view_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'view_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
				'view_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_article_view_history_ibfk_1 FOREIGN KEY ([[view_id]]) REFERENCES ommu_article_views ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_article_view_history';
		$this->dropTable($tableName);
	}
}
