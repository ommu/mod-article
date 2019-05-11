<?php
/**
 * ArticleLikes

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:04 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_likes".
 *
 * The followings are the available columns in table "ommu_article_likes":
 * @property string $like_id
 * @property integer $publish
 * @property integer $article_id
 * @property string $user_id
 * @property string $likes_date
 * @property string $likes_ip
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArticleLikeHistory[] $histories
 * @property Articles $article
 *
 */

namespace ommu\article\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users;

class ArticleLikes extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['likes_ip','updated_date'];

	public $articleTitle;
	public $userDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_article_likes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['publish', 'article_id', 'user_id'], 'integer'],
			[['article_id', 'user_id'], 'required'],
			[['likes_date', 'updated_date', 'likes_ip'], 'safe'],
			[['likes_ip'], 'string', 'max' => 20],
			[['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Articles::className(), 'targetAttribute' => ['article_id' => 'article_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'like_id' => Yii::t('app', 'Like'),
			'publish' => Yii::t('app', 'Publish'),
			'article_id' => Yii::t('app', 'Article'),
			'user_id' => Yii::t('app', 'User'),
			'likes_date' => Yii::t('app', 'Likes Date'),
			'likes_ip' => Yii::t('app', 'Likes Ip'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'articleTitle' => Yii::t('app', 'Article'),
			'userDisplayname' => Yii::t('app', 'User'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories()
	{
		return $this->hasMany(ArticleLikeHistory::className(), ['like_id' => 'like_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArticle()
	{
		return $this->hasOne(Articles::className(), ['article_id' => 'article_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
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
		if(!Yii::$app->request->get('article')) {
			$this->templateColumns['articleTitle'] = [
				'attribute' => 'articleTitle',
				'value' => function($model, $key, $index, $column) {
					return $model->article->title;
				},
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['userDisplayname'] = [
				'attribute' => 'userDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
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
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
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
				->where(['like_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * fungsi insert like
	 */

	public function insertLike($article_id)
	{
		$user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
		$like = articleLikes::find()->select(['article_id', 'likes'])->where(['article_id' => $article_id, 'user_id' => $user_id])->one();

		if($like == null) {
			$like = new articleLikes;
			$like->article_id = $article_id;
		} else
			$like->likes = $like->likes+1;
			
		$like->save();
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->user_id == null)
					$this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}

			$this->like_ip = Yii::$app->request->userIP;
		}
		return true;
	}
}
