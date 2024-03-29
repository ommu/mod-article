<?php
/**
 * Article Download Histories (article-download-history)
 * @var $this DownloadController
 * @var $model ArticleDownloadHistory
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 23 March 2018, 16:13 WIB
 * @modified date 23 March 2018, 16:13 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

	$this->breadcrumbs=array(
		'Article Download Histories'=>array('manage'),
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
				'value'=>$model->download->file->article->category->title->message ? $model->download->file->article->category->title->message : '-',
			),
			array(
				'name'=>'article_search',
				'value'=>$model->download->file->article->title ? $model->download->file->article->title : '-',
			),
			array(
				'name'=>'user_search',
				'value'=>$model->download->user->displayname ? $model->download->user->displayname : '-',
			),
			array(
				'name'=>'download_date',
				'value'=>!in_array($model->download_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->download_date) : '-',
			),
			array(
				'name'=>'download_ip',
				'value'=>$model->download_ip ? $model->download_ip : '-',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>