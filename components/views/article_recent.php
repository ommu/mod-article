<?php
/**
 * @var $this ArticleRecentComponent
 * @var $model ArticleRecent
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-article
 *
 */

if($model != null) {?>
<div class="box recent-news-article">
	<h3>Berita Terbaru</h3>
	<ul>
		<?php 
		$i=0;
		foreach($model as $key => $val) {
		$i++;
			$image = Yii::app()->request->baseUrl.'/public/article/article_default.png';
			$medias = $val->medias;
			if(!empty($medias)) {
				$article_cover = $val->view->article_cover ? $val->view->article_cover : $medias[0]->cover_filename;
				$article_cover = Yii::app()->request->baseUrl.'/public/article/'.$val->article_id.'/'.$article_cover;
			}
			if($i == 1) {?>
				<li <?php echo !empty($medias) ? 'class="solid"' : '';?>>
					<a href="<?php echo Yii::app()->createUrl('article/site/view', array('id'=>$val->article_id, 'slug'=>$this->urlTitle($val->title)))?>" title="<?php echo $val->title?>">
						<?php if(!empty($medias)) {?><img src="<?php echo Utility::getTimThumb($image, 230, 100, 1)?>" alt="<?php echo $val->title?>" /><?php }?>
						<?php echo $val->title?>
					</a>
				</li>
			<?php } else {?>
				<li><a href="<?php echo Yii::app()->createUrl('article/site/view', array('id'=>$val->article_id, 'slug'=>$this->urlTitle($val->title)))?>" title="<?php echo $val->title?>"><?php echo $val->title?></a></li>
			<?php }
		}?>
	</ul>
</div>
<?php }?>