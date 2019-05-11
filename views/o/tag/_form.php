<?php
/**
 * Article Tags (article-tag)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\o\TagController
 * @var $model ommu\article\models\ArticleTag
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
use app\components\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;
use ommu\article\models\Articles;
?>

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php
$list = ArrayHelper::map(Articles::find()->where(['publish'=>1])->all(),'article_id','title');
echo $form->field($model,'article_id')
	->dropDownList($list)
	->label($model->getAttributeLabel('article_id')); ?>
<?php 

echo $form->field($model, 'tag_id_i')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('tag_id_i')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>