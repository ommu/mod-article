<?php
/**
 * ArticleTag
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:10 WIB
 * @modified date 12 May 2019, 18:50 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_tag".
 *
 * The followings are the available columns in table "ommu_article_tag":
 * @property integer $id
 * @property integer $article_id
 * @property integer $tag_id
 * @property string $creation_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property Articles $article
 * @property CoreTags $tag
 * @property Users $creation
 *
 */

namespace ommu\article\models;

use Yii;
use app\models\CoreTags;
use ommu\users\models\Users;

class ArticleTag extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['creation_date', 'creationDisplayname'];

	public $tagBody;
	public $articleTitle;
	public $creationDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_article_tag';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['article_id', 'tag_id'], 'required'],
			[['article_id', 'tag_id', 'creation_id'], 'integer'],
			[['tagBody'], 'string'],
			[['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Articles::className(), 'targetAttribute' => ['article_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'article_id' => Yii::t('app', 'Article'),
			'tag_id' => Yii::t('app', 'Tag'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'tagBody' => Yii::t('app', 'Tag'),
			'articleTitle' => Yii::t('app', 'Article'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
		];
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
	public function getTag()
	{
		return $this->hasOne(CoreTags::className(), ['tag_id' => 'tag_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\query\ArticleTag the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\article\models\query\ArticleTag(get_called_class());
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
		if(!Yii::$app->request->get('article') && !Yii::$app->request->get('id')) {
			$this->templateColumns['articleTitle'] = [
				'attribute' => 'articleTitle',
				'value' => function($model, $key, $index, $column) {
					return isset($model->article) ? $model->article->title : '-';
					// return $model->articleTitle;
				},
			];
		}
		if(!Yii::$app->request->get('tag')) {
			$this->templateColumns['tagBody'] = [
				'attribute' => 'tagBody',
				'value' => function($model, $key, $index, $column) {
					return isset($model->tag) ? $model->tag->body : '-';
					// return $model->tagBody;
				},
			];
		}
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creationDisplayname'] = [
				'attribute' => 'creationDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
					// return $model->creationDisplayname;
				},
			];
		}
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

		$this->tagBody = isset($this->tag) ? $this->tag->body : '';
		// $this->articleTitle = isset($this->article) ? $this->article->title : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
		}
		return true;
	}
}
