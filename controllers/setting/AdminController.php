<?php
/**
 * SettingController
 * @var $this ommu\article\controllers\setting\AdminController
 * @var $model ommu\article\models\ArticleSetting
 *
 * SettingController implements the CRUD actions for ArticleSetting model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	Update
 *	View
 *	Delete
 *	Headline
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 09:34 WIB
 * @link https://github.com/ommu/mod-article
 *
 */
 
namespace ommu\article\controllers\setting;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\article\models\ArticleSetting;
use ommu\article\models\search\ArticleCategory as ArticleCategorySearch;
use ommu\article\models\search\ArticleSetting as ArticleSettingSearch;

class SettingController extends Controller
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
					'headline' => ['POST'],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		return $this->redirect(['update']);
	}

	public function actionUpdate()
	{
		$searchModel = new ArticleCategorySearch();
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

		$model = ArticleSetting::findOne(1);
		if ($model === null)
			$model = new ArticleSetting();

		if($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Article Setting success updated.'));
			return $this->redirect(['update']);
		}

		$this->view->title = Yii::t('app', 'Article Setting');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	protected function findModel($id)
	{
		if(($model = ArticleSetting::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
