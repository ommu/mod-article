<?php
/**
 * ImageController
 * @var $this ommu\article\controllers\o\ImageController
 * @var $model ommu\article\models\ArticleMedia
 *
 * ImageController implements the CRUD actions for ArticleMedia model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	Update
 *	View
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 11:00 WIB
 * @modified date 17 May 2019, 11:45 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\controllers\o;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\article\models\ArticleMedia;
use ommu\article\models\search\ArticleMedia as ArticleMediaSearch;
use yii\web\UploadedFile;

class ImageController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();
		if(Yii::$app->request->get('id'))
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
	 * Lists all ArticleMedia models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new ArticleMediaSearch();
		if(($id = Yii::$app->request->get('id')) != null)
			$searchModel = new ArticleMediaSearch(['article_id'=>$id]);
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

		if(($article = Yii::$app->request->get('article')) != null)
			$article = \ommu\article\models\Articles::findOne($article);

		$this->view->title = Yii::t('app', 'Photos');
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
	 * Creates a new ArticleMedia model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		if(($id = Yii::$app->request->get('id')) == null)
			throw new \yii\web\NotAcceptableHttpException(Yii::t('app', 'The requested page does not exist.'));

		$model = new ArticleMedia(['article_id'=>$id]);
		$setting = $model->article->getSetting(['media_image_limit', 'media_file_limit']);

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			$model->media_filename = UploadedFile::getInstance($model, 'media_filename');

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Article photo success created.'));
				if($model->redirectUpdate)
					return $this->redirect(['update', 'id'=>$model->id]);
				return $this->redirect(['manage', 'id'=>$model->article_id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		if($model->article->category->single_photo || $setting->media_image_limit == 1)
			unset($this->subMenu['photo']);
		if($model->article->category->single_file || $setting->media_file_limit == 1)
			unset($this->subMenu['document']);

		$this->view->title = Yii::t('app', 'Create Photo');
		if($id)
			$this->view->title = Yii::t('app', 'Create Photo Article: {title}', ['title' => $model->article->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing ArticleMedia model.
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
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			$model->media_filename = UploadedFile::getInstance($model, 'media_filename');

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Article photo success updated.'));
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

		$this->view->title = Yii::t('app', 'Update Photo: {media-filename}', ['media-filename' => $model->media_filename]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single ArticleMedia model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);
		$this->subMenuParam = $model->article_id;
		$setting = $model->article->getSetting(['media_image_limit', 'media_file_limit']);

		if($model->article->category->single_photo || $setting->media_image_limit == 1)
			unset($this->subMenu['photo']);
		if($model->article->category->single_file || $setting->media_file_limit == 1)
			unset($this->subMenu['document']);

		$this->view->title = Yii::t('app', 'Detail Photo: {media-filename}', ['media-filename' => $model->media_filename]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ArticleMedia model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Article photo success deleted.'));
			return $this->redirect(['manage', 'id'=>$model->article_id]);
		}
	}

	/**
	 * Finds the ArticleMedia model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArticleMedia the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = ArticleMedia::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
