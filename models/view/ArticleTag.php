<?php
/**
 * ArticleTag
 *
 * This is the model class for table "_article_tag".
 *
 * The followings are the available columns in table "_article_tag":
 * @property string $tag_id
 * @property string $articles
 * @property string $article_all

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:34 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\view;

use Yii;
use yii\helpers\Url;

class ArticleTag extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_article_tag';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['tag_id'], 'required'],
			[['tag_id', 'article_all'], 'integer'],
			[['articles'], 'number'],
		];
	}

	public static function primaryKey() {
		return ['tag_id'];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'tag_id' => Yii::t('app', 'Tag'),
			'articles' => Yii::t('app', 'Articles'),
			'article_all' => Yii::t('app', 'Article All'),
		];
	}
	
	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['tag_id'] = 'tag_id';
		$this->templateColumns['articles'] = 'articles';
		$this->templateColumns['article_all'] = 'article_all';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {
			// Create action
		}
		return true;	
	}

	/**
	 * after validate attributes
	 */
	public function afterValidate()
	{
		parent::afterValidate();
		// Create action
		
		return true;
	}
	
	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
		// Create action
	}

	/**
	 * Before delete attributes
	 */
	public function beforeDelete()
	{
		if(parent::beforeDelete()) {
			// Create action
		}
		return true;
	}

	/**
	 * After delete attributes
	 */
	public function afterDelete()
	{
		parent::afterDelete();
		// Create action
	}
}
