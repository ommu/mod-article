<?php
/**
 * Articles (articles)
 * @var $this AdminController
 * @var $model Articles
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @modified date 26 March 2018, 14:07 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

	$this->breadcrumbs=array(
		'Articles'=>array('manage'),
		$model->title=>array('view','id'=>$model->article_id),
		'Update',
	);

	$medias = $model->medias;
	$media_image_limit = $setting->media_image_limit;
	$photoCondition = 0;
	$fileCondition = 0;
	if($media_image_limit != 1 && $model->category->single_photo == 0)
		$photoCondition = 1;
	if($media_file_limit != 1 && $model->category->single_file == 0)
		$fileCondition = 1;
?>

<div class="form">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'setting'=>$setting,
		'photoCondition'=>$photoCondition,
		'fileCondition'=>$fileCondition,
		'media_image_type'=>$media_image_type,
		'media_file_type'=>$media_file_type,
	)); ?>
</div>

<?php if($photoCondition == 1) {?>
<div class="boxed mt-15">
	<h3><?php echo Yii::t('phrase', 'Article Photo'); ?></h3>
	<div class="clearfix horizontal-data" name="four">
		<ul id="media-render">
			<?php 
			$this->renderPartial('_form_cover', array('model'=>$model, 'medias'=>$medias, 'media_image_limit'=>$media_image_limit));
			if(!empty($medias)) {
				foreach($medias as $key => $data)
					$this->renderPartial('_form_view_covers', array('data'=>$data));
			}?>
		</ul>
	</div>
</div>
<?php }?>