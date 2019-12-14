<?php
/* @var $this SiteController */

$this->pageTitle=$category->name;

?>

<div class="modul forum category">
	<a name="scrollTo"></a>
	<div class="title">
		<h3><?php echo Yii::t('wot', 'Forum')?></h3>
	</div>
	<div class="cell">

		<h3 class="category-title"><?php echo CHtml::link(Yii::t('wot', 'Forum'), array('/forum')) ?> &rarr; <?php echo CHtml::encode($category->name) ?></h3>

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
						'header'=>false,
					),
					'template'=>'<div class="forum-nav">{pager}'.(Yii::app()->user->account ? '<div class="forum-button">'.CHtml::link(Yii::t('wot', 'Add Topic'), array('/forum/new', 'id'=>$category->id), array('class'=>'create-theme')).'</div>' : '').'</div>{items}<div class="forum-nav">{pager}'.(Yii::app()->user->account ? '<div class="forum-button">'.CHtml::link(Yii::t('wot', 'Add Topic'), array('/forum/new', 'id'=>$category->id), array('class'=>'create-theme')).'</div>' : '').'</div>',
					'columns'=>array(
						array(
							'type'=>'html',
							'value'=>'$data->fixed ? "<span class=\"theme-fixed\" title=\"Тема прилеплена \"?></span>" : ($data->closed ? "<span class=\"theme-closed\" title=\"Тема закрыта\"></span>" : "")',
							'header'=>'',
						),
						array(
							'header'=>Yii::t('wot', 'Name'),
							'type'=>'html',
							'value'=>'CHtml::link(CHtml::encode($data->name), array("/forum/theme", "category_id"=>$data->category_id, "id"=>$data->id))',
							'filter'=>false,
						),
						array(
							'type'=>'html',
							'header'=>Yii::t('wot', 'Author'),
							'value'=>'CHtml::link($data->account->nickname, array("/profile/account", "id"=>$data->account_id), array("class"=>"theme-author"))',
						),
						array(
							'header'=>Yii::t('wot', 'Messages'),
							'value'=>'$data->messagesCount',
						),
						array(
							'type'=>'html',
							'header'=>Yii::t('wot', 'Last message'),
							'value'=>'$data->lastMessage',
						),
					),
					'emptyText'=>Yii::t('wot', 'No topics created'),
					'summaryText'=>false,
			));
			?>

	</div>
</div>