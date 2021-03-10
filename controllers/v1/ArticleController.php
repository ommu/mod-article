<?php
namespace ommu\article\controllers\v1;

use app\components\api\ActiveController;
use ommu\article\models\Articles;
use Yii;

class ArticleController extends ActiveController
{
	use \app\components\api\TraitController;

	public $modelClass = 'ommu\article\models\Articles';
	// Gunakan authentikasi menggunakan bearerAuth JWT
	public static $authType = 2;

	// sembunyikan kolom quote dan headline_date
	public function fields() {
		$fields = parent::fields();
		$this->deleteArray($fields, 'quote');
		// unset($fields['quote'], $fields['headline_date']);
		Yii::error('### fields: ' . print_r($fields, true));
		return $fields;
	}
}
