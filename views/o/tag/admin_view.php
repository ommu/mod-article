<?php
/**
 * Article Tags (article-tag)
 * @var $this TagController
 * @var $model ArticleTag
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (opensource.ommu.co)
 * @created date 23 March 2018, 05:30 WIB
 * @modified date 23 March 2018, 05:30 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

	$this->breadcrumbs=array(
		'Article Tags'=>array('manage'),
		$model->id,
	);
?>

<div class="dialog-content">
	<?php $this->widget('application.libraries.core.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'id',
				'value'=>$model->id,
			),
			array(
				'name'=>'publish',
				'value'=>$model->publish == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'tag_id',
				'value'=>$model->tag_id ? str_replace('-', ' ', $model->tag->body) : '-',
			),
			array(
				'name'=>'category_search',
				'value'=>$model->article->category->title->message ? $model->article->category->title->message : '-',
			),
			array(
				'name'=>'article_id',
				'value'=>$model->article_id ? $model->article->title : '-',
			),
			array(
				'name'=>'creation_date',
				'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? Utility::dateFormat($model->creation_date, true) : '-',
			),
			array(
				'name'=>'creation_id',
				'value'=>$model->creation_id ? $model->creation->displayname : '-',
			),
			array(
				'name'=>'modified_date',
				'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? Utility::dateFormat($model->modified_date, true) : '-',
			),
			array(
				'name'=>'modified_id',
				'value'=>$model->modified_id ? $model->modified->displayname : '-',
			),
			array(
				'name'=>'updated_date',
				'value'=>!in_array($model->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? Utility::dateFormat($model->updated_date, true) : '-',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>