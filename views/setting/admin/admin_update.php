<?php
/**
 * Article Settings (article-setting)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\setting\AdminController
 * @var $model ommu\article\models\ArticleSetting
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 09:34 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\MenuContent;
use app\components\widgets\MenuOption;
use app\components\grid\GridView;
use yii\widgets\Pjax;

$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Add Category'), 'url' => Url::to(['category/create']), 'icon' => 'plus-square'],
];
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>


<?php Pjax::begin(); ?>
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
			<?php echo $this->description != '' ? "<p class=\"text-muted font-13 m-b-30\">$this->description</p>" : '';?>

			<?php //echo $this->render('_search', ['model'=>$searchModel]); ?>

			<?php echo $this->render('_option_form', ['model'=>$searchModel, 'gridColumns'=>$searchModel->activeDefaultColumns($columns), 'route'=>$this->context->route]); ?>

			<?php 
			$columnData = $columns;
			array_push($columnData, [
				'class' => 'app\components\grid\ActionColumn',
				'header' => Yii::t('app', 'Option'),
				'buttons' => [
					'view' => function ($url, $model, $key) {
						$url = Url::to(['category/view', 'id'=>$model->primaryKey]);
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'View Article Category')]);
					},
					'update' => function ($url, $model, $key) {
						$url = Url::to(['category/update', 'id'=>$model->primaryKey]);
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update Article Category')]);
					},
					'delete' => function ($url, $model, $key) {
						$url = Url::to(['category/delete', 'id'=>$model->primaryKey]);
						return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
							'title' => Yii::t('app', 'Delete Article Category'),
							'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
							'data-method' => 'post',
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
		</div>
	</div>
</div>
<?php Pjax::end(); ?>
<div class="col-md-12 col-sm-12 col-xs-12">
	<?php if(Yii::$app->session->hasFlash('success'))
		echo $this->flashMessage(Yii::$app->session->getFlash('success'));
	else if(Yii::$app->session->hasFlash('error'))
		echo $this->flashMessage(Yii::$app->session->getFlash('error'), 'danger');?>
	<div class="x_panel">
		<div class="x_content">
			<?php echo $this->render('_form', [
				'model' => $model,
			]); ?>
		</div>
	</div>
	
</div>