<?php
/**
 * Article Likes (article-likes)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\o\LikeController
 * @var $model ommu\article\models\ArticleLikes
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 11:11 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Article Likes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->like_id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->like_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
?>

<?php
$attributes = [
	'like_id',
	[
		'attribute' => 'publish',
		'value' => $model->publish == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
	],
	[
		'attribute' => 'article_search',
		'value' => $model->article->title,
	],
	[
		'attribute' => 'user_search',
		'value' => $model->user_id ? $model->user->displayname : '-',
	],
	[
		'attribute' => 'likes_date',
		'value' => !in_array($model->likes_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->likes_date, 'datetime') : '-',
	],
	'likes_ip',
	[
		'attribute' => 'updated_date',
		'value' => !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-',
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>