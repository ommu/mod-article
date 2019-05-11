<?php
/**
 * Article Categories (article-category)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\setting\CategoryController
 * @var $model ommu\article\models\ArticleCategory
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 09:35 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'parent_id')
	->textInput(['type' => 'number'])
	->label($model->getAttributeLabel('parent_id')); ?>

<!-- <?php echo $form->field($model, 'name')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('name')); ?>

<?php echo $form->field($model, 'desc')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('desc')); ?> -->

<?php 
if (!$model->isNewRecord){
	if(!$model->getErrors())
		$model->name_i = $model->title->message;
}
echo $form->field($model, 'name_i')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('name_i')); ?>

<?php 
if (!$model->isNewRecord){
	if(!$model->getErrors())
		$model->desc_i = $model->description->message;
}
echo $form->field($model, 'desc_i')
	->textarea(['rows'=>6, 'cols'=>50, 'maxlength'=>true])
	->label($model->getAttributeLabel('desc_i')); ?>

<?php echo $form->field($model, 'single_photo')
	->checkbox()
	->label($model->getAttributeLabel('single_photo')); ?>

<?php echo $form->field($model, 'single_file')
	->checkbox()
	->label($model->getAttributeLabel('single_file')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>