<?php
/**
 * Article Categories (article-category)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\setting\CategoryController
 * @var $model ommu\article\models\ArticleCategory
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 09:35 WIB
 * @modified date 11 May 2019, 21:30 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="article-category-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
