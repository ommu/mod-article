<?php
/**
 * Article View Histories (article-view-history)
 * @var $this ViewController
 * @var $model ArticleViewHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 23 March 2018, 16:13 WIB
 * @modified date 23 March 2018, 16:13 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

	$this->breadcrumbs=array(
		'Article View Histories'=>array('manage'),
		$model->id,
	);
?>

<div class="dialog-content">
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'id',
				'value'=>$model->id,
			),
			array(
				'name'=>'category_search',
				'value'=>$model->view->article->category->title->message ? $model->view->article->category->title->message : '-',
			),
			array(
				'name'=>'article_search',
				'value'=>$model->view->article->title ? $model->view->article->title : '-',
			),
			array(
				'name'=>'user_search',
				'value'=>$model->view->user->displayname ? $model->view->user->displayname : '-',
			),
			array(
				'name'=>'view_date',
				'value'=>!in_array($model->view_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->view_date, true) : '-',
			),
			array(
				'name'=>'view_ip',
				'value'=>$model->view_ip ? $model->view_ip : '-',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>