<?php
/**
 * Article View Histories (article-view-history)
 * @var $this ViewhistoryController
 * @var $model ArticleViewHistory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 8 January 2017, 21:21 WIB
 * @link https://github.com/ommu/ommu-article
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('id'); ?><br/>
			<?php echo $form->textField($model,'id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('view_id'); ?><br/>
			<?php echo $form->textField($model,'view_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('view_date'); ?><br/>
			<?php echo $form->textField($model,'view_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('view_ip'); ?><br/>
			<?php echo $form->textField($model,'view_ip'); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
