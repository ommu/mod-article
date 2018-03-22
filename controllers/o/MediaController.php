<?php
/**
 * MediaController
 * @var $this MediaController
 * @var $model ArticleMedia
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Edit
 *	View
 *	RunAction
 *	Delete
 *	Publish
 *	Setcover
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

class MediaController extends Controller
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
				'actions'=>array('index','manage','edit','view','runaction','delete','publish','setcover'),
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
		$model=new ArticleMedia('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ArticleMedia'])) {
			$model->attributes=$_GET['ArticleMedia'];
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
		
		$pageTitle = Yii::t('phrase', 'Article Medias');
		if($article != null) {
			$data = Articles::model()->findByPk($article);
			$pageTitle = Yii::t('phrase', 'Article Media: {article_title} from category {category_name}', array ('{article_title}'=>$data->title, '{category_name}'=>$data->cat->title->message));
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
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit($id) 
	{
		$setting = ArticleSetting::model()->findByPk(1, array(
			'select' => 'media_image_type',
		));
		$media_image_type = unserialize($setting->media_image_type);
		if(empty($media_image_type))
			$media_image_type = array();
		
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['ArticleMedia'])) {
			$model->attributes=$_POST['ArticleMedia'];
			
			if($model->save()) {
				Yii::app()->user->setFlash('success', Yii::t('phrase', 'Article media success updated.'));
				$this->redirect(array('edit','id'=>$model->media_id));
			}
		}

		$this->pageTitle = Yii::t('phrase', 'Update Media: {photo_media} from article {article_title}', array('{photo_media}'=>$model->cover_filename, '{article_title}'=>$model->article->title));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_edit', array(
			'model'=>$model,
			'media_image_type'=>$media_image_type,
		));
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) 
	{
		$model=$this->loadModel($id);

		$this->pageTitle = Yii::t('phrase', 'View Media: {photo_media} from article {article_title}', array('{photo_media}'=>$model->cover_filename, '{article_title}'=>$model->article->title));
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
		$actions  = $_GET['action'];

		if(count($id) > 0) {
			$criteria = new CDbCriteria;
			$criteria->addInCondition('media_id', $id);

			if($actions == 'publish') {
				ArticleMedia::model()->updateAll(array(
					'publish' => 1,
				),$criteria);
			} elseif($actions == 'unpublish') {
				ArticleMedia::model()->updateAll(array(
					'publish' => 0,
				),$criteria);
			} elseif($actions == 'trash') {
				ArticleMedia::model()->updateAll(array(
					'publish' => 2,
				),$criteria);
			} elseif($actions == 'delete') {
				ArticleMedia::model()->deleteAll($criteria);
			}
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax'])) {
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
				if(isset($_GET['hook']) && $_GET['hook'] == 'admin') {
					$url = Yii::app()->controller->createUrl('o/admin/getcover', array('id'=>$model->article_id,'replace'=>'true'));
					echo CJSON::encode(array(
						'type' => 2,
						'id' => 'media-render',
						'get' => $url,
					));				
				} else {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-article-media',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Article media success deleted.').'</strong></div>',
					));
				}
			}
			Yii::app()->end();
		}
		
		if(isset($_GET['hook']) && $_GET['hook'] == 'admin')
			$dialogGroundUrl = Yii::app()->controller->createUrl('o/admin/edit', array('id'=>$model->article_id));
		else 
			$dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogDetail = true;
		$this->dialogGroundUrl = $dialogGroundUrl;
		$this->dialogWidth = 350;

		$this->pageTitle = Yii::t('phrase', 'Delete Media: {photo_media} from article {article_title}', array('{photo_media}'=>$model->cover_filename, '{article_title}'=>$model->article->title));
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
					'id' => 'partial-article-media',
					'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Article media success updated.').'</strong></div>',
				));
			}
			Yii::app()->end();
		}
		
		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 350;

		$this->pageTitle = Yii::t('phrase', '{title}: {photo_media} from article {article_title}', array('{title}'=>$title, '{photo_media}'=>$model->cover_filename, '{article_title}'=>$model->article->title));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_publish', array(
			'title'=>$title,
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionSetcover($id) 
	{
		$model = $this->loadModel($id);
		
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			//change value active or publish
			$model->cover = 1;
			$model->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			
			if($model->update()) {
				if(isset($_GET['hook']) && $_GET['hook'] == 'admin') {
					$url = Yii::app()->controller->createUrl('o/admin/getcover', array('id'=>$model->article_id,'replace'=>'true'));
					echo CJSON::encode(array(
						'type' => 2,
						'id' => 'media-render',
						'get' => $url,
					));
				} else {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-article-media',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Article media success updated.').'</strong></div>',
					));
				}
			}
			Yii::app()->end();
		}
		
		if(isset($_GET['hook']) && $_GET['hook'] == 'admin')
			$dialogGroundUrl = Yii::app()->controller->createUrl('o/admin/edit', array('id'=>$model->article_id));
		else 
			$dialogGroundUrl = Yii::app()->controller->createUrl('manage');		
		$this->dialogDetail = true;
		$this->dialogGroundUrl = $dialogGroundUrl;
		$this->dialogWidth = 350;

		$this->pageTitle = Yii::t('phrase', 'Cover Photo: {photo_media} from article {article_title}', array('{photo_media}'=>$model->cover_filename, '{article_title}'=>$model->article->title));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_cover');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = ArticleMedia::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='article-media-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
