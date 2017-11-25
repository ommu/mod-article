<?php
/**
 * SiteController
 * @var $this SiteController
 * @var $model Articles
 * @var $form CActiveForm
 * version: 1.3.0
 * Reference start
 *
 * TOC :
 *	Index
 *	View
 *	Download
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-article
 * @contact (+62)856-299-4114
 *
 *----------------------------------------------------------------------------------------------------------
 */

class SiteController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $defaultAction = 'index';

	/**
	 * Initialize admin page theme
	 */
	public function init() 
	{
		$permission = ArticleSetting::getInfo('permission');
		$siteType = OmmuSettings::getInfo('site_type');
		if($permission == 1 || ($permission == 0 && !Yii::app()->user->isGuest)) {
			$arrThemes = Utility::getCurrentTemplate('public');
			Yii::app()->theme = $arrThemes['folder'];
			$this->layout = $arrThemes['layout'];
		} else
			$this->redirect($siteType == 0 ? Yii::app()->createUrl('site/index') : Yii::app()->createUrl('site/login'));
	}

	/**
	 * @return array action filters
	 */
	public function filters() 
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() 
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','download'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'$user->level == 1',
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex() 
	{
		$setting = ArticleSetting::model()->findByPk(1,array(
			'select' => 'meta_description, meta_keyword',
		));
		
		if(isset($_GET['category']) && $_GET['category'])
			$title = ArticleCategory::model()->findByPk($_GET['category']);

		$criteria=new CDbCriteria;
		$criteria->condition = 'publish = :publish AND published_date <= curdate()';
		$criteria->params = array(
			':publish'=>1,
		);
		$criteria->order = 'published_date DESC';
		if(isset($_GET['category']) && $_GET['category'] != '')
			$criteria->compare('cat_id',$_GET['category']);

		$dataProvider = new CActiveDataProvider('Articles', array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>9,
			),
		));

		$this->pageTitle = (isset($_GET['category']) && $_GET['category']) ? $title->title->message : Yii::t('phrase', 'Articles');
		$this->pageDescription = $setting->meta_description;
		$this->pageMeta = $setting->meta_keyword;
		$this->render('front_index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) 
	{
		$setting = ArticleSetting::model()->findByPk(1,array(
			'select' => 'meta_keyword',
		));

		$model=$this->loadModel($id);
		ArticleViews::insertView($model->article_id);
		
		//Random Article
		$criteria=new CDbCriteria;
		$criteria->condition = 'publish = :publish AND published_date <= curdate() AND article_id <> :id';
		$criteria->params = array(
			':publish'=>1,
			':id'=>$id,
		);
		$criteria->compare('cat_id',$model->cat_id);
		$criteria->order = 'RAND()';
		$criteria->limit = 4;
		$random = Articles::model()->findAll($criteria);
		
		$this->pageTitleShow = true;
		$this->pageTitle = $model->title;
		$this->pageDescription = Utility::shortText(Utility::hardDecode($model->body),250);
		$this->pageMeta = ArticleTag::getKeyword($setting->meta_keyword, $model->tags);
		if(!empty($medias)) {
			$media = $model->view->media_cover ? $model->view->media_cover : $medias[0]->media;
			if($model->article_type == 'standard')
				$media = Yii::app()->request->baseUrl.'/public/article/'.$model->article_id.'/'.$media;
			else if($model->article_type == 'video')
				$media = 'http://www.youtube.com/watch?v='.$media;
			$this->pageImage = $media;
		}
		$this->render('front_view',array(
			'model'=>$model,
			'random'=>$random,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionDownload($id) 
	{
		$model=$this->loadModel($id);
		ArticleDownloads::insertDownload($model->article_id);
		$this->redirect(Yii::app()->request->baseUrl.'/public/article/'.$model->article_id.'/'.$model->media_file);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = Articles::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) 
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='articles-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
