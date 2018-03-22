<?php
/**
 * ArticleFiles
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (opensource.ommu.co)
 * @created date 22 March 2018, 06:09 WIB
 * @link https://github.com/ommu/ommu-article
 *
 * This is the model class for table "ommu_article_files".
 *
 * The followings are the available columns in table 'ommu_article_files':
 * @property string $file_id
 * @property integer $publish
 * @property string $article_id
 * @property string $file_filename
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Articles $article
 * @property Users $creation
 * @property Users $modified
 */

class ArticleFiles extends OActiveRecord
{
	public $gridForbiddenColumn = array('modified_date','modified_search','updated_date');
	public $old_file_filename_i;

	// Variable Search
	public $category_search;
	public $article_search;
	public $creation_search;
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArticleFiles the static model class
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
		return $matches[1].'.ommu_article_files';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('article_id, file_filename', 'required'),
			array('publish', 'numerical', 'integerOnly'=>true),
			array('article_id, creation_id, modified_id', 'length', 'max'=>11),
			array('
				old_file_filename_i', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('file_id, publish, article_id, file_filename, creation_date, creation_id, modified_date, modified_id, updated_date, 
				category_search, article_search, creation_search, modified_search', 'safe', 'on'=>'search'),
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
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'file_id' => Yii::t('attribute', 'File'),
			'publish' => Yii::t('attribute', 'Publish'),
			'article_id' => Yii::t('attribute', 'Article'),
			'file_filename' => Yii::t('attribute', 'File Filename'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'updated_date' => Yii::t('attribute', 'Updated Date'),
			'old_file_filename_i' => Yii::t('attribute', 'Old File Filename'),
			'category_search' => Yii::t('attribute', 'Category'),
			'article_search' => Yii::t('attribute', 'Article'),
			'creation_search' => Yii::t('attribute', 'Creation'),
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
			'article' => array(
				'alias'=>'article',
				'select'=>'publish, cat_id, title',
			),
			'creation' => array(
				'alias'=>'creation',
				'select'=>'displayname',
			),
			'modified' => array(
				'alias'=>'modified',
				'select'=>'displayname',
			),
		);

		$criteria->compare('t.file_id', $this->file_id);
		if(Yii::app()->getRequest()->getParam('type') == 'publish')
			$criteria->compare('t.publish', 1);
		elseif(Yii::app()->getRequest()->getParam('type') == 'unpublish')
			$criteria->compare('t.publish', 0);
		elseif(Yii::app()->getRequest()->getParam('type') == 'trash')
			$criteria->compare('t.publish', 2);
		else {
			$criteria->addInCondition('t.publish', array(0,1));
			$criteria->compare('t.publish', $this->publish);
		}
		$criteria->compare('t.article_id', Yii::app()->getRequest()->getParam('article') ? Yii::app()->getRequest()->getParam('article') : $this->article_id);
		$criteria->compare('t.file_filename', strtolower($this->file_filename), true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '1970-01-01 00:00:00')))
			$criteria->compare('date(t.creation_date)', date('Y-m-d', strtotime($this->creation_date)));
		$criteria->compare('t.creation_id', Yii::app()->getRequest()->getParam('creation') ? Yii::app()->getRequest()->getParam('creation') : $this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '1970-01-01 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified') ? Yii::app()->getRequest()->getParam('modified') : $this->modified_id);
		if($this->updated_date != null && !in_array($this->updated_date, array('0000-00-00 00:00:00', '1970-01-01 00:00:00')))
			$criteria->compare('date(t.updated_date)', date('Y-m-d', strtotime($this->updated_date)));

		$criteria->compare('article.cat_id', $this->category_search);
		$criteria->compare('article.title', strtolower($this->article_search), true);
		if(Yii::app()->getRequest()->getParam('article') && Yii::app()->getRequest()->getParam('publish'))
			$criteria->compare('article.publish', Yii::app()->getRequest()->getParam('publish'));
		$criteria->compare('creation.displayname', strtolower($this->creation_search), true);
		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);

		if(!Yii::app()->getRequest()->getParam('ArticleFiles_sort'))
			$criteria->order = 't.file_id DESC';

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
			if(!Yii::app()->getRequest()->getParam('article')) {
				$this->templateColumns['category_search'] = array(
					'name' => 'category_search',
					'value' => '$data->article->cat->title->message',
					'filter'=> ArticleCategory::getCategory(),
					'type' => 'raw',
				);
				$this->templateColumns['article_search'] = array(
					'name' => 'article_search',
					'value' => '$data->article->title',
				);
			}
			$this->templateColumns['file_filename'] = array(
				'name' => 'file_filename',
				'value' => '$data->file_filename ? CHtml::link($data->file_filename, Yii::app()->request->baseUrl.\'/public/article/\'.$data->article_id.\'/\'.$data->file_filename, array(\'target\' => \'_blank\')) : \'-\'',
			);
			$this->templateColumns['creation_date'] = array(
				'name' => 'creation_date',
				'value' => '!in_array($data->creation_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\')) ? Utility::dateFormat($data->creation_date) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => 'native-datepicker',
				/*
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'creation_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'creation_date_filter',
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
			if(!Yii::app()->getRequest()->getParam('creation')) {
				$this->templateColumns['creation_search'] = array(
					'name' => 'creation_search',
					'value' => '$data->creation->displayname ? $data->creation->displayname : \'-\'',
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
			if(!Yii::app()->getRequest()->getParam('modified')) {
				$this->templateColumns['modified_search'] = array(
					'name' => 'modified_search',
					'value' => '$data->modified->displayname ? $data->modified->displayname : \'-\'',
				);
			}
			$this->templateColumns['updated_date'] = array(
				'name' => 'updated_date',
				'value' => '!in_array($data->updated_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\')) ? Utility::dateFormat($data->updated_date) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => 'native-datepicker',
				/*
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'updated_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'updated_date_filter',
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
			if(!Yii::app()->getRequest()->getParam('type')) {
				$this->templateColumns['publish'] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'publish\',array(\'id\'=>$data->file_id)), $data->publish)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter'=>array(
						1=>Yii::t('phrase', 'Yes'),
						0=>Yii::t('phrase', 'No'),
					),
					'type' => 'raw',
				);
			}
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
				'select'=>$column
			));
			return $model->$column;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		$controller = strtolower(Yii::app()->controller->id);
		$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
		$setting = ArticleSetting::model()->findByPk(1, array(
			'select'=>'media_file_type',
		));

		$media_file_type = unserialize($setting->media_file_type);
		if(empty($media_file_type))
			$media_file_type = array();
		
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			else
				$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			
			//if($currentAction != 'o/admin/insertcover') {
				$file_filename = CUploadedFile::getInstance($this, 'file_filename');
				if($file_filename != null && $this->article->article_type == 'standard') {
					$extension = pathinfo($file_filename->name, PATHINFO_EXTENSION);
					if(!in_array(strtolower($extension), $media_file_type))
						$this->addError('file_filename', Yii::t('phrase', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}.', array(
							'{name}'=>$file_filename->name,
							'{extensions}'=>Utility::formatFileType($media_file_type, false),
						)));
					
				} else {
					if($this->isNewRecord && $controller == 'o/file')
						$this->addError('file_filename', 'File cannot be blank.');
				}
			//}
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() 
	{
		$controller = strtolower(Yii::app()->controller->id);
		$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);

		if(parent::beforeSave()) {
			$article_path = "public/article/".$this->article_id;

			if($this->article->article_type == 'standard') {
				// Add directory
				if(!file_exists($article_path)) {
					@mkdir($article_path, 0755, true);

					// Add file in directory (index.php)
					$newFile = $article_path.'/index.php';
					$FileHandle = fopen($newFile, 'w');
				} else
					@chmod($article_path, 0755, true);
			
				//Update article file
				if(in_array($currentAction, array('o/file/add','o/file/edit'))) {
					$this->file_filename = CUploadedFile::getInstance($this, 'file_filename');
					if($this->file_filename != null) {
						if($this->file_filename instanceOf CUploadedFile) {
							$fileName = time().'_'.Utility::getUrlTitle($this->article->title).'.'.strtolower($this->file_filename->extensionName);
							if($this->file_filename->saveAs($article_path.'/'.$fileName)) {
								if(!$this->isNewRecord) {
									if($this->old_file_filename_i != '' && file_exists($article_path.'/'.$this->old_file_filename_i))
										rename($article_path.'/'.$this->old_file_filename_i, 'public/article/verwijderen/'.$this->article_id.'_'.$this->old_file_filename_i);
								}
								$this->file_filename = $fileName;
							}
						}
					} else {
						if(!$this->isNewRecord && $this->file_filename == '')
							$this->file_filename = $this->old_file_filename_i;
					}
				}
			}
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() 
	{
		parent::afterSave();
		
		$setting = ArticleSetting::model()->findByPk(1, array(
			'select'=>'media_file_limit',
		));
		$article_path = "public/article/".$this->article_id;

		if($this->article->article_type == 'standard') {
			if(!file_exists($article_path)) {
				@mkdir($article_path, 0755, true);

				// Add file in directory (index.php)
				$newFile = $article_path.'/index.php';
				$FileHandle = fopen($newFile, 'w');
			} else
				@chmod($article_path, 0755, true);
			
			//delete other file (if media_file_limit = 1)
			if($setting->media_file_limit == 1) {
				$files = self::model()->findAll(array(
					'condition'=>'file_id <> :file AND publish <> :publish AND article_id = :article',
					'params'=>array(
						':file'=>$this->file_id,
						':publish'=>2,
						':article'=>$this->article_id,
					),
				));
				if($files != null) {
					foreach($files as $key => $val)
						self::model()->updateByPk($val->file_id, array('publish'=>2));
				}
			}
		}
	}

	/**
	 * After delete attributes
	 */
	protected function afterDelete() 
	{
		parent::afterDelete();
		//delete article file
		$article_path = "public/article/".$this->article_id;
		
		if($this->article->article_type == 'standard' && $this->file_filename != '' && file_exists($article_path.'/'.$this->file_filename))
			rename($article_path.'/'.$this->file_filename, 'public/article/verwijderen/'.$this->article_id.'_'.$this->file_filename);
	}

}