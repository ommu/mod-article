<?php
/**
 * Articles (articles)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\AdminController
 * @var $model ommu\article\models\Articles
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 09:33 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\redactor\widgets\Redactor;
use ommu\article\models\ArticleCategory;
use ommu\article\models\Articles;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Url;
use app\models\CoreTags;
use yii\helpers\ArrayHelper;
use yii2mod\selectize\Selectize;

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
$category = ArticleCategory::getCategory(1);
echo $form->field($model,'cat_id')
	->dropDownList($category)
	->label($model->getAttributeLabel('cat_id')); ?>

<?php echo $form->field($model, 'title')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('title')); ?>


<?php echo $form->field($model, 'body')
	->textarea(['rows'=>2,'rows'=>6])
	->widget(Redactor::className(), ['id' => time().'_redactor', 'clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('body')); ?>

<?php echo $form->field($model, 'tag_2')->hiddenInput()
	->label(false);
?>

<?php if ($model->isNewRecord){
 echo $form->field($model, 'tag_id_i',['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
		
		->widget(Selectize::className(), [
			'items' => ArrayHelper::map(CoreTags::find()->all(), 'tag_id', 'body'),
			'options' => [
				'multiple' => true,
			],
			'pluginOptions' => [
				'persist' => false,
				'createOnBlur' => true,
				'create' => true,
				'onItemAdd' => new \yii\web\JsExpression('function(value, $item) { var tag = $item["context"].innerHTML; tags.push(tag); $("#articles-tag_2").val(tags.join(",")); }'),
				'onItemRemove' => new \yii\web\JsExpression('function(value, $item) { var tag = $item["context"].innerHTML; var pos = tags.indexOf(tag); if(pos > -1) { tags.splice(pos, 1)}; $("#articles-tag_2").val(tags.join(",")); }'),
			]
		])
		->label($model->getAttributeLabel('tag_id_i'));
		// var val = $("#input-id").val(); val = val + tag + ","; $("#input-id").val(val);
		}?>


<?php echo $form->field($model, 'published_date')
	->widget(DatePicker::classname(), ['dateFormat' => Yii::$app->formatter->dateFormat, 'options' => ['class' => 'form-control']])
	->label($model->getAttributeLabel('published_date')); ?>

<?php echo $form->field($model, 'comment_code')
	->checkbox()
	->label($model->getAttributeLabel('comment_code')); ?>

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

<?php
$js = <<<JS
var tags = [];
JS;

$this->registerJs($js, \yii\web\View::POS_HEAD);