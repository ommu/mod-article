<?php
/**
 * ArticleCategory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 10:18 WIB
 * @modified date 21 May 2019, 12:55 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "_article_category".
 *
 * The followings are the available columns in table "_article_category":
 * @property integer $id
 * @property string $publish
 * @property string $pending
 * @property string $unpublish
 * @property integer $all
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
			[['id', 'all', 'article_id'], 'integer'],
			[['publish', 'pending', 'unpublish'], 'number'],
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
			'pending' => Yii::t('app', 'Pending'),
			'unpublish' => Yii::t('app', 'Unpublish'),
			'all' => Yii::t('app', 'All'),
			'article_id' => Yii::t('app', 'Article'),
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
