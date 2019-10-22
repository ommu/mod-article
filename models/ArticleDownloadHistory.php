<?php
/**
 * ArticleDownloadHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:03 WIB
 * @modified date 12 May 2019, 18:26 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_download_history".
 *
 * The followings are the available columns in table "ommu_article_download_history":
 * @property integer $id
 * @property integer $download_id
 * @property string $download_date
 * @property string $download_ip
 *
 * The followings are the available model relations:
 * @property ArticleDownloads $download
 *
 */

namespace ommu\article\models;

use Yii;

class ArticleDownloadHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	public $fileName;
	public $articleTitle;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_article_download_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['download_id', 'download_ip'], 'required'],
			[['download_id'], 'integer'],
			[['download_date'], 'safe'],
			[['download_ip'], 'string', 'max' => 20],
			[['download_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleDownloads::className(), 'targetAttribute' => ['download_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'download_id' => Yii::t('app', 'Download'),
			'download_date' => Yii::t('app', 'Download Date'),
			'download_ip' => Yii::t('app', 'Download IP'),
			'fileName' => Yii::t('app', 'File'),
			'articleTitle' => Yii::t('app', 'Article'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDownload()
	{
		return $this->hasOne(ArticleDownloads::className(), ['id' => 'download_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\query\ArticleDownloadHistory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\article\models\query\ArticleDownloadHistory(get_called_class());
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
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('download')) {
			$this->templateColumns['articleTitle'] = [
				'attribute' => 'articleTitle',
				'value' => function($model, $key, $index, $column) {
					return isset($model->download->file->article) ? $model->download->file->article->title : '-';
					// return $model->articleTitle;
				},
			];
			$this->templateColumns['fileName'] = [
				'attribute' => 'fileName',
				'value' => function($model, $key, $index, $column) {
					return isset($model->download->file) ? $model->download->file->file_filename : '-';
					// return $model->fileName;
				},
			];
		}
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

		// $this->fileName = isset($this->download->file) ? $this->download->file->file_filename : '-';
		// $this->articleTitle = isset($model->download->file->article) ? $model->download->file->article->title : '-';
	}
}
