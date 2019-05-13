<?php
/**
 * Article Like Histories (article-like-history)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\history\LikeController
 * @var $model ommu\article\models\ArticleLikeHistory
 * @var $searchModel ommu\article\models\search\ArticleLikeHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:59 WIB
 * @modified date 13 May 2019, 17:13 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\DetailView;
use ommu\article\models\ArticleLikes;

$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="article-like-history-manage">
<?php Pjax::begin(); ?>

<?php if($like != null) {
$model = $like;
echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'articleTitle',
			'value' => function ($model) {
				$articleTitle = isset($model->article) ? $model->article->title : '-';
				if($articleTitle != '-')
					return Html::a($articleTitle, ['admin/view', 'id'=>$model->article_id], ['title'=>$articleTitle, 'class'=>'modal-btn']);
				return $articleTitle;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'userDisplayname',
			'value' => isset($model->user) ? $model->user->displayname : '-',
		],
		[
			'attribute' => 'likes_date',
			'value' => Yii::$app->formatter->asDatetime($model->likes_date, 'medium'),
		],
		'likes_ip',
	],
]);
}?>

<?php //echo $this->render('_search', ['model'=>$searchModel]); ?>

<?php echo $this->render('_option_form', ['model'=>$searchModel, 'gridColumns'=>$searchModel->activeDefaultColumns($columns), 'route'=>$this->context->route]); ?>

<?php 
$columnData = $columns;
array_push($columnData, [
	'class' => 'app\components\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'urlCreator' => function($action, $model, $key, $index) {
		if($action == 'view')
			return Url::to(['view', 'id'=>$key]);
		if($action == 'update')
			return Url::to(['update', 'id'=>$key]);
		if($action == 'delete')
			return Url::to(['delete', 'id'=>$key]);
	},
	'template' => '{view} {delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>