<?php
/**
 * Article Like Histories (article-like-history)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\history\LikeController
 * @var $model ommu\article\models\ArticleLikeHistory
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 13 May 2019, 17:13 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if(!$small) {
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Like Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->like->article->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<div class="article-like-history-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->filterYesNo($model->publish),
		'visible' => !$small,
	],
	[
		'attribute' => 'articleTitle',
		'value' => function ($model) {
			$articleTitle = isset($model->like->article) ? $model->like->article->title : '-';
			if($articleTitle != '-')
				return Html::a($articleTitle, ['admin/view', 'id'=>$model->like->article_id], ['title'=>$articleTitle, 'class'=>'modal-btn']);
			return $articleTitle;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'view.user_id',
		'value' => isset($model->like->user) ? $model->like->user->displayname : '-',
	],
	[
		'attribute' => 'likes_date',
		'value' => Yii::$app->formatter->asDatetime($model->likes_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'likes_ip',
		'value' => $model->likes_ip,
		'visible' => !$small,
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