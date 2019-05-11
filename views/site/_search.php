<?php
/**
 * Articles (articles)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\SiteController
 * @var $model ommu\article\models\search\Articles
 * @var $form yii\widgets\ActiveForm
 *
 * @author Agus Susilo <smartgdi@gmail.com>
 * @contact (+62) 857-297-29382
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 November 2017, 13:54 WIB
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
		<?= $form->field($model, 'article_id') ?>

		<?= $form->field($model, 'publish') ?>

		<?= $form->field($model, 'cat_id') ?>

		<?= $form->field($model, 'title') ?>

		<?= $form->field($model, 'body') ?>

		<?= $form->field($model, 'quote') ?>

		<?= $form->field($model, 'published_date') ?>

		<?= $form->field($model, 'headline') ?>

		<?= $form->field($model, 'comment_code') ?>

		<?= $form->field($model, 'creation_date') ?>

		<?= $form->field($model, 'creation_id') ?>

		<?= $form->field($model, 'modified_date') ?>

		<?= $form->field($model, 'modified_id') ?>

		<?= $form->field($model, 'updated_date') ?>

		<?= $form->field($model, 'headline_date') ?>

		<?= $form->field($model, 'slug') ?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
