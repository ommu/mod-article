<?php
/**
 * Article Files (article-files)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\o\FileController
 * @var $model ommu\article\models\ArticleFiles
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 11:09 WIB
 * @modified date 17 May 2019, 11:45 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use ommu\article\models\Articles;
?>

<div class="article-files-form">

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $uploadPath = join('/', [Articles::getUploadPath(false), $model->article_id]);
$fileFilename = !$model->isNewRecord && $model->old_file_filename != '' ? Html::a($model->old_file_filename, Url::to(join('/', ['@webpublic', $uploadPath, $model->old_file_filename])), ['class'=>'d-inline-block mb-3']) : '';
echo $form->field($model, 'file_filename', ['template' => '{label}{beginWrapper}<div>'.$fileFilename.'</div>{input}{error}{endWrapper}'])
	->fileInput()
	->label($model->getAttributeLabel('file_filename')); ?>

<?php if($model->isNewRecord) {
	echo $form->field($model, 'redirectUpdate')
		->checkbox()
		->label($model->getAttributeLabel('redirectUpdate'));
} ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>