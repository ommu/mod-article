<?php
/**
 * ArticleViews

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:11 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_views".
 *
 * The followings are the available columns in table "ommu_article_views":
 * @property string $view_id
 * @property integer $publish
 * @property integer $article_id
 * @property string $user_id
 * @property integer $views
 * @property string $view_date
 * @property string $view_ip
 * @property string $deleted_date
 *
 * The followings are the available model relations:
 * @property ArticleViewHistory[] $histories
 * @property Articles $article
 *
 */

namespace ommu\article\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users;

class ArticleViews extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	public $articleTitle;
	public $userDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_article_views';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['publish', 'article_id', 'user_id', 'views'], 'integer'],
			[['article_id', 'user_id'], 'required'],
			[['view_date', 'view_ip'], 'safe'],
			[['view_ip'], 'string', 'max' => 20],
			[['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Articles::className(), 'targetAttribute' => ['article_id' => 'article_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'view_id' => Yii::t('app', 'View'),
			'publish' => Yii::t('app', 'Publish'),
			'article_id' => Yii::t('app', 'Article'),
			'user_id' => Yii::t('app', 'User'),
			'views' => Yii::t('app', 'Views'),
			'view_date' => Yii::t('app', 'View Date'),
			'view_ip' => Yii::t('app', 'View Ip'),
			'deleted_date' => Yii::t('app', 'Deleted Date'),
			'articleTitle' => Yii::t('app', 'Article'),
			'userDisplayname' => Yii::t('app', 'User'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories()
	{
		return $this->hasMany(ArticleViewHistory::className(), ['view_id' => 'view_id']);
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

		$this->templateColumns['views'] = [
			'attribute' => 'views',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['history/view/index', 'view'=>$model->primaryKey]);
				return Html::a($model->views ? $model->views : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];

		$this->templateColumns['view_date'] = [
			'attribute' => 'view_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->view_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'view_date'),
		];
		$this->templateColumns['view_ip'] = 'view_ip';
		$this->templateColumns['deleted_date'] = [
			'attribute' => 'deleted_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->deleted_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'deleted_date'),
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
				->where(['view_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * before validate attributes
	 */
	public function insertView($article_id)
	{
		$user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
		$view = ArticleViews::find()->where(['article_id' => $article_id, 'user_id' => $user_id])->one();
		if($view == null) {
			$view = new ArticleViews;
			$view->article_id = $article_id;
		} else {
			$view->views = $view->views+1;
		}
			
		$view->save();
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

			$this->view_ip = Yii::$app->request->userIP;
		}
		return true;
	}
}
