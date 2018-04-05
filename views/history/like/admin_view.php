<?php
/**
 * Article Like Histories (article-like-history)
 * @var $this LikeController
 * @var $model ArticleLikeHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (opensource.ommu.co)
 * @created date 23 March 2018, 16:13 WIB
 * @modified date 23 March 2018, 16:13 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

	$this->breadcrumbs=array(
		'Article Like Histories'=>array('manage'),
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
				'value'=>$model->publish == 1 ? Yii::t('phrase', 'Like') : Yii::t('phrase', 'Unlike'),
			),
			array(
				'name'=>'category_search',
				'value'=>$model->like->article->category->title->message ? $model->like->article->category->title->message : '-',
			),
			array(
				'name'=>'article_search',
				'value'=>$model->like->article->title ? $model->like->article->title : '-',
			),
			array(
				'name'=>'user_search',
				'value'=>$model->like->user->displayname ? $model->like->user->displayname : '-',
			),
			array(
				'name'=>'likes_date',
				'value'=>!in_array($model->likes_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->likes_date, true) : '-',
			),
			array(
				'name'=>'likes_ip',
				'value'=>$model->likes_ip ? $model->likes_ip : '-',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>