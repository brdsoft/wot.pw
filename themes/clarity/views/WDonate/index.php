<div class="modul donate">
	<div class="title">
		<h3><?=CHtml::encode($this->title)?></h3>
	</div>
	<div class="cell">
		<? if ($this->balance){ ?><p class="ballance">Баланс сайта: <span><?php echo $this->controller->site->balance ?> руб.</span></p><? } ?>
		<form action="http://wot.pw/rshop/pay" method="get" target="_blank">
			<input type="hidden" value="<?php echo $this->controller->site->id ?>" name="site">
			<input type="text" name="amount" value="Укажите сумму" onfocus="if(this.value=='Укажите сумму'){this.value=''}" onblur="if(this.value==''){this.value='Укажите сумму'}">
			<button class="send" type="submit">Пополнить</button>
		</form>
	</div>
</div>
