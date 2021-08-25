<?php
/**
 * m210825_132850_article_module_create_table_category
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

class m210825_132850_article_module_create_table_category extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_article_category';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\' COMMENT \'Enable,Disable\'',
				'parent_id' => Schema::TYPE_SMALLINT . '(5)',
				'name' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL COMMENT \'trigger[delete]\'',
				'desc' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL COMMENT \'trigger[delete],text\'',
				'single_photo' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'single_file' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[id]])',
			], $tableOptions);
		}

        $this->createIndex(
            'idWithPublish',
            $tableName,
            ['id', 'publish']
        );

        $this->createIndex(
            'publishWithParent',
            $tableName,
            ['publish', 'parent_id']
        );

        $this->createIndex(
            'parent_id',
            $tableName,
            'parent_id'
        );

        $this->createIndex(
            'name',
            $tableName,
            'name'
        );
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_article_category';
		$this->dropTable($tableName);
	}
}
