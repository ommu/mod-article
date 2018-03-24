<?php
/**
 * ArticleMedia
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @modified date 23 March 2018, 23:20 WIB
 * @link https://github.com/ommu/ommu-article
 *
 * This is the model class for table "ommu_article_media".
 *
 * The followings are the available columns in table 'ommu_article_media':
 * @property string $media_id
 * @property integer $publish
 * @property integer $cover
 * @property integer $orders
 * @property string $article_id
 * @property string $media_filename
 * @property string $cover_filename
 * @property string $caption
 * @property string $description
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

class ArticleMedia extends OActiveRecord
{
	public $gridForbiddenColumn = array('caption','description','creation_date','creation_search','modified_date','modified_search','updated_date');
	public $media_type_i;	//0=video, 1=photo
	public $old_cover_filename_i;
	
	// Variable Search
	public $category_search;
	public $article_search;
	public $creation_search;
	public $modified_search;
	public $media_type_search;
	public $caption_search;
	public $description_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArticleMedia the static model class
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
		return $matches[1].'.ommu_article_media';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('article_id, cover_filename', 'required'),
			array('publish, cover, orders,
				media_type_i', 'numerical', 'integerOnly'=>true),
			array('article_id, creation_id, modified_id', 'length', 'max'=>11),
			array('caption', 'length', 'max'=>150),
			array('cover, orders, media_filename, caption, description,
				media_type_i, old_cover_filename_i', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('media_id, publish, cover, orders, article_id, media_filename, cover_filename, caption, description, creation_date, creation_id, modified_date, modified_id, updated_date, 
				category_search, article_search, creation_search, modified_search, media_type_search, caption_search, description_search', 'safe', 'on'=>'search'),
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
			'view' => array(self::BELONGS_TO, 'ViewArticleMedia', 'media_id'),
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
			'media_id' => Yii::t('attribute', 'Media'),
			'publish' => Yii::t('attribute', 'Publish'),
			'cover' => Yii::t('attribute', 'Cover'),
			'orders' => Yii::t('attribute', 'Orders'),
			'article_id' => Yii::t('attribute', 'Article'),
			'media_filename' => Yii::t('attribute', 'Media Filename'),
			'cover_filename' => Yii::t('attribute', 'Cover Filename'),
			'caption' => Yii::t('attribute', 'Caption'),
			'description' => Yii::t('attribute', 'Description'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'updated_date' => Yii::t('attribute', 'Updated Date'),
			'old_cover_filename_i' => Yii::t('attribute', 'Old Cover Filename'),
			'category_search' => Yii::t('attribute', 'Category'),
			'article_search' => Yii::t('attribute', 'Article'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
			'media_type_search' => Yii::t('attribute', 'Media Type'),
			'caption_search' => Yii::t('attribute', 'Caption'),
			'description_search' => Yii::t('attribute', 'Description'),
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
			'view' => array(
				'alias'=>'view',
			),
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

		$criteria->compare('t.media_id', $this->media_id);
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
		$criteria->compare('t.cover', $this->cover);
		$criteria->compare('t.orders', $this->orders);
		$criteria->compare('t.article_id', Yii::app()->getRequest()->getParam('article') ? Yii::app()->getRequest()->getParam('article') : $this->article_id);
		$criteria->compare('t.media_filename', strtolower($this->media_filename), true);
		$criteria->compare('t.cover_filename', strtolower($this->cover_filename), true);
		$criteria->compare('t.caption', strtolower($this->caption), true);
		$criteria->compare('t.description', strtolower($this->description), true);
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
		$criteria->compare('view.media', $this->media_type_search);
		$criteria->compare('view.caption', $this->caption_search);
		$criteria->compare('view.description', $this->description_search);

		if(!Yii::app()->getRequest()->getParam('ArticleMedia_sort'))
			$criteria->order = 't.media_id DESC';

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
					'value' => '$data->article->category->title->message',
					'filter'=> ArticleCategory::getCategory(),
					'type' => 'raw',
				);
				$this->templateColumns['article_search'] = array(
					'name' => 'article_search',
					'value' => '$data->article->title',
				);
			}
			$this->templateColumns['media_type_search'] = array(
				'name' => 'media_type_search',
				'value' => '$data->view->media == \'video\' ? Yii::t(\'phrase\', \'Video\') : Yii::t(\'phrase\', \'Photo\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					'video'=>Yii::t('phrase', 'Video'),
					'photo'=>Yii::t('phrase', 'Photo'),
				),
			);
			$this->templateColumns['media_filename'] = array(
				'name' => 'media_filename',
				'value' => '$data->media_filename ? $data->media_filename : \'-\'',
			);
			$this->templateColumns['cover_filename'] = array(
				'name' => 'cover_filename',
				'value' => '$data->cover_filename ? $data->cover_filename : \'-\'',
			);
			$this->templateColumns['caption'] = array(
				'name' => 'caption',
				'value' => '$data->caption ? $data->caption : \'-\'',
			);
			$this->templateColumns['description'] = array(
				'name' => 'description',
				'value' => '$data->description ? $data->description : \'-\'',
			);
			if(!Yii::app()->getRequest()->getParam('creation')) {
				$this->templateColumns['creation_search'] = array(
					'name' => 'creation_search',
					'value' => '$data->creation->displayname ? $data->creation->displayname : \'-\'',
				);
			}
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
			$this->templateColumns['orders'] = array(
				'name' => 'orders',
				'value' => '$data->orders',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->templateColumns['caption_search'] = array(
				'name' => 'caption_search',
				'value' => '$data->view->caption == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			$this->templateColumns['description_search'] = array(
				'name' => 'description_search',
				'value' => '$data->view->description == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			$this->templateColumns['cover'] = array(
				'name' => 'cover',
				'value' => '$data->cover == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			if(!Yii::app()->getRequest()->getParam('type')) {
				$this->templateColumns['publish'] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'publish\', array(\'id\'=>$data->media_id)), $data->publish)',
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
	 * Resize Photo
	 */
	public static function resizePhoto($photo, $size) 
	{
		Yii::import('ext.phpthumb.PhpThumbFactory');
		$resizePhoto = PhpThumbFactory::create($photo, array('jpegQuality'=>90, 'correctPermissions'=>true));
		if($size['height'] == 0)
			$resizePhoto->resize($size['width']);
		else
			$resizePhoto->adaptiveResize($size['width'], $size['height']);
		
		$resizePhoto->save($photo);
		
		return true;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		$controller = strtolower(Yii::app()->controller->id);
		$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
		
		$setting = ArticleSetting::model()->findByPk(1, array(
			'select'=>'media_image_type',
		));

		$media_image_type = unserialize($setting->media_image_type);
		if(empty($media_image_type))
			$media_image_type = array();

		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			else
				$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;

			if($this->media_type_i == 0 && $this->media_filename == '')
				$this->addError('media_filename', Yii::t('phrase', '{attribute} cannot be blank.', array('{attribute}'=>$this->getAttributeLabel('media_filename'))));
			
			if($currentAction != 'o/admin/insertcover') {
				$cover_filename = CUploadedFile::getInstance($this, 'cover_filename');
				if($cover_filename != null) {
					$extension = pathinfo($cover_filename->name, PATHINFO_EXTENSION);
					if(!in_array(strtolower($extension), $media_image_type))
						$this->addError('cover_filename', Yii::t('phrase', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}.', array(
							'{name}'=>$cover_filename->name,
							'{extensions}'=>Utility::formatFileType($media_image_type, false),
						)));
					
				} else {
					if($this->isNewRecord && $controller == 'o/media')
						$this->addError('cover_filename', Yii::t('phrase', '{attribute} cannot be blank.', array('{attribute}'=>$this->getAttributeLabel('cover_filename'))));
				}
			}
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

			// Add directory
			if(!file_exists($article_path)) {
				@mkdir($article_path, 0755, true);

				// Add file in directory (index.php)
				$newFile = $article_path.'/index.php';
				$FileHandle = fopen($newFile, 'w');
			} else
				@chmod($article_path, 0755, true);
		
			//Update album photo
			if(in_array($currentAction, array('o/media/edit'))) {
				$this->cover_filename = CUploadedFile::getInstance($this, 'cover_filename');
				if($this->cover_filename != null) {
					if($this->cover_filename instanceOf CUploadedFile) {
						$fileName = time().'_'.Utility::getUrlTitle($this->article->title).'.'.strtolower($this->cover_filename->extensionName);
						if($this->cover_filename->saveAs($article_path.'/'.$fileName)) {
							if(!$this->isNewRecord) {
								if($this->old_cover_filename_i != '' && file_exists($article_path.'/'.$this->old_cover_filename_i))
									rename($article_path.'/'.$this->old_cover_filename_i, 'public/article/verwijderen/'.$this->article_id.'_'.$this->old_cover_filename_i);
							}
							$this->cover_filename = $fileName;
						}
					}
				} else {
					if(!$this->isNewRecord && $this->cover_filename == '')
						$this->cover_filename = $this->old_cover_filename_i;
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
			'select'=>'media_image_limit, media_image_resize, media_image_resize_size',
		));
		$media_image_resize_size = unserialize($setting->media_image_resize_size);
		$article_path = "public/article/".$this->article_id;
	
		if(!file_exists($article_path)) {
			@mkdir($article_path, 0755, true);

			// Add file in directory (index.php)
			$newFile = $article_path.'/index.php';
			$FileHandle = fopen($newFile, 'w');
		} else
			@chmod($article_path, 0755, true);
	
		//resize cover after upload
		if($setting->media_image_resize == 1 && $this->cover_filename != '')
			self::resizePhoto($article_path.'/'.$this->cover_filename, $media_image_resize_size);
		
		//delete other cover_filename (if media_image_limit = 1)
		if($setting->media_image_limit == 1) {
			$medias = self::model()->findAll(array(
				'condition'=>'media_id <> :media AND publish <> :publish AND article_id = :article',
				'params'=>array(
					':media'=>$this->media_id,
					':publish'=>2,
					':article'=>$this->article_id,
				),
			));
			if($medias != null) {
				foreach($medias as $key => $val)
					self::model()->updateByPk($val->media_id, array('publish'=>2));
			}
		}
		
		//update if new cover (cover = 1)
		if($this->cover == 1) {
			self::model()->updateAll(array('cover'=>0), 'media_id <> :media AND publish <> :publish AND article_id = :article', array(
				':media'=>$this->media_id, 
				':publish'=>2, 
				':article'=>$this->article_id,
			));
		}
	}

	/**
	 * After delete attributes
	 */
	protected function afterDelete() 
	{
		parent::afterDelete();
		//delete article image
		$article_path = "public/article/".$this->article_id;
		
		if($this->cover_filename != '' && file_exists($article_path.'/'.$this->cover_filename))
			rename($article_path.'/'.$this->cover_filename, 'public/article/verwijderen/'.$this->article_id.'_'.$this->cover_filename);

		//reset cover in article
		$medias = $this->article->medias;
		if($medias != null && $this->cover == 1)
			self::model()->updateByPk($medias[0]->media_id, array('cover'=>1));
	}

}