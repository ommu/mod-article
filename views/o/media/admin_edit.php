<?php
/**
 * Article Media (article-media)
 * @var $this MediaController
 * @var $model ArticleMedia
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @modified date 24 March 2018, 20:56 WIB
 * @link https://github.com/ommu/ommu-article
 *
 */

	$this->breadcrumbs=array(
		'Article Media'=>array('manage'),
		$model->cover_filename=>array('view','id'=>$model->media_id),
		'Update',
	);
?>

<div class="form">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'media_image_type'=>$media_image_type,
	)); ?>
</div>
