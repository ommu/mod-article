<?php
/**
 * Article Files (article-files)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\o\FileController
 * @var $model ommu\article\models\ArticleFiles
 * @var $searchModel ommu\article\models\search\ArticleFiles
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 11:09 WIB
 * @modified date 17 May 2019, 11:45 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;
use devgroup\dropzone\DropZone;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Article'), 'url' => ['admin/index']];
if ($article != null) {
    $this->params['breadcrumbs'][] = ['label' => $article->title, 'url' => ['admin/view', 'id' => $article->id]];
}
$this->params['breadcrumbs'][] = $this->title;

if (($id = Yii::$app->request->get('article')) != null) {
	$this->params['menu']['content'] = [
		['label' => Yii::t('app', 'Add Document'), 'url' => Url::to(['create', 'id' => $id]), 'icon' => 'plus-square', 'htmlOptions' => ['class' => 'btn btn-success']],
	];
}
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="article-files-manage">
<?php Pjax::begin(); ?>

<?php if ($article != null) {
    echo $this->render('/admin/admin_view', ['model' => $article, 'small' => true]);
} ?>

<?php if ($id != null) {
	echo DropZone::widget([
		'name' => 'file_filename',
		'url' => Url::to(['o/file/upload', 'id' => $id]),
		'htmlOptions' => ['class' => 'mb-4'],
		'message' => Yii::t('app', 'Drop documents here to upload'),
	]);
} ?>

<?php //echo $this->render('_search', ['model' => $searchModel]); ?>

<?php echo $this->render('_option_form', ['model' => $searchModel, 'gridColumns' => $searchModel->activeDefaultColumns($columns), 'route' => $this->context->route]); ?>

<?php
$columnData = $columns;
array_push($columnData, [
	'class' => 'app\components\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'urlCreator' => function($action, $model, $key, $index) {
        if ($action == 'view') {
            return Url::to(['view', 'id' => $key]);
        }
        if ($action == 'update') {
            return Url::to(['update', 'id' => $key]);
        }
        if ($action == 'delete') {
            return Url::to(['delete', 'id' => $key]);
        }
	},
	'buttons' => [
		'view' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'Detail'), 'data-pjax' => 0]);
		},
		'update' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update'), 'data-pjax' => 0]);
		},
		'delete' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete'),
				'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				'data-method'  => 'post',
			]);
		},
	],
	'template' => '{view} {update} {delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>