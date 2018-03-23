<?php
/**
 * ArticleDownloads
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 8 December 2016, 11:36 WIB
 * @modified date 22 March 2018, 16:54 WIB
 * @link https://github.com/ommu/ommu-article
 *
 * This is the model class for table "ommu_article_downloads".
 *
 * The followings are the available columns in table 'ommu_article_downloads':
 * @property string $download_id
 * @property string $file_id
 * @property string $user_id
 * @property integer $downloads
 * @property string $download_date
 * @property string $download_ip
 *
 * The followings are the available model relations:
 * @property ArticleDownloadHistory[] $histories
 * @property ArticleFiles $file
 * @property Users $user
 */

class ArticleDownloads extends OActiveRecord
{
	public $gridForbiddenColumn = array('download_date','download_ip');

	// Variable Search
	public $category_search;
	public $article_search;
	public $file_search;
	public $user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArticleDownloads the static model class
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
		return $matches[1].'.ommu_article_downloads';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('file_id, user_id', 'required'),
			array('downloads', 'numerical', 'integerOnly'=>true),
			array('file_id, user_id', 'length', 'max'=>11),
			array('download_ip', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('download_id, file_id, user_id, downloads, download_date, download_ip,
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
			'histories' => array(self::HAS_MANY, 'ArticleDownloadHistory', 'download_id'),
			'file' => array(self::BELONGS_TO, 'ArticleFiles', 'file_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'download_id' => Yii::t('attribute', 'Download'),
			'file_id' => Yii::t('attribute', 'File'),
			'user_id' => Yii::t('attribute', 'User'),
			'downloads' => Yii::t('attribute', 'Downloads'),
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
			'file' => array(
				'alias'=>'file',
				'select'=>'publish, article_id, file_filename'
			),
			'file.article' => array(
				'alias'=>'article',
				'select'=>'publish, cat_id, title'
			),
			'user' => array(
				'alias'=>'user',
				'select'=>'displayname',
			),
		);

		$criteria->compare('t.download_id', $this->download_id);
		$criteria->compare('t.file_id', Yii::app()->getRequest()->getParam('file') ? Yii::app()->getRequest()->getParam('file') : $this->file_id);
		$criteria->compare('t.user_id', Yii::app()->getRequest()->getParam('user') ? Yii::app()->getRequest()->getParam('user') : $this->user_id);
		$criteria->compare('t.downloads', $this->downloads);
		if($this->download_date != null && !in_array($this->download_date, array('0000-00-00 00:00:00', '1970-01-01 00:00:00')))
			$criteria->compare('date(t.download_date)', date('Y-m-d', strtotime($this->download_date)));
		$criteria->compare('t.download_ip', strtolower($this->download_ip), true);

		$criteria->compare('article.cat_id', $this->category_search);
		$criteria->compare('article.title', strtolower($this->article_search), true);
		if(Yii::app()->getRequest()->getParam('article') && Yii::app()->getRequest()->getParam('publish'))
			$criteria->compare('article.publish', Yii::app()->getRequest()->getParam('publish'));
		$criteria->compare('file.file_filename',strtolower($this->file_search), true);
		if(Yii::app()->getRequest()->getParam('file') && Yii::app()->getRequest()->getParam('publish'))
			$criteria->compare('file.publish', Yii::app()->getRequest()->getParam('publish'));
		$criteria->compare('file.column_name_relation', strtolower($this->file_search), true);
		$criteria->compare('user.displayname', strtolower($this->user_search), true);

		if(!Yii::app()->getRequest()->getParam('ArticleDownloads_sort'))
			$criteria->order = 't.download_id DESC';

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
			if(!Yii::app()->getRequest()->getParam('file')) {
				$this->templateColumns['file_search'] = array(
					'name' => 'file_search',
					'value' => '$data->file->file_filename',
				);
				$this->templateColumns['category_search'] = array(
					'name' => 'category_search',
					'value' => '$data->file->article->category->title->message',
					'filter'=> ArticleCategory::getCategory(),
					'type' => 'raw',
				);
				$this->templateColumns['article_search'] = array(
					'name' => 'article_search',
					'value' => '$data->file->article->title',
				);
			}
			if(!Yii::app()->getRequest()->getParam('user')) {
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->user->displayname ? $data->user->displayname : \'-\'',
				);
			}
			$this->templateColumns['downloads'] = array(
				'name' => 'downloads',
				'value' => 'CHtml::link($data->downloads, Yii::app()->controller->createUrl("history/download/manage", array(\'download\'=>$data->download_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->templateColumns['download_date'] = array(
				'name' => 'download_date',
				'value' => '!in_array($data->download_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\')) ? Utility::dateFormat($data->download_date) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => 'native-datepicker',
				/*
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'download_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'download_date_filter',
						'on_datepicker' => 'on',
						'placeholder' => Yii::t('phrase', 'filter'),
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'dd-mm-yy',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
				*/
			);
			$this->templateColumns['download_ip'] = array(
				'name' => 'download_ip',
				'value' => '$data->download_ip',
				'htmlOptions' => array(
					'class' => 'center',
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
				'select' => $column
			));
			return $model->$column;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

	/**
	 * User get information
	 */
	public static function insertDownload($file_id)
	{
		$criteria=new CDbCriteria;
		$criteria->select = 'download_id, file_id, user_id, downloads';
		$criteria->compare('file_id', $file_id);
		$criteria->compare('user_id', !Yii::app()->user->isGuest ? Yii::app()->user->id : null);
		$findDownload = self::model()->find($criteria);
		
		if($findDownload != null)
			self::model()->updateByPk($findDownload->download_id, array('downloads'=>$findDownload->downloads + 1, 'download_ip'=>$_SERVER['REMOTE_ADDR']));
		
		else {
			$download=new ArticleDownloads;
			$download->file_id = $file_id;
			$download->save();
		}
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->user_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			
			$this->download_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}

}