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
 * @modified date 11 May 2019, 23:45 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\article\models\ArticleSetting;
use ommu\article\models\ArticleCategory;
?>

<div class="article-setting-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->license = $model->licenseCode();
echo $form->field($model, 'license')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('license'))
	->hint(Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'<br/>'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX')); ?>

<?php $permission = ArticleSetting::getPermission();
echo $form->field($model, 'permission', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($permission)
	->label($model->getAttributeLabel('permission'))
	->hint(Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.')); ?>

<?php echo $form->field($model, 'meta_description')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_description')); ?>

<?php echo $form->field($model, 'meta_keyword')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_keyword')); ?>

<div class="ln_solid"></div>

<?php $headline = ArticleSetting::getHeadline();
echo $form->field($model, 'headline')
	->dropDownList($headline)
	->label($model->getAttributeLabel('headline')); ?>

<?php echo $form->field($model, 'headline_limit')
	->textInput(['type'=>'number'])
	->label($model->getAttributeLabel('headline_limit')); ?>

<?php $category = ArticleCategory::getCategory(1);
echo $form->field($model, 'headline_category')
	->checkboxList($category)
	->label($model->getAttributeLabel('headline_category')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'media_image_limit')
	->textInput(['type'=>'number'])
	->label($model->getAttributeLabel('media_image_limit')); ?>

<?php $mediaImageResize = ArticleSetting::getMediaImageResize();
echo $form->field($model, 'media_image_resize')
	->radioList($mediaImageResize)
	->label($model->getAttributeLabel('media_image_resize')); ?>

<?php $media_image_resize_size_height = $form->field($model, 'media_image_resize_size[height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6 col-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('height')])
	->label($model->getAttributeLabel('media_image_resize_size[height]')); ?>

<?php echo $form->field($model, 'media_image_resize_size[width]', ['template' => '{hint}{beginWrapper}{input}{endWrapper}'.$media_image_resize_size_height.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-6 col-sm-offset-3', 'error'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3', 'hint'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('width')])
	->label($model->getAttributeLabel('media_image_resize_size'))
	->hint(Yii::t('app', 'If you have selected "Yes" above, please input the maximum dimensions for the project image. If your users upload a image that is larger than these dimensions, the server will attempt to scale them down automatically. This feature requires that your PHP server is compiled with support for the GD Libraries.')); ?>

<?php $media_image_view_size_small_height = $form->field($model, 'media_image_view_size[small][height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6 col-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('height')])
	->label($model->getAttributeLabel('media_image_view_size[small][height]')); ?>

<?php echo $form->field($model, 'media_image_view_size[small][width]', ['template' => '{label}<div class="h5 col-md-6 col-sm-9 col-xs-12">'.$model->getAttributeLabel('media_image_view_size[small]').'</div>{beginWrapper}{input}{endWrapper}'.$media_image_view_size_small_height.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-6 col-sm-offset-3', 'error'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3', 'hint'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('width')])
	->label($model->getAttributeLabel('media_image_view_size')); ?>

<?php $media_image_view_size_medium_height = $form->field($model, 'media_image_view_size[medium][height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6 col-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('height')])
	->label($model->getAttributeLabel('media_image_view_size[medium][height]')); ?>

<?php echo $form->field($model, 'media_image_view_size[medium][width]', ['template' => '<div class="h5 col-md-6 col-sm-9 col-xs-12 col-sm-offset-3 mt-0">'.$model->getAttributeLabel('media_image_view_size[medium]').'</div>{beginWrapper}{input}{endWrapper}'.$media_image_view_size_medium_height.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-6 col-sm-offset-3', 'error'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3', 'hint'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('width')])
	->label($model->getAttributeLabel('media_image_view_size[medium][width]')); ?>

<?php $media_image_view_size_large_height = $form->field($model, 'media_image_view_size[large][height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6 col-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('height')])
	->label($model->getAttributeLabel('media_image_view_size[large][height]')); ?>

<?php echo $form->field($model, 'media_image_view_size[large][width]', ['template' => '<div class="h5 col-md-6 col-sm-9 col-xs-12 col-sm-offset-3 mt-0">'.$model->getAttributeLabel('media_image_view_size[large]').'</div>{beginWrapper}{input}{endWrapper}'.$media_image_view_size_large_height.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-6 col-sm-offset-3', 'error'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3', 'hint'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('width')])
	->label($model->getAttributeLabel('media_image_view_size[large][width]')); ?>

<?php echo $form->field($model, 'media_image_type')
	->textInput()
	->label($model->getAttributeLabel('media_image_type'))
	->hint(Yii::t('app', 'pisahkan jenis file dengan koma (,). example: "jpg, png, bmp, jpeg"')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'media_file_limit')
	->textInput(['type'=>'number'])
	->label($model->getAttributeLabel('media_file_limit')); ?>

<?php echo $form->field($model, 'media_file_type')
	->textInput()
	->label($model->getAttributeLabel('media_file_type'))
	->hint(Yii::t('app', 'pisahkan jenis file dengan koma (,). example: "pdf, doc, docx"')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>