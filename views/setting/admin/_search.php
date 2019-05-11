<?php
/**
 * Article Settings (article-setting)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\setting\AdminController
 * @var $model ommu\article\models\search\ArticleSetting
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 09:34 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="search-form">
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>
		<?php echo $form->field($model, 'id'); ?>

		<?php echo $form->field($model, 'license'); ?>

		<?php echo $form->field($model, 'permission'); ?>

		<?php echo $form->field($model, 'meta_keyword'); ?>

		<?php echo $form->field($model, 'meta_description'); ?>

		<?php echo $form->field($model, 'headline'); ?>

		<?php echo $form->field($model, 'headline_limit'); ?>

		<?php echo $form->field($model, 'headline_category'); ?>

		<?php echo $form->field($model, 'media_limit'); ?>

		<?php echo $form->field($model, 'media_resize'); ?>

		<?php echo $form->field($model, 'media_resize_size'); ?>

		<?php echo $form->field($model, 'media_view_size'); ?>

		<?php echo $form->field($model, 'media_file_type'); ?>

		<?php echo $form->field($model, 'upload_file_type'); ?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
