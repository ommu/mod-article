<?php
/**
 * ArticleLikes
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 10:23 WIB
 * @modified date 21 May 2019, 12:52 WIB
 * @link https://github.com/ommu/mod-article
 * 
 * This is the model class for table "_article_likes".
 *
 * The followings are the available columns in table "_article_likes":
 * @property integer $id
 * @property string $likes
 * @property string $unlikes
 * @property integer $like_all
 *
 */

namespace ommu\article\models\view;

use Yii;

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
			[['id', 'like_all'], 'integer'],
			[['likes', 'unlikes'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
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

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['id'] = [
			'attribute' => 'id',
			'value' => function($model, $key, $index, $column) {
				return $model->id;
			},
		];
		$this->templateColumns['likes'] = [
			'attribute' => 'likes',
			'value' => function($model, $key, $index, $column) {
				return $model->likes;
			},
		];
		$this->templateColumns['unlikes'] = [
			'attribute' => 'unlikes',
			'value' => function($model, $key, $index, $column) {
				return $model->unlikes;
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
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}
}
