<?php
/**
 * ArticleFiles
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:04 WIB
 * @modified date 12 May 2019, 18:26 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_files".
 *
 * The followings are the available columns in table "ommu_article_files":
 * @property integer $id
 * @property integer $publish
 * @property integer $article_id
 * @property string $file_filename
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArticleDownloads[] $downloads
 * @property Articles $article
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\article\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;
use thamtech\uuid\helpers\UuidHelper;
use ommu\users\models\Users;
use ommu\article\models\view\Articles as ArticlesView;

class ArticleFiles extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname','updated_date'];


	public $old_file_filename;
	public $articleTitle;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_article_files';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['file_filename'], 'required', 'on' => 'formCreate'],
			[['publish', 'article_id', 'creation_id', 'modified_id'], 'integer'],
			[['file_filename'], 'string'],
			[['creation_date', 'modified_date', 'updated_date','file_filename_i','article_id'], 'safe'],
			[['file_filename'], 'file', 'extensions' => 'pdf, doc, docx'],
			[['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Articles::className(), 'targetAttribute' => ['article_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'article_id' => Yii::t('app', 'Article'),
			'file_filename' => Yii::t('app', 'File Filename'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'old_file_filename' => Yii::t('app', 'Old File Filename'),
			'downloads' => Yii::t('app', 'Downloads'),
			'articleTitle' => Yii::t('app', 'Article'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDownloads($count=false)
	{
		if($count == false)
			return $this->hasMany(ArticleDownloads::className(), ['file_id' => 'id']);

		$model = ArticleDownloads::find()
			->where(['file_id' => $this->id]);
		$downloads = $model->count();

		return $downloads ? $downloads : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(ArticlesView::className(), ['article_id' => 'article_id']);
	}


	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArticle()
	{
		return $this->hasOne(Articles::className(), ['id' => 'article_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\query\ArticleFiles the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\article\models\query\ArticleFiles(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('article')) {
			$this->templateColumns['articleTitle'] = [
				'attribute' => 'articleTitle',
				'value' => function($model, $key, $index, $column) {
					return isset($model->article) ? $model->article->title : '-';
					// return $model->articleTitle;
				},
			];
		}
		$this->templateColumns['file_filename'] = [
			'attribute' => 'file_filename',
			'value' => function($model, $key, $index, $column) {
				$uploadPath = join('/', [self::getUploadPath(false), $model->id]);
				return $model->file_filename ? Html::img(join('/', [Url::Base(), $uploadPath, $model->file_filename]), ['alt' => $model->file_filename]) : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creationDisplayname'] = [
				'attribute' => 'creationDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
					// return $model->creationDisplayname;
				},
			];
		}
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modifiedDisplayname'] = [
				'attribute' => 'modifiedDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
					// return $model->modifiedDisplayname;
				},
			];
		}
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['downloads'] = [
			'attribute' => 'downloads',
			'value' => function($model, $key, $index, $column) {
				$downloads = $model->getDownloads(true);
				return Html::a($downloads, ['o/download/manage', 'file'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} downloads', ['count'=>$downloads])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish);
				},
				'filter' => $this->filterYesNo(),
				'contentOptions' => ['class'=>'center'],
				'format' => 'raw',
			];
		}
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
	 * relative path. default true.
	 */
	public static function getUploadPath($returnAlias=true)
	{
		return ($returnAlias ? Yii::getAlias('@public/article') : 'article');
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->old_file_filename = $this->file_filename;
		// $this->articleTitle = isset($this->article) ? $this->article->title : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
			//single file
			if($this->isNewRecord){
				if (Yii::$app->request->get('article')){
					$savearticle_id = Yii::$app->request->get('article');
				} 
				else {
					$savearticle_id = $this->article_id;
				}

				$cekcategory = Articles::find()->where(['article_id'=>$savearticle_id])->one();
				$cat_id = $cekcategory->cat_id;
				$category = ArticleCategory::find()->where(['cat_id'=>$cat_id,'publish'=>1])->one();
				if ($category->single_file == 1){
						if (Self::find()->where(['article_id'=>$savearticle_id,'publish'=>1])->all()!=null){
						$this->addError('publish', 'tidak dapat menambahkan file lagi karena single file');
					}
				}
			}
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {
			if(!$insert) {
				$uploadPath = join('/', [self::getUploadPath(), $this->article_id]);
				$verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
				$this->createUploadDirectory(self::getUploadPath(), $this->article_id);

				$this->file_filename = UploadedFile::getInstance($this, 'file_filename');
				if($this->file_filename instanceof UploadedFile && !$this->file_filename->getHasError()) {
					$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->file_filename->getExtension()); 
					if($this->file_filename->saveAs(join('/', [$uploadPath, $fileName]))) {
						if($this->old_file_filename != '' && file_exists(join('/', [$uploadPath, $this->old_file_filename])))
							rename(join('/', [$uploadPath, $this->old_file_filename]), join('/', [$verwijderenPath, $this->article_id.'-'.time().'_change_'.$this->old_file_filename]));
						$this->file_filename = $fileName;
					}
				} else {
					if($this->file_filename == '')
						$this->file_filename = $this->old_file_filename;
				}
			}
		}
		return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

		$uploadPath = join('/', [self::getUploadPath(), $this->article_id]);
		$verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
		$this->createUploadDirectory(self::getUploadPath(), $this->article_id);

		if($insert) {
			$this->file_filename = UploadedFile::getInstance($this, 'file_filename');
			if($this->file_filename instanceof UploadedFile && !$this->file_filename->getHasError()) {
				$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->file_filename->getExtension()); 
				if($this->file_filename->saveAs(join('/', [$uploadPath, $fileName])))
					self::updateAll(['file_filename' => $fileName], ['id' => $this->id]);
			}
		}
	}

	/**
	 * After delete attributes
	 */
	public function afterDelete()
	{
		parent::afterDelete();

		$uploadPath = join('/', [self::getUploadPath(), $this->article_id]);
		$verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);

		if($this->file_filename != '' && file_exists(join('/', [$uploadPath, $this->file_filename])))
			rename(join('/', [$uploadPath, $this->file_filename]), join('/', [$verwijderenPath, $this->article_id.'-'.time().'_deleted_'.$this->file_filename]));

	}
}
