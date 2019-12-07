<?php
/**
 * FileController
 * @var $this ommu\article\controllers\o\FileController
 * @var $model ommu\article\models\ArticleFiles
 *
 * FileController implements the CRUD actions for ArticleFiles model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	Update
 *	View
 *	Delete
 *	Upload
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 11:09 WIB
 * @modified date 17 May 2019, 11:45 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\controllers\o;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\article\models\ArticleFiles;
use ommu\article\models\search\ArticleFiles as ArticleFilesSearch;
use yii\web\UploadedFile;
use ommu\article\models\Articles;
use yii\web\HttpException;
use thamtech\uuid\helpers\UuidHelper;
use yii\helpers\Json;

class FileController extends Controller
{
	use \ommu\traits\FileTrait;

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();
		if(Yii::$app->request->get('id') || Yii::$app->request->get('article'))
			$this->subMenu = $this->module->params['article_submenu'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
					'upload' => ['POST'],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		return $this->redirect(['manage']);
	}

	/**
	 * Lists all ArticleFiles models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new ArticleFilesSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$gridColumn = Yii::$app->request->get('GridColumn', null);
		$cols = [];
		if($gridColumn != null && count($gridColumn) > 0) {
			foreach($gridColumn as $key => $val) {
				if($gridColumn[$key] == 1)
					$cols[] = $key;
			}
		}
		$columns = $searchModel->getGridColumn($cols);

		if(($article = Yii::$app->request->get('article')) != null) {
			$this->subMenuParam = $article;
			$article = \ommu\article\models\Articles::findOne($article);
			$setting = $article->getSetting(['media_image_limit', 'media_file_limit']);
			if($article->category->single_photo || $setting->media_image_limit == 1)
				unset($this->subMenu['photo']);
			if($article->category->single_file || $setting->media_file_limit == 1)
				unset($this->subMenu['document']);
		}

		$this->view->title = Yii::t('app', 'Documents');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'article' => $article,
		]);
	}

	/**
	 * Creates a new ArticleFiles model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		if(($id = Yii::$app->request->get('id')) == null)
			throw new \yii\web\NotAcceptableHttpException(Yii::t('app', 'The requested page does not exist.'));

		$model = new ArticleFiles(['article_id'=>$id]);
		$setting = $model->article->getSetting(['media_image_limit', 'media_file_limit']);

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->file_filename = UploadedFile::getInstance($model, 'file_filename');
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Article document success created.'));
				if($model->redirectUpdate)
					return $this->redirect(['update', 'id'=>$model->id]);
				return $this->redirect(['manage', 'article'=>$model->article_id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		if($model->article->category->single_photo || $setting->media_image_limit == 1)
			unset($this->subMenu['photo']);
		if($model->article->category->single_file || $setting->media_file_limit == 1)
			unset($this->subMenu['document']);

		$this->view->title = Yii::t('app', 'Create Document');
		if($id)
			$this->view->title = Yii::t('app', 'Create Document Article: {title}', ['title' => $model->article->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing ArticleFiles model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$this->subMenuParam = $model->article_id;
		$setting = $model->article->getSetting(['media_image_limit', 'media_file_limit']);

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->file_filename = UploadedFile::getInstance($model, 'file_filename');
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Article document success updated.'));
				return $this->redirect(['update', 'id'=>$model->id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		if($model->article->category->single_photo || $setting->media_image_limit == 1)
			unset($this->subMenu['photo']);
		if($model->article->category->single_file || $setting->media_file_limit == 1)
			unset($this->subMenu['document']);

		$this->view->title = Yii::t('app', 'Update Document: {file-filename}', ['file-filename' => $model->file_filename]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single ArticleFiles model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		if(!Yii::$app->request->isAjax) {
			$this->subMenuParam = $model->article_id;
			$setting = $model->article->getSetting(['media_image_limit', 'media_file_limit']);

			if($model->article->category->single_photo || $setting->media_image_limit == 1)
				unset($this->subMenu['photo']);
			if($model->article->category->single_file || $setting->media_file_limit == 1)
				unset($this->subMenu['document']);
		}

		$this->view->title = Yii::t('app', 'Detail Document: {file-filename}', ['file-filename' => $model->file_filename]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ArticleFiles model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Article document success deleted.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'article'=>$model->article_id]);
		}
	}

	/**
	 * Finds the ArticleFiles model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArticleFiles the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = ArticleFiles::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionUpload()
	{
		// Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		if(($id = Yii::$app->request->get('id')) == null)
			throw new \yii\web\NotAcceptableHttpException(Yii::t('app', 'The requested page does not exist.'));

		$model = new ArticleFiles(['article_id'=>$id]);
		$setting = $model->article->getSetting(['media_file_limit', 'media_file_type']);

		$uploadPath = join('/', [Articles::getUploadPath(), $id]);

		if(Yii::$app->request->isPost) {
			$fileFilename = UploadedFile::getInstanceByName('file_filename');
			if ($fileFilename->getHasError())
				throw new HttpException(500, Yii::t('app', 'Upload error'));

			if(($model->article->category->single_file && !empty($model->article->files)) || (!$model->article->category->single_file && count($model->article->files) >= $setting->media_file_limit))
				throw new HttpException(500, Yii::t('app', 'Document limited uploaded'));

			$fileFileType = $this->formatFileType($setting->media_file_type);
			if(!in_array(strtolower($fileFilename->getExtension()), $fileFileType)) {
				throw new HttpException(500, Yii::t('app', 'This document cannot be uploaded. Only files with these extensions are allowed: {extensions}', [
					'extensions'=>$this->formatFileType($fileFileType, false),
				]));
			}

			$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($fileFilename->getExtension());
			if($fileFilename->saveAs(join('/', [$uploadPath, $fileName])))
				$model->file_filename = $fileName;

			if($model->save()) {
				$response = [
					'filename' => $fileName,
				];

				return Json::encode($response);

			} else {
				if(Yii::$app->request->isAjax)
					return Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}
	}
}
