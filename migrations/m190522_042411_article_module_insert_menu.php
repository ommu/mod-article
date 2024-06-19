<?php
/**
 * m190522_042411_article_module_insert_menu
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 22 May 2019, 04:25 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use app\models\Menu;
use mdm\admin\components\Configs;

class m190522_042411_article_module_insert_menu extends \yii\db\Migration
{
	public function up()
	{
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Articles', 'article', null, Menu::getParentId('Publications#rbac'), '/article/admin/index', null, null],
				['Article Settings', 'article', null, Menu::getParentId('Settings#rbac'), '/article/setting/admin/index', null, null],
			]);
		}
	}
}
