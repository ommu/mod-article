<?php
/**
 * ArticleDownloadHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 8 January 2017, 23:04 WIB
 * @modified date 22 March 2018, 16:54 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_download_history".
 *
 * The followings are the available columns in table 'ommu_article_download_history':
 * @property string $id
 * @property string $download_id
 * @property string $download_date
 * @property string $download_ip
 *
 * The followings are the available model relations:
 * @property ArticleDownloads $download
 */

class ArticleDownloadHistory extends OActiveRecord
{
	use GridViewTrait;

	public $gridForbiddenColumn = array();

	// Variable Search
	public $category_search;
	public $article_search;
	public $file_search;
	public $user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArticleDownloadHistory the static model class
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
		return $matches[1].'.ommu_article_download_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('download_id, download_ip', 'required'),
			array('download_id', 'length', 'max'=>11),
			array('download_ip', 'length', 'max'=>20),
			array('download_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, download_id, download_date, download_ip,
				category_search, article_search, file_search, user_search', 'safe', 'on'=>'search'),
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
			'download' => array(self::BELONGS_TO, 'ArticleDownloads', 'download_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'download_id' => Yii::t('attribute', 'Download'),
			'download_date' => Yii::t('attribute', 'Download Date'),
			'download_ip' => Yii::t('attribute', 'Download Ip'),
			'category_search' => Yii::t('attribute', 'Category'),
			'article_search' => Yii::t('attribute', 'Article'),
			'file_search' => Yii::t('attribute', 'File'),
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
			'download' => array(
				'alias'=>'download',
				'select'=>'file_id, user_id'
			),
			'download.file' => array(
				'alias'=>'download_file',
				'select'=>'article_id, file_filename'
			),
			'download.file.article' => array(
				'alias'=>'download_article',
				'select'=>'cat_id, title'
			),
			'download.user' => array(
				'alias'=>'download_user',
				'select'=>'displayname'
			),
		);

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.download_id', Yii::app()->getRequest()->getParam('download') ? Yii::app()->getRequest()->getParam('download') : $this->download_id);
		if($this->download_date != null && !in_array($this->download_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.download_date)', date('Y-m-d', strtotime($this->download_date)));
		$criteria->compare('t.download_ip', strtolower($this->download_ip), true);

		$criteria->compare('download_article.cat_id', $this->category_search);
		$criteria->compare('download_article.title', strtolower($this->article_search), true);
		$criteria->compare('download_file.file_filename', strtolower($this->file_search), true);
		$criteria->compare('download_user.displayname', strtolower($this->user_search), true);

		if(!Yii::app()->getRequest()->getParam('ArticleDownloadHistory_sort'))
			$criteria->order = 't.id DESC';

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
			if(!Yii::app()->getRequest()->getParam('download')) {
				$this->templateColumns['file_search'] = array(
					'name' => 'file_search',
					'value' => '$data->download->file->file_filename',
				);
				$this->templateColumns['category_search'] = array(
					'name' => 'category_search',
					'value' => '$data->download->file->article->category->title->message',
					'filter'=> ArticleCategory::getCategory(),
					'type' => 'raw',
				);
				$this->templateColumns['article_search'] = array(
					'name' => 'article_search',
					'value' => '$data->download->file->article->title',
				);
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->download->user->displayname ? $data->download->user->displayname : \'-\'',
				);	
			}
			$this->templateColumns['download_date'] = array(
				'name' => 'download_date',
				'value' => '!in_array($data->download_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->download_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'download_date'),
			);
			$this->templateColumns['download_ip'] = array(
				'name' => 'download_ip',
				'value' => '$data->download_ip',
				'htmlOptions' => array(
					//'class' => 'center',
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