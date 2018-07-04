<?php
/**
 * Article Views (article-views)
 * @var $this ViewController
 * @var $model ArticleViews
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 23 March 2018, 05:30 WIB
 * @modified date 23 March 2018, 05:30 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

	$this->breadcrumbs=array(
		'Article Views'=>array('manage'),
		$model->view_id,
	);
?>

<div class="dialog-content">
	<?php $this->widget('application.libraries.core.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'view_id',
				'value'=>$model->view_id,
			),
			array(
				'name'=>'publish',
				'value'=>$model->publish == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'category_search',
				'value'=>$model->article->category->title->message ? $model->article->category->title->message : '-',
			),
			array(
				'name'=>'article_search',
				'value'=>$model->article->title ? $model->article->title : '-',
			),
			array(
				'name'=>'user_id',
				'value'=>$model->user_id ? $model->user->displayname : '-',
			),
			array(
				'name'=>'views',
				'value'=>$model->views ? $model->views : '-',
			),
			array(
				'name'=>'view_date',
				'value'=>!in_array($model->view_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? Utility::dateFormat($model->view_date, true) : '-',
			),
			array(
				'name'=>'view_ip',
				'value'=>$model->view_ip ? $model->view_ip : '-',
			),
			array(
				'name'=>'deleted_date',
				'value'=>!in_array($model->deleted_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? Utility::dateFormat($model->deleted_date, true) : '-',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>