<?php
/**
 * Article Views (article-views)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\o\ViewController
 * @var $model ommu\article\models\ArticleViews
 * @var $searchModel ommu\article\models\search\ArticleViews
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 October 2017, 15:56 WIB
 * @modified date 13 May 2019, 18:28 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\DetailView;
use ommu\article\models\Articles;
use ommu\users\models\Users;

$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="article-views-manage">
<?php Pjax::begin(); ?>

<?php if($article != null) {
$model = $article;
echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'categoryName',
			'value' => function ($model) {
				$categoryName = isset($model->category) ? $model->category->title->message : '-';
				if($categoryName != '-')
					return Html::a($categoryName, ['setting/category/view', 'id'=>$model->cat_id], ['title'=>$categoryName, 'class'=>'modal-btn']);
				return $categoryName;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'title',
			'value' => function ($model) {
				if($model->title != '')
					return Html::a($model->title, ['admin/view', 'id'=>$model->id], ['title'=>$model->title, 'class'=>'modal-btn']);
				return $model->title;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'published_date',
			'value' => Yii::$app->formatter->asDate($model->published_date, 'medium'),
		],
		[
			'attribute' => 'headline_date',
			'value' => Yii::$app->formatter->asDatetime($model->headline_date, 'medium'),
		],
	],
]);
}?>

<?php if($user != null) {
$model = $user;
echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'enabled',
			'value' => Users::getEnabled($model->enabled),
		],
		[
			'attribute' => 'verified',
			'value' => $model->verified == 1 ? Yii::t('app', 'Verified') : Yii::t('app', 'Unverified'),
		],
		[
			'attribute' => 'levelName',
			'value' => isset($model->level) ? $model->level->title->message : '-',
		],
		'email:email',
		[
			'attribute' => 'lastlogin_date',
			'value' => Yii::$app->formatter->asDatetime($model->lastlogin_date, 'medium'),
		],
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
	'buttons' => [
		'view' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title'=>Yii::t('app', 'Detail')]);
		},
		'update' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title'=>Yii::t('app', 'Update')]);
		},
		'delete' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete'),
				'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				'data-method'  => 'post',
			]);
		},
	],
	'template' => '{view} {delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>