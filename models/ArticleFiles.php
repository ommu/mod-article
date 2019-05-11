<?php
/**
 * ArticleFiles

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:04 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_files".
 *
 * The followings are the available columns in table "ommu_article_files":
 * @property string $file_id
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
 *
 */

namespace ommu\article\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users;
use ommu\article\models\view\Articles as ArticlesView;

class ArticleFiles extends \app\components\ActiveRecord
{
	use \app\components\traits\FileSystem;
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname','updated_date'];

	public $articleTitle;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $old_file_filename_i;
	public $download_search;

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
			[['publish', 'article_id', 'creation_id', 'modified_id'], 'integer'],
			[['file_filename'], 'required', 'on' => 'formCreate'],
			[['creation_date', 'modified_date', 'updated_date','file_filename_i','article_id'], 'safe'],
			[['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Articles::className(), 'targetAttribute' => ['article_id' => 'article_id']],
			[['file_filename'], 'file', 'extensions' => 'pdf, doc, docx'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'file_id' => Yii::t('app', 'File'),
			'publish' => Yii::t('app', 'Publish'),
			'article_id' => Yii::t('app', 'Article'),
			'file_filename' => Yii::t('app', 'File Filename'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'articleTitle' => Yii::t('app', 'Article'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'download_search' => Yii::t('app', 'Downloads'),
			'old_article_filename_i' => Yii::t('app', 'Old Filename'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(ArticlesView::className(), ['article_id' => 'article_id']);
	}

	public function getDownloads()
	{
		return $this->hasMany(ArticleDownloads::className(), ['file_id' => 'file_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArticle()
	{
		return $this->hasOne(Articles::className(), ['article_id' => 'article_id']);
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
		$this->templateColumns['file_filename'] = 'file_filename';
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
		$this->templateColumns['download_search'] = [
			'attribute' => 'download_search',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['download/index', 'file'=>$model->primaryKey]);
				return Html::a($model->view->downloads ? $model->view->downloads : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
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
				->where(['file_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * before validate attributes
	 */
	public static function getArticlePath($returnAlias=true)
	{
		return ($returnAlias ? Yii::getAlias('@webroot/public/article/file') : 'public/article');
	}

	/**
	 * afterFind
	 *
	 * Simpan nama article lama untuk keperluan jikalau kondisi update tp articlenya tidak diupdate.
	 */
	public function afterFind()
	{
		$this->old_file_filename_i = $this->file_filename;
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

			if($this->isNewRecord) {
					if (Yii::$app->request->get('article')){

					$article = Yii::$app->request->get('article');
					$this->article_id = $article;
				}
			}
			
			$filePath = Yii::getAlias('@webroot/public/article/file');
			
			// Add directory
			if(!file_exists($filePath)) {
				@mkdir($filePath, 0777,true);

				// Add file in directory (index.php)
				$indexFile = join('/', [$filePath, 'index.php']);
				if(!file_exists($indexFile)) {
					file_put_contents($indexFile, "<?php\n");
				}

			}else {
				@chmod($filePath, 0777,true);
			}
			if($this->file_filename instanceof \yii\web\UploadedFile) {
				$article = Articles::findOne($this->article_id);
				$imageName = time().'_'.$this->sanitizeFileName($article->title).'.'. $this->file_filename->extension; 
				if($this->file_filename->saveAs($filePath.'/'.$imageName)) {
					$this->file_filename = $imageName;
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

		// jika article file diperbarui, hapus article yg lama.
		if(!$insert && $this->file_filename != $this->old_file_filename_i) {
			$fname = join('/', [self::getArticlePath(), $this->old_file_filename_i]);
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

		$fname = join('/', [self::getArticlePath(), $this->file_filename]);
		if(file_exists($fname)) {
			@unlink($fname);
		}
	}
}
