<?php
/* @var $this SiteController */

$this->pageTitle=Config::all('staff_members_title')->value;

?>


	<div class="block">
		<div class="head head2">
			<?php echo Config::all('staff_members_title')->value ?>
		</div>
		<div class="head head3">
			<?php echo CHtml::link('Состав', array('/staff/members')) ?>
			<?php echo CHtml::link('Онлайн', array('/staff/attendance'), array('class'=>'active')) ?>
			<?php echo $this->site->premium_companies ? CHtml::link('Роты', array('/staff/companies')) : '' ?>
			<?php echo CHtml::link('Бои', array('/staff/battles')) ?>
			<?php echo CHtml::link('Провинции', array('/staff/provinces')) ?>
			<?php echo $this->site->premium_tactic ? CHtml::link('Планшет', '#', array('class'=>'battles', 'onClick'=>'$.fancybox({type: "iframe", href: "/staff/tactic", padding: 0, width: 1008, height: 632, closeBtn: false}); return false;')) : '' ?>
			<?php echo CHtml::link('История игроков', array('/staff/history')) ?>
			<?php //echo CHtml::link('3 Кампания', array('/staff/famepoints')) ?>
			<div class="both"></div>
		</div>
		<div class="head head3">
			<? foreach ($this->clans as $key=>$value){
					if ($clan->id == $value->id)
						$active = $key;
			?>
				<?php echo CHtml::link($value->abbreviation, array('/staff/attendance', 'id'=>$value->id), array('style'=>'background-image: url(http://'.$this->site->cluster.'.wargaming.net/clans/media/clans/emblems/cl_'.substr($value->id, -3).'/'.$value->id.'/emblem_32x32.png); background-position: 8px center; background-repeat: no-repeat; padding-left: 45px; padding-right: 0; width: 120px;', 'class'=>$clan->id == $value->id ? 'active' : '')); ?>
			<? } ?>
			<div class="both"></div>
		<?php $this->widget('zii.widgets.CMenu',array(
			'htmlOptions'=>array('class'=>'staff-attnav'),
			'items'=>[
				['active'=>$param == 0, 'label'=>'Победы', 'url'=>array('/staff/attendance', 'clan_id'=>$clan->id, 'param'=>1, 'p'=>$limit)],
				['active'=>$param == 1, 'label'=>'БС', 'url'=>array('/staff/attendance', 'clan_id'=>$clan->id, 'param'=>2, 'p'=>$limit)],
				['active'=>$param == 2, 'label'=>'WN8', 'url'=>array('/staff/attendance', 'clan_id'=>$clan->id, 'param'=>3, 'p'=>$limit)],
				['active'=>$param == 3, 'label'=>'РЭ', 'url'=>array('/staff/attendance', 'clan_id'=>$clan->id, 'param'=>4, 'p'=>$limit)],
				['active'=>$param == 4, 'label'=>'Все бои', 'url'=>array('/staff/attendance', 'clan_id'=>$clan->id, 'param'=>5, 'p'=>$limit)],
				['active'=>$param == 5, 'label'=>'Клановые бои', 'url'=>array('/staff/attendance', 'clan_id'=>$clan->id, 'param'=>6, 'p'=>$limit)],
				['active'=>$param == 6, 'label'=>'Роты', 'url'=>array('/staff/attendance', 'clan_id'=>$clan->id, 'param'=>7, 'p'=>$limit)],
				['active'=>$param == 7, 'label'=>'КБ', 'url'=>array('/staff/attendance', 'clan_id'=>$clan->id, 'param'=>8, 'p'=>$limit)],
				['active'=>$param == 8, 'label'=>'Укреп оборона', 'url'=>array('/staff/attendance', 'clan_id'=>$clan->id, 'param'=>9, 'p'=>$limit)],
				['active'=>$param == 9, 'label'=>'Укреп вылазки', 'url'=>array('/staff/attendance', 'clan_id'=>$clan->id, 'param'=>10, 'p'=>$limit)],
				['active'=>$param == 10, 'label'=>'ГК абс', 'url'=>array('/staff/attendance', 'clan_id'=>$clan->id, 'param'=>11, 'p'=>$limit)],
				['active'=>$param == 11, 'label'=>'ГК чемп', 'url'=>array('/staff/attendance', 'clan_id'=>$clan->id, 'param'=>12, 'p'=>$limit)],
				['active'=>$param == 12, 'label'=>'ГК сред', 'url'=>array('/staff/attendance', 'clan_id'=>$clan->id, 'param'=>13, 'p'=>$limit)],
				['active'=>$param == 13, 'label'=>'Рандом', 'url'=>array('/staff/attendance', 'clan_id'=>$clan->id, 'param'=>14, 'p'=>$limit)],
				['active'=>$param == 14, 'label'=>'Промресурс', 'url'=>array('/staff/attendance', 'clan_id'=>$clan->id, 'param'=>15, 'p'=>$limit)],
			],
		)); ?>
			<div class="both"></div>
		</div>
		<div class="body body2">
			<? if (!$this->site->premium_reklama){ ?>
			<div class="block">
				<div class="body" style="text-align: center;">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Темный сайт - центральный баннер -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-2157584601406270"
     data-ad-slot="8247754143"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
				</div>
			</div>
			<? } ?>


		<div class="total att-calc">

			<div class="ac-one">
				<? if ($is_div){ ?>
					Сумма за <input type="text" id="days" value="30" maxlength="2"> дней <button onClick="calcSum();">Посчитать</button>
				<? } else { ?>
					Эффективность за <input type="text" id="days" value="30" maxlength="2"> дней <button onClick="calcDiv();">Посчитать</button>
				<? } ?>
			</div>

			<div class="ac-two">
				Показывать дней <select id="ucount">
					<option>30</option>
					<option<? if ($limit == 60){ ?> selected<? } ?>>60</option>
					<option<? if ($limit == 90){ ?> selected<? } ?>>90</option>
				</select>
				<button onClick="location.href='<?php echo $this->createUrl('/staff/attendance', ['clan_id'=>$clan->id, 'param'=>$param+1]); ?>?p='+$('#ucount').val();">Показать</button>
			</div>

			<p>
				Эффективность - разница между конечной и начальной датой выбранного периода (прирост статистики).<br>
				Сумма - общее количество боев или добытого промресурса за выбранный период.<br>
				Показывать дней - количество отображаемых дней в таблице. 
			</p>

		</div>


		<div class="scrollable-table">
			<div class="st-static">
				<table>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<? $i=0; foreach ($clan->accountsOrdered as $account){ ?>
						<? $i++;if ($i%26==0){ ?>
							<tr>
								<td>&nbsp;</td>
							</tr>
						<? } ?>
						<tr>
							<td><?php echo CHtml::link($account->nickname, ['/profile/account', 'id'=>$account->id]) ?></td>
						</tr>
					<? } ?>
				</table>
			</div>
			<div class="st-scrollable">
				<table>
					<tr class="date-row">
						<? foreach ($dates as $date){ ?>
							<td><?php echo date('d.m', $date) ?></td>
						<? } ?>
						<td><? if ($is_div){ ?>Сумма<? } else { ?>Эфф.<? } ?></td>
					</tr>
					<? $i=0; foreach ($data as $key1=>$value1){ $prev = 0; ?>
						<? $i++;if ($i%26==0){ ?>
							<tr class="date-row">
								<? foreach ($dates as $date){ ?>
									<td><?php echo date('d.m', $date) ?></td>
								<? } ?>
								<td><? if ($is_div){ ?>Сумма<? } else { ?>Эфф.<? } ?></td>
							</tr>
						<? } ?>
						<tr class="stat-row"> <?php //Класс отсюда не убирать ?>
							<? foreach ($value1 as $value2){ ?>
								<td<? if (!$is_div && $prev > 0 && $value2>$prev){ ?> g<? } ?><? if (!$is_div && $prev > 0 && $value2<$prev){ ?> l<? } ?><? if ($is_div && $value2 > 0){ ?> g<? } ?>><?php echo $value2 ?></td>
							<? $prev = $value2; } ?>
							<td></td>
						</tr>
					<? } ?>
				</table>
			</div>
		</div>

		<p class="actual">* Актуальность данных: <?php echo date('d.m.Y H:i', $clan->parser_info_time+Yii::app()->params['moscow']) ?> (МСК). Московское время: <?php echo date('d.m.Y H:i', time()+Yii::app()->params['moscow']) ?></p>
	
	</div>

