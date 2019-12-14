<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'companies-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'clan_id'); ?>
		<?php echo CHtml::dropDownList('Companies[clan_id]',$model->clan_id,CHtml::listData(Clans::model()->findAll(array('order'=>'`order`')), 'id', 'abbreviation')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'order'); ?>
		<?php echo $form->textField($model,'order'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'accounts'); ?>
		<div class="checkbox">
			<input type="checkbox" id="select_all_accounts" onClick="$('input.checkbox-account').attr('checked', $('#select_all_accounts').attr('checked') == 'checked')"> <span><?php echo Yii::t('wot', 'Select all')?></span>
		</div>
		<div id="accounts" style="-webkit-column-count:3; -moz-column-count:3; column-count:3;"></div>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('wot', 'Create') : Yii::t('wot', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div>

<script type="text/javascript">
	$(function(){
		$('#Companies_clan_id').change(function(){
			getAccounts($('#Companies_clan_id').val(), '<?php echo $model->isNewRecord ? 0 : $model->id ?>');
		});
		getAccounts($('#Companies_clan_id').val(), '<?php echo $model->isNewRecord ? 0 : $model->id ?>');
	});
	
	function getAccounts(clan_id, company_id)
	{
		$.get(
			'/index.php?r=admin/companies/getAccounts&clan_id='+clan_id+'&company_id='+company_id,
			function(data){
				if (data.status == 1)
				{
					$('#accounts').empty();
					for(var i in data.data)
					{
						$('#accounts').append('<div class="checkbox"><input type="checkbox" name="Companies[accounts][]" id="Companies_accounts_'+i+'" value="'+data.data[i].id+'" class="checkbox-account"'+(data.data[i].checked ? ' checked' : '')+'><span> '+data.data[i].nickname+'</span></div>');
					}
				}
			},
			'json'
		);
	}
	
</script>
