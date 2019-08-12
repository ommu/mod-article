<?php
/**
 * ViewArticleCategory
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2015 Ommu Platform (www.ommu.co)
 * @modified date 22 March 2018, 16:55 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "_article_category".
 *
 * The followings are the available columns in table '_article_category':
 * @property integer $cat_id
 * @property string $articles
 * @property string $article_pending
 * @property string $article_unpublish
 * @property string $article_all
 * @property string $article_id
 */

class ViewArticleCategory extends OActiveRecord
{
	public $gridForbiddenColumn = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewArticleCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		preg_match("/dbname=([^;]+)/i", $this->dbConnection->connectionString, $matches);
		return $matches[1].'._article_category';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'cat_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cat_id', 'numerical', 'integerOnly'=>true),
			array('articles, article_pending, article_unpublish', 'length', 'max'=>23),
			array('article_all', 'length', 'max'=>21),
			array('article_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cat_id, articles, article_pending, article_unpublish, article_all, article_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'article' => array(self::BELONGS_TO, 'Articles', 'article_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cat_id' => Yii::t('attribute', 'Category'),
			'articles' => Yii::t('attribute', 'Articles'),
			'article_pending' => Yii::t('attribute', 'Article Pending'),
			'article_unpublish' => Yii::t('attribute', 'Article Unpublish'),
			'article_all' => Yii::t('attribute', 'Article All'),
			'article_id' => Yii::t('attribute', 'Article'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('t.cat_id', $this->cat_id);
		$criteria->compare('t.articles', $this->articles);
		$criteria->compare('t.article_pending', $this->article_pending);
		$criteria->compare('t.article_unpublish', $this->article_unpublish);
		$criteria->compare('t.article_all', $this->article_all);
		$criteria->compare('t.article_id', $this->article_id);

		if(!Yii::app()->getRequest()->getParam('ViewArticleCategory_sort'))
			$criteria->order = 't.cat_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['grid-view'] ? Yii::app()->params['grid-view']['pageSize'] : 50,
			),
		));
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->templateColumns) == 0) {
			$this->templateColumns['_option'] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			$this->templateColumns['_no'] = array(
				'header' => Yii::t('app', 'No'),
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->templateColumns['cat_id'] = array(
				'name' => 'cat_id',
				'value' => '$data->cat_id',
			);
			$this->templateColumns['articles'] = array(
				'name' => 'articles',
				'value' => '$data->articles',
			);
			$this->templateColumns['article_pending'] = array(
				'name' => 'article_pending',
				'value' => '$data->article_pending',
			);
			$this->templateColumns['article_unpublish'] = array(
				'name' => 'article_unpublish',
				'value' => '$data->article_unpublish',
			);
			$this->templateColumns['article_all'] = array(
				'name' => 'article_all',
				'value' => '$data->article_all',
			);
			$this->templateColumns['article_id'] = array(
				'name' => 'article_id',
				'value' => '$data->article_id',
			);
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id, array(
				'select' => $column,
			));
 			if(count(explode(',', $column)) == 1)
 				return $model->$column;
 			else
 				return $model;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

}