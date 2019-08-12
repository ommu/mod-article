<?php
/**
 * ArticleSetting
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 09:25 WIB
 * @modified date 11 May 2019, 22:46 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_setting".
 *
 * The followings are the available columns in table "ommu_article_setting":
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_description
 * @property string $meta_keyword
 * @property integer $headline
 * @property integer $headline_limit
 * @property string $headline_category
 * @property integer $media_image_limit
 * @property integer $media_image_resize
 * @property string $media_image_resize_size
 * @property string $media_image_view_size
 * @property string $media_image_type
 * @property integer $media_file_limit
 * @property string $media_file_type
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property Users $modified
 *
 */

namespace ommu\article\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use ommu\users\models\Users;

class ArticleSetting extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = [];

	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_article_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['license', 'permission', 'meta_description', 'meta_keyword', 'media_image_type', 'media_file_type'], 'required'],
			[['permission', 'headline', 'headline_limit', 'media_image_limit', 'media_image_resize', 'media_file_limit', 'modified_id'], 'integer'],
			[['meta_description', 'meta_keyword'], 'string'],
			[['headline', 'headline_limit', 'headline_category', 'media_image_limit', 'media_image_resize', 'media_image_resize_size', 'media_image_view_size', 'media_file_limit'], 'safe'],
			//[['headline_category', 'media_image_resize_size', 'media_image_view_size', 'media_image_type', 'media_file_type'], 'serialize'],
			[['license'], 'string', 'max' => 32],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'license' => Yii::t('app', 'License'),
			'permission' => Yii::t('app', 'Permission'),
			'meta_description' => Yii::t('app', 'Meta Description'),
			'meta_keyword' => Yii::t('app', 'Meta Keyword'),
			'headline' => Yii::t('app', 'Headline'),
			'headline_limit' => Yii::t('app', 'Headline Limit'),
			'headline_category' => Yii::t('app', 'Headline Category'),
			'media_image_limit' => Yii::t('app', 'Image Limit'),
			'media_image_resize' => Yii::t('app', 'Image Resize'),
			'media_image_resize_size' => Yii::t('app', 'Image Resize Size'),
			'media_image_view_size' => Yii::t('app', 'Image View Size'),
			'media_image_view_size[small]' => Yii::t('app', 'Small'),
			'media_image_view_size[medium]' => Yii::t('app', 'Medium'),
			'media_image_view_size[large]' => Yii::t('app', 'Large'),
			'media_image_type' => Yii::t('app', 'Photo File Type'),
			'media_file_limit' => Yii::t('app', 'Document Limit'),
			'media_file_type' => Yii::t('app', 'Document File Type'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'width' => Yii::t('app', 'Width'),
			'height' => Yii::t('app', 'Height'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\query\ArticleSetting the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\article\models\query\ArticleSetting(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['license'] = [
			'attribute' => 'license',
			'value' => function($model, $key, $index, $column) {
				return $model->license;
			},
		];
		$this->templateColumns['permission'] = [
			'attribute' => 'permission',
			'value' => function($model, $key, $index, $column) {
				return self::getPermission($model->permission);
			},
		];
		$this->templateColumns['meta_description'] = [
			'attribute' => 'meta_description',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_description;
			},
		];
		$this->templateColumns['meta_keyword'] = [
			'attribute' => 'meta_keyword',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_keyword;
			},
		];
		$this->templateColumns['headline'] = [
			'attribute' => 'headline',
			'value' => function($model, $key, $index, $column) {
				return self::getHeadline($model->headline);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['headline_limit'] = [
			'attribute' => 'headline_limit',
			'value' => function($model, $key, $index, $column) {
				return $model->headline_limit;
			},
		];
		$this->templateColumns['headline_category'] = [
			'attribute' => 'headline_category',
			'value' => function($model, $key, $index, $column) {
				return serialize($model->headline_category);
			},
		];
		$this->templateColumns['media_image_limit'] = [
			'attribute' => 'media_image_limit',
			'value' => function($model, $key, $index, $column) {
				return $model->media_image_limit;
			},
		];
		$this->templateColumns['media_image_resize'] = [
			'attribute' => 'media_image_resize',
			'value' => function($model, $key, $index, $column) {
				return self::getMediaImageResize($model->media_image_resize);
			},
			'filter' => self::getMediaImageResize(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['media_image_resize_size'] = [
			'attribute' => 'media_image_resize_size',
			'value' => function($model, $key, $index, $column) {
				return self::getSize($model->media_image_resize_size);
			},
		];
		$this->templateColumns['media_image_view_size'] = [
			'attribute' => 'media_image_view_size',
			'value' => function($model, $key, $index, $column) {
				return self::parseImageViewSize($model->media_image_view_size);
			},
			'format' => 'html',
		];
		$this->templateColumns['media_image_type'] = [
			'attribute' => 'media_image_type',
			'value' => function($model, $key, $index, $column) {
				return $model->media_image_type;
			},
		];
		$this->templateColumns['media_file_limit'] = [
			'attribute' => 'media_file_limit',
			'value' => function($model, $key, $index, $column) {
				return $model->media_file_limit;
			},
		];
		$this->templateColumns['media_file_type'] = [
			'attribute' => 'media_file_type',
			'value' => function($model, $key, $index, $column) {
				return $model->media_file_type;
			},
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modifiedDisplayname'] = [
				'attribute' => 'modifiedDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
					// return $model->modifiedDisplayname;
				},
			];
		}
	}

	/**
	 * User get information
	 */
	public static function getInfo($column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['id' => 1])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne(1);
			return $model;
		}
	}

	/**
	 * function getPermission
	 */
	public static function getPermission($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, the public can view "module name" unless they are made private.'),
			0 => Yii::t('app', 'No, the public cannot view "module name".'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getHeadline
	 */
	public static function getHeadline($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Enable'),
			0 => Yii::t('app', 'Disable'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getMediaImageResize
	 */
	public static function getMediaImageResize($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, resize photo after upload.'),
			0 => Yii::t('app', 'No, not resize photo after upload.'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getSize
	 */
	public function getSize($size)
	{
		if(empty($size))
			return '-';

		$width = $size['width'] ? $size['width'] : '~';
		$height = $size['height'] ? $size['height'] : '~';
		return $width.' x '.$height;
	}

	/**
	 * function parseImageViewSize
	 */
	public function parseImageViewSize($view_size)
	{
		if(empty($view_size))
			return '-';

		$views = [];
		foreach ($view_size as $key => $value) {
			$views[] = ucfirst($key).": ".self::getSize($value);
		}
		return Html::ul($views, ['encode'=>false, 'class'=>'list-boxed']);
	}

	/**
	 * function getHeadlineCategory
	 */
	public function getHeadlineCategory($headlineCategory)
	{
		$headlineCategory = unserialize($headlineCategory);
		return $headlineCategory;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->headline_category = unserialize($this->headline_category);
		$this->media_image_resize_size = unserialize($this->media_image_resize_size);
		$this->media_image_view_size = unserialize($this->media_image_view_size);
		$media_image_type = unserialize($this->media_image_type);
		if(!empty($media_image_type))
			$this->media_image_type = $this->formatFileType($media_image_type, false);
		$media_file_type = unserialize($this->media_file_type);
		if(!empty($media_file_type))
			$this->media_file_type = $this->formatFileType($media_file_type, false);
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if(!$this->isNewRecord) {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}

			if($this->media_image_resize_size['width'] == '')
				$this->addError('media_image_resize_size', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('media_image_resize_size')]));

			if($this->media_image_view_size['small']['width'] == '' || $this->media_image_view_size['medium']['width'] == '' || $this->media_image_view_size['large']['width'] == '')
				$this->addError('media_image_view_size', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('media_image_view_size')]));
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {
			$this->headline_category = serialize($this->headline_category);
			$this->media_image_resize_size = serialize($this->media_image_resize_size);
			$this->media_image_view_size = serialize($this->media_image_view_size);
			$this->media_image_type = serialize($this->formatFileType($this->media_image_type));
			$this->media_file_type = serialize($this->formatFileType($this->media_file_type));
		}
		return true;
	}
}
