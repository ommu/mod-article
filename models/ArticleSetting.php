<?php
/**
 * ArticleSetting
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @modified date 22 March 2018, 19:23 WIB
 * @link https://github.com/ommu/ommu-article
 *
 * This is the model class for table "ommu_article_setting".
 *
 * The followings are the available columns in table 'ommu_article_setting':
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_keyword
 * @property string $meta_description
 * @property integer $headline
 * @property integer $headline_limit
 * @property string $headline_category
 * @property integer $media_image_limit
 * @property integer $media_image_resize
 * @property string $media_image_resize_size
 * @property string $media_image_view_size
 * @property string $media_image_type
 * @property integer $media_file_limit
 * @property string $media_file_type
 * @property string $modified_date
 * @property string $modified_id
 */

class ArticleSetting extends OActiveRecord
{
	public $gridForbiddenColumn = array();

	// Variable Search
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArticleSetting the static model class
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
		return $matches[1].'.ommu_article_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('license, permission, meta_keyword, meta_description, headline, headline_limit, media_image_limit, media_image_resize, media_image_type, media_file_limit, media_file_type', 'required'),
			array('permission, headline, headline_limit, media_image_limit, media_image_resize, media_file_limit', 'numerical', 'integerOnly'=>true),
			array('license', 'length', 'max'=>32),
			array('modified_id', 'length', 'max'=>11),
			array('headline_limit', 'length', 'max'=>3),
			array('headline_category, media_image_resize_size, media_image_view_size, media_image_type, media_file_type', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, license, permission, meta_keyword, meta_description, headline, headline_limit, headline_category, media_image_limit, media_image_resize, media_image_resize_size, media_image_view_size, media_image_type, media_file_limit, media_file_type, modified_date, modified_id, 
				modified_search', 'safe', 'on'=>'search'),
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
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'license' => Yii::t('attribute', 'License Key'),
			'permission' => Yii::t('attribute', 'Public Permission Defaults'),
			'meta_keyword' => Yii::t('attribute', 'Meta Keyword'),
			'meta_description' => Yii::t('attribute', 'Meta Description'),
			'headline' => Yii::t('attribute', 'Headline'),
			'headline_limit' => Yii::t('attribute', 'Headline Limit'),
			'headline_category' => Yii::t('attribute', 'Headline Category'),
			'media_image_limit' => Yii::t('attribute', 'Image Limit'),
			'media_image_resize' => Yii::t('attribute', 'Image Resize'),
			'media_image_resize_size' => Yii::t('attribute', 'Image Resize Size'),
			'media_image_view_size' => Yii::t('attribute', 'Image View Size'),
			'media_image_type' => Yii::t('attribute', 'Image File Type'),
			'media_file_limit' => Yii::t('attribute', 'File Upload Limit'),
			'media_file_type' => Yii::t('attribute', 'Upload File Type'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'modified_search' => Yii::t('attribute', 'Modified'),
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
			'modified' => array(
				'alias'=>'modified',
				'select'=>'displayname',
			),
		);

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.license', $this->license, true);
		$criteria->compare('t.permission', $this->permission);
		$criteria->compare('t.meta_keyword', strtolower($this->meta_keyword), true);
		$criteria->compare('t.meta_description', strtolower($this->meta_description), true);
		$criteria->compare('t.headline', $this->headline);
		$criteria->compare('t.headline_limit', $this->headline_limit);
		$criteria->compare('t.headline_category', $this->headline_category, true);
		$criteria->compare('t.media_image_limit', $this->media_image_limit);
		$criteria->compare('t.media_image_resize', $this->media_image_resize);
		$criteria->compare('t.media_image_resize_size', $this->media_image_resize_size, true);
		$criteria->compare('t.media_image_view_size', $this->media_image_view_size, true);
		$criteria->compare('t.media_image_type', $this->media_image_type, true);
		$criteria->compare('t.media_file_limit', $this->media_file_limit);
		$criteria->compare('t.media_file_type', $this->media_file_type, true);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '1970-01-01 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified') ? Yii::app()->getRequest()->getParam('modified') : $this->modified_id);

		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);

		if(!Yii::app()->getRequest()->getParam('ArticleSetting_sort'))
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
			$this->templateColumns['license'] = array(
				'name' => 'license',
				'value' => '$data->license',
			);
			$this->templateColumns['permission'] = array(
				'name' => 'permission',
				'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'permission\', array(\'id\'=>$data->id)), $data->permission)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			$this->templateColumns['meta_keyword'] = array(
				'name' => 'meta_keyword',
				'value' => '$data->meta_keyword',
			);
			$this->templateColumns['meta_description'] = array(
				'name' => 'meta_description',
				'value' => '$data->meta_description',
			);
			$this->templateColumns['headline'] = array(
				'name' => 'headline',
				'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'headline\', array(\'id\'=>$data->id)), $data->headline)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			$this->templateColumns['headline_limit'] = array(
				'name' => 'headline_limit',
				'value' => '$data->headline_limit',
			);
			$this->templateColumns['headline_category'] = array(
				'name' => 'headline_category',
				'value' => '$data->headline_category',
			);
			$this->templateColumns['media_image_limit'] = array(
				'name' => 'media_image_limit',
				'value' => '$data->media_image_limit',
			);
			$this->templateColumns['media_image_resize_size'] = array(
				'name' => 'media_image_resize_size',
				'value' => '$data->media_image_resize_size',
			);
			$this->templateColumns['media_image_view_size'] = array(
				'name' => 'media_image_view_size',
				'value' => '$data->media_image_view_size',
			);
			$this->templateColumns['media_image_type'] = array(
				'name' => 'media_image_type',
				'value' => '$data->media_image_type',
			);
			$this->templateColumns['media_file_limit'] = array(
				'name' => 'media_file_limit',
				'value' => '$data->media_file_limit',
			);
			$this->templateColumns['media_image_resize'] = array(
				'name' => 'media_image_resize',
				'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'media_image_resize\', array(\'id\'=>$data->id)), $data->media_image_resize)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			$this->templateColumns['media_file_type'] = array(
				'name' => 'media_file_type',
				'value' => '$data->media_file_type',
			);
			if(!Yii::app()->getRequest()->getParam('modified')) {
				$this->templateColumns['modified_search'] = array(
					'name' => 'modified_search',
					'value' => '$data->modified->displayname ? $data->modified->displayname : \'-\'',
				);
			}
			$this->templateColumns['modified_date'] = array(
				'name' => 'modified_date',
				'value' => '!in_array($data->modified_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\')) ? Utility::dateFormat($data->modified_date) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => 'native-datepicker',
				/*
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'modified_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'modified_date_filter',
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
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk(1, array(
				'select' => $column
			));
			return $model->$column;
		
		} else {
			$model = self::model()->findByPk(1);
			return $model;
		}
	}

	/**
	 * User get information
	 */
	public static function getHeadlineCategory()
	{
		$setting = self::model()->findByPk(1, array(
			'select' => 'headline_category',
		));
		
		$headline_category = unserialize($setting->headline_category);
		if(empty($headline_category))
			$headline_category = array();
		
		return $headline_category;		
	}

	/**
	 * get Module License
	 */
	public static function getLicense($source='1234567890', $length=16, $char=4)
	{
		$mod = $length%$char;
		if($mod == 0)
			$sep = ($length/$char);
		else
			$sep = (int)($length/$char)+1;
		
		$sourceLength = strlen($source);
		$random = '';
		for ($i = 0; $i < $length; $i++)
			$random .= $source[rand(0, $sourceLength - 1)];
		
		$license = '';
		for ($i = 0; $i < $sep; $i++) {
			if($i != $sep-1)
				$license .= substr($random,($i*$char),$char).'-';
			else
				$license .= substr($random,($i*$char),$char);
		}

		return $license;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->headline == 1) {
				if($this->headline_limit != '' && $this->headline_limit <= 0)
					$this->addError('headline_limit', Yii::t('phrase', 'Headline Limit lebih besar dari 0'));
				if($this->headline_category == '')
					$this->addError('headline_category', Yii::t('phrase', 'Headline Category cannot be blank.'));
			}
			
			if($this->media_image_limit != '' && $this->media_image_limit <= 0)
				$this->addError('media_image_limit', Yii::t('phrase', 'Photo Limit lebih besar dari 0'));
			
			if($this->media_image_resize == 1 && ($this->media_image_resize_size['width'] == '' || $this->media_image_resize_size['height'] == ''))
				$this->addError('media_image_resize_size', Yii::t('phrase', 'Media Resize cannot be blank.'));
			
			if($this->media_image_view_size['large']['width'] == '' || $this->media_image_view_size['large']['height'] == '')
				$this->addError('media_image_view_size[large]', Yii::t('phrase', 'Large Size cannot be blank.'));
			
			if($this->media_image_view_size['medium']['width'] == '' || $this->media_image_view_size['medium']['height'] == '')
				$this->addError('media_image_view_size[medium]', Yii::t('phrase', 'Medium Size cannot be blank.'));
			
			if($this->media_image_view_size['small']['width'] == '' || $this->media_image_view_size['small']['height'] == '')
				$this->addError('media_image_view_size[small]', Yii::t('phrase', 'Small Size cannot be blank.'));
			
			if($this->media_file_limit != '' && $this->media_file_limit <= 0)
				$this->addError('media_file_limit', Yii::t('phrase', 'File Limit lebih besar dari 0'));
			
			// Article type is active
			
			$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() 
	{
		if(parent::beforeSave()) {
			$this->headline_category = serialize($this->headline_category);
			$this->media_image_resize_size = serialize($this->media_image_resize_size);
			$this->media_image_view_size = serialize($this->media_image_view_size);
			$this->media_image_type = serialize(Utility::formatFileType($this->media_image_type));
			$this->media_file_type = serialize(Utility::formatFileType($this->media_file_type));
		}
		return true;
	}

}