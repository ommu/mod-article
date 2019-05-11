<?php
/**
 * ArticleLikes
 *
 * This is the model class for table "_article_likes".
 *
 * The followings are the available columns in table "_article_likes":
 * @property string $like_id
 * @property integer $article_id
 * @property string $likes
 * @property string $unlikes
 * @property string $like_all

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:23 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\view;

use Yii;
use yii\helpers\Url;

class ArticleLikes extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_article_likes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['like_id', 'article_id', 'like_all'], 'integer'],
			[['article_id'], 'required'],
			[['likes', 'unlikes'], 'number'],
		];
	}

	public static function primaryKey() {
		return ['like_id'];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'like_id' => Yii::t('app', 'Like'),
			'article_id' => Yii::t('app', 'Article'),
			'likes' => Yii::t('app', 'Likes'),
			'unlikes' => Yii::t('app', 'Unlikes'),
			'like_all' => Yii::t('app', 'Like All'),
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
		$this->templateColumns['like_id'] = 'like_id';
		$this->templateColumns['article_id'] = 'article_id';
		$this->templateColumns['likes'] = 'likes';
		$this->templateColumns['unlikes'] = 'unlikes';
		$this->templateColumns['like_all'] = 'like_all';
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
