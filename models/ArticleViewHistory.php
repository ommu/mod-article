<?php
/**
 * ArticleViewHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:11 WIB
 * @modified date 12 May 2019, 18:51 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_view_history".
 *
 * The followings are the available columns in table "ommu_article_view_history":
 * @property integer $id
 * @property integer $view_id
 * @property string $view_date
 * @property string $view_ip
 *
 * The followings are the available model relations:
 * @property ArticleViews $view
 *
 */

namespace ommu\article\models;

use Yii;

class ArticleViewHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	public $articleTitle;
	public $articleId;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_article_view_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['view_id', 'view_ip'], 'required'],
			[['view_id'], 'integer'],
			[['view_date'], 'safe'],
			[['view_ip'], 'string', 'max' => 20],
			[['view_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleViews::className(), 'targetAttribute' => ['view_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'view_id' => Yii::t('app', 'View'),
			'view_date' => Yii::t('app', 'View Date'),
			'view_ip' => Yii::t('app', 'View IP'),
			'articleTitle' => Yii::t('app', 'Article'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(ArticleViews::className(), ['id' => 'view_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\query\ArticleViewHistory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\article\models\query\ArticleViewHistory(get_called_class());
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
				return isset($model->view) ? $model->view->article->title : '-';
				// return $model->articleTitle;
			},
			'visible' => !Yii::$app->request->get('view') ? true : false,
		];
		$this->templateColumns['view_date'] = [
			'attribute' => 'view_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->view_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'view_date'),
		];
		$this->templateColumns['view_ip'] = [
			'attribute' => 'view_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->view_ip;
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

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->articleTitle = isset($this->view) ? $this->view->article->title : '-';
	}
}
