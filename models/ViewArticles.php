<?php
/**
 * ViewArticles
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 9 November 2016, 18:13 WIB
 * @modified date 22 March 2018, 16:57 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "_articles".
 *
 * The followings are the available columns in table '_articles':
 * @property string $article_id
 * @property string $article_cover
 * @property string $article_video
 * @property string $media_id
 * @property string $article_file
 * @property string $file_id
 * @property string $medias
 * @property string $media_all
 * @property string $files
 * @property string $file_all
 * @property string $likes
 * @property string $like_all
 * @property string $views
 * @property string $view_all
 * @property string $downloads
 * @property string $tags
 */

class ViewArticles extends OActiveRecord
{
	public $gridForbiddenColumn = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewArticles the static model class
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
		return $matches[1].'._articles';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'article_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('article_id, media_id, file_id', 'length', 'max'=>11),
			array('medias, files, likes', 'length', 'max'=>23),
			array('media_all, file_all, like_all, tags', 'length', 'max'=>21),
			array('views, view_all, downloads', 'length', 'max'=>32),
			array('article_cover, article_video, article_file', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('article_id, article_cover, article_video, media_id, article_file, file_id, medias, media_all, files, file_all, likes, like_all, views, view_all, downloads, tags', 'safe', 'on'=>'search'),
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
			'article_id' => Yii::t('attribute', 'Article'),
			'article_cover' => Yii::t('attribute', 'Article Cover'),
			'article_video' => Yii::t('attribute', 'Article Video'),
			'media_id' => Yii::t('attribute', 'Media'),
			'article_file' => Yii::t('attribute', 'Article File'),
			'file_id' => Yii::t('attribute', 'File'),
			'medias' => Yii::t('attribute', 'Medias'),
			'media_all' => Yii::t('attribute', 'Media All'),
			'files' => Yii::t('attribute', 'Files'),
			'file_all' => Yii::t('attribute', 'File All'),
			'likes' => Yii::t('attribute', 'Likes'),
			'like_all' => Yii::t('attribute', 'Like All'),
			'views' => Yii::t('attribute', 'Views'),
			'view_all' => Yii::t('attribute', 'View All'),
			'downloads' => Yii::t('attribute', 'Downloads'),
			'tags' => Yii::t('attribute', 'Tags'),
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

		$criteria->compare('t.article_id', $this->article_id);
		$criteria->compare('t.article_cover', strtolower($this->article_cover), true);
		$criteria->compare('t.article_video', strtolower($this->article_video), true);
		$criteria->compare('t.media_id', $this->media_id);
		$criteria->compare('t.article_file', strtolower($this->article_file), true);
		$criteria->compare('t.file_id', $this->file_id);
		$criteria->compare('t.medias', $this->medias);
		$criteria->compare('t.media_all', $this->media_all);
		$criteria->compare('t.files', $this->files);
		$criteria->compare('t.file_all', $this->file_all);
		$criteria->compare('t.likes', $this->likes);
		$criteria->compare('t.like_all', $this->like_all);
		$criteria->compare('t.views', $this->views);
		$criteria->compare('t.view_all', $this->view_all);
		$criteria->compare('t.downloads', $this->downloads);
		$criteria->compare('t.tags', $this->tags);

		if(!Yii::app()->getRequest()->getParam('ViewArticles_sort'))
			$criteria->order = 't.article_id DESC';

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
			$this->templateColumns['article_id'] = array(
				'name' => 'article_id',
				'value' => '$data->article_id',
			);
			$this->templateColumns['article_cover'] = array(
				'name' => 'article_cover',
				'value' => '$data->article_cover',
			);
			$this->templateColumns['article_video'] = array(
				'name' => 'article_video',
				'value' => '$data->article_video',
			);
			$this->templateColumns['media_id'] = array(
				'name' => 'media_id',
				'value' => '$data->media_id',
			);
			$this->templateColumns['article_file'] = array(
				'name' => 'article_file',
				'value' => '$data->article_file',
			);
			$this->templateColumns['file_id'] = array(
				'name' => 'file_id',
				'value' => '$data->file_id',
			);
			$this->templateColumns['medias'] = array(
				'name' => 'medias',
				'value' => '$data->medias',
			);
			$this->templateColumns['media_all'] = array(
				'name' => 'media_all',
				'value' => '$data->media_all',
			);
			$this->templateColumns['files'] = array(
				'name' => 'files',
				'value' => '$data->files',
			);
			$this->templateColumns['file_all'] = array(
				'name' => 'file_all',
				'value' => '$data->file_all',
			);
			$this->templateColumns['likes'] = array(
				'name' => 'likes',
				'value' => '$data->likes',
			);
			$this->templateColumns['like_all'] = array(
				'name' => 'like_all',
				'value' => '$data->like_all',
			);
			$this->templateColumns['views'] = array(
				'name' => 'views',
				'value' => '$data->views',
			);
			$this->templateColumns['view_all'] = array(
				'name' => 'view_all',
				'value' => '$data->view_all',
			);
			$this->templateColumns['downloads'] = array(
				'name' => 'downloads',
				'value' => '$data->downloads',
			);
			$this->templateColumns['tags'] = array(
				'name' => 'tags',
				'value' => '$data->tags',
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