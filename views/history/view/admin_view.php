<?php
/**
 * Article View Histories (article-view-history)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\history\ViewController
 * @var $model ommu\article\models\ArticleViewHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 13 May 2019, 18:27 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'View Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->view->article->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="article-view-history-view">

<?php
$attributes = [
	'id',
	[
		'attribute' => 'viewArticleId',
		'value' => function ($model) {
			$viewArticleId = isset($model->view) ? $model->view->article->title : '-';
			if($viewArticleId != '-')
				return Html::a($viewArticleId, ['o/view/view', 'id'=>$model->view_id], ['title'=>$viewArticleId, 'class'=>'modal-btn']);
			return $viewArticleId;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'view_date',
		'value' => Yii::$app->formatter->asDatetime($model->view_date, 'medium'),
	],
	'view_ip',
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id'=>$model->id], ['title'=>Yii::t('app', 'Update'), 'class'=>'btn btn-primary']),
		'format' => 'html',
		'visible' => Yii::$app->request->isAjax ? true : false,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>