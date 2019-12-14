<div class="block online">
	<div class="head head1">
		<?=CHtml::encode($this->title)?>
	</div>
	<div class="body">
		<? if ($this->balance){ ?><p class="ballance">Баланс сайта: <span><?php echo $this->controller->site->balance ?> руб.</span></p><? } ?>
		<form action="http://wot.pw/rshop/pay" method="get" target="_blank">
			<input type="hidden" value="<?php echo $this->controller->site->id ?>" name="site">
			<input type="text" name="amount" value="укажите сумму" onfocus="if(this.value=='укажите сумму'){this.value=''}" onblur="if(this.value==''){this.value='укажите сумму'}">
			<button class="send" type="submit">Пополнить</button>
		</form>
	</div>
</div>
