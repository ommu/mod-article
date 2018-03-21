<?php
/**
 * ArticleMedia (article-media)
 * @var $this MediaController
 * @var $model ArticleMedia
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
	'id'=>'article-media-form',
	'enableAjaxValidation'=>true,
	'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

	<?php //begin.Messages ?>
	<div id="ajax-message">
		<?php
		echo $form->errorSummary($model);
		if(Yii::app()->user->hasFlash('error'))
			echo Utility::flashError(Yii::app()->user->getFlash('error'));
		if(Yii::app()->user->hasFlash('success'))
			echo Utility::flashSuccess(Yii::app()->user->getFlash('success'));
		?>
	</div>
	<?php //begin.Messages ?>

	<fieldset>
	
		<?php if(!$model->isNewRecord) {?>		
		<div class="form-group row">
			<?php echo $form->labelEx($model,'old_media_input', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php 
				if(!$model->getErrors())
					$model->old_media_input = $model->media;
				echo $form->hiddenField($model,'old_media_input');
				if($model->article->article_type == 'standard') {
					$media = Yii::app()->request->baseUrl.'/public/article/'.$model->article_id.'/'.$model->old_media_input;?>
					<img src="<?php echo Utility::getTimThumb($media, 400, 400, 3);?>" alt="">
				<?php } else if($model->article->article_type == 'video') {?>
					<iframe width="320" height="200" src="//www.youtube.com/embed/<?php echo $model->old_media_input;?>" frameborder="0" allowfullscreen></iframe>
				<?php }?>
			</div>
		</div>
		<?php }?>

		<?php if($model->article->article_type == 'standard') {?>
			<div class="form-group row">
				<?php echo $form->labelEx($model,'media', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
				<div class="col-lg-8 col-md-9 col-sm-12">
					<?php echo $form->fileField($model,'media',array('maxlength'=>64, 'class'=>'form-control')); ?>
					<?php echo $form->error($model,'media'); ?>
					<span class="small-px">extensions are allowed: <?php echo Utility::formatFileType($media_image_type, false);?></span>
				</div>
			</div>
			
		<?php }
			if($model->article->article_type == 'video') {?>
			<div class="form-group row">
				<?php echo $form->labelEx($model,'video_input', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
				<div class="col-lg-8 col-md-9 col-sm-12">
					<?php
					if(!$model->getErrors())
						$model->video_input = $model->media;
					echo $form->textField($model,'video_input',array('maxlength'=>32, 'class'=>'form-control')); ?>
					<?php echo $form->error($model,'video_input'); ?>
					<span class="small-px">http://www.youtube.com/watch?v=<strong>HOAqSoDZSho</strong></span>
				</div>
			</div>			
		<?php }?>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'caption', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textArea($model,'caption',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
				<?php echo $form->error($model,'caption'); ?>
			</div>
		</div>

		<div class="form-group row publish">
			<?php echo $form->labelEx($model,'cover', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->checkBox($model,'cover', array('class'=>'form-control')); ?>
				<?php echo $form->labelEx($model,'cover'); ?>
				<?php echo $form->error($model,'cover'); ?>
			</div>
		</div>

		<div class="form-group row publish">
			<?php echo $form->labelEx($model,'publish', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->checkBox($model,'publish', array('class'=>'form-control')); ?>
				<?php echo $form->labelEx($model,'publish'); ?>
				<?php echo $form->error($model,'publish'); ?>
			</div>
		</div>

		<div class="form-group row submit">
			<label class="col-form-label col-lg-4 col-md-3 col-sm-12">&nbsp;</label>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
			</div>
		</div>

	</fieldset>
<?php $this->endWidget(); ?>
