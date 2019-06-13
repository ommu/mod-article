<?php
/**
 * ArticleMedia
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:06 WIB
 * @modified date 12 May 2019, 18:50 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_media".
 *
 * The followings are the available columns in table "ommu_article_media":
 * @property integer $id
 * @property integer $publish
 * @property integer $cover
 * @property integer $orders
 * @property integer $article_id
 * @property string $media_filename
 * @property string $caption
 * @property string $description
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
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
use ommu\article\models\view\ArticleMedia as ArticleMediaView;
use ommu\users\models\Users;
use yii\helpers\ArrayHelper;

class ArticleMedia extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['caption', 'description', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $old_media_filename;
	public $articleTitle;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $redirectUpdate;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_article_media';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['article_id'], 'required'],
			[['publish', 'cover', 'orders', 'article_id', 'creation_id', 'modified_id', 'redirectUpdate'], 'integer'],
			[['caption', 'description'], 'string'],
			[['orders', 'media_filename', 'caption', 'description'], 'safe'],
			[['caption'], 'string', 'max' => 150],
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
			'cover' => Yii::t('app', 'Cover'),
			'orders' => Yii::t('app', 'Orders'),
			'article_id' => Yii::t('app', 'Article'),
			'media_filename' => Yii::t('app', 'Photo File'),
			'caption' => Yii::t('app', 'Caption'),
			'description' => Yii::t('app', 'Description'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'old_media_filename' => Yii::t('app', 'Old Image'),
			'articleTitle' => Yii::t('app', 'Article'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'redirectUpdate' => Yii::t('app', 'Redirect to Update'),
			'captionStatus' => Yii::t('app', 'Caption'),
			'descriptionStatus' => Yii::t('app', 'Description'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(ArticleMediaView::className(), ['id' => 'id']);
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
	 * @return \yii\db\ActiveQuery
	 */
	public function getCaptionStatus()
	{
		return $this->caption ? 1 : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDescriptionStatus()
	{
		return $this->description ? 1 : 0;
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\query\ArticleMedia the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\article\models\query\ArticleMedia(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('article') && !Yii::$app->request->get('id')) {
			$this->templateColumns['articleTitle'] = [
				'attribute' => 'articleTitle',
				'value' => function($model, $key, $index, $column) {
					return isset($model->article) ? $model->article->title : '-';
					// return $model->articleTitle;
				},
			];
		}
		$this->templateColumns['media_filename'] = [
			'attribute' => 'media_filename',
			'value' => function($model, $key, $index, $column) {
				$uploadPath = join('/', [Articles::getUploadPath(false), $model->article_id]);
				return $model->media_filename ? Html::a($model->media_filename, Url::to(join('/', ['@webpublic', $uploadPath, $model->media_filename])), ['alt' => $model->media_filename]) : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['caption'] = [
			'attribute' => 'caption',
			'value' => function($model, $key, $index, $column) {
				return $model->caption;
			},
		];
		$this->templateColumns['description'] = [
			'attribute' => 'description',
			'value' => function($model, $key, $index, $column) {
				return $model->description;
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
		$this->templateColumns['orders'] = [
			'attribute' => 'orders',
			'value' => function($model, $key, $index, $column) {
				return $model->orders;
			},
		];
		$this->templateColumns['captionStatus'] = [
			'attribute' => 'captionStatus',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->captionStatus);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['descriptionStatus'] = [
			'attribute' => 'descriptionStatus',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->descriptionStatus);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['cover'] = [
			'attribute' => 'cover',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['cover', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->cover, 'Yes,No', true);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
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
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->old_media_filename = $this->media_filename;
		// $this->articleTitle = isset($this->article) ? $this->article->title : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		$setting = $this->article->getSetting(['media_image_type']);

		if(parent::beforeValidate()) {
			if($this->media_filename instanceof UploadedFile && !$this->media_filename->getHasError()) {
				$imageFileType = $this->formatFileType($setting->media_image_type);
				if(!in_array(strtolower($this->media_filename->getExtension()), $imageFileType)) {
					$this->addError('media_filename', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', [
						'name'=>$this->media_filename->name,
						'extensions'=>$this->formatFileType($imageFileType, false),
					]));
				}
			} else {
				if($this->isNewRecord && $this->media_filename == '')
					$this->addError('media_filename', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('media_filename')]));
			}

			if($this->cover == null) {
				$medias = $this->article->medias;
				if(empty($medias))
					$this->cover = 1;
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
		$setting = $this->article->getSetting(['media_image_resize', 'media_image_resize_size']);

		if(parent::beforeSave($insert)) {
			$uploadPath = join('/', [Articles::getUploadPath(), $this->article_id]);
			$verwijderenPath = join('/', [Articles::getUploadPath(), 'verwijderen']);
			$this->createUploadDirectory(Articles::getUploadPath(), $this->article_id);

			if($this->media_filename instanceof UploadedFile && !$this->media_filename->getHasError()) {
				$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->media_filename->getExtension()); 
				if($this->media_filename->saveAs(join('/', [$uploadPath, $fileName]))) {
					$imageResize = $setting->media_image_resize_size;
					if($setting->media_image_resize)
						$this->resizeImage(join('/', [$uploadPath, $fileName]), $imageResize['width'], $imageResize['height']);
					if($this->old_media_filename != '' && file_exists(join('/', [$uploadPath, $this->old_media_filename])))
						rename(join('/', [$uploadPath, $this->old_media_filename]), join('/', [$verwijderenPath, $this->article_id.'-'.time().'_change_'.$this->old_media_filename]));
					$this->media_filename = $fileName;
				}
			} else {
				if($this->media_filename == '')
					$this->media_filename = $this->old_media_filename;
			}
		}
		return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		$setting = $this->article->getSetting(['media_image_limit']);

		parent::afterSave($insert, $changedAttributes);
		
		// delete other photo (media_image_limit = 1)
		if($setting->media_image_limit == 1) {
			$medias = self::find()
				->where(['article_id'=>$this->article_id])
				->andWhere(['<>', 'publish', 2])
				->andWhere(['<>', 'id', $this->id])
				->all();
			$mediaId = ArrayHelper::map($medias, 'id', 'id');
			self::updateAll(['publish' => 2], ['IN', 'id', $mediaId]);
		}

		// update cover (new cover = 1)
		if(array_key_exists('cover', $changedAttributes) && ($changedAttributes['cover'] != $this->cover) && $this->cover == 1)
			self::updateAll(['cover' => 0], 
				'article_id = :article AND publish <> :publish AND id <> :media', 
				[':article'=>$this->article_id, ':publish'=>2, ':media'=>$this->id]);
	}

	/**
	 * After delete attributes
	 */
	public function afterDelete()
	{
		parent::afterDelete();

		$uploadPath = join('/', [Articles::getUploadPath(), $this->article_id]);
		$verwijderenPath = join('/', [Articles::getUploadPath(), 'verwijderen']);

		if($this->media_filename != '' && file_exists(join('/', [$uploadPath, $this->media_filename])))
			rename(join('/', [$uploadPath, $this->media_filename]), join('/', [$verwijderenPath, $this->article_id.'-'.time().'_deleted_'.$this->media_filename]));

		// reset cover (delete photo, cover = 1)
		$medias = $this->article->medias;
		if(!empty($medias) && $this->cover == 1)
			self::findOne($medias[0]->id)->updateAttributes(['cover'=>1]);
	}
}
