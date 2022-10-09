<?php
/**
 * ArticleCategory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 09:26 WIB
 * @modified date 11 May 2019, 21:28 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_category".
 *
 * The followings are the available columns in table "ommu_article_category":
 * @property integer $id
 * @property integer $publish
 * @property integer $parent_id
 * @property integer $name
 * @property integer $desc
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
 * @property SourceMessage $title
 * @property SourceMessage $description
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\article\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use app\models\SourceMessage;
use yii\helpers\ArrayHelper;
use ommu\article\models\view\ArticleCategory as ArticleCategoryView;
use app\models\Users;

class ArticleCategory extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'desc_i', 'updated_date', 'pending', 'unpublish'];

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
			[['name_i', 'desc_i'], 'required'],
			[['publish', 'parent_id', 'name', 'desc', 'single_photo', 'single_file', 'creation_id', 'modified_id'], 'integer'],
			[['name_i', 'desc_i'], 'string'],
			[['name_i'], 'string', 'max' => 64],
			[['desc_i'], 'string', 'max' => 128],
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
			'parent_id' => Yii::t('app', 'Parent'),
			'name' => Yii::t('app', 'Category'),
			'desc' => Yii::t('app', 'Description'),
			'single_photo' => Yii::t('app', 'Single Photo'),
			'single_file' => Yii::t('app', 'Single File'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'name_i' => Yii::t('app', 'Category'),
			'desc_i' => Yii::t('app', 'Description'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'articles' => Yii::t('app', 'Articles'),
			'pending' => Yii::t('app', 'Pending'),
			'unpublish' => Yii::t('app', 'Unpublish'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(ArticleCategoryView::className(), ['id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArticlesByStatus($cat_id, $type='published')
	{
		$model = Articles::find()
            ->alias('t')
            ->where(['t.cat_id' => $cat_id]);
        if ($type == 'published') {
            $model->published();
        } else if ($type == 'pending') {
            $model->pending();
        }
		$articles = $model->count();

		return $articles ? $articles : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArticles($count=false, $publish=null)
	{
        if ($count == false) {
			$model = $this->hasMany(Articles::className(), ['cat_id' => 'id'])
                ->alias('articles');
            if ($publish != null) {
                $model->andOnCondition([sprintf('%s.publish', 'articles') => $publish]);
            } else {
                $model->andOnCondition(['IN', sprintf('%s.publish', 'articles'), [0,1]]);
            }

            return $model;
        }

        if ($publish === null) {
            return self::getArticlesByStatus($this->id, 'published');
        }

		$model = Articles::find()
            ->alias('t')
            ->where(['t.cat_id' => $this->id]);
        if ($publish == 0) {
            $model->unpublish();
        } else if ($publish == 1) {
            $model->published();
        } else if ($publish == 2) {
            $model->deleted();
        }
		$articles = $model->count();

		return $articles ? $articles : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPending($count=false)
	{
        if ($count == false) {
            return $this->hasMany(Articles::className(), ['cat_id' => 'id']);
        }

		return self::getArticlesByStatus($this->id, 'pending');
	}

	/**
	 * @param $type relation|array|count
	 * @return \yii\db\ActiveQuery
	 */
	public function getSubs($type='relation', $publish=1, $inherit=false)
	{
        if ($type == 'relation') {
            $model = $this->hasMany(self::className(), ['parent_id' => 'id'])
                ->alias('subs');

            if ($publish != null) {
                return $model->andOnCondition([sprintf('%s.publish', 'subs') => $publish]);
            } else {
                return $model->andOnCondition(['IN', sprintf('%s.publish', 'subs'), [0,1]]);
            }
        }

		$model = self::find()
            ->alias('t')
			->select(['t.id', 't.name'])
			->where(['t.parent_id' => $this->id]);
        if ($publish != null) {
            if ($publish == 0) {
                $model->unpublish();
            } else if ($publish == 1) {
                $model->published();
            } else if ($publish == 2) {
				$model->deleted();
            }
		} else {
            $model->andWhere(['IN', 't.publish', [0,1]]);
        }

        if ($type == 'array') {
			$model = $model->all();
            $subs = ArrayHelper::map($model, 'id', 'title.message');

            if ($inherit == true) {
                $inheritSubs = $this->subs;
                if ($inheritSubs != null) {
                    $subs = $this->getSubsInherit($inheritSubs, $subs, $type);
                }
            }

			return $subs;
		}

        $subs = $model->count();
        if ($inherit == true) {
            $inheritSubs = $this->subs;
            if ($inheritSubs != null) {
                $subs = $subs + $this->getSubsInherit($inheritSubs, $subs, $type);
            }
        }

        return $subs ? $subs : 0;
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getParent()
	{
		return $this->hasOne(ArticleCategory::className(), ['id' => 'parent_id'])
            ->select(['id', 'parent_id', 'name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getParentTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'name'])
            ->select(['id', 'message'])
            ->via('parent');
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'name'])
            ->select(['id', 'message']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDescription()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'desc'])
            ->select(['id', 'message']);
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
	 * {@inheritdoc}
	 * @return \ommu\article\models\query\ArticleCategory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\article\models\query\ArticleCategory(get_called_class());
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
		$this->templateColumns['parent_id'] = [
			'attribute' => 'parent_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->parentTitle) ? $model->parentTitle->message : '-';
			},
			'filter' => ArticleCategory::getCategory(null, 'is_null'),
		];
		$this->templateColumns['name_i'] = [
			'attribute' => 'name_i',
			'value' => function($model, $key, $index, $column) {
				return isset($model->title) ? $model->title->message : '';
			},
		];
		$this->templateColumns['desc_i'] = [
			'attribute' => 'desc_i',
			'value' => function($model, $key, $index, $column) {
				return isset($model->description) ? $model->description->message : '';
			},
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
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$articles = $model->getArticles(true);
				return Html::a($articles, ['admin/manage', 'category' => $model->primaryKey, 'status' => 'publish'], ['title' => Yii::t('app', '{count} articles', ['count' => $articles]), 'data-pjax' => 0]);
			},
			'filter' => false,
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['pending'] = [
			'attribute' => 'pending',
			'value' => function($model, $key, $index, $column) {
				$pending = $model->getPending(true);
				return Html::a($pending, ['admin/manage', 'category' => $model->primaryKey, 'status' => 'pending'], ['title' => Yii::t('app', '{count} articles', ['count' => $pending]), 'data-pjax' => 0]);
			},
			'filter' => false,
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['unpublish'] = [
			'attribute' => 'unpublish',
			'value' => function($model, $key, $index, $column) {
				$unpublish = $model->getArticles(true, 0);
				return Html::a($unpublish, ['admin/manage', 'category' => $model->primaryKey, 'publish' => 0], ['title' => Yii::t('app', '{count} articles', ['count' => $unpublish]), 'data-pjax' => 0]);
			},
			'filter' => false,
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['single_photo'] = [
			'attribute' => 'single_photo',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->single_photo);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['single_file'] = [
			'attribute' => 'single_file',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->single_file);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['setting/category/publish', 'id' => $model->primaryKey]);
				return $this->quickAction($url, $model->publish, 'Enable,Disable');
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
	 * function getCategory
	 */
	public static function getCategory($publish=null, $parent=null, $type='array')
	{
		$model = self::find()->alias('t')
			->select(['t.id', 't.name']);
		$model->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.name=title.id');
        if ($publish != null) {
            $model->andWhere(['t.publish' => $publish]);
        } else {
            $model->andWhere(['in', 't.publish', [0,1]]);
        }
        if ($parent == 'is_null') {
            $model->andWhere(['is', 't.parent_id', null]);
        } else {
            if ($parent != null) {
                $model->andWhere(['t.parent_id' => $parent]);
            }
        }

		$model = $model->orderBy('title.message ASC')->all();

        if ($type == 'array') {
            return \yii\helpers\ArrayHelper::map($model, 'id', 'title.message');
        } else if ($type == 'optgroup') {
            return self::getOptgroup($model, $publish);
        } else if ($type == 'checkboxGroup') {
            return self::getOptgroup($model, $publish, 'checkbox');
        }

		return $model;
	}

	/**
	 * function getCategory
	 */
	public static function getOptgroup($categories, $publish=null, $type='select')
    {
        $data = [];
        if ($categories != null) {
            foreach ($categories as $key => $category) {
                $subs = $category->getSubs('relation', $publish)->all();
                if ($subs == null) {
                    $data[$category->id] = $category->title->message;
                } else {
                    if ($type == 'select') {
                        $data[$category->title->message] = $category::getOptgroup($subs, $publish);
                    } else if ($type == 'checkbox') {
                        $data[$category->id] = ['id' => $category->id, 'label' => $category->title->message];
                        $data = ArrayHelper::merge($data, $category::getOptgroup($subs, $publish, 'checkbox'));
                    }
                }
            }
        }

        return $data;
    }

	/**
	 * function getSubsInherit
	 */
    public function getSubsInherit($subs, $return, $type='array')
    {
        if ($subs != null) {
            foreach ($subs as $sub) {
                $inheritSubs = $sub->subs;
                if ($type == 'array') {
                    $return = ArrayHelper::merge($return, ArrayHelper::map($inheritSubs, 'id', 'title.message'));
                } else {
                    $return = $return + $sub->getSubs('count');
                }
                if ($inheritSubs != null) {
                    $return = $sub->getSubsInherit($inheritSubs, $return, $type);
                }
            }
            return $return;
        }
    }

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->name_i = isset($this->title) ? $this->title->message : '';
		// $this->desc_i = isset($this->description) ? $this->description->message : '';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		// $this->article = $this->getArticles(true) ? 1 : 0;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
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
        $module = strtolower(Yii::$app->controller->module->id);
        $controller = strtolower(Yii::$app->controller->id);
        $action = strtolower(Yii::$app->controller->action->id);

        $location = Inflector::slug($module.' '.$controller);

        if (parent::beforeSave($insert)) {
            if ($insert || (!$insert && !$this->name)) {
                $name = new SourceMessage();
                $name->location = $location.'_title';
                $name->message = $this->name_i;
                if ($name->save()) {
                    $this->name = $name->id;
                }

            } else {
                $name = SourceMessage::findOne($this->name);
                $name->message = $this->name_i;
                $name->save();
            }

            if ($insert || (!$insert && !$this->desc)) {
                $desc = new SourceMessage();
                $desc->location = $location.'_description';
                $desc->message = $this->desc_i;
                if ($desc->save()) {
                    $this->desc = $desc->id;
                }

            } else {
                $desc = SourceMessage::findOne($this->desc);
                $desc->message = $this->desc_i;
                $desc->save();
            }
        }
        return true;
	}
}
