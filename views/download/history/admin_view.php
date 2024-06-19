<?php
/**
 * Article Download Histories (article-download-history)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\download\HistoryController
 * @var $model ommu\article\models\ArticleDownloadHistory
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 13 May 2019, 09:42 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Article'), 'url' => ['admin/index']];
	$this->params['breadcrumbs'][] = ['label' => $model->download->article->title, 'url' => ['admin/view', 'id' => $model->file->article_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Document'), 'url' => ['o/file/manage', 'article' => $model->file->article_id]];
    $this->params['breadcrumbs'][] = ['label' => $model->file->file_filename, 'url' => ['o/file/view', 'id' => $model->download->file_id]];
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Download'), 'url' => ['download/admin/manage', 'file' => $model->download->file_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'History'), 'url' => ['manage', 'download' => $model->download->file_id]];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Detail');

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="article-download-history-view">

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
			$articleTitle = isset($model->download->article) ? $model->download->article->title : '-';
            if ($articleTitle != '-') {
                return Html::a($articleTitle, ['admin/view', 'id' => $model->file->article_id], ['title' => $articleTitle, 'class' => 'modal-btn']);
            }
			return $articleTitle;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'fileName',
		'value' => function ($model) {
			$fileName = isset($model->file) ? $model->file->file_filename : '-';
            if ($fileName != '-') {
                return Html::a($fileName, ['o/file/view', 'id' => $model->download->file_id], ['title' => $fileName, 'class' => 'modal-btn']);
            }
			return $fileName;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'download.user_id',
		'value' => isset($model->download->user) ? $model->download->user->displayname : '-',
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