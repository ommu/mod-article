<?php
/**
 * Article Media (article-media)
 * @var $this MediaController
 * @var $model ArticleMedia
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 7 November 2016, 09:56 WIB
 * @modified date 24 March 2018, 20:56 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

	$this->breadcrumbs=array(
		'Article Media'=>array('manage'),
		$model->media_filename,
	);
?>

<div class="box">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'media_id',
			'value'=>$model->media_id,
		),
		array(
			'name'=>'publish',
			'value'=>$this->quickAction(Yii::app()->controller->createUrl('publish', array('id'=>$model->media_id)), $model->publish),
			'type'=>'raw',
		),
		array(
			'name'=>'cover',
			'value'=>$model->cover == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
			'type'=>'raw',
		),
		array(
			'name'=>'orders',
			'value'=>$model->orders ? $model->orders : '-',
		),
		array(
			'name'=>'article_id',
			'value'=>$model->article_id ? $model->article->title : '-',
		),
		array(
			'name'=>'media_filename',
			'value'=>$model->media_filename ? $model->media_filename : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'cover_filename',
			'value'=>$model->cover_filename ? CHtml::image(Utility::getTimThumb(Yii::app()->request->baseUrl.'/public/article/'.$model->article_id.'/'.$model->cover_filename, 600, 600, 3)) : '-',
			'type' => 'raw',
		),
		array(
			'name'=>'caption',
			'value'=>$model->caption ? $model->caption : '-',
		),
		array(
			'name'=>'description',
			'value'=>$model->description ? $model->description : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'creation_date',
			'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->creation_date) : '-',
		),
		array(
			'name'=>'creation_search',
			'value'=>$model->creation->displayname ? $model->creation->displayname : '-',
		),
		array(
			'name'=>'modified_date',
			'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->modified_date) : '-',
		),
		array(
			'name'=>'modified_search',
			'value'=>$model->modified->displayname ? $model->modified->displayname : '-',
		),
		array(
			'name'=>'updated_date',
			'value'=>!in_array($model->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->updated_date) : '-',
		),
	),
)); ?>
</div>