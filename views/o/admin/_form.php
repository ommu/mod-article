<?php
/**
 * Articles (articles)
 * @var $this AdminController
 * @var $model Articles
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 26 March 2018, 14:07 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

	if($model->isNewRecord || (!$model->isNewRecord && ($photoCondition == 0 || $fileCondition == 0)))
		$validation = false;
	else
		$validation = true;

	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('select#Articles_media_type_i').on('change', function() {
		var id = $(this).val();
		if(id == '0') {
			$('div#video').slideDown();
		} else {
			$('div#video').slideUp();
		}
	});
EOP;
	$cs->registerScript('media-type', $js, CClientScript::POS_END);
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'articles-form',
	'enableAjaxValidation'=>$validation,
	/*
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	*/
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data',
	),
)); ?>

<?php //begin.Messages ?>
<div id="ajax-message">
	<?php 
	echo $form->errorSummary($model);
	if(Yii::app()->user->hasFlash('error'))
		echo $this->flashMessage(Yii::app()->user->getFlash('error'), 'error');
	if(Yii::app()->user->hasFlash('success'))
		echo $this->flashMessage(Yii::app()->user->getFlash('success'), 'success');
	?>
</div>
<?php //begin.Messages ?>

	<fieldset>

		<div class="row">
			<div class="col-lg-8 col-md-12">

				<div class="form-group row">
					<?php echo $form->labelEx($model, 'cat_id', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
					<div class="col-lg-6 col-md-9 col-sm-12">
						<?php
						$parent = null;
						$category = ArticleCategory::getCategory(null, $parent);

						if($category != null)
							echo $form->dropDownList($model, 'cat_id', $category, array('class'=>'form-control'));
						else
							echo $form->dropDownList($model, 'cat_id', array('prompt'=>Yii::t('phrase', 'No Category')), array('class'=>'form-control'));?>
						<?php echo $form->error($model, 'cat_id'); ?>
					</div>
				</div>
	
				<div class="form-group row">
					<?php echo $form->labelEx($model, 'title', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
					<div class="col-lg-6 col-md-9 col-sm-12">
						<?php echo $form->textField($model, 'title', array('maxlength'=>128, 'class'=>'form-control')); ?>
						<?php echo $form->error($model, 'title'); ?>
					</div>
				</div>

				<?php if($model->isNewRecord || (!$model->isNewRecord && $photoCondition == 0)) {?>
					<div class="form-group row">
						<?php echo $form->labelEx($model, 'media_type_i', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
						<div class="col-lg-6 col-md-9 col-sm-12">
							<?php $media_type_i = array(
								'0'=>Yii::t('phrase', 'Video'),
								'1'=>Yii::t('phrase', 'Photo'),
							);
							if(!$model->getErrors()) {
								$model->media_type_i = 1;
								$medias = $model->medias;
								$media_id = $model->view->media_id ? $model->view->media_id : $medias[0]->media_id;
								$media = ArticleMedia::model()->findByPk($media_id);
								if(!$model->isNewRecord && $media->view->media == 'video')
									$model->media_type_i = 0;
							}
							echo $form->dropDownList($model, 'media_type_i', $media_type_i, array('class'=>'form-control')); ?>
							<?php echo $form->error($model, 'media_type_i'); ?>
						</div>
					</div>

					<div class="form-group row filter">
						<?php echo $form->labelEx($model, 'media_photo_i', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
						<div class="col-lg-6 col-md-9 col-sm-12">
							<?php if(!$model->isNewRecord) {
								$medias = $model->medias;
								if(!empty($medias)) {
									$article_cover = $model->view->article_cover ? $model->view->article_cover : $medias[0]->cover_filename;
									if(!$model->getErrors())
										$model->old_media_photo_i = $article_cover;
									echo $form->hiddenField($model, 'old_media_photo_i');
									if($model->old_media_photo_i != '') {
										$article_cover = Yii::app()->request->baseUrl.'/public/article/'.$model->article_id.'/'.$model->old_media_photo_i;?>
										<div class="mb-15"><img src="<?php echo Utility::getTimThumb($article_cover, 320, 150, 1);?>" alt="<?php echo $model->old_media_photo_i;?>"></div>
									<?php }
								}
							}?>
							<?php echo $form->fileField($model, 'media_photo_i', array('class'=>'form-control')); ?>
							<?php echo $form->error($model, 'media_photo_i'); ?>
							<div class="small-px silent">extensions are allowed: <?php echo Utility::formatFileType($media_image_type, false);?></div>
						</div>
					</div>

					<div id="video" class="form-group row <?php echo $model->media_type_i == 1 ? 'hide' : '';?>">
						<?php echo $form->labelEx($model, 'media_video_i', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
						<div class="col-lg-6 col-md-9 col-sm-12">
							<?php if(!$model->isNewRecord) {
								$medias = $model->medias;
								if(!empty($medias))
									$article_video = $model->view->article_video ? $model->view->article_video : $medias[0]->media_filename;
								if(!$model->getErrors())
									$model->media_video_i = $article_video;
							}
							echo $form->textArea($model, 'media_video_i', array('maxlength'=>32, 'class'=>'form-control smaller')); ?>
							<?php echo $form->error($model, 'media_video_i'); ?>
							<div class="small-px slient">http://www.youtube.com/watch?v=<strong>HOAqSoDZSho</strong></div>
						</div>
					</div>
				<?php }?>
				
				<div class="form-group row">
					<?php echo $form->labelEx($model, 'keyword_i', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
					<div class="col-lg-6 col-md-9 col-sm-12">
						<?php 
						if($model->isNewRecord) {
							echo $form->textArea($model, 'keyword_i', array('rows'=>6, 'cols'=>50, 'class'=>'form-control'));
							
						} else {
							//echo $form->textField($model, 'keyword_i', array('maxlength'=>32,'class'=>'span-6'));
							$url = Yii::app()->controller->createUrl('o/tag/add', array('type'=>'article'));
							$article = $model->article_id;
							$tagId = 'Articles_keyword_i';
							$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
								'model' => $model,
								'attribute' => 'keyword_i',
								'source' => Yii::app()->createUrl('globaltag/suggest'),
								'options' => array(
									//'delay '=> 50,
									'minLength' => 1,
									'showAnim' => 'fold',
									'select' => "js:function(event, ui) {
										$.ajax({
											type: 'post',
											url: '$url',
											data: { article_id: '$article', tag_id: ui.item.id, tag: ui.item.value },
											dataType: 'json',
											success: function(response) {
												$('form #$tagId').val('');
												$('form #keyword-suggest').append(response.data);
											}
										});

									}"
								),
								'htmlOptions' => array(
									'class'=>'form-control'
								),
							));
							echo $form->error($model, 'keyword_i');
						}?>
						<?php if($model->isNewRecord) {?><div class="small-px slient">tambahkan tanda koma (,) jika ingin menambahkan keyword lebih dari satu</div><?php }?>
						<div id="keyword-suggest" class="suggest">
							<?php 
							if($setting->meta_keyword != '-') {
								$arrKeyword = explode(',', $setting->meta_keyword);
								foreach($arrKeyword as $row) {?>
									<div class="d"><?php echo $row;?></div>
							<?php }
							}
							if(!$model->isNewRecord) {
								$tags = $model->tags;
								if(!empty($tags)) {
									foreach($tags as $key => $val) {?>
									<div><?php echo $val->tag->body;?><a href="<?php echo Yii::app()->controller->createUrl('o/tag/delete', array('id'=>$val->id,'type'=>'article'));?>" title="<?php echo Yii::t('phrase', 'Delete');?>"><?php echo Yii::t('phrase', 'Delete');?></a></div>
								<?php }
								}
							}?>
						</div>
					</div>
				</div>

			</div>
			<div class="col-lg-4 col-md-12">
				
				<?php if($model->isNewRecord || (!$model->isNewRecord && $fileCondition == 0)) {?>
					<div class="form-group row">
						<?php echo $form->labelEx($model, 'media_file_i', array('class'=>'col-form-label col-lg-12 col-md-3 col-sm-12')); ?>
						<div class="col-lg-12 col-md-9 col-sm-12">
							<?php if(!$model->isNewRecord) {
								$files = $model->files;
								if(!empty($files)) {
									$article_file = $model->view->article_file ? $model->view->article_file : $files[0]->file_filename;
									if(!$model->getErrors())
										$model->old_media_file_i = $article_file;
									echo $form->hiddenField($model, 'old_media_file_i');
									if($model->old_media_file_i != '') {
										$file = Yii::app()->request->baseUrl.'/public/article/'.$model->article_id.'/'.$model->old_media_file_i;?>
										<div class="mb-15"><a href="<?php echo $file;?>"><?php echo $model->old_media_file_i;?></a></div>
									<?php }
								}
							}?>
							<?php echo $form->fileField($model, 'media_file_i', array('class'=>'form-control')); ?>
							<?php echo $form->error($model, 'media_file_i'); ?>
							<div class="small-px silent">extensions are allowed: <?php echo Utility::formatFileType($media_file_type, false);?></div>
						</div>
					</div>
				<?php }?>

				<div class="form-group row">
					<?php echo $form->labelEx($model, 'published_date', array('class'=>'col-form-label col-lg-12 col-md-3 col-sm-12')); ?>
					<div class="col-lg-12 col-md-9 col-sm-12">
						<?php $model->published_date = !$model->isNewRecord ? (!in_array($model->published_date, array('0000-00-00','1970-01-01','0002-12-02','-0001-11-30')) ? date('Y-m-d', strtotime($model->published_date)) : '') : '';
						/* $this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'model'=>$model,
							'attribute'=>'published_date',
							//'mode'=>'datetime',
							'options'=>array(
								'dateFormat' => 'yy-mm-dd',
							),
							'htmlOptions'=>array(
								'class' => 'form-control',
							),
						)); */
						echo $form->dateField($model, 'published_date', array('class'=>'form-control')); ?>
						<?php echo $form->error($model, 'published_date'); ?>
					</div>
				</div>

				<?php if(OmmuSettings::getInfo('site_type') == 1) {?>
				<div class="form-group row publish">
					<?php echo $form->labelEx($model, 'comment_code', array('class'=>'col-form-label col-lg-12 col-md-3 col-sm-12')); ?>
					<div class="col-lg-12 col-md-9 col-sm-12">
						<?php echo $form->checkBox($model, 'comment_code', array('class'=>'form-control')); ?>
						<?php echo $form->labelEx($model, 'comment_code'); ?>
						<?php echo $form->error($model, 'comment_code'); ?>
					</div>
				</div>
				<?php } else {
					$model->comment_code = 0;
					echo $form->hiddenField($model, 'comment_code');
				}?>

				<?php if($setting->headline == 1) {?>
				<div class="form-group row publish">
					<?php echo $form->labelEx($model, 'headline', array('class'=>'col-form-label col-lg-12 col-md-3 col-sm-12')); ?>
					<div class="col-lg-12 col-md-9 col-sm-12">
						<?php echo $form->checkBox($model, 'headline', array('class'=>'form-control')); ?>
						<?php echo $form->labelEx($model, 'headline'); ?>
						<?php echo $form->error($model, 'headline'); ?>
					</div>
				</div>
				<?php } else {
					$model->headline = 0;
					echo $form->hiddenField($model, 'headline');
				}?>

				<div class="form-group row publish">
					<?php echo $form->labelEx($model, 'publish', array('class'=>'col-form-label col-lg-12 col-md-3 col-sm-12')); ?>
					<div class="col-lg-12 col-md-9 col-sm-12">
						<?php echo $form->checkBox($model, 'publish', array('class'=>'form-control')); ?>
						<?php echo $form->labelEx($model, 'publish'); ?>
						<?php echo $form->error($model, 'publish'); ?>
					</div>
				</div>
				
			</div>
		</div>
	</fieldset>

	<fieldset>
	
		<div class="form-group row">
			<?php echo $form->labelEx($model, 'quote', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php $this->widget('yiiext.imperavi-redactor-widget.ImperaviRedactorWidget', array(
					'model'=>$model,
					'attribute'=>'quote',
					'options'=>array(
						'buttons'=>array(
							'html', '|', 
							'bold', 'italic', 'deleted', '|',
						),
					),
					'plugins' => array(
						'fontcolor' => array('js' => array('fontcolor.js')),
						'table' => array('js' => array('table.js')),
						'fullscreen' => array('js' => array('fullscreen.js')),
					),
					'htmlOptions'=>array(
						'class' => 'form-control',
					),
				)); ?>
				<div class="small-px silent"><?php echo Yii::t('phrase', 'Note : add {$quote} in description article');?></div>
				<?php echo $form->error($model, 'quote'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'body', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php $this->widget('yiiext.imperavi-redactor-widget.ImperaviRedactorWidget', array(
					'model'=>$model,
					'attribute'=>'body',
					'options'=>array(
						'buttons'=>array(
							'html', 'formatting', '|', 
							'bold', 'italic', 'deleted', '|',
							'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
							'link', '|',
						),
					),
					'plugins' => array(
						'fontcolor' => array('js' => array('fontcolor.js')),
						'table' => array('js' => array('table.js')),
						'fullscreen' => array('js' => array('fullscreen.js')),
					),
					'htmlOptions'=>array(
						'class' => 'form-control',
					),
				)); ?>
				<?php echo $form->error($model, 'body'); ?>
			</div>
		</div>

		<div class="form-group row submit">
			<label class="col-form-label col-lg-3 col-md-3 col-sm-12">&nbsp;</label>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
			</div>
		</div>

	</fieldset>
<?php $this->endWidget(); ?>