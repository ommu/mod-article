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
 *	RunAction
 *	Publish
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 11:09 WIB
 * @link https://github.com/ommu/mod-article
 *
 */
 
namespace ommu\article\controllers\o;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\helpers\Url;
use ommu\article\models\ArticleFiles;
use ommu\article\models\ArticleDownloads;
use ommu\article\models\search\ArticleFile;
use yii\web\UploadedFile;

class FileController extends Controller
{
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
					'publish' => ['POST'],
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
		$searchModel = new ArticleFile();
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

		$this->view->title = Yii::t('app', 'Article Files');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new ArticleFiles model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new ArticleFiles();
		$model->scenario = 'formCreate';

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->file_filename = UploadedFile::getInstance($model, 'file_filename');

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'file success created.'));	
				
				if (Yii::$app->request->get('article')) {
					return Yii::$app->response->redirect(Url::to(['index', 'article' => Yii::$app->request->get('article')]));
				}

				return $this->redirect(['manage']);
			}
		}
			$this->view->title = Yii::t('app', 'Create Article Files');
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

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->file_filename = UploadedFile::getInstance($model, 'file_filename');
			if(!($model->file_filename instanceof UploadedFile)) {
				$model->file_filename = $model->old_file_filename_i;
			}

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'File success updated.'));
				if (Yii::$app->request->get('article')) {
					return Yii::$app->response->redirect(Url::to(['index', 'article' => Yii::$app->request->get('article')]));
				}
				return $this->redirect(['manage']);
			}
		}
			$this->view->title = Yii::t('app', 'Update Article Files: {file_filename}', ['file_filename' => $model->file_filename]);
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

		$this->view->title = Yii::t('app', 'View Article Files: {file_filename}', ['file_filename' => $model->file_filename]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_view', [
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
			if (Yii::$app->request->get('article')) {
					return Yii::$app->controller->redirect(Url::to(['index', 'article' => Yii::$app->request->get('article')]));
				}
			//return $this->redirect(['view', 'id' => $model->file_id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'Article Files success deleted.'));
			
			
			return $this->redirect(['manage']);

		}

	}

	/**
	 * actionPublish an existing ArticleFiles model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Article Files success updated.'));
			return $this->redirect(['manage']);
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
}
