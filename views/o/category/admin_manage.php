<?php
/**
 * Article Categories (article-category)
 * @var $this CategoryController
 * @var $model ArticleCategory
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 22 March 2018, 16:26 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

	$this->breadcrumbs=array(
		'Article Categories'=>array('manage'),
		Yii::t('phrase', 'Manage'),
	);
	$this->menu=array(
		array(
			'label' => Yii::t('phrase', 'Filter'),
			'url' => array('javascript:void(0);'),
			'itemOptions' => array('class' => 'search-button'),
			'linkOptions' => array('title' => Yii::t('phrase', 'Filter')),
		),
		array(
			'label' => Yii::t('phrase', 'Grid Options'),
			'url' => array('javascript:void(0);'),
			'itemOptions' => array('class' => 'grid-button'),
			'linkOptions' => array('title' => Yii::t('phrase', 'Grid Options')),
		),
	);

?>

<?php //begin.Search ?>
<div class="search-form">
<?php $this->renderPartial('_search', array(
	'model'=>$model,
)); ?>
</div>
<?php //end.Search ?>

<?php //begin.Grid Option ?>
<div class="grid-form">
<?php $this->renderPartial('_option_form', array(
	'model'=>$model,
	'gridColumns'=>$this->activeDefaultColumns($columns),
)); ?>
</div>
<?php //end.Grid Option ?>

<div id="partial-article-category">
	<?php //begin.Messages ?>
	<div id="ajax-message">
	<?php
	if(Yii::app()->user->hasFlash('error'))
		echo $this->flashMessage(Yii::app()->user->getFlash('error'), 'error');
	if(Yii::app()->user->hasFlash('success'))
		echo $this->flashMessage(Yii::app()->user->getFlash('success'), 'success');
	?>
	</div>
	<?php //begin.Messages ?>

	<div class="boxed">
		<?php //begin.Grid Item ?>
		<?php 
			$columnData   = $columns;
			array_push($columnData, array(
				'header' => Yii::t('phrase', 'Options'),
				'class' => 'CButtonColumn',
				'buttons' => array(
					'view' => array(
						'label' => Yii::t('phrase', 'Detail Article Category'),
						'imageUrl' => Yii::app()->params['grid-view']['buttonImageUrl'],
						'options' => array(
							'class' => 'view',
						),
						'url' => 'Yii::app()->controller->createUrl(\'view\', array(\'id\'=>$data->primaryKey))'),
					'update' => array(
						'label' => Yii::t('phrase', 'Update Article Category'),
						'imageUrl' => Yii::app()->params['grid-view']['buttonImageUrl'],
						'options' => array(
							'class' => 'update',
						),
						'url' => 'Yii::app()->controller->createUrl(\'edit\', array(\'id\'=>$data->primaryKey))'),
					'delete' => array(
						'label' => Yii::t('phrase', 'Delete Article Category'),
						'imageUrl' => Yii::app()->params['grid-view']['buttonImageUrl'],
						'options' => array(
							'class' => 'delete',
						),
						'url' => 'Yii::app()->controller->createUrl(\'delete\', array(\'id\'=>$data->primaryKey))'),
				),
				'template' => '{view}|{update}|{delete}',
			));

			$this->widget('application.libraries.yii-traits.system.OGridView', array(
				'id'=>'article-category-grid',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>$columnData,
				'template'=>Yii::app()->params['grid-view']['gridTemplate'],
				'pager'=>array('header'=>''),
				'afterAjaxUpdate'=>'reinstallDatePicker',
			));
		?>
		<?php //end.Grid Item ?>
	</div>
</div>