<?php
/**
 * Article Likes (article-likes)
 * @var $this LikeController
 * @var $model ArticleLikes
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 23 March 2018, 05:30 WIB
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
			<?php echo $model->getAttributeLabel('user_search'); ?>
			<?php echo $form->textField($model, 'user_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('likes_date'); ?>
			<?php /* $this->widget('application.libraries.core.components.system.CJuiDatePicker', array(
				'model'=>$model,
				'attribute'=>'likes_date',
				//'mode'=>'datetime',
				'options'=>array(
					'dateFormat' => 'yy-mm-dd',
				),
				'htmlOptions'=>array(
					'class' => 'form-control',
				 ),
			)); */
			echo $form->dateField($model, 'likes_date', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('likes_ip'); ?>
			<?php echo $form->textField($model, 'likes_ip', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('updated_date'); ?>
			<?php /* $this->widget('application.libraries.core.components.system.CJuiDatePicker', array(
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
			<?php echo $model->getAttributeLabel('publish'); ?>
			<?php echo $form->dropDownList($model, 'publish', array('0'=>Yii::t('phrase', 'Unlike'), '1'=>Yii::t('phrase', 'Like')), array('class'=>'form-control')); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
