<?php
/**
 * ArticleLikeHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:05 WIB
 * @modified date 12 May 2019, 18:27 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_like_history".
 *
 * The followings are the available columns in table "ommu_article_like_history":
 * @property integer $id
 * @property integer $publish
 * @property integer $like_id
 * @property string $likes_date
 * @property string $likes_ip
 *
 * The followings are the available model relations:
 * @property ArticleLikes $like
 *
 */

namespace ommu\article\models;

use Yii;
use yii\helpers\Url;

class ArticleLikeHistory extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	public $articleTitle;
	public $articleId;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_article_like_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['publish', 'like_id', 'likes_ip'], 'required'],
			[['publish', 'like_id'], 'integer'],
			[['likes_date'], 'safe'],
			[['likes_ip'], 'string', 'max' => 20],
			[['like_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleLikes::className(), 'targetAttribute' => ['like_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Like'),
			'like_id' => Yii::t('app', 'Like'),
			'likes_date' => Yii::t('app', 'Likes Date'),
			'likes_ip' => Yii::t('app', 'Likes IP'),
			'articleTitle' => Yii::t('app', 'Article'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLike()
	{
		return $this->hasOne(ArticleLikes::className(), ['id' => 'like_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\query\ArticleLikeHistory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\article\models\query\ArticleLikeHistory(get_called_class());
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
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('like')) {
			$this->templateColumns['articleTitle'] = [
				'attribute' => 'articleTitle',
				'value' => function($model, $key, $index, $column) {
					return isset($model->like) ? $model->like->article->title : '-';
					// return $model->articleTitle;
				},
			];
		}
		$this->templateColumns['likes_date'] = [
			'attribute' => 'likes_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->likes_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'likes_date'),
		];
		$this->templateColumns['likes_ip'] = [
			'attribute' => 'likes_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->likes_ip;
			},
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
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

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->articleTitle = isset($this->like) ? $this->like->article->title : '-';
	}
}
