<?php
/**
 * ArticleLikeHistory

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:05 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_like_history".
 *
 * The followings are the available columns in table "ommu_article_like_history":
 * @property string $id
 * @property integer $publish
 * @property string $like_id
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

	public $like_search;

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
			[['like_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleLikes::className(), 'targetAttribute' => ['like_id' => 'like_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'like_id' => Yii::t('app', 'Like'),
			'likes_date' => Yii::t('app', 'Likes Date'),
			'likes_ip' => Yii::t('app', 'Likes Ip'),
			'like_search' => Yii::t('app', 'Like'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLike()
	{
		return $this->hasOne(ArticleLikes::className(), ['like_id' => 'like_id']);
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
		if(!Yii::$app->request->get('like')) {
			$this->templateColumns['like_search'] = [
				'attribute' => 'like_search',
				'value' => function($model, $key, $index, $column) {
					return $model->like->like_id;
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
		$this->templateColumns['likes_ip'] = 'likes_ip';
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish);
				},
				'filter' => $this->filterYesNo(),
				'contentOptions' => ['class'=>'center'],
				'format' => 'raw',
			];
		}
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
