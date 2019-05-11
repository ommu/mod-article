<?php
namespace ommu\article\controllers\v1;

use app\components\api\ActiveController;
use ommu\article\models\ArticleCategory;

class CategoryController extends ActiveController
{
	public static $authType = 2;
	public $modelClass = 'ommu\article\models\ArticleCategory';
}