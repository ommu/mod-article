<?php
/**
 * SyncController
 * @var $this SyncController
 * @var $model Articles
 * @var $form CActiveForm
 * version: 0.0.1
 * Reference start
 *
 * TOC :
 *	Index
 *	IndexFile
 *	IndexImage
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2015 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Articles
 * @contect (+62)856-299-4114
 *
 *----------------------------------------------------------------------------------------------------------
 */

class SyncController extends Controller
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
			} else {
				$this->redirect(Yii::app()->createUrl('site/login'));
			}
		} else {
			$this->redirect(Yii::app()->createUrl('site/login'));
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
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('indexfile','indeximage'),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level) && in_array(Yii::app()->user->level, array(1,2))',
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
		$this->redirect(array('o/admin/manage'));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndexFile() 
	{
		ini_set('max_execution_time', 0);
		ob_start();
		
		$criteria=new CDbCriteria;
		/* $criteria->condition = 'media_file <> :media_file';
		$criteria->params = array(
			':media_file'=>'',
		); */
		$criteria->order = 'article_id DESC';
		$article = Articles::model()->findAll($criteria);
		
		$path = 'public/article';
		
		foreach($article as $key => $item) {
			// Add User Folder
			$article_path = $path.'/'.$item->article_id;
			if(!file_exists($article_path)) {
				@mkdir($article_path, 0755, true);

				// Add file in directory (index.php)
				$newFile = $article_path.'/index.php';
				$FileHandle = fopen($newFile, 'w');
			} else
				@chmod($article_path, 0755, true);
				
			$mediaFile = $path.'old/'.$item->media_file;
			if($item->media_file != '' && file_exists($mediaFile)) {
				rename($mediaFile, $article_path.'/'.$item->media_file);
			}
		}
		
		echo 'Ommu Done..';
		ob_end_flush();		
	}

	/**
	 * Manages all models.
	 */
	public function actionIndexImage() 
	{
		ini_set('max_execution_time', 0);
		ob_start();
		
		$criteria=new CDbCriteria;
		/* $criteria->condition = 'media_image <> :media_image';
		$criteria->params = array(
			':media_image'=>'',
		); */
		$criteria->order = 'article_id DESC';
		$article = Articles::model()->findAll($criteria);
		
		$path = 'public/article';
		
		foreach($article as $key => $item) {
			// Add User Folder
			$article_path = $path.'/'.$item->article_id;
			if(!file_exists($article_path)) {
				@mkdir($article_path, 0755, true);

				// Add file in directory (index.php)
				$newFile = $article_path.'/index.php';
				$FileHandle = fopen($newFile, 'w');
			} else
				@chmod($article_path, 0755, true);
				
			$mediaImages = $path.'old/thumbnail/'.$item->media_image;
			if(file_exists($mediaImages) && $item->media_id != 0 && $item->media_image != '') {
				file_put_contents('file.txt', $item->article_id);
				$images = new ArticleMedia;
				$images->article_id = $item->article_id;
				$images->cover = 1;
				$images->media = $item->media_image;
				if($images->save()) {				
					rename($mediaImages, $article_path.'/'.$item->media_image);					
				}
			}
		}
		
		echo 'Ommu Done..';
		ob_end_flush();		
	}
}
