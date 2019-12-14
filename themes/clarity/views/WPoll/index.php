<? if (!$poll) return; ?>

<? if ($poll->access && (!Yii::app()->user->account || !isset(Yii::app()->controller->clans[Yii::app()->user->account->clan_id]))) return; ?>

<div class="modul poll" id="poll_<?php echo $poll->id ?>">
	<div class="title">
		<h3><?=CHtml::encode($this->title)?></h3>
	</div>
	<div class="cell">
		<div class="question"><?=CHtml::encode($poll->question)?></div>
		<? if (!Yii::app()->user->account || $answer || !$poll->enabled) {	$stat = $poll->calculateAnswers(); ?>
			<ul>
				<? for ($i = 1; $i <= 10; $i++){ if ($poll['answer'.$i] == '') continue; ?>
					<li<?php echo Yii::app()->user->account && $answer && $answer->answer == $i ? ' class="active"' : '' ?>><?php echo CHtml::encode($poll['answer'.$i]) ?> - <strong><?php echo isset($stat[$i]->percent) ? $stat[$i]->percent : 0 ?>%</strong> (<?php echo isset($stat[$i]->count) ? $stat[$i]->count : 0 ?>)<span><span style="width:<?php echo isset($stat[$i]->percent) ? $stat[$i]->percent : 0 ?>%"></span></span></li>
				<? } ?>
			</ul>
		<? } else {?>
			<ul>
				<? for ($i = 1; $i <= 10; $i++){ if ($poll['answer'.$i] == '') continue; ?>
					<li><input value="<?php echo $i ?>" name="poll" id="poll_<?php echo $poll->id ?>_<?php echo $i ?>" type="radio"><label for="poll_<?php echo $poll->id ?>_<?php echo $i ?>"><?php echo CHtml::encode($poll['answer'.$i]) ?></label></li>
				<? } ?>
			</ul>
			<button onClick="pollAnswer(<?php echo $poll->id ?>, '<?php echo Yii::app()->request->csrfToken ?>')">Ответить</button>
		<? } ?>
	</div>
</div>
