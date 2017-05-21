<?php
/**
 * Articles (articles)
 * @var $this SiteController
 * @var $model Articles
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-article
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Articles'=>array('manage'),
		Phrase::trans($model->cat->name,2),
		$model->title,
	);
?>

<?php $this->widget('application.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'article_id',
		'publish',
		'cat_id',
		'user_id',
		'media_id',
		'headline',
		'comment_code',
		'article_type',
		'title',
		'body',
		'quote',
		'media_file',
		'published_date',
		'comment',
		'view',
		'likes',
		'download',
		'creation_date',
		'modified_date',
	),
)); ?>

<?php if($random != null) {
	foreach($random as $key => $row) { ?>
		<a href="<?php echo Yii::app()->controller->createUrl('view', array('id'=>$row->article_id,'t'=>Utility::getUrlTitle($row->title)));?>" title="<?php echo $row->title;?>"><?php echo Utility::shortText(Utility::hardDecode($row->title),40);?></a>
		<br/><?php echo Utility::dateFormat($row->published_date, true);?>
		<br/><?php echo $row->view;?>
		<p><?php echo Utility::shortText(Utility::hardDecode($row->body),100);?></p>
<?php }
}?>