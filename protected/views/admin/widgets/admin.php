<?php
$this->pageTitle=Yii::t('wot', 'Admin Panel').' — '.Yii::t('wot', 'Widgets');
?>

<h2><a href="/admin"><?php echo Yii::t('wot', 'Admin Panel') ?></a> <span class="rarr"></span> <?php echo Yii::t('wot', 'Widgets') ?></h2>

<div class="content-inner-page">

	<div class="content-column">

		<div class="subnav">
			<?php echo CHtml::link(Yii::t('wot', 'Go to Site Design'), array('/admin/sites')) ?>
		</div>

		<div class="filling">

			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'cssFile'=>false,
				'pager'=>array(
					'cssFile'=>false,
				),
				'id'=>'roles-grid',
				'summaryText'=>false,
				'dataProvider'=>$model->search(),
				'columns'=>array(
					[
						'name'=>'name',
						'value'=>'"{".$data->name."}"',
					],
					'description',
					[
						'name'=>'params',
						'type'=>'ntext',
					],
					[
						'name'=>'example',
						'type'=>'ntext',
					],
					'premium',
				),
			)); ?>
			
			<p>
				<?php echo Yii::t('wot', 'Warning! If the Widget parameters contains any spaces you should put these parameters into double quotation mark. Example - title="Widget Title"') ?>
			</p>
			<p>
				<?php echo Yii::t('wot', 'Parameters can be set in random order') ?>
			</p>

		</div>

	</div>
	
	<div class="aside-column">
		<?php $this->renderPartial('//layouts/adminSidebar'); ?>
	</div>

</div>