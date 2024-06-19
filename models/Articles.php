<?php
/**
 * Articles
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
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
 * @property ArticleGrid $grid
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
use app\models\Users;
use yii\web\UploadedFile;
use thamtech\uuid\helpers\UuidHelper;
use yii\helpers\ArrayHelper;
use yii\base\Event;
use app\models\SourceMessage;

class Articles extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['body', 'headline_date', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];
    //, 'tagBody', 'oFile', 'oMedia'

	public $old_image;
	public $old_file;
	public $image;
	public $file;
	public $tag;

	public $categoryName;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $tagBody;
	public $tagId;
    public $oFile;
    public $oLike;
    public $oMedia;
    public $oView;


	const EVENT_BEFORE_SAVE_ARTICLE = 'BeforeSaveArticle';

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
			[['cat_id', 'title', 'body'], 'required'],
			[['publish', 'cat_id', 'headline', 'creation_id', 'modified_id'], 'integer'],
			[['body'], 'string'],
			[['published_date', 'image', 'file', 'tag'], 'safe'],
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
			'body' => Yii::t('app', 'Article'),
			'published_date' => Yii::t('app', 'Published Date'),
			'headline' => Yii::t('app', 'Headline'),
			'headline_date' => Yii::t('app', 'Headline Date'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'image' => Yii::t('app', 'Cover'),
			'file' => Yii::t('app', 'Document'),
			'tag' => Yii::t('app', 'Tag'),
			'categoryName' => Yii::t('app', 'Category'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'tagBody' => Yii::t('app', 'Tags'),
            'oFile' => Yii::t('app', 'Documents'),
            'oLike' => Yii::t('app', 'Likes'),
            'oMedia' => Yii::t('app', 'Photos'),
            'oView' => Yii::t('app', 'Views'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFiles($count=false, $publish=1)
	{
        if ($count == false) {
            return $this->hasMany(ArticleFiles::className(), ['article_id' => 'id'])
                ->alias('t')
                ->select(['id', 'publish', 'article_id', 'file_filename'])
                ->andOnCondition([sprintf('%s.publish', 't') => $publish]);
        }

		$model = ArticleFiles::find()
            ->alias('t')
            ->where(['t.article_id' => $this->id]);
        if ($publish == null) {
            $model->andWhere(['in', 't.publish', [0,1]]);
        } else {
            if ($publish == 0) {
                $model->unpublish();
            } else if ($publish == 1) {
                $model->published();
            } else if ($publish == 2) {
                $model->deleted();
            }
        }
		$files = $model->count();

		return $files ? $files : 0;
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrid()
    {
        return $this->hasOne(ArticleGrid::className(), ['id' => 'id']);
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLikes($count=false, $publish=1)
	{
        if ($count == false) {
            return $this->hasMany(ArticleLikes::className(), ['article_id' => 'id'])
                ->alias('likes')
                ->andOnCondition([sprintf('%s.publish', 'likes') => $publish]);
        }

		$model = ArticleLikes::find()
            ->alias('t')
            ->where(['t.article_id' => $this->id]);
        if ($publish == 0) {
            $model->unpublish();
        } else if ($publish == 1) {
            $model->published();
        } else if ($publish == 2) {
            $model->deleted();
        }
		$likes = $model->count();

		return $likes ? $likes : 0;
	}

	/**
	 * @param $type string (relation|cover|count)
	 * @return \yii\db\ActiveQuery
	 */
	public function getMedias($type='relation', $publish=1)
	{
        if ($type == 'relation') {
            return $this->hasMany(ArticleMedia::className(), ['article_id' => 'id'])
                ->alias('t')
                ->select(['id', 'publish', 'article_id', 'media_filename'])
                ->andOnCondition([sprintf('%s.publish', 't') => $publish]);
        }

        if ($type == 'cover') {
			return $this->hasMany(ArticleMedia::className(), ['article_id' => 'id'])
                ->alias('t')
                ->select(['id', 'publish', 'cover', 'article_id', 'media_filename'])
                ->andOnCondition([sprintf('%s.publish', 't') => $publish])
                ->andOnCondition([sprintf('%s.cover', 't') => 1]);
        }

		$model = ArticleMedia::find()
            ->alias('t')
            ->where(['t.article_id' => $this->id]);
        if ($publish == null) {
            $model->andWhere(['in', 't.publish', [0,1]]);
        } else {
            if ($publish == 0) {
                $model->unpublish();
            } else if ($publish == 1) {
                $model->published();
            } else if ($publish == 2) {
                $model->deleted();
            }
        }
		$media = $model->count();

		return $media ? $media : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTags($result=false, $val='id')
	{
        if ($result == true) {
            return \yii\helpers\ArrayHelper::map($this->tags, 'tag_id', $val=='id' ? 'id' : 'tag.body');
        }

		return $this->hasMany(ArticleTag::className(), ['article_id' => 'id'])
            ->select(['id', 'article_id', 'tag_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getViews($count=false, $publish=1)
	{
        if ($count == false) {
            return $this->hasMany(ArticleViews::className(), ['article_id' => 'id'])
                ->alias('views')
                ->andOnCondition([sprintf('%s.publish', 'views') => $publish]);
        }

		$model = ArticleViews::find()
            ->alias('t')
            ->where(['t.article_id' => $this->id]);
        if ($publish == 0) {
            $model->unpublish();
        } else if ($publish == 1) {
            $model->published();
        } else if ($publish == 2) {
            $model->deleted();
        }
		$views = $model->sum('views');

		return $views ? $views : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(ArticleCategory::className(), ['id' => 'cat_id'])
            ->select(['id', 'name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategoryTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'name'])
            ->select(['id', 'message'])
            ->via('category');
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id'])
            ->select(['user_id', 'displayname']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id'])
            ->select(['user_id', 'displayname']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCoverAttribute()
	{
		$model = $this->getMedias('cover')->one();
        if (!$model) {
            return [];
        }
		return ['id' => $model->id, 'cover' => $model->media_filename];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCover()
	{
		$model = $this->getCoverAttribute();
        if (is_array($model)  && empty($model)) {
            return;
        }
		return $model['cover'];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDocumentAttribute()
	{
		$model = $this->getFiles(false)->one();
        if (!$model) {
            return [];
        }
		return ['id' => $model->id, 'file' => $model->file_filename];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDocument()
	{
		$model = $this->getDocumentAttribute();
        if (is_array($model)  && empty($model)) {
            return;
        }
		return $model['file'];
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

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['cat_id'] = [
			'attribute' => 'cat_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->categoryTitle) ? $model->categoryTitle->message : '-';
				// return $model->categoryName;
			},
			'filter' => ArticleCategory::getCategory(null, 'is_null', 'optgroup'),
			'visible' => !Yii::$app->request->get('category') ? true : false,
		];
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
		$this->templateColumns['tagBody'] = [
			'attribute' => 'tagBody',
			'value' => function($model, $key, $index, $column) {
				return implode(', ', $model->getTags(true, 'title'));
			},
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
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => !Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['oView'] = [
			'attribute' => 'oView',
			'value' => function($model, $key, $index, $column) {
				$views = $model->grid->view;
				return Html::a($views, ['view/admin/manage', 'article' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} views', ['count' => $views]), 'data-pjax' => 0]);
			},
            'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['oLike'] = [
			'attribute' => 'oLike',
			'value' => function($model, $key, $index, $column) {
				$likes = $model->grid->like;
				return Html::a($likes, ['like/admin/manage', 'article' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} likes', ['count' => $likes]), 'data-pjax' => 0]);
			},
            'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['oMedia'] = [
			'attribute' => 'oMedia',
			'value' => function($model, $key, $index, $column) {
				$media = $model->grid->media;
				return Html::a($media, ['o/image/manage', 'article' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} media', ['count' => $media]), 'data-pjax' => 0]);
			},
            'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['oFile'] = [
			'attribute' => 'oFile',
			'value' => function($model, $key, $index, $column) {
				$files = $model->grid->file;
				return Html::a($files, ['o/file/manage', 'article' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} files', ['count' => $files]), 'data-pjax' => 0]);
			},
            'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
        $this->templateColumns['headline'] = [
            'attribute' => 'headline',
            'value' => function($model, $key, $index, $column) {
                $setting = $model->getSetting(['headline_category']);
                if (!is_array(($headlineCategory = $setting->headline_category))) {
                    $headlineCategory = [];
                }
                if (!in_array($model->cat_id, $headlineCategory)) {
                    return '-';
                }
                $url = Url::to(['headline', 'id' => $model->primaryKey]);
                return $this->quickAction($url, $model->headline, 'Yes,No', true);
            },
            'filter' => $this->filterYesNo(),
            'contentOptions' => ['class' => 'text-center'],
            'format' => 'raw',
            'visible' => ArticleSetting::getInfo('headline') ? true : false,
        ];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id' => $model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('trash') ? true : false,
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * function getSetting
	 */
	public function getSetting($field=[])
	{
        if (empty($field)) {
            $field = ['headline', 'headline_limit', 'headline_category', 'media_image_limit', 'media_image_resize', 'media_image_resize_size', 'media_image_view_size', 'media_image_type', 'media_file_limit', 'media_file_type'];
        }

		$setting = ArticleSetting::find()
			->select($field)
			->where(['id' => 1])
			->one();
		
		return $setting;
	}

	/**
	 * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
	 * relative path. default true.
	 */
	public static function getUploadPath($returnAlias=true)
	{
		return ($returnAlias ? Yii::getAlias('@public/article') : 'article');
	}

	/**
	 * function getHeadlines
	 */
	public function getHeadlines()
	{
		$setting = ArticleSetting::find()
			->select(['headline_limit', 'headline_category'])
			->where(['id' => 1])
            ->one();

        if (!is_array(($headlineCategory = $setting->headline_category))) {
            $headlineCategory = [];
        }

		$model = self::find()
			->select(['id'])
			->where(['publish' => 1])
			->andWhere(['IN', 'cat_id', $headlineCategory])
			->andWhere(['headline' => 1])
			->orderBy(['headline_date' => SORT_DESC])
			->all();
		
		$headline = [];
        if (!empty($model)) {
			$i = 0;
			foreach ($model as $val) {
				$i++;
                if ($i <= $setting->headline_limit) {
                    $headline[$i] = $val->id;
                }
            }
        }
		
		return $headline;
	}

	/**
	 * function parseTag
	 */
	public static function parseTag($tags, $attr='tag', $sep='li')
	{
        if (!is_array($tags) || (is_array($tags) && empty($tags))) {
            return '-';
        }

		$items = [];
		foreach ($tags as $key => $val) {
			$items[$val] = Html::a($val, ['admin/manage', $attr => $key], ['title' => $val]);
		}

        if ($sep == 'li') {
			return Html::ul($items, ['item' => function($item, $index) {
				return Html::tag('li', $item);
			}, 'class' => 'list-boxed']);
		}

		return implode($sep, $items);
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->published_date = Yii::$app->formatter->asDate($this->published_date, 'php:Y-m-d');
		// $this->categoryName = isset($this->category) ? $this->category->title->message : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		// $this->tag = implode(', ', $this->getTags(true, 'title'));
		$this->old_image = $this->cover;
		$this->old_file = $this->document;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		$setting = $this->getSetting(['media_image_type', 'media_file_type']);

        if (parent::beforeValidate()) {
            if ($this->image instanceof UploadedFile && !$this->image->getHasError()) {
				$imageFileType = $this->formatFileType($setting->media_image_type);
                if (!in_array(strtolower($this->image->getExtension()), $imageFileType)) {
					$this->addError('image', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', [
						'name' => $this->image->name,
						'extensions' => $setting->media_image_type,
					]));
                }
            } else {
                if ($this->isNewRecord || (!$this->isNewRecord && $this->category->single_photo && $this->old_image == '')) {
                    $this->addError('image', Yii::t('app', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('image')]));
                }
			}

            if ($this->file instanceof UploadedFile && !$this->file->getHasError()) {
				$fileFileType = $this->formatFileType($setting->media_file_type);
                if (!in_array(strtolower($this->file->getExtension()), $fileFileType)) {
					$this->addError('file', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', [
						'name' => $this->file->name,
						'extensions' => $setting->media_file_type,
					]));
				}
			}
			
            if ($this->headline && !$this->publish) {
                $this->publish = 1;
            }

            if ($this->isNewRecord) {
                if ($this->creation_id == null) {
                    $this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            } else {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
        if (parent::beforeSave($insert)) {
            $this->published_date = Yii::$app->formatter->asDate($this->published_date, 'php:Y-m-d');
        
            if (!$insert) {
                // generate upload path
                $uploadPath = join('/', [self::getUploadPath(), $this->id]);
                $verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
                $this->createUploadDirectory(self::getUploadPath(), $this->id);

                // set tags
                $event = new Event(['sender' => $this]);
                Event::trigger(self::className(), self::EVENT_BEFORE_SAVE_ARTICLE, $event);
            }
        }
        return true;
	}
		

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
        $setting = $this->getSetting(['headline', 'headline_limit', 'media_image_resize', 'media_image_resize_size']);

        parent::afterSave($insert, $changedAttributes);

        // generate upload path
        $uploadPath = join('/', [self::getUploadPath(), $this->id]);
        $verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
        $this->createUploadDirectory(self::getUploadPath(), $this->id);

        // Reset headline
        if ($setting->headline && array_key_exists('headline', $changedAttributes) && ($changedAttributes['headline'] != $this->headline) && (count($this->headlines) == $setting->headline_limit) && $this->headline == 1) {
            self::updateAll(['headline' => 0], ['NOT IN', 'id', $this->headlines]);
        }

        if ($insert) {
            // set tags
            $event = new Event(['sender' => $this]);
            Event::trigger(self::className(), self::EVENT_BEFORE_SAVE_ARTICLE, $event);
        }

        // upload image
        if ($this->image instanceof UploadedFile && !$this->image->getHasError()) {
            $fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->image->getExtension());
            if ($this->image->saveAs(join('/', [$uploadPath, $fileName]))) {
                $medias = $this->medias;
                if ($insert || (!$insert && empty($medias))) {
                    $model = new ArticleMedia();
                    $model->cover = 1;
                    $model->article_id = $this->id;
                    $model->media_filename = $fileName;
                    $model->save();
                } else {
                    $coverId = $this->getCoverAttribute()['id'];
                    if (ArticleMedia::findOne($coverId)->updateAttributes(['media_filename' => $fileName])) {
                        $imageResize = $setting->media_image_resize_size;
                        if ($setting->media_image_resize) {
                            $this->resizeImage(join('/', [$uploadPath, $fileName]), $imageResize['width'], $imageResize['height']);
                        }
                    }
                    if ($this->old_image != '' && file_exists(join('/', [$uploadPath, $this->old_image]))) {
                        rename(join('/', [$uploadPath, $this->old_image]), join('/', [$verwijderenPath, $this->id.'-'.time().'_change_'.$this->old_image]));
                    }
                }
            }
        }

        // upload file
        if ($this->file instanceof UploadedFile && !$this->file->getHasError()) {
            $fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->file->getExtension());
            if ($this->file->saveAs(join('/', [$uploadPath, $fileName]))) {
                $files = $this->files;
                if ($insert || (!$insert && empty($files))) {
                    $model = new ArticleFiles();
                    $model->article_id = $this->id;
                    $model->file_filename = $fileName;
                    $model->save();
                } else {
                    $fileId = $this->getDocumentAttribute()['id'];
                    ArticleFiles::findOne($fileId)->updateAttributes(['file_filename' => $fileName]);
                    if ($this->old_file != '' && file_exists(join('/', [$uploadPath, $this->old_file]))) {
                        rename(join('/', [$uploadPath, $this->old_file]), join('/', [$verwijderenPath, $this->id.'-'.time().'_change_'.$this->old_file]));
                    }
                }
            }
        }

        return true;
	}

	/**
	 * Before delete attributes
	 */
	public function beforeDelete()
	{
        if (parent::beforeDelete()) {
            $uploadPath = join('/', [self::getUploadPath(), $this->id]);

            // upload image
            $medias = $this->medias;
            if (!empty($medias)) {
                foreach ($medias as $val) {
                    if ($this->media_filename != '' && file_exists(join('/', [$uploadPath, $this->media_filename]))) {
                        rename(join('/', [$uploadPath, $this->media_filename]), join('/', [$verwijderenPath, $this->id.'-'.time().'_deleted_'.$this->media_filename]));
                    }
                }
            }

            // upload file
            $files = $this->files;
            if (!empty($files)) {
                foreach ($files as $val) {
                    if ($this->file_filename != '' && file_exists(join('/', [$uploadPath, $this->file_filename]))) {
                        rename(join('/', [$uploadPath, $this->file_filename]), join('/', [$verwijderenPath, $this->id.'-'.time().'_deleted_'.$this->file_filename]));
                    }
                }
            }
        }
        return true;
	}

	/**
	 * After delete attributes
	 */
	public function afterDelete()
	{
        parent::afterDelete();

        $uploadPath = join('/', [self::getUploadPath(), $this->id]);
        $this->deleteFolder($uploadPath);
	}
}
