<?php
/**
 * @var $this ArticleCategoryComponent
 * @var $model ArticleCategory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-article
 *
 */

if($model != null) {?>
	<ul>
	<?php 
	echo '<li><a href="'.Yii::app()->controller->createUrl('index').'" title="'.Yii::t('phrase', 'All').'">'.Yii::t('phrase', 'All').'</a></li>';
	foreach($model as $key => $val) {
		echo '<li><a href="'.Yii::app()->controller->createUrl('index', array('cat'=>$val->cat_id, 'slug'=>$this->urlTitle($val->title->message))).'" title="'.$val->title->message.'">'.$val->title->message.'</a></li>';
	}?>
	</ul>
<?php }?>
