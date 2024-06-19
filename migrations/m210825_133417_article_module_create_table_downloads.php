<?php
/**
 * m210825_133417_article_module_create_table_downloads
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 25 August 2021, 13:34 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use yii\db\Schema;

class m210825_133417_article_module_create_table_downloads extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_article_downloads';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'file_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'downloads' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT \'1\'',
				'download_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'download_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_article_downloads_ibfk_1 FOREIGN KEY ([[file_id]]) REFERENCES ommu_article_files ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_article_downloads_ibfk_2 FOREIGN KEY ([[user_id]]) REFERENCES ommu_users ([[user_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

            $this->createIndex(
                'fileWithUser',
                $tableName,
                ['file_id', 'user_id']
            );
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_article_downloads';
		$this->dropTable($tableName);
	}
}
