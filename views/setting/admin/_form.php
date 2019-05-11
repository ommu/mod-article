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
use app\components\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;
use ommu\article\models\ArticleSetting;
use ommu\article\models\ArticleCategory;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload' => ['/redactor/upload/image'],
	'fileUpload' => ['/redactor/upload/file'],
	'plugins' => ['clips', 'fontcolor','imagemanager']
];
?>

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php 
if($model->isNewRecord || (!$model->isNewRecord && $model->license == ''))
	$model->license = ArticleSetting::getLicense();
echo $form->field($model, 'license')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('license')); ?>

<?php 
$permission = [
	1 => Yii::t('app', 'Yes, the public can view article unless they are made private.'),
	0 => Yii::t('app', 'No, the public cannot view article.'),
];
echo $form->field($model, 'permission', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12"><span class="small-px">'.Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.').'</span>{input}{error}</div>'])
	->radioList($permission)
	->label($model->getAttributeLabel('permission')); ?>

<?php echo $form->field($model, 'meta_keyword')
	->textarea(['rows'=>2,'rows'=>6])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('meta_keyword')); ?>

<?php echo $form->field($model, 'meta_description')
	->textarea(['rows'=>2,'rows'=>6])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('meta_description')); ?>

<?php echo $form->field($model, 'headline_limit')
	->textInput(['type' => 'number'])
	->label($model->getAttributeLabel('headline_limit')); ?>

<?php 

$headline_category = ArticleCategory::getCategory(1);
if(!$model->getErrors()) {
	try {
		$model->headline_category = unserialize($model->headline_category);
	}catch(\Exception $e) {
		$model->headline_category = '';
	}
}

echo $form->field($model, 'headline_category')
	->checkboxList($headline_category)
	->label($model->getAttributeLabel('headline_category')); ?>


<?php echo $form->field($model, 'media_limit')
	->textInput(['type' => 'number'])
	->label($model->getAttributeLabel('media_limit')); ?>


<?php 
$media_resize = [
	1 => Yii::t('app', 'Yes, resize media after upload.'),
	0 => Yii::t('app', 'No, not resize media after upload.'),
];
echo $form->field($model, 'media_resize')
	->radioList($media_resize)
	->label($model->getAttributeLabel('media_resize')); ?>

<div class="form-group field-articlesetting-media_resize_size-width field-articlesetting-media_resize_size-height required">
	<?php echo $form->field($model, 'media_resize_size', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('media_resize_size')); ?>
	<div class="col-md-3 col-sm-3 col-xs-12">
		<?php 
		if(!$model->getErrors()) {
			try {
				$model->media_resize_size = unserialize($model->media_resize_size);
			}catch(\Exception $e) {
				$model->media_resize_size = [];
			}
		}
		echo $form->field($model, 'media_resize_size[width]', ['template' => '{input}{error}'])
			->textInput(['type' => 'number', 'placeholder' => Yii::t('app', 'Width')])
			->label($model->getAttributeLabel('media_resize_size')); ?>
	</div>
	<div class="col-md-3 col-sm-3 col-xs-12">
		<?php echo $form->field($model, 'media_resize_size[height]', ['template' => '{input}{error}'])
			->textInput(['type' => 'number', 'placeholder' => Yii::t('app', 'Height')])
			->label($model->getAttributeLabel('media_resize_size')); ?>
	</div>
</div>


