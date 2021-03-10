<?php
/**
 * SiteController
 * @var $this ommu\article\controllers\SiteController
 * @var $model ommu\article\models\Articles
 *
 * SiteController implements the CRUD actions for Articles model.
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
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @contact (+62) 857-297-29382
 * @author Agus Susilo <smartgdi@gmail.com>
 * @created date 6 November 2017, 13:54 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use ommu\article\models\Articles;
use ommu\article\models\search\Articles as ArticlesSearch;
use ommu\article\models\search\ArticleMedia as ArticleMediaSearch;
use ommu\article\models\search\ArticleFiles as ArticleFilesSearch;
use ommu\article\models\search\ArticleViews as ArticleViewsSearch;
use ommu\article\models\ArticleMedia as ArticleMediaModel;
use ommu\article\models\ArticleFiles as ArticleFilesModel;
use ommu\article\models\ArticleViews as ArticleViewsModel;
use ommu\article\models\ArticleViews;
use ommu\article\models\ArticleDownloads;



class SiteController extends Controller
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
        if ($gridColumn != null && count($gridColumn) > 0) {
            foreach ($gridColumn as $key => $val) {
                if ($gridColumn[$key] == 1) {
                    $cols[] = $key;
                }
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

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Articles success created.'));
                return $this->redirect(['manage']);
                //return $this->redirect(['view', 'id' => $model->article_id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
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
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Articles success updated.'));
                return $this->redirect(['manage']);
                //return $this->redirect(['view', 'id' => $model->article_id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Update Articles: {title}', ['title' => $model->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}



	/**
	 * Displays a single Articles model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		//menampilkan data media
		$searchModel = new ArticleMediaSearch();
		$query = ArticleMediaModel::find()->where(['t.article_id' => $id,'t.publish' => 1])->alias('t');
		$query->joinWith(['article article']);
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
        $gridColumn = Yii::$app->request->get('GridColumn', null);
        $cols = [];
        if ($gridColumn != null && count($gridColumn) > 0) {
            foreach ($gridColumn as $key => $val) {
                if ($gridColumn[$key] == 1) {
                    $cols[] = $key;
                }
            }
        }
        $columns = $searchModel->getGridColumn($cols);
		//menampilkan data file
		$searchModel1 = new ArticleFilesSearch();
		$query1 = ArticleFilesModel::find()->where(['t.article_id' => $id,'t.publish' => 1])->alias('t');
		$query1->joinWith(['article article']);
		$dataProvider1 = new ActiveDataProvider([
			'query' => $query1,
		]);
		$gridColumn1 = Yii::$app->request->get('GridColumn1', null);
		$cols1 = [];
        if ($gridColumn1 != null && count($gridColumn1) > 0) {
            foreach ($gridColumn1 as $key => $val) {
                if ($gridColumn1[$key] == 1) {
                    $cols1[] = $key;
                }
            }
        }
		$columns1 = $searchModel1->getGridColumn($cols1);

		//view detail
		$model = $this->findModel($id);
		//related article
		$dataProvider2 = new ActiveDataProvider([
			'query' => Articles::find()->where(['cat_id' => $model->cat_id])->limit(5),
			'pagination' => false,
		]);
		
		$this->view->title = Yii::t('app', 'View Articles: {title}', ['title' => $model->title]);
		$this->view->description = '';
		$this->view->keywords = '';

		//fungsi insertviews
		$article = new ArticleViews();
		$articleView = $article->insertView($model->article_id);

		return $this->render('admin_view', [
			'model' => $model,
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'searchModel1' => $searchModel1,
			'dataProvider1' => $dataProvider1,
			'columns1' => $columns1,
			'dataProvider2' => $dataProvider2,

		]);
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

        if ($model->save(false, ['publish', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Articles success updated.'));
			return $this->redirect(['manage']);
		}
	}

	//fungsi untuk download file
	public function actionDownload($id)
	{
		$file = ArticleFilesModel::findOne($id);
		$file_filename = $file->file_filename;
		$root = Yii::getAlias('@webroot').'/public/article/file/'.$file_filename;
        if (file_exists($root)) {
			$download = new ArticleDownloads();
			$fileDownload = $download->insertDownload($file->file_id);
			return Yii::$app->response->sendFile($root);
			
		} else {
			throw new \yii\web\NotFoundHttpException("{$file_filename} is not found!");
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
        if (($model = Articles::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
