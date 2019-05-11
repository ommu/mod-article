<?php
/**
 * ArticleDownloads

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:02 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_downloads".
 *
 * The followings are the available columns in table "ommu_article_downloads":
 * @property string $download_id
 * @property string $file_id
 * @property string $user_id
 * @property integer $downloads
 * @property string $download_date
 * @property string $download_ip
 *
 * The followings are the available model relations:
 * @property ArticleDownloadHistory[] $histories
 * @property ArticleFiles $file
 *
 */

namespace ommu\article\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users;

class ArticleDownloads extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	public $file_search;
	public $userDisplayname;

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
			[['file_id', 'user_id', 'download_ip'], 'required'],
			[['file_id', 'user_id', 'downloads'], 'integer'],
			[['download_date'], 'safe'],
			[['download_ip'], 'string', 'max' => 20],
			[['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleFiles::className(), 'targetAttribute' => ['file_id' => 'file_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'download_id' => Yii::t('app', 'Download'),
			'file_id' => Yii::t('app', 'File'),
			'user_id' => Yii::t('app', 'User'),
			'downloads' => Yii::t('app', 'Downloads'),
			'download_date' => Yii::t('app', 'Download Date'),
			'download_ip' => Yii::t('app', 'Download Ip'),
			'file_search' => Yii::t('app', 'File'),
			'userDisplayname' => Yii::t('app', 'User'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories()
	{
		return $this->hasMany(ArticleDownloadHistory::className(), ['download_id' => 'download_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFile()
	{
		return $this->hasOne(ArticleFiles::className(), ['file_id' => 'file_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
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
		if(!Yii::$app->request->get('file')) {
			$this->templateColumns['file_search'] = [
				'attribute' => 'file_search',
				'value' => function($model, $key, $index, $column) {
					return $model->file->file_filename;
				},
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['userDisplayname'] = [
				'attribute' => 'userDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
		}
		
		$this->templateColumns['downloads'] = [
			'attribute' => 'downloads',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['history/download/index', 'download'=>$model->primaryKey]);
				return Html::a($model->downloads ? $model->downloads : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['download_date'] = [
			'attribute' => 'download_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->download_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'download_date'),
		];
		$this->templateColumns['download_ip'] = 'download_ip';
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['download_id' => $id])
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
	public function insertDownload($file_id)
	{
		$user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
		$download = ArticleDownloads::find()->where(['file_id' => $file_id, 'user_id' => $user_id])->one();
		if($download == null) {
			$download = new ArticleDownloads;
			$download->file_id = $file_id;
		} else
			$download->downloads = $download->downloads+1;
			
		$download->save();
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->user_id == null)
					$this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}

			$this->download_ip = Yii::$app->request->userIP;
		}
		return true;
	}
}
