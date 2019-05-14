<?php
/**
 * Articles (articles)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\AdminController
 * @var $model ommu\article\models\Articles
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 09:33 WIB
 * @modified date 13 May 2019, 21:24 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="articles-view">

<?php
$attributes = [
	'id',
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
		'format' => 'raw',
	],
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
	'title',
	[
		'attribute' => 'body',
		'value' => $model->body ? $model->body : '-',
		'format' => 'html',
	],
	[
		'attribute' => 'published_date',
		'value' => Yii::$app->formatter->asDatetime($model->published_date, 'medium'),
	],
	[
		'attribute' => 'headline',
		'value' => $model->quickAction(Url::to(['headline', 'id'=>$model->primaryKey]), $model->headline, 'Headline,Unheadline'),
		'format' => 'raw',
	],
	[
		'attribute' => 'headline_date',
		'value' => Yii::$app->formatter->asDatetime($model->headline_date, 'medium'),
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
		'attribute' => 'files',
		'value' => function ($model) {
			$files = $model->getFiles(true);
			return Html::a($files, ['o/file/manage', 'article'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} files', ['count'=>$files])]);
		},
		'format' => 'html',
	],
	[
		'attribute' => 'likes',
		'value' => function ($model) {
			$likes = $model->getLikes(true);
			return Html::a($likes, ['o/like/manage', 'article'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} likes', ['count'=>$likes])]);
		},
		'format' => 'html',
	],
	[
		'attribute' => 'media',
		'value' => function ($model) {
			$media = $model->getMedia(true);
			return Html::a($media, ['o/media/manage', 'article'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} media', ['count'=>$media])]);
		},
		'format' => 'html',
	],
	[
		'attribute' => 'tags',
		'value' => function ($model) {
			$tags = $model->getTags('count');
			return Html::a($tags, ['o/tag/manage', 'article'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} tags', ['count'=>$tags])]);
		},
		'format' => 'html',
	],
	[
		'attribute' => 'views',
		'value' => function ($model) {
			$views = $model->getViews(true);
			return Html::a($views, ['o/view/manage', 'article'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} views', ['count'=>$views])]);
		},
		'format' => 'html',
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