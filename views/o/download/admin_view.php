<?php
/**
 * Article Downloads (article-downloads)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\o\DownloadController
 * @var $model ommu\article\models\ArticleDownloads
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 11:14 WIB
 * @modified date 13 May 2019, 09:43 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Article'), 'url' => ['admin/index']];
    $this->params['breadcrumbs'][] = ['label' => $model->file->article->title, 'url' => ['admin/view', 'id' => $model->file->article_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Document'), 'url' => ['o/file/manage', 'article' => $model->file->article_id]];
    $this->params['breadcrumbs'][] = ['label' => $model->file->file_filename, 'url' => ['o/file/view', 'id' => $model->file_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Download'), 'url' => ['manage', 'file' => $model->file_id]];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Detail');

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="article-downloads-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'articleTitle',
		'value' => function ($model) {
			$articleTitle = isset($model->file->article) ? $model->file->article->title : '-';
            if ($articleTitle != '-') {
                return Html::a($articleTitle, ['admin/view', 'id' => $model->file->article_id], ['title' => $articleTitle, 'class' => 'modal-btn']);
            }
			return $articleTitle;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'fileFilename',
		'value' => function ($model) {
			$fileFilename = isset($model->file) ? $model->file->file_filename : '-';
            if ($fileFilename != '-') {
                return Html::a($fileFilename, ['o/file/view', 'id' => $model->file_id], ['title' => $fileFilename, 'class' => 'modal-btn']);
            }
			return $fileFilename;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'userDisplayname',
		'value' => isset($model->user) ? $model->user->displayname : '-',
	],
	[
		'attribute' => 'downloads',
		'value' => function ($model) {
			$downloads = $model->downloads;
			return Html::a($downloads, ['history/download/manage', 'download' => $model->primaryKey], ['title' => Yii::t('app', '{count} histories', ['count' => $downloads])]);
		},
		'format' => 'html',
	],
	[
		'attribute' => 'download_date',
		'value' => Yii::$app->formatter->asDatetime($model->download_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'download_ip',
		'value' => $model->download_ip,
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