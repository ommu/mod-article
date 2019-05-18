<?php
/**
 * Article Downloads (article-downloads)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\o\DownloadController
 * @var $model ommu\article\models\ArticleDownloads
 * @var $searchModel ommu\article\models\search\ArticleDownloads
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 11:14 WIB
 * @modified date 13 May 2019, 09:43 WIB
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

<div class="article-downloads-manage">
<?php Pjax::begin(); ?>

<?php if($file != null) {
$model = $file;
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
			'attribute' => 'file_filename',
			'value' => function ($model) {
				$uploadPath = Articles::getUploadPath(false);
				return $model->file_filename ? Html::a($model->file_filename, Url::to(join('/', ['@webpublic', $uploadPath, $model->file_filename])), ['width' => '100%']).'<br/><br/>'.$model->file_filename : '-';
			},
			'format' => 'html',
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
	'template' => '{view} {delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>