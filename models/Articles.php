<?php
/**
 * Articles
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 09:23 WIB
 * @modified date 12 May 2019, 18:51 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_articles".
 *
 * The followings are the available columns in table "ommu_articles":
 * @property integer $id
 * @property integer $publish
 * @property integer $cat_id
 * @property string $title
 * @property string $body
 * @property string $published_date
 * @property integer $headline
 * @property string $headline_date
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArticleFiles[] $files
 * @property ArticleLikes[] $likes
 * @property ArticleMedia[] $media
 * @property ArticleTag[] $tags
 * @property ArticleViews[] $views
 * @property ArticleCategory $category
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\article\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users;
use app\models\SourceMessage;
use app\models\CoreTags;
use ommu\article\models\view\Articles as ArticlesView;

class Articles extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['comment_code','creation_id', 'modified_id','creationDisplayname','creation_date','modifiedDisplayname','modified_date','headline_date','updated_date','body'];

	public $categoryName;
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
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['cat_id', 'title', 'body', 'published_date'], 'required'],
			[['publish', 'cat_id', 'headline', 'comment_code', 'creation_id', 'modified_id'], 'integer'],
			[['body'], 'string'],
			[['published_date','tags', 'headline_date','tag_2'], 'safe'],
			[['title'], 'string', 'max' => 128],
			[['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleCategory::className(), 'targetAttribute' => ['cat_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'cat_id' => Yii::t('app', 'Category'),
			'title' => Yii::t('app', 'Title'),
			'body' => Yii::t('app', 'Body'),
			'published_date' => Yii::t('app', 'Published Date'),
			'headline' => Yii::t('app', 'Headline'),
			'headline_date' => Yii::t('app', 'Headline Date'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'files' => Yii::t('app', 'Files'),
			'likes' => Yii::t('app', 'Likes'),
			'media' => Yii::t('app', 'Media'),
			'tags' => Yii::t('app', 'Tags'),
			'views' => Yii::t('app', 'Views'),
			'tag_id_i' => Yii::t('app', 'Tags'),
			'categoryName' => Yii::t('app', 'Category'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
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
	public function getFiles($count=false, $publish=1)
	{
		if($count == false)
			return $this->hasMany(ArticleFiles::className(), ['article_id' => 'id'])
			->andOnCondition([sprintf('%s.publish', ArticleFiles::tableName()) => $publish]);

		$model = ArticleFiles::find()
			->where(['article_id' => $this->id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$files = $model->count();

		return $files ? $files : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLikes($count=false, $publish=1)
	{
		if($count == false)
			return $this->hasMany(ArticleLikes::className(), ['article_id' => 'id'])
			->andOnCondition([sprintf('%s.publish', ArticleLikes::tableName()) => $publish]);

		$model = ArticleLikes::find()
			->where(['article_id' => $this->id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$likes = $model->count();

		return $likes ? $likes : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMedia($count=false, $publish=1)
	{
		if($count == false)
			return $this->hasMany(ArticleMedia::className(), ['article_id' => 'id'])
			->andOnCondition([sprintf('%s.publish', ArticleMedia::tableName()) => $publish]);

		$model = ArticleMedia::find()
			->where(['article_id' => $this->id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$media = $model->count();

		return $media ? $media : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTags($count=false)
	{
		if($count == false)
			return $this->hasMany(ArticleTag::className(), ['article_id' => 'id']);

		$model = ArticleTag::find()
			->where(['article_id' => $this->id]);
		$tags = $model->count();

		return $tags ? $tags : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getViews($count=false, $publish=1)
	{
		if($count == false)
			return $this->hasMany(ArticleViews::className(), ['article_id' => 'id'])
			->andOnCondition([sprintf('%s.publish', ArticleViews::tableName()) => $publish]);

		$model = ArticleViews::find()
			->where(['article_id' => $this->id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$views = $model->count();

		return $views ? $views : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(ArticleCategory::className(), ['id' => 'cat_id']);
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
	 * {@inheritdoc}
	 * @return \ommu\article\models\query\Articles the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\article\models\query\Articles(get_called_class());
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
				'value' => function($model, $key, $index, $column) {
					return isset($model->category) ? $model->category->title->message : '-';
					// return $model->categoryName;
				},
				'filter' => ArticleCategory::getCategory(),
			];
		}
		$this->templateColumns['title'] = [
			'attribute' => 'title',
			'value' => function($model, $key, $index, $column) {
				return $model->title;
			},
		];
		$this->templateColumns['body'] = [
			'attribute' => 'body',
			'value' => function($model, $key, $index, $column) {
				return $model->body;
			},
			'format' => 'html',
		];
		$this->templateColumns['published_date'] = [
			'attribute' => 'published_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDate($model->published_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'published_date'),
		];
		$this->templateColumns['headline_date'] = [
			'attribute' => 'headline_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->headline_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'headline_date'),
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
					// return $model->creationDisplayname;
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
					// return $model->modifiedDisplayname;
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
		$this->templateColumns['files'] = [
			'attribute' => 'files',
			'value' => function($model, $key, $index, $column) {
				$files = $model->getFiles(true);
				return Html::a($files, ['o/file/manage', 'article'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} files', ['count'=>$files])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['likes'] = [
			'attribute' => 'likes',
			'value' => function($model, $key, $index, $column) {
				$likes = $model->getLikes(true);
				return Html::a($likes, ['o/like/manage', 'article'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} likes', ['count'=>$likes])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['media'] = [
			'attribute' => 'media',
			'value' => function($model, $key, $index, $column) {
				$media = $model->getMedia(true);
				return Html::a($media, ['o/media/manage', 'article'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} media', ['count'=>$media])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['tags'] = [
			'attribute' => 'tags',
			'value' => function($model, $key, $index, $column) {
				$tags = $model->getTags(true);
				return Html::a($tags, ['o/tag/manage', 'article'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} tags', ['count'=>$tags])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['views'] = [
			'attribute' => 'views',
			'value' => function($model, $key, $index, $column) {
				$views = $model->getViews(true);
				return Html::a($views, ['o/view/manage', 'article'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} views', ['count'=>$views])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
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
				->where(['id' => $id])
				->one();
			return $model->$column;
			
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

		// $this->categoryName = isset($this->category) ? $this->category->title->message : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
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
