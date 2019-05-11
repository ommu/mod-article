<?php
/**
 * ArticleStatisticFile
 *
 * This is the model class for table "_article_statistic_file".
 *
 * The followings are the available columns in table "_article_statistic_file":
 * @property integer $article_id
 * @property string $files
 * @property string $file_all

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:25 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\view;

use Yii;
use yii\helpers\Url;

class ArticleStatisticFile extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_article_statistic_file';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['article_id'], 'required'],
			[['article_id', 'file_all'], 'integer'],
			[['files'], 'number'],
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
			'files' => Yii::t('app', 'Files'),
			'file_all' => Yii::t('app', 'File All'),
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
		$this->templateColumns['files'] = 'files';
		$this->templateColumns['file_all'] = 'file_all';
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
