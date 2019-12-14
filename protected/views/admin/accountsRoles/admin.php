<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' — '.Yii::t('wot', 'Roles Assignment');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel')?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'Roles Assignment')?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link(Yii::t('wot', 'Add User'), array('/admin/accountsRoles/create')) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'cssFile'=>false,
				'pager'=>array(
					'cssFile'=>false,
				),
				'id'=>'accounts-roles-grid',
				'summaryText'=>false,
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					array(
						'name'=>'account_id',
						'value'=>'$data->account_id." - ".$data->account->nickname',
						'filter'=>CHtml::activeTextField($model, 'account_id', array('id'=>false, 'placeholder'=>Yii::t('wot', 'Quick search'))),
					),
					array(
						'name'=>'role_id',
						'filter'=>CHtml::listData(Roles::model()->findAll(), 'id', 'name'),
						'value'=>'$data->role->name',
					),
					array(
						'htmlOptions'=>array('class'=>'adm-sed'),
						'class'=>'CButtonColumn',
						'updateButtonImageUrl'=>false,
						'viewButtonImageUrl'=>false,
						'deleteButtonImageUrl'=>false,
					),
				),
			)); ?>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>