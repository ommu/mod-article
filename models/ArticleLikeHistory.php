<?php
/**
 * ArticleLikeHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 7 February 2017, 02:26 WIB
 * @modified date 22 March 2018, 16:54 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_like_history".
 *
 * The followings are the available columns in table 'ommu_article_like_history':
 * @property string $id
 * @property integer $publish
 * @property string $like_id
 * @property string $likes_date
 * @property string $likes_ip
 *
 * The followings are the available model relations:
 * @property ArticleLikes $like
 */

class ArticleLikeHistory extends OActiveRecord
{
	use GridViewTrait;

	public $gridForbiddenColumn = array();

	// Variable Search
	public $category_search;
	public $article_search;
	public $user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArticleLikeHistory the static model class
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
		return $matches[1].'.ommu_article_like_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('publish, like_id, likes_ip', 'required'),
			array('publish', 'numerical', 'integerOnly'=>true),
			array('like_id', 'length', 'max'=>11),
			array('likes_ip', 'length', 'max'=>20),
			array('likes_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, publish, like_id, likes_date, likes_ip,
				category_search, article_search, user_search', 'safe', 'on'=>'search'),
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
			'like' => array(self::BELONGS_TO, 'ArticleLikes', 'like_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'publish' => Yii::t('attribute', 'Publish'),
			'like_id' => Yii::t('attribute', 'Like'),
			'likes_date' => Yii::t('attribute', 'Likes Date'),
			'likes_ip' => Yii::t('attribute', 'Likes Ip'),
			'category_search' => Yii::t('attribute', 'Category'),
			'article_search' => Yii::t('attribute', 'Article'),
			'user_search' => Yii::t('attribute', 'User'),
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

		// Custom Search
		$criteria->with = array(
			'like' => array(
				'alias' => 'like',
				'select' => 'article_id, user_id'
			),
			'like.article' => array(
				'alias' => 'like_article',
				'select' => 'cat_id, title'
			),
			'like.user' => array(
				'alias' => 'like_user',
				'select' => 'displayname'
			),
		);

		$criteria->compare('t.publish', $this->publish);
		$criteria->compare('t.like_id', Yii::app()->getRequest()->getParam('like') ? Yii::app()->getRequest()->getParam('like') : $this->like_id);
		if($this->likes_date != null && !in_array($this->likes_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.likes_date)', date('Y-m-d', strtotime($this->likes_date)));
		$criteria->compare('t.likes_ip', strtolower($this->likes_ip), true);

		$criteria->compare('like_article.cat_id', $this->category_search);
		$criteria->compare('like_article.title', strtolower($this->article_search), true);
		$criteria->compare('like_user.displayname', strtolower($this->user_search), true);

		if(!Yii::app()->getRequest()->getParam('ArticleLikeHistory_sort'))
			$criteria->order = 't.id DESC';

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
			if(!Yii::app()->getRequest()->getParam('like')) {
				$this->templateColumns['category_search'] = array(
					'name' => 'category_search',
					'value' => '$data->like->article->category->title->message',
					'filter' => ArticleCategory::getCategory(),
					'type' => 'raw',
				);
				$this->templateColumns['article_search'] = array(
					'name' => 'article_search',
					'value' => '$data->like->article->title',
				);
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->like->user->displayname ? $data->like->user->displayname : \'-\'',
				);	
			}
			$this->templateColumns['likes_date'] = array(
				'name' => 'likes_date',
				'value' => '!in_array($data->likes_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->likes_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'likes_date'),
			);
			$this->templateColumns['likes_ip'] = array(
				'name' => 'likes_ip',
				'value' => '$data->likes_ip',
				'htmlOptions' => array(
					//'class' => 'center',
				),
			);
			$this->templateColumns['publish'] = array(
				'name' => 'publish',
				'value' => '$data->publish == 1 ? Yii::t(\'phrase\', \'Like\') : Yii::t(\'phrase\', \'Unlike\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' =>array(
					1=>Yii::t('phrase', 'Like'),
					0=>Yii::t('phrase', 'Unlike'),
				),
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