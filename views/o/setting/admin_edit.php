<?php
/**
 * Article Settings (article-setting)
 * @var $this SettingController
 * @var $model ArticleSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @modified date 23 March 2018, 16:01 WIB
 * @link https://github.com/ommu/ommu-article
 *
 */

	$this->breadcrumbs=array(
		'Article Settings'=>array('manage'),
		$model->id=>array('view','id'=>$model->id),
		'Update',
	);
?>

<div id="partial-article-category">
	<?php //begin.Messages ?>
	<div id="ajax-message">
	<?php
	if(Yii::app()->user->hasFlash('error'))
		echo Utility::flashError(Yii::app()->user->getFlash('error'));
	if(Yii::app()->user->hasFlash('success'))
		echo Utility::flashSuccess(Yii::app()->user->getFlash('success'));
	?>
	</div>
	<?php //begin.Messages ?>

	<div class="boxed">
		<h3><?php echo Yii::t('phrase', 'Article Categories'); ?></h3>
		<?php //begin.Grid Item ?>
		<?php 
			$columnData   = $columns;
			array_push($columnData, array(
				'header' => Yii::t('phrase', 'Options'),
				'class'=>'CButtonColumn',
				'buttons' => array(
					'view' => array(
						'label' => Yii::t('phrase', 'Detail Article Category'),
						'imageUrl' => false,
						'options' => array(
							'class' => 'view',
						),
						'url' => 'Yii::app()->controller->createUrl(\'o/category/view\',array(\'id\'=>$data->primaryKey))'),
					'update' => array(
						'label' => Yii::t('phrase', 'Update Article Category'),
						'imageUrl' => false,
						'options' => array(
							'class' => 'update',
						),
						'url' => 'Yii::app()->controller->createUrl(\'o/category/edit\',array(\'id\'=>$data->primaryKey))'),
					'delete' => array(
						'label' => Yii::t('phrase', 'Delete Article Category'),
						'imageUrl' => false,
						'options' => array(
							'class' => 'delete',
						),
						'url' => 'Yii::app()->controller->createUrl(\'o/category/delete\',array(\'id\'=>$data->primaryKey))'),
				),
				'template' => '{view}|{update}|{delete}',
			));

			$this->widget('application.libraries.core.components.system.OGridView', array(
				'id'=>'article-category-grid',
				'dataProvider'=>$category->search(),
				'filter'=>$category,
				'columns' => $columnData,
				'pager' => array('header' => ''),
			));
		?>
		<?php //end.Grid Item ?>
	</div>
</div>

<div class="form" name="post-on">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'article'=>$article,
	)); ?>
</div>
