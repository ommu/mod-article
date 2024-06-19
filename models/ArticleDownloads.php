<?php
/**
 * ArticleDownloads
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 10:02 WIB
 * @modified date 12 May 2019, 18:26 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_downloads".
 *
 * The followings are the available columns in table "ommu_article_downloads":
 * @property integer $id
 * @property integer $file_id
 * @property integer $user_id
 * @property integer $downloads
 * @property string $download_date
 * @property string $download_ip
 *
 * The followings are the available model relations:
 * @property ArticleDownloadHistory[] $histories
 * @property ArticleFiles $file
 * @property Users $user
 *
 */

namespace ommu\article\models;

use Yii;
use yii\helpers\Html;
use app\models\Users;

class ArticleDownloads extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	public $fileFilename;
	public $userDisplayname;
	public $articleTitle;
	public $articleId;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_article_downloads';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['file_id'], 'required'],
			[['file_id', 'user_id', 'downloads'], 'integer'],
			[['user_id', 'download_ip'], 'safe'],
			[['download_ip'], 'string', 'max' => 20],
			[['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleFiles::className(), 'targetAttribute' => ['file_id' => 'id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'file_id' => Yii::t('app', 'File'),
			'user_id' => Yii::t('app', 'User'),
			'downloads' => Yii::t('app', 'Downloads'),
			'download_date' => Yii::t('app', 'Download Date'),
			'download_ip' => Yii::t('app', 'Download IP'),
			'histories' => Yii::t('app', 'Histories'),
			'fileFilename' => Yii::t('app', 'File'),
			'userDisplayname' => Yii::t('app', 'User'),
			'articleTitle' => Yii::t('app', 'Article'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories($count=false)
	{
        if ($count == false) {
            return $this->hasMany(ArticleDownloadHistory::className(), ['download_id' => 'id']);
        }

		$model = ArticleDownloadHistory::find()
            ->alias('t')
            ->where(['t.download_id' => $this->id]);
		$histories = $model->count();

		return $histories ? $histories : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFile()
	{
		return $this->hasOne(ArticleFiles::className(), ['id' => 'file_id'])
            ->select(['id', 'article_id', 'file_filename']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArticle()
	{
		return $this->hasOne(Articles::className(), ['id' => 'article_id'])
            ->select(['id', 'cat_id', 'title'])
            ->via('file');
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id'])
            ->select(['user_id', 'displayname']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\query\ArticleDownloads the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\article\models\query\ArticleDownloads(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
        parent::init();

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['articleTitle'] = [
			'attribute' => 'articleTitle',
			'value' => function($model, $key, $index, $column) {
				return isset($model->article) ? $model->article->title : '-';
				// return $model->articleTitle;
			},
			'visible' => !Yii::$app->request->get('file') && !Yii::$app->request->get('article') ? true : false,
		];
		$this->templateColumns['fileFilename'] = [
			'attribute' => 'fileFilename',
			'value' => function($model, $key, $index, $column) {
				return isset($model->file) ? $model->file->file_filename : '-';
				// return $model->fileFilename;
			},
			'visible' => !Yii::$app->request->get('file') ? true : false,
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->displayname : '-';
				// return $model->userDisplayname;
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['download_date'] = [
			'attribute' => 'download_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->download_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'download_date'),
		];
		$this->templateColumns['download_ip'] = [
			'attribute' => 'download_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->download_ip;
			},
		];
		$this->templateColumns['downloads'] = [
			'attribute' => 'downloads',
			'value' => function($model, $key, $index, $column) {
				$downloads = $model->downloads;
				return Html::a($downloads, ['download/history/manage', 'download' => $model->primaryKey], ['title' => Yii::t('app', '{count} histories', ['count' => $downloads]), 'data-pjax' => 0]);
			},
			'filter' => false,
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * function insertDownload
	 */
	public function insertDownload($file_id, $user_id=null)
	{
        if ($user_id == null) {
            $user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
        }

		$findDownload = self::find()
			->select(['id', 'downloads'])
			->where(['file_id' => $file_id]);
        if ($user_id != null) {
            $findDownload->andWhere(['user_id' => $user_id]);
        } else {
            $findDownload->andWhere(['is', 'user_id', null]);
        }
		$findDownload = $findDownload->one();

        if ($findDownload !== null) {
            $findDownload->updateAttributes(['downloads' => $findDownload->downloads+1, 'download_ip' => $_SERVER['REMOTE_ADDR']]);
        } else {
			$download = new ArticleDownloads();
			$download->file_id = $file_id;
			$download->user_id = $user_id;
			$download->save();
		}
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->fileFilename = isset($this->file) ? $this->file->file_filename : '-';
		// $this->userDisplayname = isset($this->user) ? $this->user->displayname : '-';
		// $this->articleTitle = isset($model->file->article) ? $model->file->article->title : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                if ($this->user_id == null) {
                    $this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
            $this->download_ip = $_SERVER['REMOTE_ADDR'];
        }
        return true;
	}
}
