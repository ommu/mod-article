<?php
/**
 * Article Downloads (article-downloads)
 * @var $this DownloadController
 * @var $model ArticleDownloads
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (opensource.ommu.co)
 * @created date 23 March 2018, 05:30 WIB
 * @modified date 23 March 2018, 05:30 WIB
 * @link https://github.com/ommu/ommu-article
 *
 */

	$this->breadcrumbs=array(
		'Article Downloads'=>array('manage'),
		$model->download_id,
	);
?>

<div class="dialog-content">
	<?php $this->widget('application.libraries.core.components.system.FDetailView', array(
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
				'value'=>$model->user_id ? $model->user->displayname : '-',
			),
			array(
				'name'=>'downloads',
				'value'=>$model->downloads ? $model->downloads : '-',
			),
			array(
				'name'=>'download_date',
				'value'=>!in_array($model->download_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->download_date, true) : '-',
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