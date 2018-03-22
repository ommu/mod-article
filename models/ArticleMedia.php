<?php
/**
 * ArticleMedia
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-article
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
 * This is the model class for table "ommu_article_media".
 *
 * The followings are the available columns in table 'ommu_article_media':
 * @property string $media_id
 * @property integer $publish
 * @property integer $cover
 * @property string $article_id
 * @property string $media_filename
 * @property string $cover_filename
 * @property string $caption
 * @property string $description
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property Articles $article
 */
class ArticleMedia extends CActiveRecord
{
	public $defaultColumns = array();
	public $video_input;
	public $old_media_input;
	
	// Variable Search
	public $category_search;
	public $article_search;
	public $type_search;
	public $creation_search;
	public $modified_search;
	
	/**
	 * Returns the static model of the specified AR class.
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
		return 'ommu_article_media';
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
			array('publish, cover, creation_id, modified_id', 'numerical', 'integerOnly'=>true),
			array('article_id', 'length', 'max'=>11),
			array('
				video_input', 'length', 'max'=>32),
			array('cover, media_filename, cover_filename, caption, description, creation_date, modified_date,
				old_media_input, video_input', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('media_id, publish, cover, article_id, media_filename, cover_filename, caption, description, creation_date, creation_id, modified_date, modified_id,
				category_search, article_search, type_search, creation_search, modified_search', 'safe', 'on'=>'search'),
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
			'media_id' => Yii::t('attribute', 'Media'),
			'publish' => Yii::t('attribute', 'Publish'),
			'cover' => Yii::t('attribute', 'Cover'),
			'article_id' => Yii::t('attribute', 'Article'),
			'media_filename' => Yii::t('attribute', 'Media (Filename)'),
			'cover_filename' => Yii::t('attribute', 'Media (Photo)'),
			'caption' => Yii::t('attribute', 'Caption'),
			'description' => Yii::t('attribute', 'Description'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'old_media_input' => Yii::t('attribute', 'Old Media'),
			'video_input' => Yii::t('attribute', 'Video'),
			'category_search' => Yii::t('attribute', 'Category'),
			'article_search' => Yii::t('attribute', 'Article'),
			'type_search' => Yii::t('attribute', 'Article Type'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		
		// Custom Search
		$criteria->with = array(
			'article' => array(
				'alias'=>'article',
				'select'=>'publish, cat_id, article_type, title',
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

		$criteria->compare('t.media_id',$this->media_id);
		if(isset($_GET['type']) && $_GET['type'] == 'publish')
			$criteria->compare('t.publish',1);
		elseif(isset($_GET['type']) && $_GET['type'] == 'unpublish')
			$criteria->compare('t.publish',0);
		elseif(isset($_GET['type']) && $_GET['type'] == 'trash')
			$criteria->compare('t.publish',2);
		else {
			$criteria->addInCondition('t.publish', array(0,1));
			$criteria->compare('t.publish',$this->publish);
		}
		$criteria->compare('t.cover',$this->cover);
		if(isset($_GET['article']))
			$criteria->compare('t.article_id',$_GET['article']);
		else
			$criteria->compare('t.article_id',$this->article_id);
		$criteria->compare('t.media_filename',strtolower($this->media_filename),true);
		$criteria->compare('t.cover_filename',strtolower($this->cover_filename),true);
		$criteria->compare('t.caption',strtolower($this->caption),true);
		$criteria->compare('t.description',strtolower($this->description),true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		if(isset($_GET['creation']))
			$criteria->compare('t.creation_id',$_GET['creation']);
		else
			$criteria->compare('t.creation_id',$this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		if(isset($_GET['modified']))
			$criteria->compare('t.modified_id',$_GET['modified']);
		else
			$criteria->compare('t.modified_id',$this->modified_id);
		
		$criteria->compare('article.cat_id',$this->category_search);
		$criteria->compare('article.title',strtolower($this->article_search),true);
		if(isset($_GET['article']) && isset($_GET['publish']))
			$criteria->compare('article.publish',$_GET['publish']);
		$criteria->compare('article.article_type',strtolower($this->type_search),true);
		$criteria->compare('creation.displayname',strtolower($this->creation_search),true);
		$criteria->compare('modified.displayname',strtolower($this->modified_search),true);

		if(!isset($_GET['ArticleMedia_sort']))
			$criteria->order = 't.media_id DESC';

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
		}else {
			//$this->defaultColumns[] = 'media_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'cover';
			$this->defaultColumns[] = 'article_id';
			$this->defaultColumns[] = 'media_filename';
			$this->defaultColumns[] = 'cover_filename';
			$this->defaultColumns[] = 'caption';
			$this->defaultColumns[] = 'description';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'creation_id';
			$this->defaultColumns[] = 'modified_date';
			$this->defaultColumns[] = 'modified_id';
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
			if(!isset($_GET['article'])) {
				$this->defaultColumns[] = array(
					'name' => 'category_search',
					'value' => '$data->article->category->title->message',
					'filter'=> ArticleCategory::getCategory(),
					'type' => 'raw',
				);
				$this->defaultColumns[] = array(
					'name' => 'article_search',
					'value' => '$data->article->title',
				);
				$this->defaultColumns[] = array(
					'name' => 'type_search',
					'value' => '$data->article->article_type == \'standard\' ? Yii::t(\'attribute\', \'Standard\') : ($data->article->article_type == \'video\' ? Yii::t(\'attribute\', \'Video\') : Yii::t(\'attribute\', \'Audio\'))',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter'=>array(
						'standard'=>Yii::t('attribute', 'Standard'),
						'video'=>Yii::t('attribute', 'Video'),
						'quote'=>Yii::t('attribute', 'Quote'),
					),
				);
			}
			$this->defaultColumns[] = array(
				'name' => 'cover_filename',
				'value' => '$data->article->article_type == \'video\' ? CHtml::link("http://www.youtube.com/watch?v=".$data->cover_filename, "http://www.youtube.com/watch?v=".$data->cover_filename, array(\'target\' => \'_blank\')) : CHtml::link($data->cover_filename, Yii::app()->request->baseUrl.\'/public/article/\'.$data->article_id.\'/\'.$data->cover_filename, array(\'target\' => \'_blank\'))',
				'type' => 'raw',
			);
			/*
			$this->defaultColumns[] = array(
				'name' => 'creation_search',
				'value' => '$data->creation->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Utility::dateFormat($data->creation_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this, 
					'attribute'=>'creation_date', 
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'creation_date_filter',
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
			);
			*/
			$this->defaultColumns[] = array(
				'name' => 'caption',
				'value' => '$data->caption != \'\' ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
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
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish", array("id"=>$data->media_id)), $data->publish, 1)',
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
	 * ArticleMedia get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id, array(
				'select'=>$column
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
	public static function resizePhoto($photo, $size) {
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
		
		if(parent::beforeValidate()) 
		{
			if(!$this->isNewRecord) {
				if($this->article->article_type == 'video' && $this->video_input == '')
					$this->addError('video_input', Yii::t('phrase', 'Video cannot be blank.'));
				$this->modified_id = Yii::app()->user->id;
				
			} else
				$this->creation_id = Yii::app()->user->id;
			
			if($currentAction != 'o/admin/insertcover') {
				$cover_filename = CUploadedFile::getInstance($this, 'cover_filename');
				if($cover_filename != null && $this->article->article_type == 'standard') {
					$extension = pathinfo($cover_filename->name, PATHINFO_EXTENSION);
					if(!in_array(strtolower($extension), $media_image_type))
						$this->addError('cover_filename', Yii::t('phrase', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}.', array(
							'{name}'=>$cover_filename->name,
							'{extensions}'=>Utility::formatFileType($media_image_type, false),
						)));
					
				} else {
					if($this->isNewRecord && $controller == 'o/media')
						$this->addError('cover_filename', 'Media (Photo) cannot be blank.');
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

			if($this->article->article_type == 'standard') {
				// Add directory
				if(!file_exists($article_path)) {
					@mkdir($article_path, 0755, true);

					// Add file in directory (index.php)
					$newFile = $article_path.'/index.php';
					$FileHandle = fopen($newFile, 'w');
				} else
					@chmod($article_path, 0755, true);
			
				//Update album photo
				if(in_array($currentAction, array('o/media/add','o/media/edit'))) {
					$this->cover_filename = CUploadedFile::getInstance($this, 'cover_filename');
					if($this->cover_filename != null) {
						if($this->cover_filename instanceOf CUploadedFile) {
							$fileName = time().'_'.Utility::getUrlTitle($this->article->title).'.'.strtolower($this->cover_filename->extensionName);
							if($this->cover_filename->saveAs($article_path.'/'.$fileName)) {
								if(!$this->isNewRecord) {
									if($this->old_media_input != '' && file_exists($article_path.'/'.$this->old_media_input))
										rename($article_path.'/'.$this->old_media_input, 'public/article/verwijderen/'.$this->article_id.'_'.$this->old_media_input);
								}
								$this->cover_filename = $fileName;
							}
						}
					} else {
						if(!$this->isNewRecord && $this->cover_filename == '')
							$this->cover_filename = $this->old_media_input;
					}
				}
				
			} else if($this->article->article_type == 'video' && $controller == 'o/media')
				$this->cover_filename = $this->video_input;
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() {
		parent::afterSave();
		
		$setting = ArticleSetting::model()->findByPk(1, array(
			'select'=>'media_image_limit, media_image_resize, media_image_resize_size',
		));
		$media_image_resize_size = unserialize($setting->media_image_resize_size);
		$article_path = "public/article/".$this->article_id;
		
		if($this->article->article_type == 'standard') {
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
	protected function afterDelete() {
		parent::afterDelete();
		//delete article image
		$article_path = "public/article/".$this->article_id;
		
		if($this->article->article_type == 'standard' && $this->cover_filename != '' && file_exists($article_path.'/'.$this->cover_filename))
			rename($article_path.'/'.$this->cover_filename, 'public/article/verwijderen/'.$this->article_id.'_'.$this->cover_filename);

		//reset cover in article
		$medias = $this->article->medias;
		if($medias != null && $this->cover == 1)
			self::model()->updateByPk($medias[0]->media_id, array('cover'=>1));
	}

}