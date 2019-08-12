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

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Like Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->like->article->title;

if(!$small) {
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
	],
	[
		'attribute' => 'likeArticleId',
		'value' => function ($model) {
			$likeArticleId = isset($model->like) ? $model->like->article->title : '-';
			if($likeArticleId != '-')
				return Html::a($likeArticleId, ['o/like/view', 'id'=>$model->like_id], ['title'=>$likeArticleId, 'class'=>'modal-btn']);
			return $likeArticleId;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'likes_date',
		'value' => Yii::$app->formatter->asDatetime($model->likes_date, 'medium'),
	],
	'likes_ip',
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