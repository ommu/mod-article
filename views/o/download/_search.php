<?php
/**
 * Article Downloads (article-downloads)
 * @var $this DownloadController
 * @var $model ArticleDownloads
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 8 January 2017, 20:54 WIB
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
			<?php echo $model->getAttributeLabel('file_search'); ?>
			<?php echo $form->textField($model, 'file_search', array('class'=>'form-control')); ?>
		</li>

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
			<?php echo $model->getAttributeLabel('downloads'); ?>
			<?php echo $form->textField($model, 'downloads', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('download_date'); ?>
			<?php echo $this->filterDatepicker($model, 'download_date', false); ?>
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
