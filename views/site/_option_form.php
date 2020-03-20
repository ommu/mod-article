<?php
/**
 * Articles (articles)
 * @var $this app\components\View
 * @var $this ommu\article\controllers\SiteController
 * @var $model ommu\article\models\search\Articles
 * @var $form yii\widgets\ActiveForm
 *
 * @author Agus Susilo <smartgdi@gmail.com>
 * @contact (+62) 857-297-29382
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 6 November 2017, 13:54 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

$js = <<<JS
	$('form[name="gridoption"] :checkbox').click(function() {
		var url = $('form[name="gridoption"]').attr('action');
		var data = $('form[name="gridoption"] :checked').serialize();
		$.ajax({
			url: url,
			data: data,
			success: function(response) {
				//$("#w0").yiiGridView("applyFilter");
				//$.pjax({container: '#w0'});
				return false;
			}
		});
	});
JS;
	$this->registerJs($js, \app\components\View::POS_READY);
?>

<div class="grid-form">
	<?php echo Html::beginForm(Url::to(['/'.$route]), 'get', ['name' => 'gridoption']);
		$columns = [];
		foreach($model->templateColumns as $key => $column) {
			if($key == '_no')
				continue;
			$columns[$key] = $key;
		}
	?>
		<ul>
			<?php foreach($columns as $key => $val): ?> 
			<li>
				<?php echo Html::checkBox(sprintf("GridColumn[%s]", $key), in_array($key, $gridColumns) ? true : false, ['id'=>'GridColumn_'.$key]); ?>
				<?php echo Html::label($model->getAttributeLabel($val), 'GridColumn_'.$val); ?>
			</li>
			<?php endforeach; ?>
		</ul>
	<?php echo Html::endForm(); ?>
</div>