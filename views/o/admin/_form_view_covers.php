<?php
/**
 * Articles (articles)
 * @var $this AdminController
 * @var $model Articles
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 20 October 2016, 10:14 WIB
 * @modified date 26 March 2018, 14:07 WIB
 * @link https://github.com/ommu/mod-article
 *
 */
?>

<?php if($data->cover_filename != '') {?>
<li>
	<?php if($data->cover == 0) {?>
		<a id="set-cover" href="<?php echo Yii::app()->controller->createUrl('o/media/setcover', array('id'=>$data->media_id, 'hook'=>'admin'));?>" title="<?php echo Yii::t('phrase', 'Set Cover');?>"><?php echo Yii::t('phrase', 'Set Cover');?></a>
	<?php }?>
	<a id="set-delete" href="<?php echo Yii::app()->controller->createUrl('o/media/delete', array('id'=>$data->media_id, 'hook'=>'admin'));?>" title="<?php echo Yii::t('phrase', 'Delete Photo');?>"><?php echo Yii::t('phrase', 'Delete Photo');?></a>
	<?php 
	$article_cover = Yii::app()->request->baseUrl.'/public/article/'.$data->article_id.'/'.$data->cover_filename;?>
	<img src="<?php echo Utility::getTimThumb($article_cover, 320, 250, 1);?>" alt="<?php echo $data->article->title;?>" />
</li>
<?php }?>