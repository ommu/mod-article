<?php
/**
 * Article Media (article-media)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\o\ImageController
 * @var $model ommu\article\models\ArticleMedia
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 11:00 WIB
 * @modified date 17 May 2019, 11:45 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\article\models\Articles;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Photos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->media_filename;

if(!$small) {
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail Photo'), 'url' => Url::to(['view', 'id'=>$model->id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
	['label' => Yii::t('app', 'Update Photo'), 'url' => Url::to(['update', 'id'=>$model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete Photo'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<div class="article-media-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'cover',
		'value' => $model->quickAction(Url::to(['cover', 'id'=>$model->primaryKey]), $model->cover, 'Yes,No', true),
		'format' => 'raw',
	],
	'orders',
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
		'attribute' => 'media_filename',
		'value' => function ($model) {
			$uploadPath = join('/', [Articles::getUploadPath(false), $model->article_id]);
			return $model->media_filename ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->media_filename])), ['alt'=>$model->media_filename, 'class'=>'mb-3']).'<br/>'.$model->media_filename : '-';
		},
		'format' => 'html',
	],
	'caption',
	[
		'attribute' => 'description',
		'value' => $model->description ? $model->description : '-',
		'format' => 'html',
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
	],
	[
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
	],
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