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
 *	List
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 23 Juni 2016, 14:46 WIB
 * @link https://github.com/oMMu/Ommu-Articles
 * @contect (+62)856-299-4114
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
				'actions'=>array('index','list'),
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
				$cat = ArticleCategory::model()->findByPk($category, array(
					'select' => 'publish, dependency',
				));
				if($cat->dependency != 0)
					$criteria->compare('t.cat_id', $category);
				else {
					$catSub = ArticleCategory::model()->findAll(array(
						'condition'=>'publish = :publish AND dependency = :dependency',
						'params'=>array(
							':dependency'=>$category,
							':publish'=>1,
						),
					));
					$catData = array();
					if($catSub != null) {
						foreach($catSub as $val)
							$catData[] = $val->cat_id;
					}
					$criteria->addInCondition('t.cat_id', $catData);
				}				
			}
			$criteria->compare('t.publish', 1);
			$criteria->compare('date(t.published_date) <', $now);
			$criteria->order = 't.published_date DESC, t.article_id DESC';
			
			if($paging != null && $paging != '' && $paging == 'true') {
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
					$article_url = Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->request->baseUrl.'/';
					$article_path = 'public/article/'.$item->article_id;
					
					if($item->media_id != 0 && file_exists($article_path.'/'.$item->cover->media))
						$media_image = $article_url.$article_path.'/'.$item->cover->media;
					if($item->media_file != '' && file_exists($article_path.'/'.$val->media_file))
						$media_file = $article_url.$article_path.'/'.$item->media_file;
					
					$data[] = array(
						'id'=>$item->article_id,
						'category'=>Phrase::trans($item->cat->name, 2),
						'title'=>$item->title,
						'intro'=>Utility::shortText(Utility::hardDecode($item->body),200),
						'media_image'=>$item->media_id != 0 ? $media_image : '-',
						'media_file'=>$item->media_file != '' ? $media_file : '-',
						'published_date'=>Utility::dateFormat($item->published_date, true),
						'view'=>$item->view,
						'likes'=>$item->likes,
						'download'=>$item->download,
						'creation_date'=>Utility::dateFormat($item->creation_date, true),
					);					
				}
			} else
				$data = array();
			
			if($paging != null && $paging != '' && $paging == 'true')
				$this->_sendResponse(200, CJSON::encode($this->renderJson($data)));
			
			else {		
				$pager = OFunction::getDataProviderPager($dataProvider);
				$get = array_merge($_GET, array($pager['pageVar']=>$pager['nextPage']));
				$nextPager = $pager['nextPage'] != 0 ? OFunction::validHostURL(Yii::app()->controller->createUrl('search', $get)) : '-';
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
