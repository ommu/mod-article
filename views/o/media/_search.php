<?php
/**
 * Article Media (article-media)
 * @var $this MediaController
 * @var $model ArticleMedia
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @modified date 24 March 2018, 20:56 WIB
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
			<?php echo $model->getAttributeLabel('category_search'); ?>
			<?php $category = ArticleCategory::getCategory();
			if($category == null)
				echo $form->textField($model, 'category_search', $category, array('prompt'=>'', 'class'=>'form-control'));
			else
				echo $form->textField($model, 'category_search', array('prompt'=>''), array('class'=>'form-control'));?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('article_search'); ?>
			<?php echo $form->textField($model, 'article_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('media_filename'); ?>
			<?php echo $form->textField($model, 'media_filename', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('cover_filename'); ?>
			<?php echo $form->textField($model, 'cover_filename', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('caption'); ?>
			<?php echo $form->textField($model, 'caption', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('description'); ?>
			<?php echo $form->textField($model, 'description', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_date'); ?>
			<?php /* $this->widget('application.libraries.core.components.system.CJuiDatePicker',array(
				'model'=>$model,
				'attribute'=>'creation_date',
				//'mode'=>'datetime',
				'options'=>array(
					'dateFormat' => 'dd-mm-yy',
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
			<?php /* $this->widget('application.libraries.core.components.system.CJuiDatePicker',array(
				'model'=>$model,
				'attribute'=>'modified_date',
				//'mode'=>'datetime',
				'options'=>array(
					'dateFormat' => 'dd-mm-yy',
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
			<?php echo $model->getAttributeLabel('updated_date'); ?>
			<?php /* $this->widget('application.libraries.core.components.system.CJuiDatePicker',array(
				'model'=>$model,
				'attribute'=>'updated_date',
				//'mode'=>'datetime',
				'options'=>array(
					'dateFormat' => 'dd-mm-yy',
				),
				'htmlOptions'=>array(
					'class' => 'form-control',
				 ),
			)); */
			echo $form->dateField($model, 'updated_date', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('orders'); ?>
			<?php echo $form->textField($model, 'orders', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('cover'); ?>
			<?php echo $form->dropDownList($model, 'cover', array('0'=>Yii::t('phrase', 'No'), '1'=>Yii::t('phrase', 'Yes')), array('class'=>'form-control')); ?>
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
