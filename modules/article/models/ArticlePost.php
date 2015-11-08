<?php
/**
 * ArticlePost * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
 *
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 *
 * --------------------------------------------------------------------------------------
 *
 * This is the model class for table "ommu_article_post".
 *
 * The followings are the available columns in table 'ommu_article_post':
 * @property string $post_id
 * @property string $post_name
 * @property integer $orders
 */
class ArticlePost extends CActiveRecord
{
	public $defaultColumns = array();
	
	public $count_article;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArticlePost the static model class
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
		return 'ommu_article_post';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('post_name', 'required'),
			array('orders', 'numerical', 'integerOnly'=>true),
			array('
				count_article', 'length', 'max'=>11),
			array('post_name', 'length', 'max'=>32),
			array('orders', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('post_id, post_name, orders,
				count_article', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'post_id' => 'Post',
			'post_name' => 'Post Name',
			'orders' => 'Orders',
			'count_article' => 'Article',
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

		$criteria->compare('t.post_id',$this->post_id);
		$criteria->compare('t.post_name',$this->post_name,true);
		$criteria->compare('t.orders',$this->orders);
		$criteria->compare('t.count_article',$this->count_article);

		if(!isset($_GET['ArticlePost_sort']))
			$criteria->order = 'post_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}


	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) {
		if($columns !== null) {
			foreach($columns as $val) {
				/*
				if(trim($val) == 'enabled') {
					$this->defaultColumns[] = array(
						'name'  => 'enabled',
						'value' => '$data->enabled == 1? "Ya": "Tidak"',
					);
				}
				*/
				$this->defaultColumns[] = $val;
			}
		} else {
			//$this->defaultColumns[] = 'post_id';
			$this->defaultColumns[] = 'post_name';
			$this->defaultColumns[] = 'orders';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			$this->defaultColumns[] = 'post_name';
			$this->defaultColumns[] = array(
				'header' => 'count_article',
				'value' => '$data->count_article." ".Phrase::trans(26000,1)',
				'type' => 'raw',
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
			$model = self::model()->findByPk($id,array(
				'select' => $column
			));
			return $model->$column;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;			
		}
	}

	/**
	 * Get category
	 * 0 = unpublish
	 * 1 = publish
	 */
	public static function getPost() {
		$model = self::model()->findAll();

		$items = array();
		if($model != null) {
			foreach($model as $key => $val) {
				$items[$val->post_id] = $val->post_name;
			}
			return $items;
		} else {
			return false;
		}
	}

	/**
	 * Get Article
	 */
	public static function getArticle($id, $type=null) {
		$criteria=new CDbCriteria;
		$criteria->compare('post_id',$id);
		
		if($type == null) {
			//$criteria->select = '';
			$model = Articles::model()->findAll($criteria);
		} else {
			$model = Articles::model()->count($criteria);
		}
		
		return $model;
	}
	
	protected function afterFind() {
		$this->count_article = self::getArticle($this->post_id, 'count');
		parent::afterFind();
	}

}