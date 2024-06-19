<?php
/**
 * m221009_190520_article_module_update_role
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 22 May 2019, 04:25 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m221009_190520_article_module_update_role extends \yii\db\Migration
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }

        return $authManager;
    }

	public function up()
	{
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;
        $schema = $this->db->getSchema()->defaultSchema;

        // route
		$tableName = Yii::$app->db->tablePrefix . $authManager->itemTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
            $this->update($tableName, ['name' => '/article/view/admin/*'], ['name' => '/article/o/view/*']);
            $this->update($tableName, ['name' => '/article/view/admin/index'], ['name' => '/article/o/view/index']);
            $this->update($tableName, ['name' => '/article/download/admin/*'], ['name' => '/article/o/download/*']);
            $this->update($tableName, ['name' => '/article/download/admin/index'], ['name' => '/article/o/download/index']);
            $this->update($tableName, ['name' => '/article/like/admin/*'], ['name' => '/article/o/like/*']);
            $this->update($tableName, ['name' => '/article/like/admin/index'], ['name' => '/article/o/like/index']);
            $this->update($tableName, ['name' => '/article/download/history/*'], ['name' => '/article/history/download/*']);
            $this->update($tableName, ['name' => '/article/view/history/*'], ['name' => '/article/history/view/*']);
            $this->update($tableName, ['name' => '/article/like/history/*'], ['name' => '/article/history/like/*']);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
            // permission
            $this->update($tableName, ['child' => '/article/view/admin/*'], ['parent' => 'articleModLevelModerator', 'child' => '/article/o/view/*']);
            $this->update($tableName, ['child' => '/article/download/admin/*'], ['parent' => 'articleModLevelModerator', 'child' => '/article/o/download/*']);
            $this->update($tableName, ['child' => '/article/like/admin/*'], ['parent' => 'articleModLevelModerator', 'child' => '/article/o/like/*']);
            $this->update($tableName, ['child' => '/article/download/history/*'], ['parent' => 'articleModLevelModerator', 'child' => '/article/history/download/*']);
            $this->update($tableName, ['child' => '/article/view/history/*'], ['parent' => 'articleModLevelModerator', 'child' => '/article/history/view/*']);
            $this->update($tableName, ['child' => '/article/like/history/*'], ['parent' => 'articleModLevelModerator', 'child' => '/article/history/like/*']);
		}
	}
}
