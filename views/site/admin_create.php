<?php
/**
 * Articles (articles)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\SiteController
 * @var $model ommu\article\models\Articles
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Agus Susilo <smartgdi@gmail.com>
 * @contact (+62) 857-297-29382
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 6 November 2017, 13:54 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>