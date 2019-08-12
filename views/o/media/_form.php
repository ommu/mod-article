<?php
/**
 * Article Media (article-media)
 * @var $this MediaController
 * @var $model ArticleMedia
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 24 March 2018, 20:56 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('select#ArticleMedia_media_type_i').on('change', function() {
		var id = $(this).val();
		if(id == '0') {
			$('div#video').slideDown();
		} else {
			$('div#video').slideUp();
		}
	});
EOP;
	$cs->registerScript('js', $js, CClientScript::POS_END);
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'article-media-form',
	'enableAjaxValidation'=>true,
	/*
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	*/
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data',
	),
)); ?>

	<?php //begin.Messages ?>
	<div id="ajax-message">
		<?php echo $form->errorSummary($model);
		if(Yii::app()->user->hasFlash('error'))
			echo $this->flashMessage(Yii::app()->user->getFlash('error'), 'error');
		if(Yii::app()->user->hasFlash('success'))
			echo $this->flashMessage(Yii::app()->user->getFlash('success'), 'success'); ?>
	</div>
	<?php //begin.Messages ?>

	<fieldset>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'media_type_i', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php $media_type_i = array(
					'0'=>Yii::t('phrase', 'Video'),
					'1'=>Yii::t('phrase', 'Photo'),
				);
				if(!$model->getErrors()) {
					$model->media_type_i = 1;
					if(!$model->isNewRecord && $model->view->media == 'video')
						$model->media_type_i = 0;
				}
				echo $form->dropDownList($model, 'media_type_i', $media_type_i, array('class'=>'form-control')); ?>
				<?php echo $form->error($model, 'media_type_i'); ?>
			</div>
		</div>
		
		<div id="video" class="form-group row <?php echo $model->media_type_i == 1 ? 'hide' : '';?>">
			<?php echo $form->labelEx($model, 'media_filename', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php echo $form->textArea($model, 'media_filename', array('maxlength'=>32, 'class'=>'form-control smaller')); ?>
				<?php echo $form->error($model, 'media_filename'); ?>
				<div class="small-px">http://www.youtube.com/watch?v=<strong>HOAqSoDZSho</strong></div>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'cover_filename', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php if(!$model->isNewRecord) {
					if(!$model->getErrors())
						$model->old_cover_filename_i = $model->cover_filename;
					echo $form->hiddenField($model, 'old_cover_filename_i');
				}
		 		if($model->old_cover_filename_i != '') {
					$cover_filename = Yii::app()->request->baseUrl.'/public/article/'.$model->article_id.'/'.$model->old_cover_filename_i;?>
					<div class="mb-10"><img src="<?php echo Utility::getTimThumb($cover_filename, 400, 400, 3);?>" alt=""></div>
				<?php }?>
				<?php echo $form->fileField($model, 'cover_filename', array('maxlength'=>64, 'class'=>'form-control')); ?>
				<?php echo $form->error($model, 'cover_filename'); ?>
				<div class="small-px">extensions are allowed: <?php echo Utility::formatFileType($media_image_type, false);?></div>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'caption', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php echo $form->textField($model, 'caption', array('maxlength'=>150, 'class'=>'form-control')); ?>
				<?php echo $form->error($model, 'caption'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'description', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php $this->widget('yiiext.imperavi-redactor-widget.ImperaviRedactorWidget', array(
					'model'=>$model,
					'attribute'=>'description',
					'options'=>array(
						'buttons'=>array(
							'html', 'formatting', '|', 
							'bold', 'italic', 'deleted', '|',
							'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
							'link', '|',
						),
					),
					'plugins' => array(
						'fontcolor' => array('js' => array('fontcolor.js')),
						'table' => array('js' => array('table.js')),
						'fullscreen' => array('js' => array('fullscreen.js')),
					),
					'htmlOptions'=>array(
						'class' => 'form-control',
					),
				)); ?>
				<?php echo $form->error($model, 'description'); ?>
			</div>
		</div>

		<div class="form-group row publish">
			<?php echo $form->labelEx($model, 'cover', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php echo $form->checkBox($model, 'cover', array('class'=>'form-control')); ?>
				<?php echo $form->labelEx($model, 'cover'); ?>
				<?php echo $form->error($model, 'cover'); ?>
			</div>
		</div>

		<div class="form-group row publish">
			<?php echo $form->labelEx($model, 'publish', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php echo $form->checkBox($model, 'publish', array('class'=>'form-control')); ?>
				<?php echo $form->labelEx($model, 'publish'); ?>
				<?php echo $form->error($model, 'publish'); ?>
			</div>
		</div>

		<div class="form-group row submit">
			<label class="col-form-label col-lg-3 col-md-3 col-sm-12">&nbsp;</label>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
			</div>
		</div>

	</fieldset>
<?php $this->endWidget(); ?>