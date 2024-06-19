<?php
/**
 * Article Views (article-views)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\view\AdminController
 * @var $model ommu\article\models\ArticleViews
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 23 October 2017, 15:56 WIB
 * @modified date 13 May 2019, 18:28 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Article'), 'url' => ['admin/index']];
    $this->params['breadcrumbs'][] = ['label' => $model->article->title, 'url' => ['admin/view', 'id' => $model->article_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'View'), 'url' => ['manage', 'id' => $model->article_id]];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Detail');

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="article-views-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id' => $model->primaryKey]), $model->publish),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'articleTitle',
		'value' => function ($model) {
			$articleTitle = isset($model->article) ? $model->article->title : '-';
            if ($articleTitle != '-') {
                return Html::a($articleTitle, ['admin/view', 'id' => $model->article_id], ['title' => $articleTitle, 'class' => 'modal-btn']);
            }
			return $articleTitle;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'userDisplayname',
		'value' => isset($model->user) ? $model->user->displayname : '-',
	],
	[
		'attribute' => 'views',
		'value' => function ($model) {
			$views = $model->views;
			return Html::a($views, ['view/history/manage', 'view' => $model->primaryKey], ['title' => Yii::t('app', '{count} histories', ['count' => $views])]);
		},
		'format' => 'html',
	],
	[
		'attribute' => 'view_date',
		'value' => Yii::$app->formatter->asDatetime($model->view_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'view_ip',
		'value' => $model->view_ip,
		'visible' => !$small,
	],
	[
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		'visible' => !$small,
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