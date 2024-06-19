<?php
/**
 * ArticleCategoryGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 10 October 2022, 09:24 WIB
 * @link https://bitbucket.org/ommu/article
 *
 * This is the model class for table "ommu_article_category_grid".
 *
 * The followings are the available columns in table "ommu_article_category_grid":
 * @property integer $id
 * @property integer $article
 * @property string $modified_date
 *
 * The followings are the available model relations:
 * @property ArticleCategory $0
 *
 */

namespace ommu\article\models;

use Yii;

class ArticleCategoryGrid extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_article_category_grid';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['id', 'article'], 'required'],
			[['id', 'article'], 'integer'],
			[['id'], 'unique'],
			[['id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleCategory::className(), 'targetAttribute' => ['id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'article' => Yii::t('app', 'Article'),
			'modified_date' => Yii::t('app', 'Modified Date'),
		];
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
}
