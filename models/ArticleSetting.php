<?php
/**
 * ArticleSetting

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 09:25 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_setting".
 *
 * The followings are the available columns in table "ommu_article_setting":
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_keyword
 * @property string $meta_description
 * @property integer $headline
 * @property integer $headline_limit
 * @property string $headline_category
 * @property integer $media_limit
 * @property integer $media_resize
 * @property string $media_resize_size
 * @property string $media_view_size
 * @property string $media_file_type
 * @property string $upload_file_type
 * @property string $modified_date
 * @property integer $modified_id
 *
 */

namespace ommu\article\models;

use Yii;
use yii\helpers\Url;
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
			[['license', 'permission', 'meta_keyword', 'meta_description', 'headline', 'headline_limit', 'headline_category', 'media_limit', 'media_resize', 'media_resize_size', 'media_view_size', 'media_file_type', 'upload_file_type'], 'required'],
			[['permission', 'headline', 'headline_limit', 'media_limit', 'media_resize', 'modified_id'], 'integer'],
			[['meta_keyword', 'meta_description'], 'string'],
			[['modified_date'], 'safe'],
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
			'meta_keyword' => Yii::t('app', 'Meta Keyword'),
			'meta_description' => Yii::t('app', 'Meta Description'),
			'headline' => Yii::t('app', 'Headline'),
			'headline_limit' => Yii::t('app', 'Headline Limit'),
			'headline_category' => Yii::t('app', 'Headline Category'),
			'media_limit' => Yii::t('app', 'Media Limit'),
			'media_resize' => Yii::t('app', 'Media Resize'),
			'media_resize_size' => Yii::t('app', 'Media Resize Size'),
			'media_view_size' => Yii::t('app', 'Media View Size'),
			'media_file_type' => Yii::t('app', 'Media File Type'),
			'upload_file_type' => Yii::t('app', 'Upload File Type'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'media_view_size[small]' => Yii::t('app', 'Small'),
			'media_view_size[medium]' => Yii::t('app', 'Medium'),
			'media_view_size[large]' => Yii::t('app', 'Large'),
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
		$this->templateColumns['license'] = 'license';
		$this->templateColumns['meta_keyword'] = 'meta_keyword';
		$this->templateColumns['meta_description'] = 'meta_description';
		$this->templateColumns['headline_limit'] = 'headline_limit';
		$this->templateColumns['headline_category'] = 'headline_category';
		$this->templateColumns['media_limit'] = 'media_limit';
		$this->templateColumns['media_resize_size'] = 'media_resize_size';
		$this->templateColumns['media_view_size'] = 'media_view_size';
		$this->templateColumns['media_file_type'] = 'media_file_type';
		$this->templateColumns['upload_file_type'] = 'upload_file_type';
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
				},
			];
		}
		$this->templateColumns['permission'] = [
			'attribute' => 'permission',
			'value' => function($model, $key, $index, $column) {
				return $model->permission;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['media_resize'] = [
			'attribute' => 'media_resize',
			'value' => function($model, $key, $index, $column) {
				return $model->media_resize;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['headline'] = [
			'attribute' => 'headline',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['headline', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->headline, 'Headline,Unheadline', true);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
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

	public static function getLicense($source='1234567890', $length=16, $char=4)
	{
		$mod = $length%$char;
		if($mod == 0)
			$sep = ($length/$char);
		else
			$sep = (int)($length/$char)+1;
		
		$sourceLength = strlen($source);
		$random = '';
		for ($i = 0; $i < $length; $i++)
			$random .= $source[rand(0, $sourceLength - 1)];
		
		$license = '';
		for ($i = 0; $i < $sep; $i++) {
			if($i != $sep-1)
				$license .= substr($random,($i*$char),$char).'-';
			else
				$license .= substr($random,($i*$char),$char);
		}

		return $license;
	}

	public function getSize($media_resize_size)
	{
		$mediaSize = unserialize($media_resize_size);
		return $mediaSize['width'].' x '.$mediaSize['height'];
	}

	public function getHeadlineCategory($headline_category)
	{
		$headline_category = unserialize($headline_category);
		return $headline_category;
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
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {
			$this->media_resize_size = serialize($this->media_resize_size);
			$this->media_view_size = serialize($this->media_view_size);
			$this->headline_category = serialize($this->headline_category);
			$this->upload_file_type = serialize($this->formatFileType($this->upload_file_type));
			$this->media_file_type = serialize($this->formatFileType($this->media_file_type));
		}
		return true;	
	}

}
