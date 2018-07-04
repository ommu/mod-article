<?php
/**
 * Articles
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 26 March 2018, 05:07 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_articles".
 *
 * The followings are the available columns in table 'ommu_articles':
 * @property string $article_id
 * @property integer $publish
 * @property integer $cat_id
 * @property string $title
 * @property string $body
 * @property string $quote
 * @property string $published_date
 * @property integer $headline
 * @property integer $comment_code
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 * @property string $headline_date
 * @property string $updated_date
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property ArticleFiles[] $files
 * @property ArticleLikes[] $likes
 * @property ArticleMedia[] $medias
 * @property ArticleTag[] $tags
 * @property ArticleViews[] $views
 * @property ArticleCategory $category
 * @property Users $creation
 * @property Users $modified
 */

class Articles extends OActiveRecord
{
	public $gridForbiddenColumn = array('body','quote','comment_code','creation_search','modified_date','modified_search','headline_date','updated_date','slug','photo_search','like_search','tag_search');
	public $media_type_i;	//0=video, 1=photo
	public $media_video_i;
	public $media_photo_i;
	public $old_media_photo_i;
	public $media_file_i;
	public $old_media_file_i;
	public $keyword_i;
	
	// Variable Search
	public $creation_search;
	public $modified_search;
	public $photo_search;
	public $view_search;
	public $like_search;
	public $downlaod_search;
	public $tag_search;

