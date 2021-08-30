<?php
/**
 * Articles (articles)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\AdminController
 * @var $model ommu\article\models\Articles
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 09:33 WIB
 * @modified date 13 May 2019, 21:24 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;
use ommu\article\models\ArticleCategory;
use ommu\article\models\Articles;
use ommu\selectize\Selectize;
use ommu\flatpickr\Flatpickr;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload' => ['/redactor/upload/image'],
	'fileUpload' => ['/redactor/upload/file'],
	'plugins' => ['clips', 'fontcolor', 'imagemanager']
];
?>

<div class="articles-form">

<?php $form = ActiveForm::begin([
	'options' => ['class' => 'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $category = ArticleCategory::getCategory(1, 'is_null', 'optgroup');
echo $form->field($model, 'cat_id')
	->dropDownList($category, ['prompt' => ''])
	->label($model->getAttributeLabel('cat_id')); ?>

<?php echo $form->field($model, 'title')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('title')); ?>

<?php $uploadPath = join('/', [Articles::getUploadPath(false), $model->id]);
if ($model->isNewRecord || (!$model->isNewRecord && ($model->category->single_photo || $setting->media_image_limit == 1))) {
	$cover = !$model->isNewRecord && $model->cover ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->cover])), ['alt' => $model->cover, 'class' => 'd-block border border-width-3 mb-4']).$model->cover.'<hr/>' : '';
	echo $form->field($model, 'image', ['template' => '{label}{beginWrapper}<div>'.$cover.'</div>{input}{error}{hint}{endWrapper}'])
		->fileInput()
		->label($model->getAttributeLabel('image'))
		->hint(Yii::t('app', 'extensions are allowed: {extensions}', ['extensions' => $setting->media_image_type]));
} ?>

<?php echo $form->field($model, 'body')
	->textarea(['rows' => 6, 'cols' => 50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('body')); ?>

<?php 
if ($model->isNewRecord || (!$model->isNewRecord && ($model->category->single_file || $setting->media_file_limit == 1))) {
    $file = !$model->isNewRecord && $model->document ? Html::a($model->document, Url::to(join('/', ['@webpublic', $uploadPath, $model->document])), ['title' => $model->document, 'target' => '_blank', 'class' => 'd-inline-block mb-4']) : '';
    echo $form->field($model, 'file', ['template' => '{label}{beginWrapper}<div>'.$file.'</div>{input}{error}{hint}{endWrapper}'])
        ->fileInput()
        ->label($model->getAttributeLabel('file'))
        ->hint(Yii::t('app', 'extensions are allowed: {extensions}', ['extensions' => $setting->media_file_type]));
} ?>

<?php $tagSuggestUrl = Url::to(['/admin/tag/suggest']);
echo $form->field($model, 'tag')
	->widget(Selectize::className(), [
		'url' => $tagSuggestUrl,
		'queryParam' => 'term',
		'pluginOptions' => [
			'valueField' => 'label',
			'labelField' => 'label',
			'searchField' => ['label'],
			'persist' => false,
			'createOnBlur' => false,
			'create' => true,
		],
	])
	->label($model->getAttributeLabel('tag')); ?>

<hr/>

<?php 
if ($model->isNewRecord && !$model->getErrors()) {
	$model->published_date = Yii::$app->formatter->asDate('now', 'php:Y-m-d');
}
echo $form->field($model, 'published_date')
    ->widget(Flatpickr::className(), ['model' => $model, 'attribute' => 'published_date'])
	->label($model->getAttributeLabel('published_date')); ?>

<?php 
if ($setting->headline) {
    echo $form->field($model, 'headline')
        ->checkbox()
        ->label($model->getAttributeLabel('headline'));
} ?>

<?php 
if ($model->isNewRecord && !$model->getErrors()) {
    $model->publish = 1;
}
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>