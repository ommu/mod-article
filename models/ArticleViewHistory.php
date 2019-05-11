<?php
/**
 * ArticleViewHistory

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:11 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_view_history".
 *
 * The followings are the available columns in table "ommu_article_view_history":
 * @property string $id
 * @property string $view_id
 * @property string $view_date
 * @property string $view_ip
 *
 * The followings are the available model relations:
 * @property ArticleViews $view
 *
 */

namespace ommu\article\models;

use Yii;
use yii\helpers\Url;

class ArticleViewHistory extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	public $view_search;

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
			[['view_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleViews::className(), 'targetAttribute' => ['view_id' => 'view_id']],
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
			'view_ip' => Yii::t('app', 'View Ip'),
			'view_search' => Yii::t('app', 'View'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(ArticleViews::className(), ['view_id' => 'view_id']);
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
		if(!Yii::$app->request->get('view')) {
			$this->templateColumns['view_search'] = [
				'attribute' => 'view_search',
				'value' => function($model, $key, $index, $column) {
					return $model->view->view_id;
				},
			];
		}
		$this->templateColumns['view_date'] = [
			'attribute' => 'view_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->view_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'view_date'),
		];
		$this->templateColumns['view_ip'] = 'view_ip';
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
