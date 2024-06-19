<?php
/**
 * Articles (articles)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\AdminController
 * @var $model ommu\article\models\Articles
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 09:33 WIB
 * @modified date 13 May 2019, 21:24 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\article\models\Articles;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Article'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $model->title;
} ?>

<div class="articles-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id' => $model->primaryKey]), $model->publish),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'categoryName',
		'value' => function ($model) {
			$categoryName = isset($model->categoryTitle) ? $model->categoryTitle->message : '-';
            if ($categoryName != '-') {
                return Html::a($categoryName, ['setting/category/view', 'id' => $model->cat_id], ['title' => $categoryName, 'class' => 'modal-btn']);
            }
			return $categoryName;
		},
		'format' => 'html',
	],
	'title',
	[
		'attribute' => 'body',
		'value' => $model->body ? $model->body : '-',
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'image',
		'value' => function ($model) {
			$uploadPath = join('/', [Articles::getUploadPath(false), $model->id]);
			return $model->cover ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->cover])), ['alt' => $model->cover, 'class' => 'd-block border border-width-3 mb-4']).$model->cover : '-';
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'file',
		'value' => function ($model) {
			$uploadPath = join('/', [Articles::getUploadPath(false), $model->id]);
			return $model->document ? Html::a($model->document, Url::to(join('/', ['@webpublic', $uploadPath, $model->document])), ['title' => $model->document, 'target' => '_blank']) : '-';
		},
		'format' => 'raw',
		'visible' => !$small && $model->category->single_file ? true : false,
	],
	[
		'attribute' => 'tagBody',
		'value' => function ($model) {
            return $model::parseTag($model->getTags(true, 'title'), 'tag', ', ');
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'published_date',
		'value' => Yii::$app->formatter->asDate($model->published_date, 'medium'),
	],
	[
		'attribute' => 'headline',
		'value' => function ($model) {
			$setting = $model->getSetting(['headline_category']);
            if (!is_array(($headlineCategory = $setting->headline_category))) {
                $headlineCategory = [];
            }
            if (!in_array($model->cat_id, $headlineCategory)) {
                return '-';
            }
			return $model->quickAction(Url::to(['headline', 'id' => $model->primaryKey]), $model->headline, 'Headline,Not headline', true);
		},
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'headline_date',
		'value' => Yii::$app->formatter->asDatetime($model->headline_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'oMedia',
		'value' => function ($model) {
			$medias = $model->grid->media;
			return Html::a($medias, ['o/image/manage', 'article' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} medias', ['count' => $medias])]);
		},
		'format' => 'html',
		'visible' => !$small && !$model->category->single_file ? true : false,
	],
	[
		'attribute' => 'oFile',
		'value' => function ($model) {
			$files = $model->grid->file;
			return Html::a($files, ['o/file/manage', 'article' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} files', ['count' => $files])]);
		},
		'format' => 'html',
		'visible' => !$small && !$model->category->single_file ? true : false,
	],
	[
		'attribute' => 'oView',
		'value' => function ($model) {
			$views = $model->grid->view;
			return Html::a($views, ['view/admin/manage', 'article' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} views', ['count' => $views])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'oLike',
		'value' => function ($model) {
			$likes = $model->grid->like;
			return Html::a($likes, ['like/admin/manage', 'article' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} likes', ['count' => $likes])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Update'), 'class' => 'btn btn-primary btn-sm']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>