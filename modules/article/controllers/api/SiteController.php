<?php
/**
 * SiteController
 * @var $this SiteController
 * @var $model Articles
 * @var $form CActiveForm
 * version: 0.0.1
 * Reference start
 *
 * TOC :
 *	Index
 *	Main
 *	List
 *	Detail
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 23 Juni 2016, 14:46 WIB
 * @link https://github.com/ommu/Articles
 * @contact (+62)856-299-4114
 *
 *----------------------------------------------------------------------------------------------------------
 */

class SiteController extends ControllerApi
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $defaultAction = 'index';

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
				'actions'=>array('index','main','list','detail'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
				//'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level != 1)',
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
		$this->redirect(Yii::app()->createUrl('site/index'));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionMain() 
	{		
		if(Yii::app()->request->isPostRequest) {
			$category = trim($_POST['category']);
			$pagesize = trim($_POST['pagesize']);
			
			$catOther = array(12,17,22);
			$catParent = 0;
			if($category != null && $category != '') {
				if(in_array($category, $catOther))
					$this->redirect(Yii::app()->createUrl('site/index'));
				
				$cat = ArticleCategory::model()->findByPk($category, array(
					'select' => 'cat_id, dependency',
				));
				if($cat->dependency == 0)
					$catParent = $category;
				else
					$this->redirect(Yii::app()->createUrl('site/index'));
			}
		
			$criteria=new CDbCriteria;
			$criteria->select = array('cat_id','name');		
			$criteria->addNotInCondition('t.cat_id', $catOther);
			$criteria->compare('t.publish', 1);
			$criteria->compare('t.dependency', $catParent);
			
			$categoryFind = ArticleCategory::model()->findAll($criteria);
			
			if($categoryFind != null) {
				$return = '';
				foreach($categoryFind as $key => $val) {
					$criteriaArticle=new CDbCriteria;
					$now = new CDbExpression("NOW()");
					
					if($category != null && $category != '')
						$criteriaArticle->compare('t.cat_id', $val->cat_id);
						
					else {
						$categorySub = ArticleCategory::model()->findAll(array(
							'condition'=>'publish = :publish AND dependency = :dependency',
							'params'=>array(
								':publish'=>1,
								':dependency'=>$val->cat_id,
							),
						));
						$catData = array();
						if($categorySub != null) {
							foreach($categorySub as $row)
								$catData[] = $row->cat_id;
						}					
						$criteriaArticle->addInCondition('t.cat_id', $catData);
					}
					$criteriaArticle->compare('t.publish', 1);
					$criteriaArticle->compare('date(t.published_date) <', $now);
					$criteriaArticle->limit = $pagesize != null && $pagesize != '' ? $pagesize : 4;
					$criteriaArticle->order = 't.published_date DESC, t.article_id DESC';
			
					$article = Articles::model()->findAll($criteriaArticle);
		
					$data = '';					
					if(!empty($article)) {
						foreach($article as $key => $item) {
							$article_url = Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->request->baseUrl;
							$article_path = 'public/article/'.$item->article_id;
							
							$medias = $item->medias;
							if(!empty($medias)) {
								$media = $item->view->media_cover ? $item->view->media_cover : $medias[0]->media;
								if(file_exists($article_path.'/'.$media))
									$media_image = $article_url.'/'.$article_path.'/'.$media;
							}
							
							$data[] = array(
								'id'=>$item->article_id,
								'category'=>Phrase::trans($item->cat->name),
								'title'=>ucwords(strtolower($item->title)),
								'intro'=>$item->body != '' ? Utility::shortText(Utility::hardDecode($item->body),200) : '-',
								'media_image'=>!empty($medias) ? $media_image : '-',
								'view'=>$item->view->views,
								'likes'=>$item->view->likes,
								'download'=>$item->view->downloads,
								'published_date'=>Utility::dateFormat($item->published_date),
								'share'=>Articles::getShareUrl($item->article_id, $item->title),
							);
						}
					} else
						$data = array();
				
					$categoryTitle = Phrase::trans($val->name);
					$return[] = array(
						'id'=>$val->cat_id,
						'category'=>$categoryTitle,
						//'count'=>$articleCount,
						'category_source'=>Utility::getUrlTitle($categoryTitle),
						'data'=>$data,
					);
				}
			}
			$this->_sendResponse(200, CJSON::encode($this->renderJson($return)));	
			
		} else 
			$this->redirect(Yii::app()->createUrl('site/index'));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionList() 
	{
		if(Yii::app()->request->isPostRequest) {
			$category = trim($_POST['category']);
			$tag = trim($_POST['tag']);
			$paging = trim($_POST['paging']);
			$pagesize = trim($_POST['pagesize']);
			
			$criteria=new CDbCriteria;
			$criteria->with = array(
				'tag_ONE' => array(
					'alias'=>'a',
				),
				'tag_ONE.tag_TO' => array(
					'alias'=>'b',
				),
			);
			$now = new CDbExpression("NOW()");
			if($tag != null && $tag != '') {
				$criteria->condition = 'b.body = :body';
				$criteria->params = array(
					':body'=>$tag,
				);
			}
			if($category != null && $category != '') {
				$catExplode = explode(',', $category);
				$catArray = array();
				foreach($catExplode as $val) {
					$cat = ArticleCategory::model()->findByPk($val, array(
						'select' => 'publish, dependency',
					));
					if($cat != null) {
						if($cat->dependency != 0) {
							if(!in_array($val, $catArray))
								$catArray[] = $val;
						} else {
							$catSub = ArticleCategory::model()->findAll(array(
								'condition'=>'publish = :publish AND dependency = :dependency',
								'params'=>array(
									':publish'=>1,
									':dependency'=>$val,
								),
							));
							if($catSub != null) {
								foreach($catSub as $item) {
									if(!in_array($item->cat_id, $catArray))
										$catArray[] = $item->cat_id;
								}
							}
						}
					}
				}
			}
			$criteria->compare('t.publish', 1);
			$criteria->addInCondition('t.cat_id', $catArray);
			$criteria->compare('date(t.published_date) <', $now);
			$criteria->order = 't.published_date DESC, t.article_id DESC';
			
			if($paging != null && $paging != '' && $paging == 'false') {
				$criteria->limit = $pagesize != null && $pagesize != '' ? $pagesize : 5;
				$model = Articles::model()->findAll($criteria);
			} else {
				$dataProvider = new CActiveDataProvider('Articles', array(
					'criteria'=>$criteria,
					'pagination'=>array(
						'pageSize'=>$pagesize != null && $pagesize != '' ? $pagesize : 20,
					),
				));
				$model = $dataProvider->getData();
			}
			
			if(!empty($model)) {
				foreach($model as $key => $item) {
					$article_url = Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->request->baseUrl;
					$article_path = 'public/article/'.$item->article_id;
							
					$medias = $item->medias;
					if(!empty($medias)) {
						$media = $item->view->media_cover ? $item->view->media_cover : $medias[0]->media;
						if(file_exists($article_path.'/'.$media))
							$media_image = $article_url.'/'.$article_path.'/'.$media;
					}					
					if($item->media_file != '' && file_exists($article_path.'/'.$item->media_file))
						$media_file = $article_url.'/'.$article_path.'/'.$item->media_file;
					
					$data[] = array(
						'id'=>$item->article_id,
						'category'=>Phrase::trans($item->cat->name),
						'title'=>ucwords(strtolower($item->title)),
						'intro'=>$item->body != '' ? Utility::shortText(Utility::hardDecode($item->body),200) : '-',
						'media_image'=>!empty($medias) ? $media_image : '-',
						'media_file'=>$item->media_file != '' ? $media_file : '-',
						'view'=>$item->view->views,
						'likes'=>$item->view->likes,
						'download'=>$item->view->downloads,
						'published_date'=>Utility::dateFormat($item->published_date),
						'share'=>Articles::getShareUrl($item->article_id, $item->title),
					);					
				}
			} else
				$data = array();
			
			if($paging != null && $paging != '' && $paging == 'false')
				$this->_sendResponse(200, CJSON::encode($this->renderJson($data)));
				
			else {
				$pager = OFunction::getDataProviderPager($dataProvider);
				$get = array_merge($_GET, array($pager['pageVar']=>$pager['nextPage']));
				$nextPager = $pager['nextPage'] != 0 ? OFunction::validHostURL(Yii::app()->controller->createUrl('list', $get)) : '-';
				$return = array(
					'data' => $data,
					'pager' => $pager,
					'nextPager' => $nextPager,
				);
				$this->_sendResponse(200, CJSON::encode($this->renderJson($return)));					
			}
			
		} else 
			$this->redirect(Yii::app()->createUrl('site/index'));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionDetail() 
	{
		if(Yii::app()->request->isPostRequest) {
			$id = trim($_POST['id']);
			
			$model = Articles::model()->findByPk($id);
			
			if($model != null) {
				$article_url = Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->request->baseUrl;
				$article_path = 'public/article/'.$model->article_id;
							
				$medias = $model->medias;
				if(!empty($medias)) {
					$media = $model->view->media_cover ? $model->view->media_cover : $medias[0]->media;
					if(file_exists($article_path.'/'.$media))
						$media_image = $article_url.'/'.$article_path.'/'.$media;
				}				
				if($model->media_file != '' && file_exists($article_path.'/'.$model->media_file))
					$media_file = $article_url.'/'.$article_path.'/'.$model->media_file;
				
				$return = array(
					'success'=>'1',
					'id'=>$model->article_id,
					'category'=>Phrase::trans($model->cat->name),
					'title'=>ucwords(strtolower($model->title)),
					'body'=>Utility::softDecode($model->body),
					'media_image'=>!empty($medias) ? $media_image : '-',
					'media_file'=>$model->media_file != '' ? $media_file : '-',
					'view'=>$model->view->views,
					'likes'=>$model->view->likes,
					'download'=>$model->view->downloads,
					'published_date'=>Utility::dateFormat($model->published_date),
					'share'=>Articles::getShareUrl($model->article_id, $model->title),
				);
				
			} else {
				$return = array(
					'success'=>'0',
					'error'=>'NULL',
					'message'=>Yii::t('phrase', 'error, article tidak ditemukan'),
				);
			}
			$this->_sendResponse(200, CJSON::encode($this->renderJson($return)));
			
		} else 
			$this->redirect(Yii::app()->createUrl('site/index'));
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
