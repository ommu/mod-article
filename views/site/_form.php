<?php
/**
 * Articles (articles)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\SiteController
 * @var $model ommu\article\models\Articles
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Agus Susilo <smartgdi@gmail.com>
 * @contact (+62) 857-297-29382
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 November 2017, 13:54 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\redactor\widgets\Redactor;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload' => ['/redactor/upload/image'],
	'fileUpload' => ['/redactor/upload/file'],
	'plugins' => ['clips', 'fontcolor', 'imagemanager']
];
?>

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'cat_id')
	->textInput(['type' => 'number'])
	->label($model->getAttributeLabel('cat_id')); ?>

<?php echo $form->field($model, 'title')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('title')); ?>

<?php echo $form->field($model, 'body')
	->textarea(['rows'=>2,'rows'=>6])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('body')); ?>

<?php echo $form->field($model, 'published_date')
	->widget(DatePicker::classname(), ['dateFormat' => Yii::$app->formatter->dateFormat, 'options' => ['class' => 'form-control']])
	->label($model->getAttributeLabel('published_date')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<?php echo $form->field($model, 'headline')
	->checkbox()
	->label($model->getAttributeLabel('headline')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>