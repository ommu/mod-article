<?php
/**
 * @var $this ArticleHeadlineComponent
 * @var $model ArticleHeadline
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-article
 *
 */

if($model != null) {?>
<div class="boxed" id="headline">
	<h2></h2>
	<div class="list-view">
		<?php foreach($model as $key => $val) {?>
		<div class="sep">
			<?php
			$article_cover = Yii::app()->request->baseUrl.'/public/article/article_default.png';
			if($val->view->article_cover != '')
				$article_cover = Yii::app()->request->baseUrl.'/public/article/'.$val->article_id.'/'.$val->view->article_cover;?>

			<a class="img" href="<?php echo Yii::app()->createUrl('article/'.$controller.'/view', array('id'=>$val->article_id, 'slug'=>$this->urlTitle($val->title)));?>" title="<?php echo $val->title;?>"><img src="<?php echo Utility::getTimThumb($article_cover, 400, 270, 1);?>"></a> 
			<div class="date">
				<?php echo Utility::dateFormat($val->creation_date, true);?>
				<?php //begin.Tools ?>
				<div class="tools">
					<?php /* if(Yii::app()->params['article_mod_comment'] == 1) {?><span class="comment"><?php echo $val->comment;?></span><?php } */?>
					<?php if(Yii::app()->params['article_mod_view'] == 1) {?><span class="view"><?php echo $val->view->views ? $val->view->views : 0;?></span><?php }?>
					<?php if(Yii::app()->params['article_mod_like'] == 1) {?><span class="like"><?php echo $val->view->likes ? $val->view->likes : 0;?></span><?php }?>
				</div>
				<?php //end.Tools ?>
			</div>
			<a class="title" href="<?php echo Yii::app()->createUrl('article/'.$controller.'/view', array('id'=>$val->article_id, 'slug'=>$this->urlTitle($val->title)));?>" title="<?php echo $val->title;?>"><?php echo $val->title;?></a><br/>
			<p><?php echo Utility::shortText(Utility::hardDecode($val->body),300,' ...'); ?></p>
		</div>
		<?php }?>
	</div>
</div>
<?php }?>