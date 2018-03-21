<?php
/**
 * Article Category (article-category)
 * @var $this CategoryController
 * @var $model ArticleCategory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
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
			<?php echo $model->getAttributeLabel('cat_id'); ?><br/>
			<?php echo $form->textField($model,'cat_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('publish'); ?><br/>
			<?php echo $form->textField($model,'publish'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('parent'); ?><br/>
			<?php echo $form->textField($model,'parent'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('name'); ?><br/>
			<?php echo $form->textField($model,'name'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('desc'); ?><br/>
			<?php echo $form->textField($model,'desc'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('single_photo'); ?><br/>
			<?php echo $form->textField($model,'single_photo'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('single_file'); ?><br/>
			<?php echo $form->textField($model,'single_file'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_date'); ?><br/>
			<?php echo $form->textField($model,'creation_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_id'); ?><br/>
			<?php echo $form->textField($model,'creation_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_date'); ?><br/>
			<?php echo $form->textField($model,'modified_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_id'); ?><br/>
			<?php echo $form->textField($model,'modified_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('updated_date'); ?><br/>
			<?php echo $form->textField($model,'updated_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('slug'); ?><br/>
			<?php echo $form->textField($model,'slug'); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
