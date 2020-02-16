<?php
/**
 * ArticleFiles
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
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
use ommu\article\models\view\ArticleFiles as ArticleFilesView;
use ommu\users\models\Users;
use yii\helpers\ArrayHelper;

class ArticleFiles extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname','updated_date'];

	public $old_file_filename;
	public $articleTitle;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $redirectUpdate;

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
			[['article_id', ], 'required'],
			[['publish', 'article_id', 'creation_id', 'modified_id', 'redirectUpdate'], 'integer'],
			[['file_filename'], 'safe'],
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
			'file_filename' => Yii::t('app', 'Document File'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'old_file_filename' => Yii::t('app', 'Old Document'),
			'downloads' => Yii::t('app', 'Downloads'),
			'articleTitle' => Yii::t('app', 'Article'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'redirectUpdate' => Yii::t('app', 'Redirect to Update'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(ArticleFilesView::className(), ['id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDownloads($count=false)
	{
		if($count == false)
			return $this->hasMany(ArticleDownloads::className(), ['file_id' => 'id']);

		$model = ArticleDownloads::find()
			->alias('t')
			->where(['t.file_id' => $this->id]);
		$downloads = $model->sum('downloads');

		return $downloads ? $downloads : 0;
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

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		if(!$this->hasMethod('search'))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['articleTitle'] = [
			'attribute' => 'articleTitle',
			'value' => function($model, $key, $index, $column) {
				return isset($model->article) ? $model->article->title : '-';
				// return $model->articleTitle;
			},
			'visible' => !Yii::$app->request->get('article') ? true : false,
		];
		$this->templateColumns['file_filename'] = [
			'attribute' => 'file_filename',
			'value' => function($model, $key, $index, $column) {
				$uploadPath = join('/', [Articles::getUploadPath(false), $model->article_id]);
				return $model->file_filename ? Html::a($model->file_filename, Url::to(join('/', ['@webpublic', $uploadPath, $model->file_filename])), ['title'=>$model->file_filename, 'target'=>'_blank']) : '-';
			},
			'format' => 'raw',
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => !Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
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
				return Html::a($downloads, ['o/download/manage', 'file'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} downloads', ['count'=>$downloads]), 'data-pjax'=>0]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
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
		$setting = $this->article->getSetting(['media_file_type']);

		if(parent::beforeValidate()) {
			// $this->file_filename = UploadedFile::getInstance($this, 'file_filename');
			if($this->file_filename instanceof UploadedFile && !$this->file_filename->getHasError()) {
				$fileFileType = $this->formatFileType($setting->media_file_type);
				if(!in_array(strtolower($this->file_filename->getExtension()), $fileFileType)) {
					$this->addError('file_filename', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', [
						'name'=>$this->file_filename->name,
						'extensions'=>$this->formatFileType($fileFileType, false),
					]));
				}
			} else {
				if($this->isNewRecord && $this->file_filename == '')
					$this->addError('file_filename', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('file_filename')]));
			}

			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
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
			$uploadPath = join('/', [Articles::getUploadPath(), $this->article_id]);
			$verwijderenPath = join('/', [Articles::getUploadPath(), 'verwijderen']);
			$this->createUploadDirectory(Articles::getUploadPath(), $this->article_id);

			// $this->file_filename = UploadedFile::getInstance($this, 'file_filename');
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
		return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		$setting = $this->article->getSetting(['media_file_limit']);

		parent::afterSave($insert, $changedAttributes);
		
		// delete other photo (media_file_limit = 1)
		if($setting->media_file_limit == 1) {
			$medias = self::find()
				->where(['article_id'=>$this->article_id])
				->andWhere(['<>', 'publish', 2])
				->andWhere(['<>', 'id', $this->id])
				->all();
			$mediaId = ArrayHelper::map($medias, 'id', 'id');
			self::updateAll(['publish' => 2], ['IN', 'id', $mediaId]);
		}
	}

	/**
	 * After delete attributes
	 */
	public function afterDelete()
	{
		parent::afterDelete();

		$uploadPath = join('/', [Articles::getUploadPath(), $this->article_id]);
		$verwijderenPath = join('/', [Articles::getUploadPath(), 'verwijderen']);

		if($this->file_filename != '' && file_exists(join('/', [$uploadPath, $this->file_filename])))
			rename(join('/', [$uploadPath, $this->file_filename]), join('/', [$verwijderenPath, $this->article_id.'-'.time().'_deleted_'.$this->file_filename]));

	}
}
