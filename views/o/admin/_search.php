<?php
/**
 * Articles (articles)
 * @var $this AdminController
 * @var $model Articles
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 26 March 2018, 14:07 WIB
 * @link https://github.com/ommu/mod-article
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('cat_id'); ?>
			<?php $category = ArticleCategory::getCategory();
			if($category == null)
				echo $form->textField($model, 'cat_id', $category, array('prompt'=>'', 'class'=>'form-control'));
			else
				echo $form->textField($model, 'cat_id', array('prompt'=>''), array('class'=>'form-control'));?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('title'); ?>
			<?php echo $form->textField($model, 'title', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('body'); ?>
			<?php echo $form->textField($model, 'body', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('quote'); ?>
			<?php echo $form->textField($model, 'quote', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('published_date'); ?>
			<?php /* $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'model'=>$model,
				'attribute'=>'published_date',
				//'mode'=>'datetime',
				'options'=>array(
					'dateFormat' => 'yy-mm-dd',
				),
				'htmlOptions'=>array(
					'class' => 'form-control',
				 ),
			)); */
			echo $form->dateField($model, 'published_date', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_date'); ?>
			<?php /* $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'model'=>$model,
				'attribute'=>'creation_date',
				//'mode'=>'datetime',
				'options'=>array(
					'dateFormat' => 'yy-mm-dd',
				),
				'htmlOptions'=>array(
					'class' => 'form-control',
				 ),
			)); */
			echo $form->dateField($model, 'creation_date', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_search'); ?>
			<?php echo $form->textField($model, 'creation_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_date'); ?>
			<?php /* $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'model'=>$model,
				'attribute'=>'modified_date',
				//'mode'=>'datetime',
				'options'=>array(
					'dateFormat' => 'yy-mm-dd',
				),
				'htmlOptions'=>array(
					'class' => 'form-control',
				 ),
			)); */
			echo $form->dateField($model, 'modified_date', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_search'); ?>
			<?php echo $form->textField($model, 'modified_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('headline_date'); ?>
			<?php /* $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'model'=>$model,
				'attribute'=>'headline_date',
				//'mode'=>'datetime',
				'options'=>array(
					'dateFormat' => 'yy-mm-dd',
				),
				'htmlOptions'=>array(
					'class' => 'form-control',
				 ),
			)); */
			echo $form->dateField($model, 'headline_date', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('updated_date'); ?>
			<?php /* $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'model'=>$model,
				'attribute'=>'updated_date',
				//'mode'=>'datetime',
				'options'=>array(
					'dateFormat' => 'yy-mm-dd',
				),
				'htmlOptions'=>array(
					'class' => 'form-control',
				 ),
			)); */
			echo $form->dateField($model, 'updated_date', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('slug'); ?>
			<?php echo $form->textField($model, 'slug', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('comment_code'); ?>
			<?php echo $form->dropDownList($model, 'comment_code', array('0'=>Yii::t('phrase', 'No'), '1'=>Yii::t('phrase', 'Yes')), array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('headline'); ?>
			<?php echo $form->dropDownList($model, 'headline', array('0'=>Yii::t('phrase', 'No'), '1'=>Yii::t('phrase', 'Yes')), array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('publish'); ?>
			<?php echo $form->dropDownList($model, 'publish', array('0'=>Yii::t('phrase', 'No'), '1'=>Yii::t('phrase', 'Yes')), array('class'=>'form-control')); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
