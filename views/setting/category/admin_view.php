<?php
/**
 * Article Categories (article-category)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\setting\CategoryController
 * @var $model ommu\article\models\ArticleCategory
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 09:35 WIB
 * @modified date 11 May 2019, 21:30 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $model->title->message;

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id' => $model->id]), 'icon' => 'eye', 'htmlOptions' => ['class' => 'btn btn-info']],
        ['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class' => 'btn btn-primary']],
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="article-category-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id' => $model->primaryKey]), $model->publish, 'Enable,Disable'),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'parent_id',
		'value' => isset($model->parent) ? $model->parent->name_i : '-',
	],
	[
		'attribute' => 'name_i',
		'value' => $model->name_i,
	],
	[
		'attribute' => 'desc_i',
		'value' => $model->desc_i,
	],
	[
		'attribute' => 'single_photo',
		'value' => $model->filterYesNo($model->single_photo),
		'visible' => !$small,
	],
	[
		'attribute' => 'single_file',
		'value' => $model->filterYesNo($model->single_file),
		'visible' => !$small,
	],
	[
		'attribute' => 'articles',
		'value' => function ($model) {
			$articles = $model->getArticles(true);
			return Html::a($articles, ['admin/manage', 'category' => $model->primaryKey, 'status' => 'publish'], ['title' => Yii::t('app', '{count} articles', ['count' => $articles])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'pending',
		'value' => function ($model) {
			$pending = $model->getPending(true);
			return Html::a($pending, ['admin/manage', 'category' => $model->primaryKey, 'status' => 'pending'], ['title' => Yii::t('app', '{count} articles', ['count' => $pending])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'unpublish',
		'value' => function ($model) {
			$unpublish = $model->getArticles(true, 0);
			return Html::a($unpublish, ['admin/manage', 'category' => $model->primaryKey, 'publish' => 0], ['title' => Yii::t('app', '{count} articles', ['count' => $unpublish])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
		'visible' => !$small,
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
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Update'), 'class' => 'btn btn-primary btn-sm']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>