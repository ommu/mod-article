<?php
/**
 * ArticleDownloadHistory

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 10:03 WIB
 * @link https://github.com/ommu/mod-article
 *
 * This is the model class for table "ommu_article_download_history".
 *
 * The followings are the available columns in table "ommu_article_download_history":
 * @property string $id
 * @property string $download_id
 * @property string $download_date
 * @property string $download_ip
 *
 * The followings are the available model relations:
 * @property ArticleDownloads $download
 *
 */

namespace ommu\article\models;

use Yii;
use yii\helpers\Url;

class ArticleDownloadHistory extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	public $download_search;

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
			[['download_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleDownloads::className(), 'targetAttribute' => ['download_id' => 'download_id']],
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
			'download_ip' => Yii::t('app', 'Download Ip'),
			'download_search' => Yii::t('app', 'Download'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDownload()
	{
		return $this->hasOne(ArticleDownloads::className(), ['download_id' => 'download_id']);
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
		if(!Yii::$app->request->get('download')) {
			$this->templateColumns['download_search'] = [
				'attribute' => 'download_search',
				'value' => function($model, $key, $index, $column) {
					return $model->download->download_id;
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
				->where(['id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

}
