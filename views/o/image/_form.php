<?php
/**
 * Article Media (article-media)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\o\ImageController
 * @var $model ommu\article\models\ArticleMedia
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 11:00 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;
use yii\helpers\ArrayHelper;
use ommu\article\models\Articles;

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

<?php echo $form->field($model, 'cover')
	->checkbox()
	->label($model->getAttributeLabel('cover')); ?>

<div class="form-group">
	<?php echo $form->field($model, 'article_id', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('article_id')); ?>
	<div class="col-md-9 col-sm-9 col-xs-12">
		<?php
		if (!Yii::$app->request->get('article')){ 
			$articlelist = ArrayHelper::map(Articles::find()->where(['publish'=>1])->all(), 'article_id', 'title');
			echo $form->field($model, 'article_id', ['template' => '{input}{error}'])
			->dropdownList($articlelist)
			->label($model->getAttributeLabel('article_id'));
		}
		else {
			if ($model->isNewRecord){
				$getArticle = Yii::$app->request->get('article');
				$model->article_id = $getArticle;
				}
				else {
					$getArticle = $model->article_id;
				}
				$articles = Articles::find()->where(['article_id'=>$getArticle])->one();
				echo $articles->title;
			} 
		?>

	</div>
</div>


<div class="form-group field-articles-media_filename required">
	<?php echo $form->field($model, 'media_filename', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('media_filename')); ?>
	<div class="col-md-9 col-sm-9 col-xs-12">
		<?php if (!$model->isNewRecord) {
			if($model->old_media_filename != '')
				echo Html::img(join('/', [Url::Base(), Articles::getUploadPath(false), $model->old_media_filename]), ['class'=>'mb-15', 'width'=>'100%']);
		} ?>

		<?php echo $form->field($model, 'media_filename', ['template' => '{input}{error}'])
			->fileInput() 
			->label($model->getAttributeLabel('media_filename')); ?>
	</div>
</div>

<?php echo $form->field($model, 'caption')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('caption')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>