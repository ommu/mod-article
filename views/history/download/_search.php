<?php
/**
 * Article Download Histories (article-download-history)
 * @var $this DownloadController
 * @var $model ArticleDownloadHistory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 8 January 2017, 21:21 WIB
 * @modified date 23 March 2018, 16:13 WIB
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
			<?php echo $model->getAttributeLabel('download_date'); ?>
			<?php /* $this->widget('application.libraries.core.components.system.CJuiDatePicker',array(
				'model'=>$model,
				'attribute'=>'download_date',
				//'mode'=>'datetime',
				'options'=>array(
					'dateFormat' => 'yy-mm-dd',
				),
				'htmlOptions'=>array(
					'class' => 'form-control',
				 ),
			)); */
			echo $form->dateField($model, 'download_date', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('download_ip'); ?>
			<?php echo $form->textField($model, 'download_ip', array('class'=>'form-control')); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
