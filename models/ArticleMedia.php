<?php
/**
 * ArticleMedia

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:06 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_media".
 *
 * The followings are the available columns in table "ommu_article_media":
 * @property string $media_id
 * @property integer $publish
 * @property integer $cover
 * @property integer $article_id
 * @property string $media_filename
 * @property string $caption
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Articles $article
 *
 */

namespace ommu\article\models;

use Yii;
use yii\helpers\Url;
use ommu\users\models\Users;

class ArticleMedia extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	use \app\components\traits\FileSystem;

	public $gridForbiddenColumn = ['updated_date','creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname'];

	public $articleTitle;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $old_media_filename_i;

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
			[['publish', 'cover', 'article_id', 'creation_id', 'modified_id'], 'integer'],
			[['caption'], 'required'],
			[['media_filename'], 'required', 'on' => 'formCreate'],
			[['creation_date', 'modified_date', 'updated_date','media_filename','old_media_filename_i'], 'safe'],
			[['caption'], 'string', 'max' => 150],
			[['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Articles::className(), 'targetAttribute' => ['article_id' => 'article_id']],
			[['media_filename'], 'file', 'extensions' => 'jpeg, jpg, png, bmp, gif'],

		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'media_id' => Yii::t('app', 'Media'),
			'publish' => Yii::t('app', 'Publish'),
			'cover' => Yii::t('app', 'Cover'),
			'article_id' => Yii::t('app', 'Article'),
			'media_filename' => Yii::t('app', 'Media Filename'),
			'caption' => Yii::t('app', 'Caption'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'articleTitle' => Yii::t('app', 'Article'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArticle()
	{
		return $this->hasOne(Articles::className(), ['article_id' => 'article_id']);
	}

	public function getCategory()
	{
		return $this->hasOne(ArticleCategory::className(), ['cat_id' => 'cat_id']);
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
					return $model->article->title;
				},
			];
		}
		$this->templateColumns['media_filename'] = 'media_filename';
		$this->templateColumns['caption'] = 'caption';
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
		$this->templateColumns['cover'] = [
			'attribute' => 'cover',
			'value' => function($model, $key, $index, $column) {
				return $model->cover;
			},
			'contentOptions' => ['class'=>'center'],
			
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
				->where(['media_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	public static function getMediaPath($returnAlias=true)
	{
		return ($returnAlias ? Yii::getAlias('@webroot/public/article/media') : 'public/article/media');
	}

	public static function getSettingMediaLimit()
	{
		$setting = ArticleSetting::find()->limit(1)->one();
		return $setting->media_limit;
	}

	public static function getCategorySinglePhoto()
	{
		$category = ArticleCategory::find()->where(['single_photo'=>1])->one()->limit(1);
		return $category->single_photo;
	}


	/**
	 * afterFind
	 *
	 * Simpan nama banner lama untuk keperluan jikalau kondisi update tp bannernya tidak diupdate.
	 */
	public function afterFind()
	{
		$this->old_media_filename_i = $this->media_filename;
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
			
			//single foto
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
					 // print_r($category->single_photo);
						// exit();
					 if ($category->single_photo == 1){
					 		if (ArticleMedia::find()->where(['article_id'=>$savearticle_id,'publish'=>1])->all()!=null){
								$this->addError('publish', 'tidak dapat menambahkan media lagi karena single photo');
					 	}
					 }
			}
			//notifikasi media limit
			$countmedia=count(ArticleMedia::find()->where(['article_id'=>$this->article_id,'publish'=>1])->all());
			if ($countmedia>=$this->getSettingMediaLimit()&&$this->isNewRecord){
			 $this->addError('publish', 'article media lebih sudah mencapai limit'.'='.self::getSettingMediaLimit());
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
			
			$cover=ArticleMedia::find()->where(['cover'=>1,'publish'=>1])->one();
			if (!empty($cover)) {
				if ($this->cover==1)
				{
						$cover->cover = 0;
						$cover->save();					
				}
			}
			
			

			if($this->isNewRecord) {
					if (Yii::$app->request->get('article')){

					$article = Yii::$app->request->get('article');
					$this->article_id = $article;
				}
			}

			$mediaPath = Yii::getAlias('@webroot/public/article/media');
			
			// Add directory
			if(!file_exists($mediaPath)) {
				@mkdir($mediaPath, 0755,true);

				// Add file in directory (index.php)
				$indexFile = join('/', [$mediaPath, 'index.php']);
				if(!file_exists($indexFile)) {
					file_put_contents($indexFile, "<?php\n");
				}

			}else {
				@chmod($bannerPath, 0755,true);
			}
			if($this->media_filename instanceof \yii\web\UploadedFile) {
				$article = Articles::findOne($this->article_id);
				$imageName = time().'_'.$this->sanitizeFileName($article->title).'.'. $this->media_filename->extension; 
				if($this->media_filename->saveAs($mediaPath.'/'.$imageName)) {
					$this->media_filename = $imageName;
					@chmod($imageName, 0777);
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
		if(!$insert && $this->media_filename != $this->old_media_filename_i) {
			$fname = join('/', [self::getMediaPath(), $this->old_media_filename_i]);
			if(file_exists($fname)) {
				@unlink($fname);
			}
		}
	}

	/**
	 * After delete attributes
	 */
	public function afterDelete()
	{
		parent::afterDelete();

		$fname = join('/', [self::getMediaPath(), $this->media_filename]);
		if(file_exists($fname)) {
			@unlink($fname);
		}
	}
}
