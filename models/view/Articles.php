<?php
/**
 * Articles
 *
 * This is the model class for table "_articles".
 *
 * The followings are the available columns in table "_articles":
 * @property integer $article_id
 * @property string $media_id
 * @property string $media_cover
 * @property string $media_caption
 * @property string $medias
 * @property string $media_all
 * @property string $files
 * @property string $file_all
 * @property string $likes
 * @property string $like_all
 * @property string $views
 * @property string $view_all
 * @property string $downloads
 * @property string $download_all
 * @property string $tags

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:17 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\view;

use Yii;
use yii\helpers\Url;

class Articles extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_articles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['article_id', 'media_id', 'media_all', 'file_all', 'like_all', 'tags'], 'integer'],
			[['media_cover'], 'string'],
			[['medias', 'files', 'likes', 'views', 'view_all', 'downloads', 'download_all'], 'number'],
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
			'medias' => Yii::t('app', 'Medias'),
			'media_all' => Yii::t('app', 'Media All'),
			'files' => Yii::t('app', 'Files'),
			'file_all' => Yii::t('app', 'File All'),
			'likes' => Yii::t('app', 'Likes'),
			'like_all' => Yii::t('app', 'Like All'),
			'views' => Yii::t('app', 'Views'),
			'view_all' => Yii::t('app', 'View All'),
			'downloads' => Yii::t('app', 'Downloads'),
			'download_all' => Yii::t('app', 'Download All'),
			'tags' => Yii::t('app', 'Tags'),
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
		$this->templateColumns['medias'] = 'medias';
		$this->templateColumns['media_all'] = 'media_all';
		$this->templateColumns['files'] = 'files';
		$this->templateColumns['file_all'] = 'file_all';
		$this->templateColumns['likes'] = 'likes';
		$this->templateColumns['like_all'] = 'like_all';
		$this->templateColumns['views'] = 'views';
		$this->templateColumns['view_all'] = 'view_all';
		$this->templateColumns['downloads'] = 'downloads';
		$this->templateColumns['download_all'] = 'download_all';
		$this->templateColumns['tags'] = 'tags';
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