<div class="form-group field-articlesetting-media_view_size-width field-articlesetting-media_view_size-height required">
	<?php echo $form->field($model, 'media_view_size', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('media_view_size')); ?>
	<div class="col-md-9 col-sm-9 col-xs-12">
		<?php 
		if(!$model->getErrors()) {
			try {
				$model->media_view_size = unserialize($model->media_view_size);
			}catch(\Exception $e) {
				$model->media_view_size = [];
			}
		}

		if(empty($model->media_view_size))			
			$model->media_view_size = [];

		echo Html::label($model->getAttributeLabel('media_view_size[small]'), null, ['class'=>'control-label col-md-4 col-sm-4 col-xs-12']); ?>
		<?php 
		echo $form->field($model, 'media_view_size[small][width]', ['template' => '<div class="col-md-4 col-sm-4 col-xs-12">{input}{error}</div>', 'options' => ['tag' => null]])
			->textInput(['type' => 'number', 'placeholder' => Yii::t('app', 'Width')])
			->label($model->getAttributeLabel('media_view_size')); ?>
		<?php echo $form->field($model, 'media_view_size[small][height]', ['template' => '<div class="col-md-4 col-sm-4 col-xs-12">{input}{error}</div>', 'options' => ['tag' => null]])
			->textInput(['type' => 'number', 'placeholder' => Yii::t('app', 'Height')])
			->label($model->getAttributeLabel('media_view_size')); ?>

		<?php echo Html::label($model->getAttributeLabel('media_view_size[medium]'), null, ['class'=>'control-label col-md-4 col-sm-4 col-xs-12']); ?>
		<?php 
		echo $form->field($model, 'media_view_size[medium][width]', ['template' => '<div class="col-md-4 col-sm-4 col-xs-12">{input}{error}</div>', 'options' => ['tag' => null]])
			->textInput(['type' => 'number', 'placeholder' => Yii::t('app', 'Width')])
			->label($model->getAttributeLabel('media_view_size')); ?>
		<?php echo $form->field($model, 'media_view_size[medium][height]', ['template' => '<div class="col-md-4 col-sm-4 col-xs-12">{input}{error}</div>', 'options' => ['tag' => null]])
			->textInput(['type' => 'number', 'placeholder' => Yii::t('app', 'Height')])
			->label($model->getAttributeLabel('media_view_size')); ?>

		<?php echo Html::label($model->getAttributeLabel('media_view_size[large]'), null, ['class'=>'control-label col-md-4 col-sm-4 col-xs-12']); ?>
		<?php 
		echo $form->field($model, 'media_view_size[large][width]', ['template' => '<div class="col-md-4 col-sm-4 col-xs-12">{input}{error}</div>', 'options' => ['tag' => null]])
			->textInput(['type' => 'number', 'placeholder' => Yii::t('app', 'Width')])
			->label($model->getAttributeLabel('media_view_size')); ?>
		<?php echo $form->field($model, 'media_view_size[large][height]', ['template' => '<div class="col-md-4 col-sm-4 col-xs-12">{input}{error}</div>', 'options' => ['tag' => null]])
			->textInput(['type' => 'number', 'placeholder' => Yii::t('app', 'Height')])
			->label($model->getAttributeLabel('media_view_size')); ?>
	</div>
</div>

<?php 
if(!$model->getErrors()) {
	try {
		$media_file_type = unserialize($model->media_file_type);
	}catch(\Exception $e) {
		$media_file_type = '';
	}
	if(!empty($media_file_type))
		$model->media_file_type = $this->formatFileType($media_file_type, false);
}
echo $form->field($model, 'media_file_type', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}<span class="small-px">'.Yii::t('app', 'pisahkan jenis file dengan koma (,). example: "jpg, png, bmp, jpeg"').'</span></div>'])
	->textInput()
	->label($model->getAttributeLabel('media_file_type')); ?>



<?php 
if(!$model->getErrors()) {
	try {
		$upload_file_type = unserialize($model->upload_file_type);
	}catch(\Exception $e) {
	}
	if(!empty($upload_file_type))
		$model->upload_file_type = $this->formatFileType($upload_file_type, false);
}
echo $form->field($model, 'upload_file_type', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}<span class="small-px">'.Yii::t('app', 'pisahkan jenis file dengan koma (,). example: "pdf, doc, docx"').'</span></div>'])
	->textInput()
	->label($model->getAttributeLabel('upload_file_type')); ?>

<?php echo $form->field($model, 'headline')
	->checkbox()
	->label($model->getAttributeLabel('headline')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>