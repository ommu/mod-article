<?php
/**
 * LikeController
 * @var $this ommu\article\controllers\o\LikeController
 * @var $model ommu\article\models\ArticleLikes
 *
 * LikeController implements the CRUD actions for ArticleLikes model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 11:11 WIB
 * @modified date 13 May 2019, 17:13 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\controllers\o;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\article\models\ArticleLikes;
use ommu\article\models\search\ArticleLikes as ArticleLikesSearch;

class LikeController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('id') || Yii::$app->request->get('article')) {
            $this->subMenu = $this->module->params['article_submenu'];
        }
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
	 * Lists all ArticleLikes models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArticleLikesSearch();
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

        if (($article = Yii::$app->request->get('article')) != null) {
            $this->subMenuParam = $article;
			$article = \ommu\article\models\Articles::findOne($article);
			$setting = $article->getSetting(['media_image_limit', 'media_file_limit']);
            if ($article->category->single_photo || $setting->media_image_limit == 1) {
                unset($this->subMenu['photo']);
            }
            if ($article->category->single_file || $setting->media_file_limit == 1) {
                unset($this->subMenu['document']);
            }
        }
        if (($user = Yii::$app->request->get('user')) != null) {
            $user = \app\models\Users::findOne($user);
        }

		$this->view->title = Yii::t('app', 'Likes');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'article' => $article,
			'user' => $user,
		]);
	}

	/**
	 * Displays a single ArticleLikes model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

        if (!Yii::$app->request->isAjax) {
			$this->subMenuParam = $model->article_id;
			$setting = $model->article->getSetting(['media_image_limit', 'media_file_limit']);

            if ($model->article->category->single_photo || $setting->media_image_limit == 1) {
				unset($this->subMenu['photo']);
            }
            if ($model->article->category->single_file || $setting->media_file_limit == 1) {
				unset($this->subMenu['document']);
            }
        }

		$this->view->title = Yii::t('app', 'Detail Like: {article-id}', ['article-id' => $model->article->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ArticleLikes model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

        if ($model->save(false, ['publish'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Article like success deleted.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'article' => $model->article_id]);
		}
	}

	/**
	 * actionPublish an existing ArticleLikes model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

        if ($model->save(false, ['publish'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Article like success updated.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'article' => $model->article_id]);
		}
	}

	/**
	 * Finds the ArticleLikes model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArticleLikes the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArticleLikes::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
