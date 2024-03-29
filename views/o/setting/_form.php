<?php
/**
 * Article Settings (article-setting)
 * @var $this SettingController
 * @var $model ArticleSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 23 March 2018, 16:01 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('input[name="ArticleSetting[media_image_resize]"]').on('change', function() {
		var id = $(this).val();
		if(id == '1') {
			$('div#resize_size').slideDown();
		} else {
			$('div#resize_size').slideUp();
		}
	});
	
	$('select#ArticleSetting_headline').on('change', function() {
		var id = $(this).val();
		if(id == '1') {
			$('div#headline').slideDown();
		} else {
			$('div#headline').slideUp();
		}
	});
EOP;
	$cs->registerScript('js', $js, CClientScript::POS_END);
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'article-setting-form',
	'enableAjaxValidation'=>true,
	/*
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data',
	),
	*/
)); ?>

<?php //begin.Messages ?>
<div id="ajax-message">
	<?php echo $form->errorSummary($model); ?>
</div>
<?php //begin.Messages ?>

<h3><?php echo Yii::t('phrase', 'Public Settings');?></h3>
<fieldset>

	<div class="form-group row">
		<label class="col-form-label col-lg-3 col-md-3 col-sm-12">
			<?php echo $model->getAttributeLabel('license');?> <span class="required">*</span><br/>
			<span><?php echo Yii::t('phrase', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.');?></span>
		</label>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php if($model->isNewRecord || (!$model->isNewRecord && $model->license == '')) {
				$model->license = $this->licenseCode();
				echo $form->textField($model, 'license', array('maxlength'=>32, 'class'=>'form-control'));
			} else
				echo $form->textField($model, 'license', array('maxlength'=>32, 'class'=>'form-control', 'disabled'=>'disabled'));?>
			<?php echo $form->error($model, 'license'); ?>
			<div class="small-px"><?php echo Yii::t('phrase', 'Format: XXXX-XXXX-XXXX-XXXX');?></div>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'permission', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<div class="small-px"><?php echo Yii::t('phrase', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.');?></div>
			<?php if($model->isNewRecord && !$model->getErrors())
				$model->permission = 1;
			echo $form->radioButtonList($model, 'permission', array(
				1 => Yii::t('phrase', 'Yes, the public can view articles unless they are made private.'),
				0 => Yii::t('phrase', 'No, the public cannot view articles.'),
			), array('class'=>'form-control')); ?>
			<?php echo $form->error($model, 'permission'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'meta_keyword', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php echo $form->textArea($model, 'meta_keyword', array('rows'=>6, 'cols'=>50, 'class'=>'form-control smaller')); ?>
			<?php echo $form->error($model, 'meta_keyword'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'meta_description', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php echo $form->textArea($model, 'meta_description', array('rows'=>6, 'cols'=>50, 'class'=>'form-control small')); ?>
			<?php echo $form->error($model, 'meta_description'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'headline', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php if($model->isNewRecord && !$model->getErrors())
				$model->headline = 1;
			echo $form->dropDownLIst($model, 'headline', array(
				'1' => Yii::t('phrase', 'Enable'),
				'0' => Yii::t('phrase', 'Disable'),
			), array('class'=>'form-control')); ?>
			<?php echo $form->error($model, 'headline'); ?>
		</div>
	</div>

	<div id="headline" class="<?php echo $model->headline == 0 ? 'hide' : '';?>">
		<div class="form-group row">
			<?php echo $form->labelEx($model, 'headline_limit', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php if($model->isNewRecord && !$model->getErrors())
					$model->headline_limit = 0;
				echo $form->textField($model, 'headline_limit', array('maxlength'=>3, 'class'=>'form-control')); ?>
				<?php echo $form->error($model, 'headline_limit'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'headline_category', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php 
				$parent = null;
				$category = ArticleCategory::getCategory(1, $parent);
				if(!$model->getErrors())
					$model->headline_category = unserialize($model->headline_category);
				if($category != null)
					echo $form->checkBoxList($model, 'headline_category', $category, array('class'=>'form-control'));
				else
					echo $form->checkBoxList($model, 'headline_category', array('prompt'=>Yii::t('phrase', 'No Parent')), array('class'=>'form-control')); ?>
				<?php echo $form->error($model, 'headline_category'); ?>
			</div>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'media_image_limit', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php echo $form->textField($model, 'media_image_limit', array('maxlength'=>5, 'class'=>'form-control')); ?>
			<?php echo $form->error($model, 'media_image_limit'); ?>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-form-label col-lg-3 col-md-3 col-sm-12"><?php echo Yii::t('phrase', 'Image Setting');?> <span class="required">*</span></label>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<p><?php echo $model->getAttributeLabel('media_image_resize');?></p>
			<?php 
			if($model->isNewRecord && !$model->getErrors())
				$model->media_image_resize = 0;
			echo $form->radioButtonList($model, 'media_image_resize', array(
				0 => Yii::t('phrase', 'No, not resize media after upload.'),
				1 => Yii::t('phrase', 'Yes, resize media after upload.'),
			), array('class'=>'form-control')); ?>
			
			<?php if(!$model->getErrors()) {
				$model->media_image_resize_size = unserialize($model->media_image_resize_size);
				$model->media_image_view_size = unserialize($model->media_image_view_size);
			}?>
			
			<div id="resize_size" class="row mt-15 <?php echo $model->media_image_resize == 0 ? 'hide' : '';?>">
				<div class="col-lg-2 col-md-2 col-sm-2 col-6"><?php echo Yii::t('phrase', 'Width:');?></div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-6"><?php echo $form->textField($model, 'media_image_resize_size[width]', array('maxlength'=>4, 'class'=>'form-control')); ?></div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-6"><?php echo Yii::t('phrase', 'Height:');?></div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-6"><?php echo $form->textField($model, 'media_image_resize_size[height]', array('maxlength'=>4, 'class'=>'form-control')); ?></div>
			</div>
			<?php echo $form->error($model, 'media_image_resize_size'); ?>

			<p class="bold"><?php echo Yii::t('phrase', 'Large Size');?></p>
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2 col-6"><?php echo Yii::t('phrase', 'Width:');?></div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-6"><?php echo $form->textField($model, 'media_image_view_size[large][width]', array('maxlength'=>4, 'class'=>'form-control')); ?></div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-6"><?php echo Yii::t('phrase', 'Height:');?></div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-6"><?php echo $form->textField($model, 'media_image_view_size[large][height]', array('maxlength'=>4, 'class'=>'form-control')); ?></div>
			</div>
			<?php echo $form->error($model, 'media_image_view_size[large]'); ?>

			<p class="bold"><?php echo Yii::t('phrase', 'Medium Size');?></p>
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2 col-6"><?php echo Yii::t('phrase', 'Width:');?></div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-6"><?php echo $form->textField($model, 'media_image_view_size[medium][width]', array('maxlength'=>3, 'class'=>'form-control')); ?></div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-6"><?php echo Yii::t('phrase', 'Height:');?></div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-6"><?php echo $form->textField($model, 'media_image_view_size[medium][height]', array('maxlength'=>3, 'class'=>'form-control')); ?></div>
			</div>
			<?php echo $form->error($model, 'media_image_view_size[medium]'); ?>

			<p class="bold"><?php echo Yii::t('phrase', 'Small Size');?></p>
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2 col-6"><?php echo Yii::t('phrase', 'Width:');?></div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-6"><?php echo $form->textField($model, 'media_image_view_size[small][width]', array('maxlength'=>3, 'class'=>'form-control')); ?></div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-6"><?php echo Yii::t('phrase', 'Height:');?></div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-6"><?php echo $form->textField($model, 'media_image_view_size[small][height]', array('maxlength'=>3, 'class'=>'form-control')); ?></div>
			</div>
			<?php echo $form->error($model, 'media_image_view_size[small]'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'media_image_type', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php 
			if(!$model->getErrors()) {
				$media_image_type = unserialize($model->media_image_type);
				if(!empty($media_image_type))
					$model->media_image_type = Utility::formatFileType($media_image_type, false);
				else
					$model->media_image_type = 'jpg, png, bmp';
			}
			echo $form->textField($model, 'media_image_type', array('class'=>'form-control')); ?>
			<?php echo $form->error($model, 'media_image_type'); ?>
			<div class="small-px">pisahkan jenis file dengan koma (,). example: "jpg, png, bmp"</div>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'media_file_limit', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php echo $form->textField($model, 'media_file_limit', array('maxlength'=>5, 'class'=>'form-control')); ?>
			<?php echo $form->error($model, 'media_file_limit'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'media_file_type', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php
			if(!$model->getErrors()) {
				$media_file_type = unserialize($model->media_file_type);
				if(!empty($media_file_type))
					$model->media_file_type = Utility::formatFileType($media_file_type, false);
				else
					$model->media_file_type = 'mp3, mp4, pdf, doc, docx';
			}
			echo $form->textField($model, 'media_file_type', array('class'=>'form-control')); ?>
			<?php echo $form->error($model, 'media_file_type'); ?>
			<div class="small-px">pisahkan type file dengan koma (,). example: "mp3, mp4, pdf, doc, docx"</div>
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