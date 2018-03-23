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
 *	View
 *	RunAction
 *	Delete
 *	Publish
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @modified date 23 March 2018, 05:30 WIB
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
				'actions'=>array('index','manage','add','view','runaction','delete','publish'),
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
	public function actionManage() 
	{
		$model=new ArticleTag('search');
		$model->unsetAttributes();  // clear any default values
		if(Yii::app()->getRequest()->getParam('ArticleTag')) {
			$model->attributes=Yii::app()->getRequest()->getParam('ArticleTag');
		}

		$gridColumn = Yii::app()->getRequest()->getParam('GridColumn');
		$columnTemp = array();
		if($gridColumn) {
			foreach($gridColumn as $key => $val) {
				if($gridColumn[$key] == 1)
					$columnTemp[] = $key;
			}
		}
		$columns = $model->getGridColumn($columnTemp);

		$pageTitle = Yii::t('phrase', 'Article Tags');
		if($article != null) {
			$data = Articles::model()->findByPk($article);
			$pageTitle = Yii::t('phrase', 'Article Tags: {article_title}', array ('{article_title}'=>$data->title));
		}

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

		if(isset($_POST['article_id'], $_POST['tag_id'], $_POST['tag'])) 
		{
			$model->article_id = $_POST['article_id'];
			$model->tag_id = $_POST['tag_id'];
			$model->tag_input = $_POST['tag'];

			if($model->save()) {
				if(Yii::app()->getRequest()->getParam('type') == 'article')
					$url = Yii::app()->controller->createUrl('delete', array('id'=>$model->id, 'type'=>'article'));
				else 
					$url = Yii::app()->controller->createUrl('delete', array('id'=>$model->id));
				echo CJSON::encode(array(
					'data' => '<div>'.$model->tag->body.'<a href="'.$url.'" title="'.Yii::t('phrase', 'Delete').'">'.Yii::t('phrase', 'Delete').'</a></div>',
				));
			}
		}
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) 
	{
		$model=$this->loadModel($id);
		
		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 600;

		$this->pageTitle = Yii::t('phrase', 'Detail Tag: {tag_body} article {article_title}', array('{tag_body}'=>$model->tag->body, '{article_title}'=>$model->article->title));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_view', array(
			'model'=>$model,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionRunAction() {
		$id       = $_POST['trash_id'];
		$criteria = null;
		$actions  = Yii::app()->getRequest()->getParam('action');

		if(count($id) > 0) {
			$criteria = new CDbCriteria;
			$criteria->addInCondition('id', $id);

			if($actions == 'publish') {
				ArticleTag::model()->updateAll(array(
					'publish' => 1,
				),$criteria);
			} elseif($actions == 'unpublish') {
				ArticleTag::model()->updateAll(array(
					'publish' => 0,
				),$criteria);
			} elseif($actions == 'trash') {
				ArticleTag::model()->updateAll(array(
					'publish' => 2,
				),$criteria);
			} elseif($actions == 'delete') {
				ArticleTag::model()->deleteAll($criteria);
			}
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!(Yii::app()->getRequest()->getParam('ajax'))) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('manage'));
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
			$model->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			
			if($model->update()) {
				if(Yii::app()->getRequest()->getParam('type') == 'article' || Yii::app()->getRequest()->getParam('c')) {
					echo CJSON::encode(array(
						'type' => 4,
					));
				} else {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-article-tag',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Article tag success deleted.').'</strong></div>',
					));
				}
			}
			Yii::app()->end();
		}
		
		if(Yii::app()->getRequest()->getParam('type') == 'article')
			$url = Yii::app()->controller->createUrl('o/admin/edit', array('id'=>$model->article_id));
		else {
			if(Yii::app()->getRequest()->getParam('c') && count($_GET) > 2)
				$url = Yii::app()->controller->createUrl(Yii::app()->getRequest()->getParam('c').'/'.Yii::app()->getRequest()->getParam('d').'/edit', array('id'=>$model->article_id));
			else if(Yii::app()->getRequest()->getParam('c'))
				$url = Yii::app()->controller->createUrl(Yii::app()->getRequest()->getParam('c').'/edit', array('id'=>$model->article_id));
			else
				$url = Yii::app()->controller->createUrl('manage');
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = $url;
		$this->dialogWidth = 350;

		$this->pageTitle = Yii::t('phrase', 'Delete Tag: {tag_body} article {article_title}', array('{tag_body}'=>$model->tag->body, '{article_title}'=>$model->article->title));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_delete');
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionPublish($id) 
	{
		$model=$this->loadModel($id);
		
		$title = $model->publish == 1 ? Yii::t('phrase', 'Unpublish') : Yii::t('phrase', 'Publish');
		$replace = $model->publish == 1 ? 0 : 1;

		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			//change value active or publish
			$model->publish = $replace;
			$model->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;

			if($model->update()) {
				echo CJSON::encode(array(
					'type' => 5,
					'get' => Yii::app()->controller->createUrl('manage'),
					'id' => 'partial-article-tag',
					'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Article tag success updated.').'</strong></div>',
				));
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 350;

		$this->pageTitle = Yii::t('phrase', '{title} Tag: {tag_body} article {article_title}', array('{title}'=>$title, '{tag_body}'=>$model->tag->body, '{article_title}'=>$model->article->title));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_publish', array(
			'title'=>$title,
			'model'=>$model,
		));
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
