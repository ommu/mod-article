<?php
/**
 * ArticleCategory

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 09:26 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_category".
 *
 * The followings are the available columns in table "ommu_article_category":
 * @property integer $cat_id
 * @property integer $publish
 * @property integer $parent_id
 * @property string $name
 * @property string $desc
 * @property integer $single_photo
 * @property integer $single_file
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Articles[] $articles
 *
 */

namespace ommu\article\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use ommu\users\models\Users;
use app\models\SourceMessage;

class ArticleCategory extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['modified_date','modifiedDisplayname','updated_date','creation_date','creationDisplayname'];

	public $name_i;
	public $desc_i;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_article_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['publish', 'parent_id', 'name', 'desc', 'single_photo', 'single_file', 'creation_id', 'modified_id'], 'integer'],
			[['name_i', 'desc_i','single_photo','single_file'], 'required'],
			[['creation_date', 'modified_date', 'updated_date'], 'safe'],
			[['name_i'], 'string', 'max' => 32],
			[['desc_i'], 'string', 'max' => 256],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'cat_id' => Yii::t('app', 'Category'),
			'publish' => Yii::t('app', 'Publish'),
			'parent_id' => Yii::t('app', 'Parent'),
			'name' => Yii::t('app', 'Name'),
			'desc' => Yii::t('app', 'Desc'),
			'single_photo' => Yii::t('app', 'Single Photo'),
			'single_file' => Yii::t('app', 'Single File'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'name_i' => Yii::t('app', 'Name'),
			'desc_i' => Yii::t('app', 'Description'),
		];
	}

	public function getTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDescription()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'desc']);
	}
	
	public function getArticles()
	{
		return $this->hasMany(Articles::className(), ['cat_id' => 'cat_id']);
	}


	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
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
		$this->templateColumns['parent_id'] = 'parent_id';
		$this->templateColumns['name_i'] = [
			'attribute' => 'name_i',
			'value' => function($model, $key, $index, $column) {
				return isset($model->name) ?? $model->title->message ?? '-';
			},
		];
		$this->templateColumns['desc_i'] = [
			'attribute' => 'desc_i',
			'value' => function($model, $key, $index, $column) {
				return isset($model->desc) ?? $model->description->message ?? '-';
			},
		];
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
				},
			];
		}
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modifiedDisplayname'] = [
				'attribute' => 'modifiedDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
				},
			];
		}
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['single_photo'] = [
			'attribute' => 'single_photo',
			'value' => function($model, $key, $index, $column) {
				return $model->single_photo;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['single_file'] = [
			'attribute' => 'single_file',
			'value' => function($model, $key, $index, $column) {
				return $model->single_file;
			},
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['category/publish', 'id'=>$model->primaryKey]);
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
				->where(['cat_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getCategory
	 */
	public static function getCategory($publish=null)
	{
		$items = [];
		$model = self::find();
		if ($publish!=null)
			$model = $model->andWhere(['publish'=>$publish]);
		$model = $model->orderBy('name ASC')->all();

		if($model !== null) {
			foreach($model as $val) {
				$items[$val->cat_id] = '-';
				if(isset($val->title))
					$items[$val->cat_id] = $val->title->message;
			}
		}
		
		return $items;
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
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		$module = strtolower(Yii::$app->controller->module->id);
		$controller = strtolower(Yii::$app->controller->id);
		$action = strtolower(Yii::$app->controller->action->id);

		$location = Inflector::slug($module.' '.$controller);

		if(parent::beforeSave($insert)) {
			if($insert || (!$insert && !$this->name)) {
				$name = new SourceMessage();
				$name->location = $location.'_title';
				$name->message = $this->name_i;
				if($name->save())
					$this->name = $name->id;
				
			} else {
				$name = SourceMessage::findOne($this->name);
				$name->message = $this->name_i;
				$name->save();
			}

			if($insert || (!$insert && !$this->desc)) {
				$desc = new SourceMessage();
				$desc->location = $location.'_description';
				$desc->message = $this->desc_i;
				if($desc->save())
					$this->desc = $desc->id;
				
			} else {
				$desc = SourceMessage::findOne($this->desc);
				$desc->message = $this->desc_i;
				$desc->save();
			}
			
				
		}
		return true;
	}

	
}
