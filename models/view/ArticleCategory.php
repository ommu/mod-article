<?php
/**
 * ArticleCategory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:18 WIB
 * @modified date 21 May 2019, 12:55 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "_article_category".
 *
 * The followings are the available columns in table "_article_category":
 * @property integer $id
 * @property string $articles
 * @property string $article_pending
 * @property string $article_unpublish
 * @property integer $article_all
 * @property string $article_id
 *
 */

namespace ommu\article\models\view;

use Yii;

class ArticleCategory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_article_category';
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
			[['id', 'article_all', 'article_id'], 'integer'],
			[['articles', 'article_pending', 'article_unpublish'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'articles' => Yii::t('app', 'Articles'),
			'article_pending' => Yii::t('app', 'Article Pending'),
			'article_unpublish' => Yii::t('app', 'Article Unpublish'),
			'article_all' => Yii::t('app', 'Article All'),
			'article_id' => Yii::t('app', 'Article'),
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

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['id'] = [
			'attribute' => 'id',
			'value' => function($model, $key, $index, $column) {
				return $model->id;
			},
		];
		$this->templateColumns['articles'] = [
			'attribute' => 'articles',
			'value' => function($model, $key, $index, $column) {
				return $model->articles;
			},
		];
		$this->templateColumns['article_pending'] = [
			'attribute' => 'article_pending',
			'value' => function($model, $key, $index, $column) {
				return $model->article_pending;
			},
		];
		$this->templateColumns['article_unpublish'] = [
			'attribute' => 'article_unpublish',
			'value' => function($model, $key, $index, $column) {
				return $model->article_unpublish;
			},
		];
		$this->templateColumns['article_all'] = [
			'attribute' => 'article_all',
			'value' => function($model, $key, $index, $column) {
				return $model->article_all;
			},
		];
		$this->templateColumns['article_id'] = [
			'attribute' => 'article_id',
			'value' => function($model, $key, $index, $column) {
				return $model->article_id;
			},
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
}
