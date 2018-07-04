<?php
/**
 * ViewArticleLikes
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @modified date 22 March 2018, 16:56 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "_article_likes".
 *
 * The followings are the available columns in table '_article_likes':
 * @property string $like_id
 * @property string $article_id
 * @property string $likes
 * @property string $unlikes
 * @property string $like_all
 */

class ViewArticleLikes extends OActiveRecord
{
	public $gridForbiddenColumn = array();

	// Variable Search

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewArticleLikes the static model class
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
		return $matches[1].'._article_likes';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'like_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('article_id', 'required'),
			array('like_id, article_id', 'length', 'max'=>11),
			array('likes, unlikes', 'length', 'max'=>23),
			array('like_all', 'length', 'max'=>21),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('like_id, article_id, likes, unlikes, like_all', 'safe', 'on'=>'search'),
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
			'like_id' => Yii::t('attribute', 'Like'),
			'article_id' => Yii::t('attribute', 'Article'),
			'likes' => Yii::t('attribute', 'Likes'),
			'unlikes' => Yii::t('attribute', 'Unlikes'),
			'like_all' => Yii::t('attribute', 'Like All'),
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

		$criteria->compare('t.like_id', $this->like_id);
		$criteria->compare('t.article_id', $this->article_id);
		$criteria->compare('t.likes', $this->likes);
		$criteria->compare('t.unlikes', $this->unlikes);
		$criteria->compare('t.like_all', $this->like_all);

		if(!Yii::app()->getRequest()->getParam('ViewArticleLikes_sort'))
			$criteria->order = 't.like_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['grid-view'] ? Yii::app()->params['grid-view']['pageSize'] : 20,
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
			$this->templateColumns['like_id'] = array(
				'name' => 'like_id',
				'value' => '$data->like_id',
			);
			$this->templateColumns['article_id'] = array(
				'name' => 'article_id',
				'value' => '$data->article_id',
			);
			$this->templateColumns['likes'] = array(
				'name' => 'likes',
				'value' => '$data->likes',
			);
			$this->templateColumns['unlikes'] = array(
				'name' => 'unlikes',
				'value' => '$data->unlikes',
			);
			$this->templateColumns['like_all'] = array(
				'name' => 'like_all',
				'value' => '$data->like_all',
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