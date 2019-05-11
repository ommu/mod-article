<?php
/**
 * AdminController
 * @var $this ommu\article\controllers\AdminController
 * @var $model ommu\article\models\Articles
 *
 * AdminController implements the CRUD actions for Articles model.
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
 *	Headline
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 09:33 WIB
 * @link https://github.com/ommu/mod-article
 *
 */
 
namespace ommu\article\controllers;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\article\models\Articles;
use ommu\article\models\ArticleMedia;
use ommu\article\models\ArticleFile;
use ommu\article\models\search\Articles as ArticlesSearch;
use ommu\article\models\search\ArticleMedia as ArticleMediaSearch;
use ommu\article\models\search\ArticleFile as ArticleFileSearch;
use ommu\article\models\ArticleSetting;

class AdminController extends Controller
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
		return $this->redirect(['manage']);
	}

	/**
	 * Lists all Articles models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new ArticlesSearch();
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

		$this->view->title = Yii::t('app', 'Articles');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new Articles model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Articles();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Articles success created.'));
				return $this->redirect(['manage']);
				//return $this->redirect(['view', 'id'=>$model->article_id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Create Articles');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Articles model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($article)
	{
		//menampilkan data media
		$searchModel = new ArticleMediaSearch();
	
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

		//menampilkan data file
		$searchModel1 = new ArticleFileSearch();
		$dataProvider1 = $searchModel1->search(Yii::$app->request->queryParams);

		$gridColumn1 = Yii::$app->request->get('GridColumn1', null);
		$cols1 = [];
		if($gridColumn1 != null && count($gridColumn1) > 0) {
			foreach($gridColumn1 as $key => $val) {
				if($gridColumn1[$key] == 1)
					$cols1[] = $key;
			}
		}
		$columns1 = $searchModel1->getGridColumn($cols1);
		
		//menampilkan data update article
		$headline = Articles::find()->where(['publish'=>1,'headline'=>1])->all();
		$headline1 = Articles::find()->where(['publish'=>1,'headline'=>1])->orderBy(['headline_date'=> SORT_ASC])->limit(1)->one();
		$count=count($headline);
		$model = $this->findModel($article);
		if(Yii::$app->request->isPost) {
			//if ($model->load(Yii::$app->request->post())&&$model->save() ) {
			if ($model->load(Yii::$app->request->post())) {	
				if ($count>=$this->getSetting()){
					$headline1->headline=0;
					$headline1->save();	
				}
				$headline1->save();	
				$model->save();
				//return $this->redirect(['view', 'id' => $model->article_id]);
				Yii::$app->session->setFlash('success', Yii::t('app', 'Articles success updated.'));
				return $this->redirect(['manage']);

			}
		}

		$this->view->title = Yii::t('app', 'Update Articles: {title}', ['title' => $model->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'searchModel1' => $searchModel1,
			'dataProvider1' => $dataProvider1,
			'columns1' => $columns1,
		]);
	}

	/**
	 * Displays a single Articles model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View Articles: {title}', ['title' => $model->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Articles model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish','modified_id'])) {
			//return $this->redirect(['view', 'id' => $model->article_id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'Articles success deleted.'));
			return $this->redirect(['manage']);
		}
	}

	/**
	 * actionPublish an existing Articles model.
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
			Yii::$app->session->setFlash('success', Yii::t('app', 'Articles success updated.'));
			return $this->redirect(['manage']);
		}
	}

	//fungsi untuk mengambil setting headline limit
	public static function getSetting()
	{
		$setting = ArticleSetting::find()->limit(1)->one();
		return $setting->headline_limit;
	}

	/**
	 * Headline an existing Articles model.
	 * If headline is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */

	
	public function actionHeadline($id)
	{
		$model = $this->findModel($id);
		$headline = Articles::find()->where(['publish'=>1,'headline'=>1])->all();
		$headline1 = Articles::find()->where(['publish'=>1,'headline'=>1])->orderBy(['headline_date'=> SORT_ASC])->limit(1)->one();
		$count=count($headline);
		
		if ($count>=$this->getSetting())
		{
			$headline1->headline=0;
			$headline1->save();
			$model->headline = 1;	
		}
		else 
		{
			$model->headline = 1;
			
		}
		$model->publish = 1;
		if ($model->save(false, ['publish', 'headline'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Articles success updated.'));
			return $this->redirect(['manage']);
		}
	}

	

	/**
	 * Finds the Articles model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Articles the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = Articles::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
