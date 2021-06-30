<?php
/**
 * Article Files (article-files)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\o\FileController
 * @var $model ommu\article\models\ArticleFiles
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 11:09 WIB
 * @modified date 17 May 2019, 11:45 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Article'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => $model->article->title, 'url' => ['admin/view', 'id' => $model->article_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Document'), 'url' => ['manage', 'article' => $model->article_id]];
$this->params['breadcrumbs'][] = ['label' => $model->file_filename, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail Document'), 'url' => Url::to(['view', 'id' => $model->id]), 'icon' => 'eye', 'htmlOptions' => ['class' => 'btn btn-info']],
	['label' => Yii::t('app', 'Delete Document'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="article-files-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>