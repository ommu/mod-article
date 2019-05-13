<?php
/**
 * Article Download Histories (article-download-history)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\history\DownloadController
 * @var $model ommu\article\models\ArticleDownloadHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 13 May 2019, 09:42 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Download Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->download->file->file_filename;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="article-download-history-view">

<?php
$attributes = [
	'id',
	[
		'attribute' => 'downloadFileId',
		'value' => function ($model) {
			$downloadFileId = isset($model->download) ? $model->download->file->file_filename : '-';
			if($downloadFileId != '-')
				return Html::a($downloadFileId, ['o/download/view', 'id'=>$model->download_id], ['title'=>$downloadFileId, 'class'=>'modal-btn']);
			return $downloadFileId;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'download_date',
		'value' => Yii::$app->formatter->asDatetime($model->download_date, 'medium'),
	],
	'download_ip',
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