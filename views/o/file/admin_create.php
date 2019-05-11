<?php
/**
 * Article Files (article-files)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\o\FileController
 * @var $model ommu\article\models\ArticleFiles
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 11:09 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Article Files'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>