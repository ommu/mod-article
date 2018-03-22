<?php
/**
 * Article Category (article-category)
 * @var $this CategoryController
 * @var $model ArticleCategory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-article
 *
 */
?>

<?php $form=$this->beginWidget('application.libraries.core.components.system.OActiveForm', array(
	'id'=>'article-category-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<div class="dialog-content">

	<fieldset>
	
		<?php //begin.Messages ?>
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>
		<?php //begin.Messages ?>
		
		<div class="form-group row">
			<?php echo $form->labelEx($model, 'parent', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php 
				$category = ArticleCategory::getCategory();
				if($category != null)
					echo $form->dropDownList($model, 'parent', $category, array('prompt'=>Yii::t('phrase', 'No Parent'), 'class'=>'form-control'));
				else
					echo $form->dropDownList($model, 'parent', array(0=>Yii::t('phrase', 'No Parent')), array('class'=>'form-control'));?>
				<?php echo $form->error($model, 'parent'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'name_i', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textField($model, 'name_i', array('maxlength'=>32,'class'=>'form-control')); ?>
				<?php echo $form->error($model, 'name_i'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'desc_i', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textArea($model, 'desc_i', array('maxlength'=>128,'class'=>'form-control')); ?>
				<?php echo $form->error($model, 'desc_i'); ?>
			</div>
		</div>

		<div class="form-group row publish">
			<?php echo $form->labelEx($model, 'single_photo', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->checkBox($model, 'single_photo', array('class'=>'form-control')); ?>
				<?php echo $form->labelEx($model, 'single_photo'); ?>
				<?php echo $form->error($model, 'single_photo'); ?>
			</div>
		</div>

		<div class="form-group row publish">
			<?php echo $form->labelEx($model, 'single_file', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->checkBox($model, 'single_file', array('class'=>'form-control')); ?>
				<?php echo $form->labelEx($model, 'single_file'); ?>
				<?php echo $form->error($model, 'single_file'); ?>
			</div>
		</div>

		<div class="form-group row publish">
			<?php echo $form->labelEx($model, 'publish', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->checkBox($model, 'publish', array('class'=>'form-control')); ?>
				<?php echo $form->labelEx($model, 'publish'); ?>
				<?php echo $form->error($model, 'publish'); ?>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') , array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>

