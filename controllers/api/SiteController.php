<?php
/**
 * SiteController
 * @var $this SiteController
 * @var $model Articles
 * @var $form CActiveForm
 *
 * Reference start
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
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 23 Juni 2016, 14:46 WIB
 * @link https://github.com/ommu/mod-article
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
	 * Initialize public template
	 */
	public function init() 
	{
		$arrThemes = $this->currentTemplate('public');
		Yii::app()->theme = $arrThemes['folder'];
		$this->layout = $arrThemes['layout'];
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
			$cat_ignore = trim($_POST['cat_ignore']);
			$category = trim($_POST['category']);
			$pagesize = trim($_POST['pagesize']);
			
			$catIgnore = array($cat_ignore);
			$catParent = 0;
			if($category) {
				if($cat_ignore && in_array($category, $catIgnore))
					$this->redirect(Yii::app()->createUrl('site/index'));
				
				$cat = ArticleCategory::model()->findByPk($category, array(
					'select' => 'cat_id, parent',
				));
				if($cat->parent == 0)
					$catParent = $category;
				else
					$this->redirect(Yii::app()->createUrl('site/index'));
			}
		
			$criteria=new CDbCriteria;
			$criteria->select = array('cat_id','name');
			if($cat_ignore)
				$criteria->addNotInCondition('t.cat_id', $catIgnore);
			$criteria->compare('t.publish', 1);
			$criteria->compare('t.parent', $catParent);
			
			$categoryFind = ArticleCategory::model()->findAll($criteria);
			
			if($categoryFind != null) {
				$return = '';
				foreach($categoryFind as $key => $val) {
					$criteriaArticle=new CDbCriteria;
					$criteriaArticle->condition = 't.publish = :publish AND t.published_date <= curdate()';
					$criteriaArticle->params = array(
						':publish' => 1,
					);
					if($category)
						$criteriaArticle->compare('t.cat_id', $val->cat_id);						
					else {
						$subCategoryFind = ArticleCategory::model()->findAll(array(
							'condition' => 'publish = :publish AND parent_id = :parent',
							'params' => array(
								':publish' => 1,
								':parent' => $val->cat_id,
							),
						));
						$subCategoryData = array();
						if($subCategoryFind != null) {
							foreach($subCategoryFind as $row)
								$subCategoryData[] = $row->cat_id;
						}
						$criteriaArticle->addInCondition('t.cat_id', $subCategoryData);
					}
					$criteriaArticle->limit = $pagesize ? $pagesize : 4;
					$criteriaArticle->order = 't.published_date DESC, t.article_id DESC';
			
					$article = Articles::model()->findAll($criteriaArticle);
		
					$data = '';
					if(!empty($article)) {
						foreach($article as $key => $item) {
							$article_url = Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->request->baseUrl;
							$article_path = 'public/article/'.$item->article_id;
							
							$medias = $item->medias;
							if(!empty($medias)) {
								$article_cover = $item->view->article_cover ? $item->view->article_cover : $medias[0]->cover_filename;
								if($article_cover && file_exists($article_path.'/'.$article_cover))
									$cover_url_path = $article_url.'/'.$article_path.'/'.$article_cover;
							}
							
							$data[] = array(
								'id' => $item->article_id,
								'category' => $item->category->title->message,
								'title' => $item->title,
								'intro' => $item->body != '' ? Utility::shortText(Utility::hardDecode($item->body),200) : '-',
								'media_image' => $cover_url_path ? $cover_url_path : '-',
								'view' => $item->view->views ? $item->view->views : 0,
								'likes' => $item->view->likes ? $item->view->likes : 0,
								'download' => $item->view->downloads ? $item->view->downloads : 0,
								'published_date' => strtotime($item->published_date),
								'creation_date' => strtotime($item->creation_date),
								'share' => Articles::getShareUrl($item->article_id, $item->slug),
							);
						}
					} else
						$data = array();
				
					$categoryTitle = $val->title->message;
					$return[] = array(
						'id' => $val->cat_id,
						'category' => $categoryTitle,
						//'count' => $articleCount,
						'category_slug' => $val->slug ? $val->slug : $this->urlTitle($categoryTitle),
						'data' => $data,
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
			/*
			$criteria->with = array(
				'tag_ONE' => array(
					'alias' => 'a',
				),
				'tag_ONE.tag_TO' => array(
					'alias' => 'b',
				),
			);
			if($tag) {
				$criteria->condition = 'b.body = :body';
				$criteria->params = array(
					':body' => $tag,
				);
			}
			*/
			
			$catData = array();
			if($category) {
				$categoryExplode = explode(',', $category);
				foreach($categoryExplode as $val) {
					$categoryFind = ArticleCategory::model()->findByPk($val, array(
						'select' => 'publish, parent',
					));
					if($categoryFind != null && $categoryFind->publish == 1) {
						if($categoryFind->parent != 0) {
							if(!in_array($val, $catData))
								$catData[] = $val;
						} else {
							$subCategoryFind = ArticleCategory::model()->findAll(array(
								'condition' => 'publish = :publish AND parent_id = :parent',
								'params' => array(
									':publish' => 1,
									':parent' => $val,
								),
							));
							if($subCategoryFind != null) {
								foreach($subCategoryFind as $row) {
									if(!in_array($row->cat_id, $catData))
										$catData[] = $row->cat_id;
								}
							}
						}
					}
				}
			}
			$criteria->condition = 't.publish = :publish AND t.published_date <= curdate()';
			$criteria->params = array(
				':publish' => 1,
			);
			$criteria->addInCondition('t.cat_id', $catData);
			$criteria->order = 't.published_date DESC, t.article_id DESC';
			
			if($paging && $paging == 'true') {
				$dataProvider = new CActiveDataProvider('Articles', array(
					'criteria' => $criteria,
					'pagination' => array(
						'pageSize' => $pagesize ? $pagesize : 20,
					),
				));
				$model = $dataProvider->getData();
			} else {
				$criteria->limit = $pagesize ? $pagesize : 5;
				$model = Articles::model()->findAll($criteria);
			}
			
			if(!empty($model)) {
				foreach($model as $key => $item) {
					$article_url = Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->request->baseUrl;
					$article_path = 'public/article/'.$item->article_id;
							
					$medias = $item->medias;
					if(!empty($medias)) {
						$article_cover = $item->view->article_cover ? $item->view->article_cover : $medias[0]->cover_filename;
						if($article_cover && file_exists($article_path.'/'.$article_cover))
							$cover_url_path = $article_url.'/'.$article_path.'/'.$article_cover;
					}
					if($item->media_file && file_exists($article_path.'/'.$item->media_file))
						$file_url_path = $article_url.'/'.$article_path.'/'.$item->media_file;
					
					$data[] = array(
						'id' => $item->article_id,
						'category' => $item->category->title->message,
						'title' => $item->title,
						'intro' => $item->body != '' ? Utility::shortText(Utility::hardDecode($item->body),200) : '-',
						'media_image' => $cover_url_path ? $cover_url_path : '-',
						'media_file' => $file_url_path ? $file_url_path : '-',
						'view' => $item->view->views ? $item->view->views : 0,
						'likes' => $item->view->likes ? $item->view->likes : 0,
						'download' => $item->view->downloads ? $item->view->downloads : 0,
						'published_date' => strtotime($item->published_date),
						'creation_date' => strtotime($item->creation_date),
						'share' => Articles::getShareUrl($item->article_id, $item->slug),
					);					
				}
			} else
				$data = array();
			
			if($paging && $paging == 'true') {
				$pager = OFunction::getDataProviderPager($dataProvider);
				$get = array_merge($_GET, array($pager['pageVar'] => $pager['nextPage']));
				$nextPager = $pager['nextPage'] != 0 ? OFunction::validHostURL(Yii::app()->controller->createUrl('list', $get)) : '-';
				$return = array(
					'data' => $data,
					'pager' => $pager,
					'nextPager' => $nextPager,
				);
				$this->_sendResponse(200, CJSON::encode($this->renderJson($return)));
				
			} else
				$this->_sendResponse(200, CJSON::encode($this->renderJson($data)));
			
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
					$article_cover = $model->view->article_cover ? $model->view->article_cover : $medias[0]->cover_filename;
					if($article_cover && file_exists($article_path.'/'.$article_cover))
						$cover_url_path = $article_url.'/'.$article_path.'/'.$article_cover;
				}
				if($model->media_file && file_exists($article_path.'/'.$model->media_file))
					$file_url_path = $article_url.'/'.$article_path.'/'.$model->media_file;
				
				$return = array(
					'success' => '1',
					'id' => $model->article_id,
					'category' => $model->category->title->message,
					'title' => $model->title,
					'body' => Utility::softDecode($model->body),
					'media_image' => $cover_url_path ? $cover_url_path : '-',
					'media_file' => $file_url_path ? $file_url_path : '-',
					'view' => $model->view->views ? $model->view->views : 0,
					'likes' => $model->view->likes ? $model->view->likes : 0,
					'download' => $model->view->downloads ? $model->view->downloads : 0,
					'published_date' => strtotime($model->published_date),
					'creation_date' => strtotime($model->creation_date),
					'share' => Articles::getShareUrl($model->article_id, $model->slug),
				);
				
			} else {
				$return = array(
					'success' => '0',
					'error' => 'NULL',
					'message' => Yii::t('phrase', 'error, article tidak ditemukan'),
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
