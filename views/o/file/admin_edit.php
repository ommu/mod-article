<?php
/**
 * Article Files (article-files)
 * @var $this FileController
 * @var $model ArticleFiles
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 22 March 2018, 08:59 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

	$this->breadcrumbs=array(
		'Article Files'=>array('manage'),
		$model->file_filename=>array('view','id'=>$model->file_id),
		'Update',
	);
?>

<div class="form">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'media_file_type'=>$media_file_type,
	)); ?>
</div>