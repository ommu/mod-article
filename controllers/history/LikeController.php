<?php
/**
 * LikeController
 * @var $this LikeController
 * @var $model ArticleLikeHistory
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 7 February 2017, 02:35 WIB
 * @link https://github.com/ommu/ommu-article
 *
 *----------------------------------------------------------------------------------------------------------
 */

class LikeController extends Controller
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
		if(!Yii::app()->user->isGuest) {
			if(in_array(Yii::app()->user->level, array(1,2))) {
				$arrThemes = Utility::getCurrentTemplate('admin');
				Yii::app()->theme = $arrThemes['folder'];
				$this->layout = $arrThemes['layout'];
			}
		} else
			$this->redirect(Yii::app()->createUrl('site/login'));
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','manage'),
				'users'=>array('@'),
				'expression'=>'in_array($user->level, array(1,2))',
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
		$this->redirect(array('manage'));
	}

	/**
	 * Manages all models.
	 */
	public function actionManage($like=null) 
	{
		$pageTitle = Yii::t('phrase', 'Article Likes Data');
		if($like != null) {
			$data = ArticleLikes::model()->findByPk($like);
			$pageTitle = Yii::t('phrase', 'Article Likes Data: {article_title} from category {category_name} - user Guest', array ('{article_title}'=>$data->article->title, '{category_name}'=>$data->article->category->title->message));	
			if($data->user->displayname)
				$pageTitle = Yii::t('phrase', 'Article Likes Data: {article_title} from category {category_name} - user {user_displayname}', array ('{article_title}'=>$data->article->title, '{category_name}'=>$data->article->category->title->message, '{user_displayname}'=>$data->user->displayname));
		}
		
		$model=new ArticleLikeHistory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ArticleLikeHistory'])) {
			$model->attributes=$_GET['ArticleLikeHistory'];
		}

		$columnTemp = array();
		if(isset($_GET['GridColumn'])) {
			foreach($_GET['GridColumn'] as $key => $val) {
				if($_GET['GridColumn'][$key] == 1) {
					$columnTemp[] = $key;
				}
			}
		}
		$columns = $model->getGridColumn($columnTemp);

		$this->pageTitle = $pageTitle;
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_manage', array(
			'model'=>$model,
			'columns' => $columns,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = ArticleLikeHistory::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='article-like-history-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
