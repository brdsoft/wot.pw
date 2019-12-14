<?php
$this->pageTitle='Просмотр получателей';
?>

<h2>Просмотр получателей</h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link('&larr;', array('/admin/notices')) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.grid.CGridView', array(
                'cssFile'=>false,
                'pager'=>array(
                    'cssFile'=>false,
                ),
                'id'=>'notices-recipients-grid',
                'dataProvider'=>$model->search(),
                'filter'=>$model,
                'columns'=>array(
                    array(
                        'name'=>'notices_id',
                        'type'=>'html',
                        'value'=>'CHtml::link($data->notices_id, array("/admin/notices/view", "id"=>$data->notices_id))',
                        'filter'=>CHtml::activeTextField($model, 'notices_id', array('id'=>false, 'placeholder'=>'Быстрый поиск')),
                    ),
                    array(
                        'name'=>'account_id',
                        'type'=>'html',
                        'value'=>'$data->account_id." - ".CHtml::link($data->account->nickname, array("/profile/account", "id"=>$data->account_id))',
                        'filter'=>CHtml::activeTextField($model, 'account_id', array('id'=>false, 'placeholder'=>'Быстрый поиск')),
                    ),
                    array(
                        'name'=>'answer',
                        'value'=>'$data->answer ? Notices::model()->answersAllowed[$data->answer] : "Нет ответа"',
                        'filter'=>false,
                    ),
                ),
            )); ?>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>