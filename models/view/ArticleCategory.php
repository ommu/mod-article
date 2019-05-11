<?php
/**
 * ArticleCategory
 *
 * This is the model class for table "_article_category".
 *
 * The followings are the available columns in table "_article_category":
 * @property integer $cat_id
 * @property string $articles
 * @property string $article_pending
 * @property string $article_unpublish
 * @property string $article_all
 * @property integer $article_id

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:18 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\view;

use Yii;
use yii\helpers\Url;

class ArticleCategory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_article_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['cat_id', 'article_all', 'article_id'], 'integer'],
			[['articles', 'article_pending', 'article_unpublish'], 'number'],
		];
	}

	public static function primaryKey() {
		return ['cat_id'];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'cat_id' => Yii::t('app', 'Category'),
			'articles' => Yii::t('app', 'Articles'),
			'article_pending' => Yii::t('app', 'Article Pending'),
			'article_unpublish' => Yii::t('app', 'Article Unpublish'),
			'article_all' => Yii::t('app', 'Article All'),
			'article_id' => Yii::t('app', 'Article'),
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
		$this->templateColumns['cat_id'] = 'cat_id';
		$this->templateColumns['articles'] = 'articles';
		$this->templateColumns['article_pending'] = 'article_pending';
		$this->templateColumns['article_unpublish'] = 'article_unpublish';
		$this->templateColumns['article_all'] = 'article_all';
		$this->templateColumns['article_id'] = 'article_id';
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
