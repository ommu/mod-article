<?php
/**
 * AdminController
 * @var $this ommu\article\controllers\AdminController
 * @var $model ommu\article\models\Articles
 *
 * AdminController implements the CRUD actions for Articles model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  Create
 *  Update
 *  View
 *  Delete
 *  RunAction
 *  Publish
 *  Headline
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 09:33 WIB
 * @modified date 13 May 2019, 21:24 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\article\models\Articles;
use ommu\article\models\search\Articles as ArticlesSearch;
use ommu\article\models\ArticleSetting;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

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
        $queryParams = Yii::$app->request->queryParams;
        if (($tag = Yii::$app->request->get('tag')) != null) {
            $queryParams = ArrayHelper::merge(Yii::$app->request->queryParams, ['tagId' => $tag]);
        }
		$dataProvider = $searchModel->search($queryParams);

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

        if (($category = Yii::$app->request->get('category')) != null) {
            $category = \ommu\article\models\ArticleCategory::findOne($category);
        }
        if (($tag = Yii::$app->request->get('tag')) != null) {
            $tag = \app\models\CoreTags::findOne($tag);
        }

		$this->view->title = Yii::t('app', 'Articles');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'category' => $category,
			'tag' => $tag,
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
		$setting = $model->getSetting(['headline', 'headline_category', 'media_image_type', 'media_file_type']);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->image = UploadedFile::getInstance($model, 'image');
            $model->file = UploadedFile::getInstance($model, 'file');
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Article success created.'));
                return $this->redirect(['manage']);
                //return $this->redirect(['view', 'id' => $model->id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Create Article');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
			'setting' => $setting,
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
		$setting = $model->getSetting(['headline', 'headline_category', 'media_image_limit', 'media_image_type', 'media_file_limit', 'media_file_type']);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->category->single_photo || $setting->media_image_limit == 1) {
                $model->image = UploadedFile::getInstance($model, 'image');
            }
            if ($model->category->single_file || $setting->media_file_limit == 1) {
                $model->file = UploadedFile::getInstance($model, 'file');
            }
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Article success updated.'));
                return $this->redirect(['manage']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->subMenu = $this->module->params['article_submenu'];
        if ($model->category->single_photo || $setting->media_image_limit == 1) {
            unset($this->subMenu[1]['photo']);
        }
        if ($model->category->single_file || $setting->media_file_limit == 1) {
            unset($this->subMenu[1]['document']);
        }

		$this->view->title = Yii::t('app', 'Update Article: {title}', ['title' => $model->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
			'setting' => $setting,
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
		$setting = $model->getSetting(['media_image_limit', 'media_file_limit']);

		$this->subMenu = $this->module->params['article_submenu'];
        if ($model->category->single_photo || $setting->media_image_limit == 1) {
            unset($this->subMenu[1]['photo']);
        }
        if ($model->category->single_file || $setting->media_file_limit == 1) {
            unset($this->subMenu[1]['document']);
        }

		$this->view->title = Yii::t('app', 'Detail Article: {title}', ['title' => $model->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
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

        if ($model->save(false, ['publish', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Article success deleted.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
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

        if ($model->save(false, ['publish', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Article success updated.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
		}
	}

	/**
	 * actionHeadline an existing Articles model.
	 * If headline is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionHeadline($id)
	{
		$setting = ArticleSetting::find()
			->select(['headline_category'])
			->where(['id' => 1])
            ->one();

        if (!is_array(($headlineCategory = $setting->headline_category))) {
            $headlineCategory = [];
        }

		$model = $this->findModel($id);

        if (!in_array($model->cat_id, $headlineCategory)) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

		$model->headline = 1;
		$model->publish  = 1;

        if ($model->save(false, ['publish', 'headline', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Article success updated.'));
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
        if (($model = Articles::findOne($id)) !== null) {
            $model->tag = implode(', ', $model->getTags(true, 'title'));

            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