	/**
	 * Behaviors for this model
	 */
	public function behaviors() 
	{
		return array(
			'sluggable' => array(
				'class'=>'ext.yii-sluggable.SluggableBehavior',
				'columns' => array('title'),
				'unique' => true,
				'update' => true,
			),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Articles the static model class
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
		return $matches[1].'.ommu_articles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cat_id, title, body, published_date', 'required'),
			array('publish, cat_id, headline, comment_code,
				media_type_i', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>128),
			array('creation_id, modified_id', 'length', 'max'=>11),
			array('quote,
				media_type_i, media_video_i, media_photo_i, old_media_photo_i, media_file_i, old_media_file_i, keyword_i', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('article_id, publish, cat_id, title, body, quote, published_date, headline, comment_code, creation_date, creation_id, modified_date, modified_id, headline_date, updated_date, slug, 
				creation_search, modified_search, photo_search, view_search, like_search, downlaod_search, tag_search', 'safe', 'on'=>'search'),
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
			'view' => array(self::BELONGS_TO, 'ViewArticles', 'article_id'),
			'files' => array(self::HAS_MANY, 'ArticleFiles', 'article_id', 'on'=>'files.publish = 1'),
			'likes' => array(self::HAS_MANY, 'ArticleLikes', 'article_id', 'on'=>'likes.publish = 1'),
			'medias' => array(self::HAS_MANY, 'ArticleMedia', 'article_id', 'on'=>'medias.publish = 1'),
			'tags' => array(self::HAS_MANY, 'ArticleTag', 'article_id', 'on'=>'tags.publish = 1'),
			'views' => array(self::HAS_MANY, 'ArticleViews', 'article_id', 'on'=>'views.publish = 1'),
			'category' => array(self::BELONGS_TO, 'ArticleCategory', 'cat_id'),
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
			'article_id' => Yii::t('attribute', 'Article'),
			'publish' => Yii::t('attribute', 'Publish'),
			'cat_id' => Yii::t('attribute', 'Category'),
			'title' => Yii::t('attribute', 'Title'),
			'body' => Yii::t('attribute', 'Article'),
			'quote' => Yii::t('attribute', 'Quote'),
			'published_date' => Yii::t('attribute', 'Published Date'),
			'headline' => Yii::t('attribute', 'Headline'),
			'comment_code' => Yii::t('attribute', 'Comment'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'headline_date' => Yii::t('attribute', 'Headline Date'),
			'updated_date' => Yii::t('attribute', 'Updated Date'),
			'slug' => Yii::t('attribute', 'Slug'),
			'media_type_i' => Yii::t('attribute', 'Media Type'),
			'media_video_i' => Yii::t('attribute', 'Media (Video)'),
			'media_photo_i' => Yii::t('attribute', 'Media (Photo)'),
			'old_media_photo_i' => Yii::t('attribute', 'Old Media (Photo)'),
			'media_file_i' => Yii::t('attribute', 'Media (File)'),
			'old_media_file_i' => Yii::t('attribute', 'Old File (Download)'),
			'keyword_i' => Yii::t('attribute', 'Keyword'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
			'photo_search' => Yii::t('attribute', 'Photos'),
			'view_search' => Yii::t('attribute', 'Views'),
			'like_search' => Yii::t('attribute', 'Likes'),
			'downlaod_search' => Yii::t('attribute', 'Downloads'),
			'tag_search' => Yii::t('attribute', 'Tags'),
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
			'creation' => array(
				'alias'=>'creation',
				'select'=>'displayname',
			),
			'modified' => array(
				'alias'=>'modified',
				'select'=>'displayname',
			),
		);

		$criteria->compare('t.article_id', $this->article_id);
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

		if(Yii::app()->getRequest()->getParam('category')) {
			$category = ArticleCategory::model()->findByPk(Yii::app()->getRequest()->getParam('category'));
			if($category->parent_id == 0) {
				$parent = Yii::app()->getRequest()->getParam('category');
				$categoryFind = ArticleCategory::model()->findAll(array(
					'condition' => 'parent_id = :parent',
					'params' => array(
						':parent' => $parent,
					),
				));
				$items = array();
				$items[] = Yii::app()->getRequest()->getParam('category');
				if($categoryFind != null) {
					foreach($categoryFind as $key => $val) {
						$items[] = $val->cat_id;
					}
				}
				$criteria->addInCondition('t.cat_id', $items);
				$criteria->compare('t.cat_id', $this->cat_id);
				
			} else
				$criteria->compare('t.cat_id', Yii::app()->getRequest()->getParam('category'));
		} else
			$criteria->compare('t.cat_id', $this->cat_id);
		$criteria->compare('t.title', strtolower($this->title), true);
		$criteria->compare('t.body', strtolower($this->body), true);
		$criteria->compare('t.quote', strtolower($this->quote), true);
		if($this->published_date != null && !in_array($this->published_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.published_date)', date('Y-m-d', strtotime($this->published_date)));
		$criteria->compare('t.headline', $this->headline);
		$criteria->compare('t.comment_code', $this->comment_code);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.creation_date)', date('Y-m-d', strtotime($this->creation_date)));
		$criteria->compare('t.creation_id', Yii::app()->getRequest()->getParam('creation') ? Yii::app()->getRequest()->getParam('creation') : $this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified') ? Yii::app()->getRequest()->getParam('modified') : $this->modified_id);
		if($this->headline_date != null && !in_array($this->headline_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.headline_date)', date('Y-m-d', strtotime($this->headline_date)));
		if($this->updated_date != null && !in_array($this->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.updated_date)', date('Y-m-d', strtotime($this->updated_date)));
		$criteria->compare('t.slug', strtolower($this->slug), true);

		$criteria->compare('creation.displayname', strtolower($this->creation_search), true);
		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);
		$criteria->compare('view.medias', $this->photo_search);
		$criteria->compare('view.views', $this->view_search);
		$criteria->compare('view.likes', $this->like_search);
		$criteria->compare('view.downloads', $this->downlaod_search);
		$criteria->compare('view.tags', $this->tag_search);

		if(!Yii::app()->getRequest()->getParam('Articles_sort'))
			$criteria->order = 't.article_id DESC';

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
	protected function afterConstruct() 
	{
		$setting = ArticleSetting::model()->findByPk(1, array(
			'select' => 'headline',
		));

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
			$category = ArticleCategory::model()->findByPk(Yii::app()->getRequest()->getParam('category'));
			if(!Yii::app()->getRequest()->getParam('category') || (Yii::app()->getRequest()->getParam('category') && $category->parent_id == 0)) {
				if($category->parent_id == 0)
					$parent = Yii::app()->getRequest()->getParam('category');
				else
					$parent = null;
				$this->templateColumns['cat_id'] = array(
					'name' => 'cat_id',
					'value' => '$data->category->title->message',
					'filter'=> ArticleCategory::getCategory(null, $parent),
					'type' => 'raw',
				);
			}
			$this->templateColumns['title'] = array(
				'name' => 'title',
				'value' => '$data->title',
			);
			$this->templateColumns['body'] = array(
				'name' => 'body',
				'value' => '$data->body',
			);
			$this->templateColumns['quote'] = array(
				'name' => 'quote',
				'value' => '$data->quote',
			);
			if(!Yii::app()->getRequest()->getParam('creation')) {
				$this->templateColumns['creation_search'] = array(
					'name' => 'creation_search',
					'value' => '$data->creation->displayname ? $data->creation->displayname : \'-\'',
				);
			}
			$this->templateColumns['creation_date'] = array(
				'name' => 'creation_date',
				'value' => '!in_array($data->creation_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Utility::dateFormat($data->creation_date) : \'-\'',
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
						'dateFormat' => 'yy-mm-dd',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
				*/
			);
			$this->templateColumns['published_date'] = array(
				'name' => 'published_date',
				'value' => '!in_array($data->published_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Utility::dateFormat($data->published_date) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => 'native-datepicker',
				/*
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'published_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'published_date_filter',
						'on_datepicker' => 'on',
						'placeholder' => Yii::t('phrase', 'filter'),
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'yy-mm-dd',
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
				'value' => '!in_array($data->modified_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Utility::dateFormat($data->modified_date) : \'-\'',
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
						'dateFormat' => 'yy-mm-dd',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
				*/
			);
			$this->templateColumns['headline_date'] = array(
				'name' => 'headline_date',
				'value' => '!in_array($data->headline_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Utility::dateFormat($data->headline_date) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => 'native-datepicker',
				/*
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'headline_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'headline_date_filter',
						'on_datepicker' => 'on',
						'placeholder' => Yii::t('phrase', 'filter'),
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'yy-mm-dd',
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
				'value' => '!in_array($data->updated_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Utility::dateFormat($data->updated_date) : \'-\'',
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
						'dateFormat' => 'yy-mm-dd',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
				*/
			);
			$this->templateColumns['slug'] = array(
				'name' => 'slug',
				'value' => '$data->slug',
			);
			$this->templateColumns['photo_search'] = array(
				'name' => 'photo_search',
				'value' => 'CHtml::link($data->view->medias ? $data->view->medias : 0, Yii::app()->controller->createUrl("o/media/manage", array(\'article\'=>$data->article_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->templateColumns['view_search'] = array(
				'name' => 'view_search',
				'value' => 'CHtml::link($data->view->views ? $data->view->views : 0, Yii::app()->controller->createUrl("o/view/manage", array(\'article\'=>$data->article_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->templateColumns['like_search'] = array(
				'name' => 'like_search',
				'value' => 'CHtml::link($data->view->likes ? $data->view->likes : 0, Yii::app()->controller->createUrl("o/like/manage", array(\'article\'=>$data->article_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->templateColumns['downlaod_search'] = array(
				'name' => 'downlaod_search',
				'value' => 'CHtml::link($data->view->downloads ? $data->view->downloads : 0, Yii::app()->controller->createUrl("o/download/manage", array(\'article\'=>$data->article_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->templateColumns['tag_search'] = array(
				'name' => 'tag_search',
				'value' => 'CHtml::link($data->view->tags ? $data->view->tags : 0, Yii::app()->controller->createUrl("o/tag/manage", array(\'article\'=>$data->article_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->templateColumns['comment_code'] = array(
				'name' => 'comment_code',
				'value' => '$data->comment_code == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			if($setting->headline == 1) {
				$this->templateColumns['headline'] = array(
					'name' => 'headline',
					'value' => 'in_array($data->cat_id, ArticleSetting::getHeadlineCategory()) ? ($data->headline == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : Utility::getPublish(Yii::app()->controller->createUrl(\'headline\', array(\'id\'=>$data->article_id)), $data->headline, Yii::t(\'phrase\', \'Headline,Headline\'))) : \'-\'',
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
			if(!Yii::app()->getRequest()->getParam('type')) {
				$this->templateColumns['publish'] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'publish\', array(\'id\'=>$data->article_id)), $data->publish)',
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
	 * Articles get information
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

	/**
	 * Articles get information
	 */
	public static function getHeadline()
	{
		$setting = ArticleSetting::model()->findByPk(1, array(
			'select' => 'headline_limit, headline_category',
		));
		$headline_category = unserialize($setting->headline_category);
		if(empty($headline_category))
			$headline_category = array();
					
		$criteria=new CDbCriteria;
		$criteria->compare('publish', 1);
		$criteria->addInCondition('cat_id', $headline_category);
		$criteria->compare('headline', 1);
		$criteria->order = 'headline_date DESC';
		
		$model = self::model()->findAll($criteria);
		
		$headline = array();
		if(!empty($model)) {
			$i=0;
			foreach($model as $key => $val) {
				$i++;
				if($i <= $setting->headline_limit)
					$headline[] = $val->article_id;
			}
		}
		
		return $headline;
	}

	/**
	 * Albums get information
	 */
	public function searchIndexing($index)
	{
		Yii::import('application.vendor.ommu.article.models.*');
		
		$criteria=new CDbCriteria;
		$criteria->compare('publish', 1);
		$criteria->compare('date(published_date) <', date('Y-m-d H:i:s'));
		$criteria->order = 'article_id DESC';
		//$criteria->limit = 10;
		$model = Articles::model()->findAll($criteria);
		foreach($model as $key => $item) {
			$medias = $item->medias;
			if(!empty($medias)) {
				$article_cover = $item->view->article_cover ? $item->view->article_cover : $medias[0]->cover_filename;
				$article_cover = Yii::app()->request->baseUrl.'/public/article/'.$item->article_id.'/'.$article_cover;
			} else 
				$article_cover = '';
			$url = Yii::app()->createUrl('article/site/view', array('id'=>$item->article_id,'slug'=>Utility::getUrlTitle($item->title)));
				
			$doc = new Zend_Search_Lucene_Document();
			$doc->addField(Zend_Search_Lucene_Field::UnIndexed('id', CHtml::encode($item->article_id), 'utf-8')); 
			$doc->addField(Zend_Search_Lucene_Field::Keyword('category', CHtml::encode($item->category->title->message), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::Text('media', CHtml::encode($article_cover), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::Text('title', CHtml::encode($item->title), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::Text('body', CHtml::encode(Utility::hardDecode(Utility::softDecode($item->body))), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::Text('url', CHtml::encode(Utility::getProtocol().'://'.Yii::app()->request->serverName.$url), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::UnIndexed('date', CHtml::encode($item->published_date), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::UnIndexed('creation', CHtml::encode($item->creation->displayname), 'utf-8'));
			$index->addDocument($doc);
		}
		
		return true;
	}

	/**
	 * User get information
	 */
	public static function getShareUrl($id, $slug=null)
	{
		if($slug && $slug != '-')
			return Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->controller->createUrl('site/view', array('id'=>$id, 'slug'=>Utility::getUrlTitle($slug)));
		else
			return Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->controller->createUrl('site/view', array('id'=>$id));
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		$setting = ArticleSetting::model()->findByPk(1, array(
			'select' => 'media_image_type, media_file_type',
		));
		$media_image_type = unserialize($setting->media_image_type);
		if(empty($media_image_type))
			$media_image_type = array();
		$media_file_type = unserialize($setting->media_file_type);
		if(empty($media_file_type))
			$media_file_type = array();
		
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			else
				$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			
			if($this->headline == 1 && $this->publish == 0)
				$this->addError('publish', Yii::t('phrase', '{attribute} cannot be blank.', array('{attribute}'=>$this->getAttributeLabel('publish'))));

			if($this->media_type_i == 0 && $this->media_video_i == '')
				$this->addError('media_video_i', Yii::t('phrase', '{attribute} cannot be blank.', array('{attribute}'=>$this->getAttributeLabel('media_video_i'))));
			
			$media_photo_i = CUploadedFile::getInstance($this, 'media_photo_i');
			if($media_photo_i != null) {
				$extension = pathinfo($media_photo_i->name, PATHINFO_EXTENSION);
				if(!in_array(strtolower($extension), $media_image_type))
					$this->addError('media_photo_i', Yii::t('phrase', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}.', array(
						'{name}'=>$media_photo_i->name,
						'{extensions}'=>Utility::formatFileType($media_image_type, false),
					)));
			}
			
			$media_file_i = CUploadedFile::getInstance($this, 'media_file_i');
			if($media_file_i != null) {
				$extension = pathinfo($media_file_i->name, PATHINFO_EXTENSION);
				if(!in_array(strtolower($extension), $media_file_type))
					$this->addError('media_file_i', Yii::t('phrase', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}.', array(
						'{name}'=>$media_file_i->name,
						'{extensions}'=>Utility::formatFileType($media_file_type, false),
					)));
			}
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() 
	{
		if(parent::beforeSave()) {
			if($this->isNewRecord) {
				$article_path = "public/article/".$this->article_id;
				// Add directory
				if(!file_exists($article_path)) {
					@mkdir($article_path, 0755, true);

					// Add file in directory (index.php)
					$newFile = $article_path.'/index.php';
					$FileHandle = fopen($newFile, 'w');
				} else
					@chmod($article_path, 0755, true);
			}
			
			$this->published_date = date('Y-m-d', strtotime($this->published_date));
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
			'select' => 'headline, media_image_limit, media_image_resize, media_image_resize_size, media_file_limit',
		));
		$media_image_resize_size = unserialize($setting->media_image_resize_size);
		
		$article_path = "public/article/".$this->article_id;

		// Add directory
		if(!file_exists($article_path)) {
			@mkdir($article_path, 0755, true);

			// Add file in directory (index.php)
			$newFile = $article_path.'/index.php';
			$FileHandle = fopen($newFile, 'w');
		} else
			@chmod($article_path, 0755, true);

		if($this->isNewRecord) {
			//input keyword
			if(trim($this->keyword_i) != '') {
				$keyword_i = Utility::formatFileType($this->keyword_i);
				if(!empty($keyword_i)) {
					foreach($keyword_i as $key => $val) {
						$subject = new ArticleTag;
						$subject->article_id = $this->article_id;
						$subject->tag_id = 0;
						$subject->tag_i = $val;
						$subject->save();
					}
				}
			}
		}
		
		$this->media_photo_i = CUploadedFile::getInstance($this, 'media_photo_i');
		if($this->media_photo_i != null && ($this->isNewRecord || (!$this->isNewRecord && ($setting->media_image_limit == 1 || ($setting->media_image_limit != 1 && $this->category->single_photo == 1))))) {
			if($this->media_photo_i instanceOf CUploadedFile) {
				$fileName = time().'_'.Utility::getUrlTitle($this->title).'.'.strtolower($this->media_photo_i->extensionName);
				if($this->media_photo_i->saveAs($article_path.'/'.$fileName)) {
					$medias = $this->medias;
					if($this->isNewRecord || (!$this->isNewRecord && $medias == null)) {
						$images = new ArticleMedia;
						$images->media_type_i = $this->media_type_i;
						$images->cover = 1;
						$images->article_id = $this->article_id;
						if($this->media_type_i == 0 && $this->media_video_i != '')
							$images->media_filename = $this->media_video_i;
						$images->cover_filename = $fileName;
						$images->save();
					} else {
						if($this->old_media_photo_i != '' && file_exists($article_path.'/'.$this->old_media_photo_i))
							rename($article_path.'/'.$this->old_media_photo_i, 'public/article/verwijderen/'.$this->article_id.'_'.$this->old_media_photo_i);
						$media_id = $this->view->media_id ? $this->view->media_id : $medias[0]->media_id;
						if(ArticleMedia::model()->updateByPk($media_id, array('media_filename'=>$this->media_video_i, 'cover_filename'=>$fileName))) {
							if($setting->media_image_resize == 1)
								ArticleMedia::resizePhoto($article_path.'/'.$fileName, $media_image_resize_size);
						}
					}
				}
			}
		}

		$medias = $this->medias;
		if($this->isNewRecord || (!$this->isNewRecord && $medias == null)) {
			if($this->media_type_i == 0 && $this->media_video_i != '') {
				$video = new ArticleMedia;
				$images->media_type_i = $this->media_type_i;
				$video->cover = 1;
				$video->article_id = $this->article_id;
				$video->media_filename = $this->media_video_i;
				$video->save();
			}
		} else {
			if($this->media_type_i == 0 && $this->media_video_i != '')
				ArticleMedia::model()->updateByPk($medias[0]->media_id, array('media_filename'=>$this->media_video_i));
		}
		
		$this->media_file_i = CUploadedFile::getInstance($this, 'media_file_i');
		if($this->media_file_i != null && ($this->isNewRecord || (!$this->isNewRecord && ($setting->media_file_limit == 1 || ($setting->media_file_limit != 1 && $this->category->single_file == 1))))) {
			if($this->media_file_i instanceOf CUploadedFile) {
				$fileName = time().'_file-'.Utility::getUrlTitle($this->title).'.'.strtolower($this->media_file_i->extensionName);
				if($this->media_file_i->saveAs($article_path.'/'.$fileName)) {
					$files = $this->files;
					if($this->isNewRecord || (!$this->isNewRecord && $files == null)) {
						$images = new ArticleFiles;
						$images->article_id = $this->article_id;
						$images->file_filename = $fileName;
						$images->save();
					} else {
						if($this->old_media_file_i != '' && file_exists($article_path.'/'.$this->old_media_file_i))
							rename($article_path.'/'.$this->old_media_file_i, 'public/article/verwijderen/'.$this->article_id.'_'.$this->old_media_file_i);
						$file_id = $this->view->file_id ? $this->view->file_id : $files[0]->file_id;
						ArticleFiles::model()->updateByPk($file_id, array('file_filename'=>$fileName));
					}
				}
			}
		}
		
		// Reset headline
		if($setting->headline == 1 && $this->headline == 1) {
			$headline = self::getHeadline();
			
			$criteria=new CDbCriteria;
			$criteria->addNotInCondition('article_id', $headline);
			self::model()->updateAll(array('headline'=>0), $criteria);
		}
	}

	/**
	 * Before delete attributes
	 */
	protected function beforeDelete() 
	{
		if(parent::beforeDelete()) {
			$article_path = "public/article/".$this->article_id;
			
			//delete media photos
			$medias = $this->medias;
			if(!empty($medias)) {
				foreach($medias as $val) {
					if($val->cover_filename != '' && file_exists($article_path.'/'.$val->cover_filename))
						rename($article_path.'/'.$val->cover_filename, 'public/article/verwijderen/'.$val->article_id.'_'.$val->cover_filename);
				}
			}

			//delete media files
			$files = $this->files;
			if(!empty($files)) {
				foreach($files as $val) {
					if($val->file_filename != '' && file_exists($article_path.'/'.$val->file_filename))
						rename($article_path.'/'.$val->file_filename, 'public/article/verwijderen/'.$val->article_id.'_'.$val->file_filename);
				}
			}
		}
		return true;
	}

	/**
	 * After delete attributes
	 */
	protected function afterDelete() 
	{
		parent::afterDelete();
		//delete article image
		$article_path = "public/article/".$this->article_id;
		Utility::deleteFolder($article_path);
	}

}