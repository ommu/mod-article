<?php
/**
 * Article Download Histories (article-download-history)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\history\DownloadController
 * @var $model ommu\article\models\ArticleDownloadHistory
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 13 May 2019, 09:42 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if(!$small) {
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Download Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->download->file->file_filename;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
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
			$articleTitle = isset($model->download->file->article) ? $model->download->file->article->title : '-';
			if($articleTitle != '-')
				return Html::a($articleTitle, ['admin/view', 'id'=>$model->download->file->article_id], ['title'=>$articleTitle, 'class'=>'modal-btn']);
			return $articleTitle;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'fileName',
		'value' => function ($model) {
			$fileName = isset($model->download->file) ? $model->download->file->file_filename : '-';
			if($fileName != '-')
				return Html::a($fileName, ['o/file/view', 'id'=>$model->download->file_id], ['title'=>$fileName, 'class'=>'modal-btn']);
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
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>