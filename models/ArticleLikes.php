<?php
/**
 * ArticleLikes
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:04 WIB
 * @modified date 12 May 2019, 18:27 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_likes".
 *
 * The followings are the available columns in table "ommu_article_likes":
 * @property integer $id
 * @property integer $publish
 * @property integer $article_id
 * @property integer $user_id
 * @property string $likes_date
 * @property string $likes_ip
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArticleLikeHistory[] $histories
 * @property Articles $article
 * @property Users $user
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

	public $gridForbiddenColumn = ['likes_ip', 'updated_date'];

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
			[['article_id'], 'required'],
			[['publish', 'article_id', 'user_id'], 'integer'],
			[['user_id', 'likes_ip'], 'safe'],
			[['likes_ip'], 'string', 'max' => 20],
			[['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Articles::className(), 'targetAttribute' => ['article_id' => 'id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
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
			'article_id' => Yii::t('app', 'Article'),
			'user_id' => Yii::t('app', 'User'),
			'likes_date' => Yii::t('app', 'Likes Date'),
			'likes_ip' => Yii::t('app', 'Likes IP'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'histories' => Yii::t('app', 'Histories'),
			'articleTitle' => Yii::t('app', 'Article'),
			'userDisplayname' => Yii::t('app', 'User'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories($count=false)
	{
		if($count == false)
			return $this->hasMany(ArticleLikeHistory::className(), ['like_id' => 'id']);

		$model = ArticleLikeHistory::find()
			->alias('t')
			->where(['t.like_id' => $this->id]);
		$histories = $model->count();

		return $histories ? $histories : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArticle()
	{
		return $this->hasOne(Articles::className(), ['id' => 'article_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\query\ArticleLikes the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\article\models\query\ArticleLikes(get_called_class());
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
		$this->templateColumns['articleTitle'] = [
			'attribute' => 'articleTitle',
			'value' => function($model, $key, $index, $column) {
				return isset($model->article) ? $model->article->title : '-';
				// return $model->articleTitle;
			},
			'visible' => !Yii::$app->request->get('article') ? true : false,
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->displayname : '-';
				// return $model->userDisplayname;
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
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
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['histories'] = [
			'attribute' => 'histories',
			'value' => function($model, $key, $index, $column) {
				$histories = $model->getHistories(true);
				return Html::a($histories, ['history/like/manage', 'like'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} histories', ['count'=>$histories]), 'data-pjax'=>0]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('trash') ? true : false,
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
	 * function insertLike
	 */

	public function insertLike($article_id, $user_id=null)
	{
		if($user_id == null)
			$user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

		$findLike = self::find()
			->select(['id'])
			->where(['publish' => 1])
			->andWhere(['article_id' => $article_id]);
		if($user_id != null)
			$findLike->andWhere(['user_id' => $user_id]);
		else
			$findLike->andWhere(['is', 'user_id', null]);
		$findLike = $findLike->one();

		if($findLike === null) {
			$like = new ArticleLikes();
			$like->article_id = $article_id;
			$like->user_id = $user_id;
			$like->save();
		}
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->articleTitle = isset($this->article) ? $this->article->title : '-';
		// $this->userDisplayname = isset($this->user) ? $this->user->displayname : '-';
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
			$this->likes_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}
