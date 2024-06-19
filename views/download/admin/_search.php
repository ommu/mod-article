<?php
/**
 * Article Downloads (article-downloads)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\download\AdminController
 * @var $model ommu\article\models\search\ArticleDownloads
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 11:14 WIB
 * @modified date 13 May 2019, 09:43 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="article-downloads-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'fileFilename');?>

		<?php echo $form->field($model, 'userDisplayname');?>

		<?php echo $form->field($model, 'downloads');?>

		<?php echo $form->field($model, 'download_date')
			->input('date');?>

		<?php echo $form->field($model, 'download_ip');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>