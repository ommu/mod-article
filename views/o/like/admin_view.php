<?php
/**
 * Article Likes (article-likes)
 * @var $this LikeController
 * @var $model ArticleLikes
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
		'Article Likes'=>array('manage'),
		$model->like_id,
	);
?>

<div class="dialog-content">
	<?php $this->widget('application.libraries.core.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'like_id',
				'value'=>$model->like_id,
			),
			array(
				'name'=>'publish',
				'value'=>$model->publish == '1' ? Yii::t('phrase', 'Like') : Yii::t('phrase', 'Unlike'),
			),
			array(
				'name'=>'article_search',
				'value'=>$model->article->title ? $model->article->title : '-',
			),
			array(
				'name'=>'category_search',
				'value'=>$model->article->category->title->message ? $model->article->category->title->message : '-',
			),
			array(
				'name'=>'user_id',
				'value'=>$model->user_id ? $model->user->displayname : '-',
			),
			array(
				'name'=>'likes_date',
				'value'=>!in_array($model->likes_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->likes_date, true) : '-',
			),
			array(
				'name'=>'likes_ip',
				'value'=>$model->likes_ip ? $model->likes_ip : '-',
			),
			array(
				'name'=>'updated_date',
				'value'=>!in_array($model->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->updated_date, true) : '-',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>