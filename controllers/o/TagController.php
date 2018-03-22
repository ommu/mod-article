<?php
/**
 * TagController
 * @var $this TagController
 * @var $model ArticleTag
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Add
 *	Delete
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-article
 *
 *----------------------------------------------------------------------------------------------------------
 */

class TagController extends Controller
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
				'actions'=>array('index','manage','add','delete'),
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
	public function actionManage($article=null) 
	{
		$pageTitle = Yii::t('phrase', 'Article Tags');
		if($article != null) {
			$data = Articles::model()->findByPk($article);
			$pageTitle = Yii::t('phrase', 'Article Tag: {article_title} from category {category_name}', array ('{article_title}'=>$data->title, '{category_name}'=>$data->cat->title->message));
		}
		
		$model=new ArticleTag('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ArticleTag'])) {
			$model->attributes=$_GET['ArticleTag'];
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd() 
	{
		$model=new ArticleTag;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['article_id'], $_POST['tag_id'], $_POST['tag'])) {
			$model->article_id = $_POST['article_id'];
			$model->tag_id = $_POST['tag_id'];
			$model->tag_input = $_POST['tag'];

			if($model->save()) {
				if(isset($_GET['type']) && $_GET['type'] == 'article')
					$url = Yii::app()->controller->createUrl('delete', array('id'=>$model->id,'type'=>'article'));
				else 
					$url = Yii::app()->controller->createUrl('delete', array('id'=>$model->id));
				echo CJSON::encode(array(
					'data' => '<div>'.$model->tag->body.'<a href="'.$url.'" title="'.Yii::t('phrase', 'Delete').'">'.Yii::t('phrase', 'Delete').'</a></div>',
				));
			}
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id) 
	{
		$model=$this->loadModel($id);
		
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$model->publish = 2;
			$model->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : 0;
			
			if($model->update()) {
				if((isset($_GET['type']) && $_GET['type'] == 'article') || isset($_GET['c'])) {
					echo CJSON::encode(array(
						'type' => 4,
					));
				} else {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-article-tag',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Article Tags success deleted.').'</strong></div>',
					));
				}
			}
			Yii::app()->end();
		}
		
		if(isset($_GET['type']) && $_GET['type'] == 'article')
			$url = Yii::app()->controller->createUrl('o/admin/edit', array('id'=>$model->article_id));
		else {
			if(isset($_GET['c']) && count($_GET) > 2)
				$url = Yii::app()->controller->createUrl($_GET['c'].'/'.$_GET['d'].'/edit', array('id'=>$model->article_id));
			else if(isset($_GET['c']))
				$url = Yii::app()->controller->createUrl($_GET['c'].'/edit', array('id'=>$model->article_id));
			else
				$url = Yii::app()->controller->createUrl('manage');
		}
		
		$this->dialogDetail = true;
		$this->dialogGroundUrl = $url;
		$this->dialogWidth = 350;

		$this->pageTitle = Yii::t('phrase', 'Delete Tag: {tag_body} from article {article_title}', array('{tag_body}'=>$model->tag->body, '{article_title}'=>$model->article->title));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_delete');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = ArticleTag::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='article-tag-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
