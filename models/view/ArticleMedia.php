<?php
/**
 * ArticleMedia
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 21 May 2019, 11:34 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "_article_media".
 *
 * The followings are the available columns in table "_article_media":
 * @property integer $id
 * @property integer $caption
 * @property integer $description
 *
 */

namespace ommu\article\models\view;

use Yii;

class ArticleMedia extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_article_media';
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
			[['id', 'caption', 'description'], 'integer'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'caption' => Yii::t('app', 'Caption'),
			'description' => Yii::t('app', 'Description'),
		];
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		if(!$this->hasMethod('search'))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['id'] = [
			'attribute' => 'id',
			'value' => function($model, $key, $index, $column) {
				return $model->id;
			},
		];
		$this->templateColumns['caption'] = [
			'attribute' => 'caption',
			'value' => function($model, $key, $index, $column) {
				return $model->caption;
			},
		];
		$this->templateColumns['description'] = [
			'attribute' => 'description',
			'value' => function($model, $key, $index, $column) {
				return $model->description;
			},
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
