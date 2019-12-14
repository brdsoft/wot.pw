<?php
/* @var $this SiteController */

$this->pageTitle=$category->name;

?>

<div class="modul forum-category">
	<div class="custom-title">
		<h4><?php echo CHtml::link('Форум', array('/forum')) ?> &rarr; <?php echo CHtml::encode($category->name) ?></h4>
	</div>
	<div class="pastic">

		<div class="table-forum">
			<? if (Yii::app()->user->account){ ?>
				<div class="forum-button">
					<?php echo CHtml::link('Создать тему', array('/forum/new', 'id'=>$category->id)) ?>
				</div>
			<? } ?>

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
							'value'=>'$data->fixed ? "<img src=\"http://webhostingw.com/icons-explorer/icons/led/16x16/pin.png\" alt=\"\" title=\"Тема прилеплена\">" : ($data->closed ? "<img src=\"http://webhostingw.com/icons-explorer/icons/led/16x16/lock.png\" alt=\"\" title=\"Тема закрыта\">" : "")',
							'header'=>'',
						),
						array(
							'header'=>'Название',
							'type'=>'html',
							'value'=>'CHtml::link(CHtml::encode($data->name), array("/forum/theme", "category_id"=>$data->category_id, "id"=>$data->id))',
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
						),
						array(
							'type'=>'html',
							'header'=>'Посл. сообщение',
							'value'=>'$data->lastMessage',
						),
					),
					'emptyText'=>'Тем нет',
					'summaryText'=>false,
			));
			?>

			<? if (Yii::app()->user->account){ ?>
				<div class="forum-button">
					<?php echo CHtml::link('Создать тему', array('/forum/new', 'id'=>$category->id)) ?>
				</div>
			<? } ?>

		</div>

	</div>
</div>