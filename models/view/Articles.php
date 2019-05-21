<?php
/**
 * Articles
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:17 WIB
 * @modified date 21 May 2019, 12:55 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "_articles".
 *
 * The followings are the available columns in table "_articles":
 * @property integer $id
 * @property string $images
 * @property integer $image_all
 * @property string $files
 * @property integer $file_all
 * @property string $views
 * @property string $view_all
 * @property string $downloads
 * @property integer $tags
 * @property string $likes
 * @property integer $like_all
 *
 */

namespace ommu\article\models\view;

use Yii;

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
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['id', 'image_all', 'file_all', 'tags', 'like_all'], 'integer'],
			[['images', 'files', 'views', 'view_all', 'downloads', 'likes'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'images' => Yii::t('app', 'Images'),
			'image_all' => Yii::t('app', 'Image All'),
			'files' => Yii::t('app', 'Files'),
			'file_all' => Yii::t('app', 'File All'),
			'views' => Yii::t('app', 'Views'),
			'view_all' => Yii::t('app', 'View All'),
			'downloads' => Yii::t('app', 'Downloads'),
			'tags' => Yii::t('app', 'Tags'),
			'likes' => Yii::t('app', 'Likes'),
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
		$this->templateColumns['id'] = [
			'attribute' => 'id',
			'value' => function($model, $key, $index, $column) {
				return $model->id;
			},
		];
		$this->templateColumns['images'] = [
			'attribute' => 'images',
			'value' => function($model, $key, $index, $column) {
				return $model->images;
			},
		];
		$this->templateColumns['image_all'] = [
			'attribute' => 'image_all',
			'value' => function($model, $key, $index, $column) {
				return $model->image_all;
			},
		];
		$this->templateColumns['files'] = [
			'attribute' => 'files',
			'value' => function($model, $key, $index, $column) {
				return $model->files;
			},
		];
		$this->templateColumns['file_all'] = [
			'attribute' => 'file_all',
			'value' => function($model, $key, $index, $column) {
				return $model->file_all;
			},
		];
		$this->templateColumns['views'] = [
			'attribute' => 'views',
			'value' => function($model, $key, $index, $column) {
				return $model->views;
			},
		];
		$this->templateColumns['view_all'] = [
			'attribute' => 'view_all',
			'value' => function($model, $key, $index, $column) {
				return $model->view_all;
			},
		];
		$this->templateColumns['downloads'] = [
			'attribute' => 'downloads',
			'value' => function($model, $key, $index, $column) {
				return $model->downloads;
			},
		];
		$this->templateColumns['tags'] = [
			'attribute' => 'tags',
			'value' => function($model, $key, $index, $column) {
				return $model->tags;
			},
		];
		$this->templateColumns['likes'] = [
			'attribute' => 'likes',
			'value' => function($model, $key, $index, $column) {
				return $model->likes;
			},
		];
		$this->templateColumns['like_all'] = [
			'attribute' => 'like_all',
			'value' => function($model, $key, $index, $column) {
				return $model->like_all;
			},
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
