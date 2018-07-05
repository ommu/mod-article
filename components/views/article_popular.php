<?php
/**
 * @var $this ArticlePopularComponent
 * @var $model ArticlePopular
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-article
 *
 */

if($model != null) {?>
<div class="boxed" id="hottest">
	<h2>Terpanas</h2>
	<div class="box">
		<ul>
		<?php 
		foreach($model as $key => $val) {?>
			<li><a href="<?php echo Yii::app()->createUrl('article/'.$controller.'/view', array('id'=>$val->article_id, 'slug'=>$this->urlTitle($val->title)))?>" title="<?php echo $val->title;?>"><?php echo $val->title;?></a></li>
		<?php }?>
		</ul>
	</div>
</div>
<?php }?>