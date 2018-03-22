<?php
/**
 * ArticleMedia (article-media)
 * @var $this MediaController
 * @var $model ArticleMedia
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 7 November 2016, 09:56 WIB
 * @link https://github.com/ommu/ommu-article
 *
 */

	$this->breadcrumbs=array(
		'Article Medias'=>array('manage'),
		$model->media_id=>array('view','id'=>$model->media_id),
		'View',
	);
?>

<div class="box">
<?php $this->widget('application.libraries.core.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'media_id',
		array(
			'name'=>'publish',
			'value'=>$model->publish == 1 ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
			'type' => 'raw',
		),
		array(
			'name'=>'cover',
			'value'=>$model->cover == 1 ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
			'type' => 'raw',
		),
		'parent',
		array(
			'name'=>'article_id',
			'value'=>$model->article->title,
		),
		array(
			'name'=>'media_filename',
			'value'=>$model->cover_filename ? $model->cover_filename : '-',
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
			'name'=>'creation_date',
			'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->creation_date, true) : '-',
		),
		array(
			'name'=>'creation_id',
			'value'=>$model->creation->displayname ? $model->creation->displayname : '-',
		),
		array(
			'name'=>'modified_date',
			'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->modified_date, true) : '-',
		),
		array(
			'name'=>'modified_id',
			'value'=>$model->modified->displayname ? $model->modified->displayname : '-',
		),
	),
)); ?>
</div>