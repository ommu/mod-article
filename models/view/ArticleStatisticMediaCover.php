<?php
/**
 * ArticleStatisticMediaCover
 *
 * This is the model class for table "_article_statistic_media_cover".
 *
 * The followings are the available columns in table "_article_statistic_media_cover":
 * @property integer $article_id
 * @property string $media_id
 * @property string $media_cover
 * @property string $media_caption

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:30 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\view;

use Yii;
use yii\helpers\Url;

class ArticleStatisticMediaCover extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_article_statistic_media_cover';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['article_id', 'media_cover', 'media_caption'], 'required'],
			[['article_id', 'media_id'], 'integer'],
			[['media_cover'], 'string'],
			[['media_caption'], 'string', 'max' => 150],
		];
	}

	public static function primaryKey() {
		return ['article_id'];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'article_id' => Yii::t('app', 'Article'),
			'media_id' => Yii::t('app', 'Media'),
			'media_cover' => Yii::t('app', 'Media Cover'),
			'media_caption' => Yii::t('app', 'Media Caption'),
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
		$this->templateColumns['article_id'] = 'article_id';
		$this->templateColumns['media_id'] = 'media_id';
		$this->templateColumns['media_cover'] = 'media_cover';
		$this->templateColumns['media_caption'] = 'media_caption';
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
