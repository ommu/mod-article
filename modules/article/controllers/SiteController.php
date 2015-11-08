<?php
/**
 * SiteController
 * @var $this SiteController
 * @var $model Articles * @var $form CActiveForm
 * Copyright (c) 2013, Ommu Platform (ommu.co). All rights reserved.
 * version: 0.0.1
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
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
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
		if(ArticleSetting::getInfo('permission') == 1) {
			$arrThemes = Utility::getCurrentTemplate('public');
			Yii::app()->theme = $arrThemes['folder'];
			$this->layout = $arrThemes['layout'];
		} else {
			$this->redirect(Yii::app()->createUrl('site/index'));
		}
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
				//'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level != 1)',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level == 1)',
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
		
		if(isset($_GET['category']) && $_GET['category'] != '')
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
				'pageSize'=>10,
			),
		));

		$this->pageTitle = 'Articles';
		$this->pageDescription = (isset($_GET['category']) && $_GET['category'] != '') ? Phrase::trans($title->name, 2) : $setting->meta_description;
		$this->pageMeta = (isset($_GET['category']) && $_GET['category'] != '') ? Phrase::trans($title->desc, 2) : $setting->meta_keyword;
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
		$setting = VideoSetting::model()->findByPk(1,array(
			'select' => 'meta_keyword',
		));

		$model=$this->loadModel($id);
		Articles::model()->updateByPk($id, array('view'=>$model->view + 1));
		
		//Random Article		
		$criteria=new CDbCriteria;
		$criteria->condition = 'publish = :publish AND published_date <= curdate() AND article_id <> :id';
		$criteria->params = array(
			':publish'=>1,
			':id'=>$id,
		);
		$criteria->order = 'RAND()';
		$criteria->addInCondition('cat_id',$model->cat_id);
		$criteria->limit = 4;		
		$random = Articles::model()->findAll($criteria);		

		$this->pageTitle = $model->title;
		$this->pageDescription = Utility::shortText(Utility::hardDecode($model->body),300);
		$this->pageMeta = ArticleTag::getKeyword($setting->meta_keyword, $id);
		if($model->media_id != 0 && $model->cover->media != '') {
			if(in_array($model->article_type, array('1','3'))) {
				$media = Yii::app()->request->baseUrl.'/public/article/'.$id.'/'.$model->cover->media;
			} else if($model->article_type == 2) {
				$media = 'http://www.youtube.com/watch?v='.$model->cover->media;
			}
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
		Articles::model()->updateByPk($id, array('download'=>$model->download + 1));
		$this->redirect(Yii::app()->request->baseUrl.'/public/article/'.$id.'/'.$model->media_file);
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
			throw new CHttpException(404, Phrase::trans(193,0));
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
