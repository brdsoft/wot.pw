<?php
/* @var $this SiteController */

$this->pageTitle='Форум';

?>

<div class="modul forum-group">
	<div class="custom-title">
		<h4>Форум</h4>
	</div>
	<div class="pastic">
		<div class="table-forum">
	<? if (!$groups){ ?>
		<p>Для отображения форума <?php echo CHtml::link('добавьте', array('/admin/forumGroups/create')) ?> хотя бы одну группу в админке.</p>
		</div>
	</div>
</div>

	<? return;} ?>

		<? foreach ($groups as $group){ ?>

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
								'value'=>'CHtml::link(CHtml::encode($data->name), array("/forum/category", "id"=>$data->id))."<span>".CHtml::encode($data->description)."</span>"',
							),
							array(
								'header'=>'Темы',
								'value'=>'$data->themesCount',
							),
							array(
								'header'=>'Сообщения',
								'value'=>'$data->messagesCount',
							),
							array(
								'name'=>'name',
								'type'=>'html',
								'header'=>'Посл. сообщение',
								'value'=>'$data->lastMessage',
							),
						),
						'summaryText'=>false,
						'emptyText'=>'Нет форумов',
				));
				?>

		<? } ?>

		</div>
	</div>
</div>