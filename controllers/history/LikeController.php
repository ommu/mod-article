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
 *	View
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 10:59 WIB
 * @modified date 13 May 2019, 17:13 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\controllers\history;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\article\models\ArticleLikeHistory;
use ommu\article\models\search\ArticleLikeHistory as ArticleLikeHistorySearch;

class LikeController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();
		if(Yii::$app->request->get('like') || Yii::$app->request->get('id') || Yii::$app->request->get('article'))
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
	 * Lists all ArticleLikeHistory models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new ArticleLikeHistorySearch();
		if(($article = Yii::$app->request->get('article')) != null)
			$searchModel = new ArticleLikeHistorySearch(['articleId'=>$article]);
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

		if(($like = Yii::$app->request->get('like')) != null) {
			$like = \ommu\article\models\ArticleLikes::findOne($like);
			$this->subMenuParam = $like->article_id;
			$setting = $like->article->getSetting(['media_image_limit', 'media_file_limit']);
			if($like->article->category->single_photo || $setting->media_image_limit == 1)
				unset($this->subMenu['photo']);
			if($like->article->category->single_file || $setting->media_file_limit == 1)
				unset($this->subMenu['document']);
		}

		if($article) {
			$this->subMenuParam = $article;
			$article = \ommu\article\models\Articles::findOne($article);
			$setting = $article->getSetting(['media_image_limit', 'media_file_limit']);
			if($article->category->single_photo || $setting->media_image_limit == 1)
				unset($this->subMenu['photo']);
			if($article->category->single_file || $setting->media_file_limit == 1)
				unset($this->subMenu['document']);
		}

		$this->view->title = Yii::t('app', 'Like Histories');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'like' => $like,
			'article' => $article,
		]);
	}

	/**
	 * Displays a single ArticleLikeHistory model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		if(!Yii::$app->request->isAjax) {
			$this->subMenuParam = $model->like->article_id;
			$setting = $model->like->article->getSetting(['media_image_limit', 'media_file_limit']);

			if($model->like->article->category->single_photo || $setting->media_image_limit == 1)
				unset($this->subMenu['photo']);
			if($model->like->article->category->single_file || $setting->media_file_limit == 1)
				unset($this->subMenu['document']);
		}

		$this->view->title = Yii::t('app', 'Detail Like History: {like-id}', ['like-id' => $model->like->article->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ArticleLikeHistory model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Article like history success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'like'=>$model->like_id]);
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
