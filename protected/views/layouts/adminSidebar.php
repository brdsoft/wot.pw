
	<div class="dashboard-container">

		<div class="dashboard dash-premium">
			<h4><?php echo Yii::t('wot', 'Дополнения')?></h4>
			<div>
				<p class="ballance"><?php echo Yii::t('wot', 'Баланс сайта')?>: <strong><?php echo $this->site->balance ?> руб.</strong></p>
					<form class="pay" action="http://wot.pw/rshop/pay" method="get" target="_blank">
						<input type="hidden" name="site" value="<?php echo $this->site->id ?>">
						<p>Пополнить на: <input type="text" name="amount" value="укажите сумму" onfocus="if(this.value=='укажите сумму'){this.value=''}" onblur="if(this.value==''){this.value='укажите сумму'}"> <button class="send" type="submit">Пополнить</button></p>
						<p><small>* Минимальная сумма пополнения 10 рублей.</small></p>
						<p><img src="/upload/98/47/9847f9456fe0f6301c6f0855b982a99d.jpg" alt=""></p>
					</form>
				<p><?php echo CHtml::link('Магазин дополнений', ['/admin/shop']) ?> | <?php echo CHtml::link('FAQ по оплате', ['/admin/shop/faq']) ?></p>
			</div>
		</div>

		<div class="dashboard dash-news">
			<h4><?php echo Yii::t('wot', 'WoT.PW News')?></h4>
			<div>
				<?php
					Yii::app()->params['skipSiteCheck'] = true;
					$news = News::model()->findAll(array('condition'=>"`site_id` = '168' AND `category_id` = '1'", 'order'=>"`id` DESC", 'limit'=>3));
					Yii::app()->params['skipSiteCheck'] = false;
				?>
				<? foreach ($news as $value){ ?>
					<p><sup><?php echo date('d.m.Y', $value->time+Yii::app()->params['moscow']) ?></sup> <a target="_blank" href="http://support.wot.pw/news/one/<?php echo $value->id ?>"><?php echo CHtml::encode($value->text1) ?></a></p>
				<? } ?>
			</div>
		</div>

		<div class="dashboard dash-support">
			<h4><?php echo Yii::t('wot', 'Support Page')?></h4>
			<div>
				<p><a href="http://support.wot.pw/forum"><?php echo Yii::t('wot', 'WoT.PW Support Forum')?></a> </p>
			</div>
		</div>

		<div class="dashboard dash-contest">
			<h4><?php echo Yii::t('wot', 'Competitions')?></h4>
			<div>
				<p><?php echo Yii::t('wot', 'Best customization for «Goliath» and «Phoenix» templates contest')?></p>
			</div>
		</div>

	</div>
