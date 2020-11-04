<?php
/**
 * m190522_042401_article_module_insert_menu
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 22 May 2019, 04:25 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use app\models\Menu;

class m190522_042401_article_module_insert_menu extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_menus';
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_menus', ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Articles', 'article', null, Menu::getParentId('Publications#rbac'), '/article/admin/index', null, null],
				['Article Settings', 'article', null, Menu::getParentId('Settings#rbac'), '/article/setting/admin/index', null, null],
			]);
		}
	}
}
