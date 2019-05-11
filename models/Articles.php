<?php
/**
 * Articles

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 09:23 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_articles".
 *
 * The followings are the available columns in table "ommu_articles":
 * @property integer $article_id
 * @property integer $publish
 * @property integer $cat_id
 * @property string $title
 * @property string $body
 * @property string $quote
 * @property string $published_date
 * @property integer $headline
 * @property integer $comment_code
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 * @property string $headline_date
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property ArticleFiles[] $files
 * @property ArticleLikes[] $likes
 * @property ArticleMedia[] $media
 * @property ArticleTag[] $tags
 * @property ArticleViews[] $views
 * @property ArticleCategory $category
 *
 */

namespace ommu\article\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\behaviors\SluggableBehavior;
use ommu\users\models\Users;
use app\models\SourceMessage;
use app\models\CoreTags;
use ommu\article\models\view\Articles as ArticlesView;

class Articles extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['comment_code','creation_id', 'modified_id','slug','creationDisplayname','creation_date','modifiedDisplayname','modified_date','headline_date','updated_date','quote','body'];

	public $category_search;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $file_search;
	public $media_search;
	public $view_search;
	public $like_search;
	public $tag_id;
	public $tag_id_i;
	public $tag_2;
	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_articles';
	}

	/**
	 * behaviors model class.
	 */
	public function behaviors() {
		return [
			[
				'class' => SluggableBehavior::className(),
				'attribute' => 'title',
				'immutable' => true,
				'ensureUnique' => true,
			],
		];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['publish', 'cat_id', 'headline', 'comment_code', 'creation_id', 'modified_id'], 'integer'],
			[['cat_id', 'title', 'body', 'quote', 'published_date'], 'required'],
			[['body', 'quote', 'slug'], 'string'],
			[['published_date', 'creation_date','tags','modified_date', 'updated_date', 'headline_date', 'creation_id', 'modified_id','tag_2'], 'safe'],
			[['title'], 'string', 'max' => 128],
			[['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleCategory::className(), 'targetAttribute' => ['cat_id' => 'cat_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'article_id' => Yii::t('app', 'Article'),
			'publish' => Yii::t('app', 'Publish'),
			'cat_id' => Yii::t('app', 'Category'),
			'title' => Yii::t('app', 'Title'),
			'body' => Yii::t('app', 'Body'),
			'quote' => Yii::t('app', 'Quote'),
			'published_date' => Yii::t('app', 'Published Date'),
			'headline' => Yii::t('app', 'Headline'),
			'comment_code' => Yii::t('app', 'Comment Code'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'headline_date' => Yii::t('app', 'Headline Date'),
			'slug' => Yii::t('app', 'Slug'),
			'category_search' => Yii::t('app', 'Category'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'file_search' => Yii::t('app', 'Files'),
			'tag_search' => Yii::t('app', 'Tags'),
			'media_search' => Yii::t('app', 'Media'),
			'view_search' => Yii::t('app', 'Views'),
			'like_search' => Yii::t('app','Likes'),
			'tag_id_i' => Yii::t('app', 'Tags'),
		];
	}

	public static function getSetting()
	{
		$setting = ArticleSetting::find()->limit(1)->one();
		return $setting->headline_limit;
	}


	public function getTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'tag_id']);
	}

	public function getTag()
	{
		return $this->hasOne(CoreTags::className(), ['tag_id' => 'tag_id']);
	}

	public function getView()
	{
		return $this->hasOne(ArticlesView::className(), ['article_id' => 'article_id']);
	}

	public function getArticle()
	{
		$article = Articles::find()->one();
		return $article;
	}
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFiles()
	{
		return $this->hasMany(ArticleFiles::className(), ['article_id' => 'article_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLikes()
	{
		return $this->hasMany(ArticleLikes::className(), ['article_id' => 'article_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMedia()
	{
		return $this->hasMany(ArticleMedia::className(), ['article_id' => 'article_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTags()
	{
		return $this->hasMany(ArticleTag::className(), ['article_id' => 'article_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getViews()
	{
		return $this->hasMany(ArticleViews::className(), ['article_id' => 'article_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(ArticleCategory::className(), ['cat_id' => 'cat_id']);
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
		if(!Yii::$app->request->get('category')) {
			$this->templateColumns['cat_id'] = [
				'attribute' => 'cat_id',
				'filter' => ArticleCategory::getCategory(),
				'value' => function($model, $key, $index, $column) {
					return isset($model->category->title)? $model->category->title->message: '-';
				},
			];
		}
		$this->templateColumns['title'] = 'title';
		$this->templateColumns['body'] = 'body';
		$this->templateColumns['quote'] = 'quote';
		$this->templateColumns['published_date'] = [
			'attribute' => 'published_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDate($model->published_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'published_date'),
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
		$this->templateColumns['headline_date'] = [
			'attribute' => 'headline_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->headline_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'headline_date'),
		];
		$this->templateColumns['slug'] = 'slug';
		$this->templateColumns['comment_code'] = [
			'attribute' => 'comment_code',
			'value' => function($model, $key, $index, $column) {
				return $model->comment_code;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['tag_search'] = [
			'attribute' => 'tag_search',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['tag/index', 'article'=>$model->primaryKey]);
				return Html::a($model->view->tags ? $model->view->tags : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['file_search'] = [
			'attribute' => 'file_search',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['file/index', 'article'=>$model->primaryKey]);
				return Html::a($model->view->files ? $model->view->files : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		
		$this->templateColumns['media_search'] = [
			'attribute' => 'media_search',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['media/index', 'article'=>$model->primaryKey]);
				return Html::a($model->view->medias ? $model->view->medias : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['view_search'] = [
			'attribute' => 'view_search',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['view/index', 'article'=>$model->primaryKey]);
				return Html::a($model->view->views ? $model->view->views : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['like_search'] = [
			'attribute' => 'like_search',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['like/index', 'article'=>$model->primaryKey]);
				return Html::a($model->view->likes ? $model->view->likes : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['headline'] = [
			'attribute' => 'headline',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['headline', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->headline, 'Headline,Unheadline', true);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
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
				->where(['article_id' => $id])
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
		//fungsi mengganti headline on create
		$headline = Articles::find()->where(['publish'=>1,'headline'=>1])->all();
		$count=count($headline);
		$headline1 = Articles::find()->where(['publish'=>1,'headline'=>1])->orderBy(['headline_date'=> SORT_ASC])->limit(1)->one();
	
		if(parent::beforeSave($insert)) {

			if ($this->isNewRecord){
				if (!empty($headline1)) {
					if ($count>=$this->getSetting()){
						$headline1->headline=0;
						$headline1->save();	
					}
				}
			}
		}
			return true;
	}
		

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		$action = strtolower(Yii::$app->controller->action->id);
		parent::afterSave($insert, $changedAttributes);
		{
			//menyimpan tag di table coretags dan article tag
			if($action == 'create'&&$this->tag_2) 
			{
				$arrayTag = explode(',', $this->tag_2);
				if (count($arrayTag)>0){
					foreach ($arrayTag as $value) {
							$tag_id = new CoreTags();
							$tag_id->body = trim($value);

							if($tag_id->save(false))
							{
								$model = new ArticleTag();
								
									$model->article_id = $this->article_id;
									$model->tag_id = $tag_id->tag_id;
									if (!$model->save()){
										file_put_contents('assets/cekerror.txt', print_r($model->getErrors(),true));
									}
								
							}
						}
				}

				
			} 
			

		}
		return true;
	}

	
}
