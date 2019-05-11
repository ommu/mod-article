<?php
/**
 * Article Like Histories (article-like-history)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\history\LikeController
 * @var $model ommu\article\models\search\ArticleLikeHistory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:59 WIB
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

		<?php echo $form->field($model, 'publish')
			->dropDownList($model->filterYesNo(), ['prompt'=>'']);?>

		<?php echo $form->field($model, 'like_id'); ?>

		<?php echo $form->field($model, 'likes_date')
			->input('date');?>

		<?php echo $form->field($model, 'likes_ip'); ?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
