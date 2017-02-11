<?php
/**
 * Articles (articles)
 * @var $this AdminController
 * @var $model Articles
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Articles
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Articles'=>array('manage'),
		$model->title=>array('view','id'=>$model->article_id),
		'Update',
	);
	$medias = $model->medias;
?>

<div class="form" <?php //echo ($model->article_type == 'standard' && $setting->media_limit != 1) ? 'name="post-on"' : ''; ?>>
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'setting'=>$setting,
	)); ?>
</div>

<?php if($model->article_type == 'standard' && $setting->media_limit != 1) {?>
<div class="boxed mt-15">
	<h3><?php echo Yii::t('phrase', 'Article Photo'); ?></h3>
	<div class="clearfix horizontal-data" name="four">
		<ul id="media-render">
			<?php 
			$this->renderPartial('_form_cover', array('model'=>$model, 'medias'=>$medias, 'setting'=>$setting));
			if($medias != null) {
				foreach($medias as $key => $val)
					$this->renderPartial('_form_view_covers', array('data'=>$val));
			}?>
		</ul>
	</div>
</div>
<?php }?>