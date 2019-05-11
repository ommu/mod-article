<?php
/**
 * Article Categories (article-category)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\setting\CategoryController
 * @var $model ommu\article\models\ArticleCategory
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 09:35 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Article Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id'=>$model->cat_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->cat_id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->cat_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
?>

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>