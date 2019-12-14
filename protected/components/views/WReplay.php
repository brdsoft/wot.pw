<?php $stat = $data[1][0]['vehicles'][$data['common']['cId']]; ?>
<div onClick="$.fancybox({type: 'iframe', href: '/replay?file=<?php echo $file ?>', padding: 0, width: 1018, closeBtn: false});" class="rm rm-<?php echo $data['common']['battleResult'] == -1 ? 'draw' : ($data['common']['battleResult'] == 1 ? 'win' : 'loss') ?>">

		<h3><?php echo $data['common']['battleResult'] == -1 ? 'Ничья' : ($data['common']['battleResult'] == 1 ? 'Победа' : 'Поражение') ?>!</h3>

		<div class="rm-tape">

			<div class="rm-tape-loot">
				<span class="rm-tape-money"><?php echo number_format(floor($data[1][0]['personal']['credits']), '0', '', ' ') ?></span>
				<span class="rm-tape-exp"><?php echo number_format(floor($data[1][0]['personal']['xp']), '0', '', ' ') ?></span>
			</div>

			<div class="rm-tape-medal">
				<? foreach ($data['common']['leftAchievements'] as $value){ ?>
					<? if ($value->name == 'markOfMastery'){ ?>
						<img src="<?php echo $value->image ?>" alt="" title="<?php echo $value->name_i18n ?>">
					<? } ?>
				<? } ?>
				<? foreach ($data['common']['rightAchievements'] as $value){ ?>
					<img src="<?php echo $value->image ?>" alt="" title="<?php echo $value->name_i18n ?>">
				<? } ?>
			</div>

		</div>

		<div class="rm-info">
			<span>Техника: <?php echo $parser::$tanks[$data[0]['vehicles'][$data['common']['cId']]['vehicleType']]->short_name_i18n ?></span>
			<span>Карта: <?php echo $data[0]['mapDisplayName'] ?></span><br>
			<span>Урон: <?php echo $stat['damageDealt'] ?></span>
			<span>Уничтожено: <?php echo $stat['kills'] ?></span>
			<span>Вытанковано: <?php echo $stat['damageBlockedByArmor'] ?></span>
		</div>

</div>