<?php
	$onPage = 20;
	$message = ForumMessages::model()->with(['account'])->find(array('condition'=>"`t`.`theme_id` = {$data->id}", 'order'=>"`t`.`id` DESC", 'limit'=>'1'));
	if (!$message)
		return;
?>

<div>
	<span><?php echo CHtml::link($message->account->nickname, array('/profile/account', 'id'=>$message->account_id)) ?> -</span>
	<span><?php echo date('d.m.y H:i', $message->time+Yii::app()->params["moscow"]) ?></span>
	<span><?php echo CHtml::link(CHtml::encode($data->name), array('/forum/theme', 'category_id'=>$message->theme->category_id, 'id'=>$message->theme->id, 'ForumMessages_page'=>ceil($message->theme->messagesCount/$onPage), '#'=>'message'.$message->id)) ?></span>
</div>
