<?php
/**
 * Article Settings (article-setting)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\setting\AdminController
 * @var $model ommu\article\models\ArticleSetting
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 11 May 2019, 23:45 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;
use app\components\widgets\MenuContent;
use app\components\widgets\MenuOption;
use yii\widgets\DetailView;
use ommu\article\models\ArticleSetting;

$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Add Category'), 'url' => Url::to(['setting/category/create']), 'htmlOptions' => ['class'=>'modal-btn'], 'icon' => 'plus-square', 'htmlOptions' => ['class'=>'btn btn-success']],
];
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<?php if($this->params['menu']['content']):
				echo MenuContent::widget(['items' => $this->params['menu']['content']]);
				endif;?>
				<ul class="nav navbar-right panel_toolbox">
					<li><a href="#" title="<?php echo Yii::t('app', 'Toggle');?>" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
					<?php if($this->params['menu']['option']):?>
					<li class="dropdown">
						<a href="#" title="<?php echo Yii::t('app', 'Options');?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
						<?php echo MenuOption::widget(['items' => $this->params['menu']['option']]);?>
					</li>
					<?php endif;?>
					<li><a href="#" title="<?php echo Yii::t('app', 'Close');?>" class="close-link"><i class="fa fa-close"></i></a></li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
<?php Pjax::begin(); ?>

<?php //echo $this->render('/setting/category/_search', ['model'=>$searchModel]); ?>

<?php echo $this->render('/setting/category/_option_form', ['model'=>$searchModel, 'gridColumns'=>$searchModel->activeDefaultColumns($columns), 'route'=>$this->context->route]); ?>

<?php
$columnData = $columns;
array_push($columnData, [
	'class' => 'app\components\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'urlCreator' => function($action, $model, $key, $index) {
		if($action == 'view')
			return Url::to(['setting/category/view', 'id'=>$key]);
		if($action == 'update')
			return Url::to(['setting/category/update', 'id'=>$key]);
		if($action == 'delete')
			return Url::to(['setting/category/delete', 'id'=>$key]);
	},
	'buttons' => [
		'view' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'Detail')]);
		},
		'update' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update')]);
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
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<?php echo \app\components\widgets\Alert::widget(['closeButton'=>false]); ?>

		<div class="x_panel">
			<div class="x_title">
				<ul class="nav navbar-right panel_toolbox">
					<li><a href="#" title="<?php echo Yii::t('app', 'Toggle');?>" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
					<li><a href="#" title="<?php echo Yii::t('app', 'Close');?>" class="close-link"><i class="fa fa-close"></i></a></li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
<div class="article-setting-index">
<?php if(!$model->isNewRecord) {
	echo DetailView::widget([
		'model' => $model,
		'options' => [
			'class'=>'table table-striped detail-view',
		],
		'attributes' => [
			'id',
			'license',
			[
				'attribute' => 'permission',
				'value' => ArticleSetting::getPermission($model->permission),
			],
			[
				'attribute' => 'meta_description',
				'value' => $model->meta_description ? $model->meta_description : '-',
			],
			[
				'attribute' => 'meta_keyword',
				'value' => $model->meta_keyword ? $model->meta_keyword : '-',
			],
			[
				'attribute' => 'headline',
				'value' => ArticleSetting::getHeadline($model->headline),
			],
			'headline_limit',
			[
				'attribute' => 'headline_category',
				'value' => serialize($model->headline_category),
			],
			'media_image_limit',
			[
				'attribute' => 'media_image_resize',
				'value' => ArticleSetting::getMediaImageResize($model->media_image_resize),
			],
			[
				'attribute' => 'media_image_resize_size',
				'value' => ArticleSetting::getSize($model->media_image_resize_size),
			],
			[
				'attribute' => 'media_image_view_size',
				'value' => ArticleSetting::parseImageViewSize($model->media_image_view_size),
				'format' => 'html',
			],
			[
				'attribute' => 'media_image_type',
				'value' => $model->media_image_type,
			],
			'media_file_limit',
			[
				'attribute' => 'media_file_type',
				'value' => $model->media_file_type,
			],
			[
				'attribute' => 'modified_date',
				'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
			],
			[
				'attribute' => 'modifiedDisplayname',
				'value' => isset($model->modified) ? $model->modified->displayname : '-',
			],
			[
				'attribute' => '',
				'value' => Html::a(Yii::t('app', 'Update'), Url::to(['update']), [
					'class' => 'btn btn-primary',
				]),
				'format' => 'html',
			],
		],
	]);
} else {
	echo $this->render('_form', [
		'model' => $model,
	]);
}?>
</div>
			</div>
		</div>
	</div>
</div>