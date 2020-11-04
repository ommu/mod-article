<?php
/**
 * m190522_042401_article_module_insert_role
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 22 May 2019, 04:25 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;

class m190522_042401_article_module_insert_role extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_auth_item';
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_auth_item', ['name', 'type', 'data', 'created_at'], [
				['articleModLevelAdmin', '2', '', time()],
				['articleModLevelModerator', '2', '', time()],
				['/article/admin/*', '2', '', time()],
				['/article/admin/index', '2', '', time()],
				['/article/o/image/*', '2', '', time()],
				['/article/o/image/index', '2', '', time()],
				['/article/o/file/*', '2', '', time()],
				['/article/o/file/index', '2', '', time()],
				['/article/o/view/*', '2', '', time()],
				['/article/o/view/index', '2', '', time()],
				['/article/o/download/*', '2', '', time()],
				['/article/o/download/index', '2', '', time()],
				['/article/o/like/*', '2', '', time()],
				['/article/o/like/index', '2', '', time()],
				['/article/history/download/*', '2', '', time()],
				['/article/history/view/*', '2', '', time()],
				['/article/history/like/*', '2', '', time()],
				['/article/setting/admin/index', '2', '', time()],
				['/article/setting/admin/update', '2', '', time()],
				['/article/setting/admin/delete', '2', '', time()],
				['/article/setting/category/*', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_auth_item_child';
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_auth_item_child', ['parent', 'child'], [
				['userAdmin', 'articleModLevelAdmin'],
				['userModerator', 'articleModLevelModerator'],
				['articleModLevelAdmin', 'articleModLevelModerator'],
				['articleModLevelAdmin', '/article/setting/admin/update'],
				['articleModLevelAdmin', '/article/setting/admin/delete'],
				['articleModLevelAdmin', '/article/setting/category/*'],
				['articleModLevelModerator', '/article/setting/admin/index'],
				['articleModLevelModerator', '/article/admin/*'],
				['articleModLevelModerator', '/article/o/image/*'],
				['articleModLevelModerator', '/article/o/file/*'],
				['articleModLevelModerator', '/article/o/view/*'],
				['articleModLevelModerator', '/article/o/download/*'],
				['articleModLevelModerator', '/article/o/like/*'],
				['articleModLevelModerator', '/article/history/download/*'],
				['articleModLevelModerator', '/article/history/view/*'],
				['articleModLevelModerator', '/article/history/like/*'],
			]);
		}
	}
}