</div>

<script type="text/javascript">
	var mdown = false;
	var mdx = 0;
	var mscroll = 0;
	$(function(){
		$('.st-scrollable').scrollLeft(99999);
		<? if ($is_div){ ?>
			calcSum();
		<? } else { ?>
			calcDiv();
		<? } ?>
		
		$('.st-scrollable')
		.mousedown(function(event){
			mdx = event.pageX;
			mdown = true;
			mscroll = $('.st-scrollable').scrollLeft();
		})
		.mouseup(function(event){
			mdown = false;
		})
		.mousemove(function(event){
			if (mdown)
			{
				$('.st-scrollable').scrollLeft(mscroll+mdx-event.pageX);
			}
		});
		$('body').mouseleave(function(event){
			mdown = false;
		})
		$('body').mouseup(function(event){
			mdown = false;
		})
		
	});
	function calcDiv()
	{
		var start = $('#days').val();
		$('.stat-row').each(function(){
			var first = '0';
			var result = 0;
			$('td:gt(-'+(start/1+2)+'):lt(-1)', this).each(function(){
				var val = $(this).html();
				if (val != '0')
				{
					if (first == '0')
						first = val;
					result = val-first;
				}
			});
			$('td:last', this).html(result.toFixed(2)).removeAttr('g').removeAttr('l');
			if (result > 0)
				$('td:last', this).attr('g', '');
			if (result < 0)
				$('td:last', this).attr('l', '');
		});
		$('.st-scrollable').scrollLeft(99999);
	}
	function calcSum()
	{
		var start = $('#days').val();
		$('.stat-row').each(function(){
			var result = 0;
			$('td:gt(-'+(start/1+2)+'):lt(-1)', this).each(function(){
				var val = $(this).html();
				if (val != '0')
				{
					result += val/1;
				}
			});
			$('td:last', this).html(result).removeAttr('g').removeAttr('l');
			if (result > 0)
				$('td:last', this).attr('g', '');
		});
		$('.st-scrollable').scrollLeft(99999);
	}
</script>