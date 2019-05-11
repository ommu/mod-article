<?php
/**
 * LikeController
 * @var $this ommu\article\controllers\history\LikeController
 * @var $model ommu\article\models\ArticleLikeHistory
 *
 * LikeController implements the CRUD actions for ArticleLikeHistory model.
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
 * @created date 20 October 2017, 10:59 WIB
 * @link https://github.com/ommu/mod-article
 *
 */
 
namespace ommu\article\controllers\history;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\article\models\ArticleLikeHistory;
use ommu\article\models\search\ArticleLikeHistory as ArticleLikeHistorySearch;

class LikeController extends Controller
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
	 * Lists all ArticleLikeHistory models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new ArticleLikeHistorySearch();
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

		$this->view->title = Yii::t('app', 'Article Like Histories');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new ArticleLikeHistory model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	
	
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish'])) {
			//return $this->redirect(['view', 'id' => $model->id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'Article Like History success deleted.'));
			return $this->redirect(['manage']);
		}
	}

	/**
	 * actionPublish an existing ArticleLikeHistory model.
	 * If publish/unpublish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

		if($model->save(false, ['publish'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Article Like History success updated.'));
			return $this->redirect(['manage']);
		}
	}

	/**
	 * Finds the ArticleLikeHistory model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArticleLikeHistory the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = ArticleLikeHistory::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
