<?php
/**
 * Articles (articles)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\AdminController
 * @var $model ommu\article\models\Articles
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 09:33 WIB
 * @link https://github.com/ommu/mod-article
 *
 */


use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\MenuOption;
use app\components\grid\GridView;
use yii\widgets\Pjax;


$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id'=>$model->article_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->article_id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->article_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];

?>

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

<!-- kolom media -->

<?php Pjax::begin(); ?>
<?php
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage Articles' ), 'url' => Url::to(['/article/admin']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Add Media'), 'url' => !Yii::$app->request->get('article') ? Url::to(['/article/media/create']) : Url::to(['/article/media/create', 'article' => Yii::$app->request->get('article')]), 'icon' => 'plus-square'],
];
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="col-md-12 col-sm-12 col-xs-12">
	<?php if(Yii::$app->session->hasFlash('success'))
		echo $this->flashMessage(Yii::$app->session->getFlash('success'));
	else if(Yii::$app->session->hasFlash('error'))
		echo $this->flashMessage(Yii::$app->session->getFlash('error'), 'danger');?>

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
						$url = Url::to(['view', 'id'=>$model->primaryKey]);
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'View Article Media')]);
					},
					'update' => function ($url, $model, $key) {
						$url = Url::to(['/article/media/update', 'id'=>$model->primaryKey]);
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update Article Media')]);
					},
					'delete' => function ($url, $model, $key) {
						$url = Url::to(['/article/media/delete', 'id'=>$model->primaryKey]);
						return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
							'title' => Yii::t('app', 'Delete Article Media'),
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

<!-- kolom file -->
<?php
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage Articles' ), 'url' => Url::to(['/article/admin']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Add File'), 'url' => !Yii::$app->request->get('article') ? Url::to(['/article/file/create']) : Url::to(['/article/file/create', 'article' => Yii::$app->request->get('article')]), 'icon' => 'plus-square'],
];
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="col-md-12 col-sm-12 col-xs-12">
	<?php if(Yii::$app->session->hasFlash('success'))
		echo $this->flashMessage(Yii::$app->session->getFlash('success'));
	else if(Yii::$app->session->hasFlash('error'))
		echo $this->flashMessage(Yii::$app->session->getFlash('error'), 'danger');?>

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

			<?php echo $this->render('_option_form', ['model'=>$searchModel1, 'gridColumns'=>$this->activeDefaultColumns($columns1), 'route'=>$this->context->route]); ?>

			<?php 
			$columnData1 = $columns1;
			array_push($columnData1, [
				'class' => 'app\components\grid\ActionColumn',
				'header' => Yii::t('app', 'Option'),
				'buttons' => [
					'view' => function ($url, $model, $key) {
						$url = Url::to(['view', 'id'=>$model->primaryKey]);
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'View Article Files')]);
					},
					'update' => function ($url, $model, $key) {
						$url = Url::to(['/article/file/update', 'id'=>$model->primaryKey]);
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update Article Files')]);
					},
					'delete' => function ($url, $model, $key) {
						$url = Url::to(['/article/file/delete', 'id'=>$model->primaryKey]);
						return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
							'title' => Yii::t('app', 'Delete Article Files'),
							'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
							'data-method' => 'post',
						]);
					},
				],
				'template' => '{view} {update} {delete}',
			]);
			
			echo GridView::widget([
				'dataProvider' => $dataProvider1,
				'filterModel' => $searchModel1,
				'columns' => $columnData1,
			]); ?>
		</div>
	</div>
</div>
<?php Pjax::end(); ?>
