<?php
/**
 * AdminController
 * @var $this ommu\article\controllers\download\AdminController
 * @var $model ommu\article\models\ArticleDownloads
 *
 * AdminController implements the CRUD actions for ArticleDownloads model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  View
 *  Delete
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 11:14 WIB
 * @modified date 13 May 2019, 09:43 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\controllers\download;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\article\models\ArticleDownloads;
use ommu\article\models\search\ArticleDownloads as ArticleDownloadsSearch;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('id') || Yii::$app->request->get('file') || Yii::$app->request->get('article')) {
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
	 * Lists all ArticleDownloads models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArticleDownloadsSearch();
        if (($article = Yii::$app->request->get('article')) != null) {
            $searchModel = new ArticleDownloadsSearch(['articleId' => $article]);
        }
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

        if (($file = Yii::$app->request->get('file')) != null) {
            $file = \ommu\article\models\ArticleFiles::findOne($file);
			$this->subMenuParam = $file->article_id;
			$setting = $file->article->getSetting(['media_image_limit', 'media_file_limit']);
            if ($file->article->category->single_photo || $setting->media_image_limit == 1) {
                unset($this->subMenu[1]['photo']);
            }
            if ($file->article->category->single_file || $setting->media_file_limit == 1) {
                unset($this->subMenu[1]['document']);
            }
        }
        if (($user = Yii::$app->request->get('user')) != null) {
            $user = \app\models\Users::findOne($user);
        }

        if ($article) {
			$this->subMenuParam = $article;
			$article = \ommu\article\models\Articles::findOne($article);
			$setting = $article->getSetting(['media_image_limit', 'media_file_limit']);
            if ($article->category->single_photo || $setting->media_image_limit == 1) {
                unset($this->subMenu[1]['photo']);
            }
            if ($article->category->single_file || $setting->media_file_limit == 1) {
                unset($this->subMenu[1]['document']);
            }
        }

		$this->view->title = Yii::t('app', 'Downloads');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'file' => $file,
			'user' => $user,
			'article' => $article,
		]);
	}

	/**
	 * Displays a single ArticleDownloads model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

        if (!Yii::$app->request->isAjax) {
			$this->subMenuParam = $model->file->article_id;
			$setting = $model->article->getSetting(['media_image_limit', 'media_file_limit']);
	
            if ($model->article->category->single_photo || $setting->media_image_limit == 1) {
				unset($this->subMenu[1]['photo']);
            }
            if ($model->article->category->single_file || $setting->media_file_limit == 1) {
				unset($this->subMenu[1]['document']);
            }
        }

		$this->view->title = Yii::t('app', 'Detail Download: {file-id}', ['file-id' => $model->file->file_filename]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'small' => false,
		]);
	}

	/**
	 * Deletes an existing ArticleDownloads model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Article download success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'article' => $model->file->article_id]);
	}

	/**
	 * Finds the ArticleDownloads model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArticleDownloads the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArticleDownloads::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
