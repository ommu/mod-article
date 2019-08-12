<?php
/**
 * Article Downloads (article-downloads)
 * @var $this DownloadController
 * @var $model ArticleDownloads
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 23 March 2018, 05:30 WIB
 * @modified date 23 March 2018, 05:30 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

	$this->breadcrumbs=array(
		'Article Downloads'=>array('manage'),
		$model->download_id,
	);
?>

<div class="dialog-content">
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'download_id',
				'value'=>$model->download_id,
			),
			array(
				'name'=>'file_search',
				'value'=>$model->file->file_filename ? $model->file->file_filename : '-',
			),
			array(
				'name'=>'category_search',
				'value'=>$model->file->article->category->title->message ? $model->file->article->category->title->message : '-',
			),
			array(
				'name'=>'article_search',
				'value'=>$model->file->article->title ? $model->file->article->title : '-',
			),
			array(
				'name'=>'user_id',
				'value'=>$model->user->displayname ? $model->user->displayname : '-',
			),
			array(
				'name'=>'downloads',
				'value'=>$model->downloads ? $model->downloads : '-',
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