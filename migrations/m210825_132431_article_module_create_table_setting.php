<?php
/**
 * m210825_132431_article_module_create_table_setting
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 25 August 2021, 13:27 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use yii\db\Schema;

class m210825_132431_article_module_create_table_setting extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_article_setting';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_TINYINT . '(1) UNSIGNED NOT NULL AUTO_INCREMENT',
				'license' => Schema::TYPE_STRING . '(32) NOT NULL',
				'permission' => Schema::TYPE_TINYINT . '(1) NOT NULL',
				'meta_description' => Schema::TYPE_TEXT . ' NOT NULL',
				'meta_keyword' => Schema::TYPE_TEXT . ' NOT NULL',
				'headline' => Schema::TYPE_TINYINT . '(1) NOT NULL COMMENT \'Enable,Disable\'',
				'headline_limit' => Schema::TYPE_SMALLINT . '(2) NOT NULL',
				'headline_category' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'serialize\'',
				'media_image_limit' => Schema::TYPE_SMALLINT . '(5) NOT NULL',
				'media_image_resize' => Schema::TYPE_TINYINT . '(1) NOT NULL',
				'media_image_resize_size' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'serialize\'',
				'media_image_view_size' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'serialize\'',
				'media_image_type' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'serialize\'',
				'media_file_limit' => Schema::TYPE_SMALLINT . '(5) NOT NULL',
				'media_file_type' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'serialize\'',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]])',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_article_setting';
		$this->dropTable($tableName);
	}
}
