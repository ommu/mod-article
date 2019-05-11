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
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'article' => $model->article_id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->article_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
?>

<?php
$attributes = [
	'article_id',
	[
		'attribute' => 'publish',
		'value' => $model->publish == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
	],
	[
		'attribute' => 'category_search',
		'value' => $model->category->title->message,
	],
	'title',
	[
		'attribute' => 'body',
		'value' => $model->body ? $model->body : '-',
		'format' => 'html',
	],
	[
		'attribute' => 'quote',
		'value' => $model->quote ? $model->quote : '-',
		'format' => 'html',
	],
	[
		'attribute' => 'published_date',
		'value' => !in_array($model->published_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? date('d-m-Y H:i:s',strtotime( $model->published_date)) : '-',
	],
	[
		'attribute' => 'headline',
		'value' => $model->headline == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
	],
	[
		'attribute' => 'comment_code',
		'value' => $model->comment_code == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
	],
	[
		'attribute' => 'creation_date',
		'value' => !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? date('d-m-Y H:i:s',strtotime( $model->creation_date)) : '-',

	],
	[
		'attribute' => 'creationDisplayname',
		'value' => $model->creation_id ? $model->creation->displayname : '-',
	],
	[
		'attribute' => 'modified_date',
		'value' => !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-',
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => $model->modified_id ? $model->modified->displayname : '-',
	],
	[
		'attribute' => 'updated_date',
		'value' => !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-',
	],
	[
		'attribute' => 'headline_date',
		'value' => !in_array($model->headline_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->headline_date, 'datetime') : '-',
	],
	[
		'attribute' => 'slug',
		'value' => $model->slug ? $model->slug : '-',
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