<?php
/**
 * ArticleTag
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:34 WIB
 * @modified date 21 May 2019, 12:25 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "_article_tag".
 *
 * The followings are the available columns in table "_article_tag":
 * @property integer $tag_id
 * @property string $articles
 * @property integer $article_all
 *
 */

namespace ommu\article\models\view;

use Yii;

class ArticleTag extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_article_tag';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['tag_id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['tag_id'], 'required'],
			[['tag_id', 'article_all'], 'integer'],
			[['articles'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'tag_id' => Yii::t('app', 'Tag'),
			'articles' => Yii::t('app', 'Articles'),
			'article_all' => Yii::t('app', 'Article All'),
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
		$this->templateColumns['tag_id'] = [
			'attribute' => 'tag_id',
			'value' => function($model, $key, $index, $column) {
				return $model->tag_id;
			},
		];
		$this->templateColumns['articles'] = [
			'attribute' => 'articles',
			'value' => function($model, $key, $index, $column) {
				return $model->articles;
			},
		];
		$this->templateColumns['article_all'] = [
			'attribute' => 'article_all',
			'value' => function($model, $key, $index, $column) {
				return $model->article_all;
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
			$model = $model->where(['tag_id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
