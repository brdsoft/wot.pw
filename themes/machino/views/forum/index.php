<?php
/* @var $this SiteController */

$this->pageTitle='Форум';

?>


<div class="block">
	<div class="head head2">
		Форум
	</div>
	<? if (!$groups){ ?>
		<div class="body body2">
			Для отображения форума <?php echo CHtml::link('добавьте', array('/admin/forumGroups/create')) ?> хотя бы одну группу в админке
		</div>
		</div>
	<? return;} ?>
	<div class="body body2">
		<? foreach ($groups as $group){ ?>
			<div class="forum-grid forum-groups">
				<?
				$dataProvider=new CArrayDataProvider($group->categories, array(
					'keyField'=>'id',
					'sort'=>array(
						'attributes'=>array(
							'id',
						),
					),
					'pagination'=>array(
						'pageSize'=>1000,
					),
				));
				$this->widget('zii.widgets.grid.CGridView', array(
						'dataProvider'=>$dataProvider,
						'cssFile'=>false,
						'pager'=>array(
							'cssFile'=>false,
						),
						'columns'=>array(
							array(
								'name'=>'name',
								'type'=>'html',
								'header'=>CHtml::encode($group->name),
								'value'=>'CHtml::link(CHtml::encode($data->name), array("/forum/category", "id"=>$data->id), array("style"=>"font-weight: bold;"))."<br><span style=\"color: gray;font-size: 11px;\">".CHtml::encode($data->description)."</span>"',
							),
							array(
								'header'=>'Темы',
								'value'=>'$data->themesCount',
								'headerHtmlOptions'=>array('style'=>'width: 50px;'),
							),
							array(
								'header'=>'Сообщения',
								'value'=>'$data->messagesCount',
								'headerHtmlOptions'=>array('style'=>'width: 80px;'),
							),
							array(
								'name'=>'name',
								'type'=>'html',
								'header'=>'Посл. сообщение',
								'value'=>'$data->lastMessage',
								'headerHtmlOptions'=>array('style'=>'width: 120px;'),
							),
						),
						'summaryText'=>false,
						'emptyText'=>'Нет форумов',
				));
				?>
			</div>
		<? } ?>
		<? if (Yii::app()->user->checkAccess('1')){ ?>
			<hr/>
			<div style="font-size: 12px;">
				<?php echo CHtml::link('Редактировать группы', array('/admin/forumGroups/admin')) ?> |
				<?php echo CHtml::link('Создать группу', array('/admin/forumGroups/create')) ?> |
				<?php echo CHtml::link('Редактировать форумы', array('/admin/forumCategories/admin')) ?> |
				<?php echo CHtml::link('Создать форум', array('/admin/forumCategories/create')) ?>
			</div>
		<? } ?>
	</div>
</div>
