<?php
/* @var $this SiteController */

$this->pageTitle=$category->name;

?>


<div class="block">
	<div class="head head2">
		<?php echo CHtml::link('Форум', array('/forum')) ?> - <?php echo CHtml::encode($category->name) ?>
	</div>
	<div class="body body2">
		<div class="forum-grid forum-themes">
			<?
			$dataProvider=new CActiveDataProvider('ForumThemes', array(
				'criteria'=>array(
					'condition'=>"`category_id` = '{$category->id}'",
					'with'=>array('messagesCount'),
				),
				'sort'=>array(
					'defaultOrder'=>"t.fixed DESC, t.time DESC",
				),
				'pagination'=>array(
					'pageSize'=>20,
				),
			));
			$this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$dataProvider,
					'cssFile'=>false,
					'ajaxUpdate'=>false,
					'pager'=>array(
						'cssFile'=>false,
					),
					'columns'=>array(
						array(
							'type'=>'html',
							'value'=>'$data->fixed ? "<img src=\"http://webhostingw.com/icons-explorer/icons/led/16x16/pin.png\" alt=\"\" title=\"Тема прилеплена\" />" : ($data->closed ? "<img src=\"http://webhostingw.com/icons-explorer/icons/led/16x16/lock.png\" alt=\"\" title=\"Тема закрыта\" />" : "")',
							'header'=>'',
							'htmlOptions'=>array('width'=>'16'),
						),
						array(
							'header'=>'Название',
							'type'=>'html',
							'value'=>'CHtml::link(CHtml::encode($data->name), array("/forum/theme", "category_id"=>$data->category_id, "id"=>$data->id), array("style"=>"font-weight: bold;"))',
							'filter'=>false,
						),
						array(
							'type'=>'html',
							'header'=>'Автор',
							'value'=>'CHtml::link($data->account->nickname, array("/profile/account", "id"=>$data->account_id))',
						),
						array(
							'header'=>'Сообщения',
							'value'=>'$data->messagesCount',
							'headerHtmlOptions'=>array('style'=>'width: 80px;'),
						),
						array(
							'type'=>'html',
							'header'=>'Посл. сообщение',
							'value'=>'$data->lastMessage',
							'headerHtmlOptions'=>array('style'=>'width: 120px;'),
						),
					),
					'emptyText'=>'Тем нет',
					'summaryText'=>false,
			));
			?>
		</div>
		<? if (Yii::app()->user->account){ ?>
			<hr/>
			<div class="pull-right" style="font-size: 12px;">
				<?php echo CHtml::link('<img src="http://webhostingw.com/icons-explorer/icons/led/16x16/add.png" alt=""/> Создать тему', array('/forum/new', 'id'=>$category->id)) ?>
			</div>
			<div class="both"></div>
		<? } ?>
	</div>
</div>
