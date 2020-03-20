<?php
/**
 * Article Settings (article-setting)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\setting\AdminController
 * @var $model ommu\article\models\ArticleSetting
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 11 May 2019, 23:45 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if(!$small) {
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Reset'), 'url' => Url::to(['delete']), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to reset this setting?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<div class="article-setting-view">

<?php 
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'license',
		'value' => $model->license,
		'visible' => !$small,
	],
	[
		'attribute' => 'permission',
		'value' => $model::getPermission($model->permission),
	],
	[
		'attribute' => 'meta_description',
		'value' => $model->meta_description ? $model->meta_description : '-',
	],
	[
		'attribute' => 'meta_keyword',
		'value' => $model->meta_keyword ? $model->meta_keyword : '-',
	],
	[
		'attribute' => 'headline',
		'value' => $model::getHeadline($model->headline),
	],
	'headline_limit',
	[
		'attribute' => 'headline_category',
		'value' => serialize($model->headline_category),
	],
	'media_image_limit',
	[
		'attribute' => 'media_image_resize',
		'value' => $model::getMediaImageResize($model->media_image_resize),
	],
	[
		'attribute' => 'media_image_resize_size',
		'value' => $model::getSize($model->media_image_resize_size),
	],
	[
		'attribute' => 'media_image_view_size',
		'value' => $model::parseImageViewSize($model->media_image_view_size),
		'format' => 'html',
	],
	[
		'attribute' => 'media_image_type',
		'value' => $model->media_image_type,
	],
	'media_file_limit',
	[
		'attribute' => 'media_file_type',
		'value' => $model->media_file_type,
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), Url::to(['update']), [
			'class' => 'btn btn-primary',
		]),
		'format' => 'html',
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