<?php
/**
 * Articles (articles)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\SiteController
 * @var $model ommu\article\models\Articles
 *
 * @author Agus Susilo <smartgdi@gmail.com>
 * @contact (+62) 857-297-29382
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 November 2017, 13:54 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	//['label' => Yii::t('app', 'Add Articles'), 'url' => Url::to(['create']), 'icon' => 'plus-square', 'htmlOptions' => ['class'=>'btn btn-success']],
];
$this->params['menu']['option'] = [
	['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	// ['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

>
			<?php echo $this->description != '' ? "<p class=\"text-muted font-13 m-b-30\">$this->description</p>" : '';?>

			<?php echo $this->render('_search', ['model'=>$searchModel]); ?>

			<?php // echo $this->render('_option_form', ['model'=>$searchModel, 'gridColumns'=>$searchModel->activeDefaultColumns($columns), 'route'=>$this->context->route]); ?>

			<?php echo ListView::widget([
				'dataProvider' => $dataProvider,
				'itemOptions' => ['class' => 'item'],
				'itemView' => function ($model, $key, $index, $widget) {
					return Html::a(Html::encode($model->title), ['view', 'id'=>$model->article_id]);
				},
			]); ?>
		</div>
	</div>
</div>
