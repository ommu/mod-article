<?php
/**
 * Article Media (article-media)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\o\ImageController
 * @var $model ommu\article\models\ArticleMedia
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 11:00 WIB
 * @modified date 17 May 2019, 11:45 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;
use ommu\article\models\Articles;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload' => ['/redactor/upload/image'],
	'fileUpload' => ['/redactor/upload/file'],
	'plugins' => ['clips', 'fontcolor', 'imagemanager']
];
?>

<div class="article-media-form">

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>
<?php $uploadPath = join('/', [Articles::getUploadPath(false), $model->article_id]);
$mediaFilename = !$model->isNewRecord && $model->old_media_filename != '' ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->old_media_filename])), ['alt'=>$model->old_media_filename, 'class'=>'d-block border border-width-3 mb-3']).$model->old_media_filename.'<hr/>' : '';
echo $form->field($model, 'media_filename', ['template' => '{label}{beginWrapper}<div>'.$mediaFilename.'</div>{input}{error}{hint}{endWrapper}'])
	->fileInput()
	->label($model->getAttributeLabel('media_filename')); ?>

<?php echo $form->field($model, 'caption')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('caption')); ?>

<?php echo $form->field($model, 'description')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('description')); ?>

<?php echo $form->field($model, 'orders')
	->textInput(['type'=>'number'])
	->label($model->getAttributeLabel('orders')); ?>

<?php echo $form->field($model, 'cover')
	->checkbox()
	->label($model->getAttributeLabel('cover')); ?>

<?php 
if ($model->isNewRecord) {
	echo $form->field($model, 'redirectUpdate')
		->checkbox()
		->label($model->getAttributeLabel('redirectUpdate'));
} ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>