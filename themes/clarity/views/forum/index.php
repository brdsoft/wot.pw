<?php
/* @var $this SiteController */

$this->pageTitle=Yii::t('wot', 'Forum');
?>

<div class="modul forum forum-index">
	<div class="title">
		<h3><?php echo Yii::t('wot', 'Forum')?></h3>
	</div>
	<div class="cell">

	<? if (!$groups){ ?>
		<p class="no-forum"><?php echo Yii::t('wot', 'To view the forum ')?> <?php echo CHtml::link(Yii::t('wot', 'add categories '), array('/admin/forumGroups/create')) ?> <?php echo Yii::t('wot', 'in the AP.')?></p>

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
								'header'=>Yii::t('wot', 'Topics'),
								'value'=>'$data->themesCount',
							),
							array(
								'header'=>Yii::t('wot', 'Messages'),
								'value'=>'$data->messagesCount',
							),
							array(
								'name'=>'name',
								'type'=>'html',
								'header'=>Yii::t('wot', 'Last message'),
								'value'=>'$data->lastMessage',
							),
						),
						'summaryText'=>false,
						'emptyText'=>Yii::t('wot', 'No forums found'),
				));
				?>

		<? } ?>

	</div>
</div>