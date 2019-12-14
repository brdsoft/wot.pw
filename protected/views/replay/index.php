<!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width">
		<link href="/css/reset.css" rel="stylesheet">
		<link href="/css/replay.css" rel="stylesheet">
		<title>WOT.pw replays</title>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/modernizr.custom.min.js'); ?>
		<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/replay.js'); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.custom-scrollbar.js'); ?>
		<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.tablesorter.min.js'); ?>
	</head>
	<body>



		<div class="replay_wrapper"><!--replay_wrapper-->

			<div class="replay_tabs"><!--контейнер с табами-->

				<a class="replay_close_btn" onClick="window.parent.$.fancybox.close(); return false;" href="javascript:;"></a>

				<ul class="replay_navigation">
					<li class="active"><a href="#personal_results_tab">Личный результат</a></li>
					<li><a href="#team_result_tab">Командный результат</a></li>
					<li><a href="#detailed_report_tab">Подробный отчет</a></li>
					<li class="noclick"><a href="/<?php echo $parser->file ?>">Скачать реплей</a></li>
				</ul>

				<div id="personal_results_tab" style="display:block;"><!--первый таб-->



					<!--Личный результат-->
					<table class="replay_inset_personal">
						<tr>
							<td class="result_title <?php echo $data['common']['battleResult'] == -1 ? 'draw' : ($data['common']['battleResult'] == 1 ? 'win' : 'loss') ?>" colspan="2">

								<h1><?php echo $data['common']['battleResult'] == -1 ? 'Ничья' : ($data['common']['battleResult'] == 1 ? 'Победа' : 'Поражение') ?>!</h1>

								<div class="ribbon_medals">

									<div class="result_stripe">
										<table>
											<tr>
												<? foreach ($data['common']['leftAchievements'] as $value){ ?>
													<td>
														<div class="medal tooltip" title="<?php echo htmlspecialchars(nl2br('<b>'.$value->name_i18n.'</b><br>'.$value->description.($value->condition ? '<br>'.$value->condition : ''))) ?>">
															<img src="<?php echo $value->image ?>" alt="">
															<? if ($value->name == 'markOfMastery' && $data[1][0]['personal']['markOfMastery'] > $data[1][0]['personal']['prevMarkOfMastery']){ ?>
															<div class="yellow-stripe"></div>
															<? } ?>
														</div>
													</td>
												<? } ?>
											</tr>
										</table>
									</div>

									<div class="result_experience">
										<div class="result_experience_credits"><?php echo number_format(floor($data[1][0]['personal']['credits']), '0', '', ' ') ?></div>
										<div class="result_experience_exp"><?php echo number_format(floor($data[1][0]['personal']['xp']), '0', '', ' ') ?></div>
									</div>

									<div class="result_medals">
										<table>
											<tr>
												<? foreach ($data['common']['rightAchievements'] as $value){ ?>
													<td>
														<div class="medal tooltip" title="<?php echo htmlspecialchars(nl2br('<b>'.$value->name_i18n.'</b><br>'.$value->description.($value->condition ? '<br>'.$value->condition : ''))) ?>">
															<img src="<?php echo $value->image ?>" alt="">
															<? if ($value->section == 'epic'){ ?>
															<div class="yellow-stripe"></div>
															<? } ?>
														</div>
													</td>
												<? } ?>
											</tr>
										</table>
									</div>

								</div>

							</td>
						</tr>
						<tr class="result_title_two">
							<td><?php echo $data[0]['mapDisplayName'] ?> &mdash; <?php echo $data['common']['battleType'] ?></td>
							<td>Боевая эффективность</td>
						</tr>
						<tr class="mark1_detail_map_efficiency">
							<td class="mark1_detail_map">

								<table class="user_result_info">
									<tr>
										<td class="user_result_map">
											<div class="urm_img"><img src="/images/replay/maps/<?php echo $data[0]['mapName'] ?>.png" alt=""></div>
											<div class="urm_tank"><img src="<?php echo str_replace('/contour', '', $parser::$tanks[$data[0]['vehicles'][$data['common']['cId']]['vehicleType']]->image) ?>" alt=""></div>
										</td>
										<td class="user_result_data">
											<div class="user_result_nick"><?php echo $data[0]['vehicles'][$data['common']['cId']]['name'].($data[0]['vehicles'][$data['common']['cId']]['clanAbbrev'] ? ' ['.$data[0]['vehicles'][$data['common']['cId']]['clanAbbrev'].']' : '') ?></div>
											<div class="user_result_time"><?php echo $data[0]['dateTime'] ?></div>
											<div class="user_result_tank"><?php echo $parser::$tanks[$data[0]['vehicles'][$data['common']['cId']]['vehicleType']]->name_i18n ?></div>
										</td>
									</tr>
								</table>

								<table class="user_result_awards">
									<tr>
										<td></td>
										<td<?php echo !$data['common']['isPremium'] ? ' class="active"' : '' ?>>Без премиума</td>
										<td<?php echo $data['common']['isPremium'] ? ' class="active"' : '' ?>>С премиумом</td>
									</tr>
									<tr>
										<td>Кредиты</td>
										<td<?php echo !$data['common']['isPremium'] ? ' class="active"' : '' ?>><?php echo number_format(floor($data[1][0]['personal']['originalCredits']), 0, '', ' ') ?></td>
										<td<?php echo $data['common']['isPremium'] ? ' class="active"' : '' ?>><?php echo number_format(floor($data[1][0]['personal']['originalCredits']*$data[1][0]['personal']['premiumCreditsFactor10']/10), 0, '', ' ') ?></td>
									</tr>
									<tr>
										<td>Опыт</td>
										<td<?php echo !$data['common']['isPremium'] ? ' class="active"' : '' ?>><?php echo number_format(floor($data[1][0]['personal']['originalXP']*$data[1][0]['personal']['dailyXPFactor10']/10), 0, '', ' ') ?></td>
										<td<?php echo $data['common']['isPremium'] ? ' class="active"' : '' ?>><?php echo number_format(floor(floor($data[1][0]['personal']['originalXP']*$data[1][0]['personal']['premiumXPFactor10']/10)*$data[1][0]['personal']['dailyXPFactor10']/10), 0, '', ' ') ?></td>
									</tr>
								</table>

							</td>
							<td class="mark1_detail_efficiency">

								<div id="wrinkle-tanks" class="default-skin">

									<table>
										<tr>
											<td colspan="8" class="mde_bg"></td>
										</tr>
										<?
											uasort($data[1][0]['personal']['details'], function($b, $a){
												return strcmp($a["deathReason"], $b["deathReason"]);
											});
											$tankmens=array('Командир', 'Механик-водитель', 'Радист', 'Наводчик', 'Заряжающий');
											$devices=array('Двигатель','Боеукладка','Топливный бак','Радиостанция','Гусеница','Орудие','Механизм поворота башни','Приборы наблюдения');
										?>
										<? foreach ($data[1][0]['personal']['details'] as $key=>$value){ ?>
										<?
											$destroyedTankmen = strrev(str_pad(decbin($value['crits'] >> 24 & 255), 5, 0, STR_PAD_LEFT));
											$destroyedDevices = strrev(str_pad(decbin($value['crits'] >> 12 & 4095), 8, 0, STR_PAD_LEFT));
											$criticalDevices =  strrev(str_pad(decbin($value['crits'] & 4095), 8, 0, STR_PAD_LEFT));
											$crits = '';
											$critsCount = 0;
											$critsText = '';
											if ((int)$destroyedTankmen)
											{
												$critsText .= '<b>Раненый экипаж:</b><ul>';
												foreach ($tankmens as $key2=>$value2)
												{
													if ($destroyedTankmen[$key2])
													{
														$critsCount++;
														$critsText .= '<li>'.$value2.'</li>';
													}
												}
												$critsText .= '</ul>';
											}
											if ((int)$criticalDevices)
											{
												$critsText .= '<b>Поврежденные модули:</b><ul>';
												foreach ($devices as $key2=>$value2)
												{
													if ($criticalDevices[$key2])
													{
														$critsCount++;
														$critsText .= '<li>'.$value2.'</li>';
													}
												}
												$critsText .= '</ul>';
											}
											if ((int)$destroyedDevices)
											{
												$critsText .= '<b>Разрушенные модули:</b><ul>';
												foreach ($devices as $key2=>$value2)
												{
													if ($destroyedDevices[$key2])
													{
														$critsCount++;
														$critsText .= '<li>'.$value2.'</li>';
													}
												}
												$critsText .= '</ul>';
											}
										?>
										<tr>
											<td class="us_n" title="<?php echo $data[0]['vehicles'][$key]['name'].($data[0]['vehicles'][$key]['clanAbbrev'] ? '['.$data[0]['vehicles'][$key]['clanAbbrev'].']' : '') ?>"><span><?php echo $data[0]['vehicles'][$key]['name'].($data[0]['vehicles'][$key]['clanAbbrev'] ? '['.$data[0]['vehicles'][$key]['clanAbbrev'].']' : '') ?></span></td>
											<td class="ic_t" style="background-image:url(<?php echo str_replace('/contour', '/small', $parser::$tanks[$data[0]['vehicles'][$key]['vehicleType']]->image) ?>)" title="<?php echo $parser::$tanks[$data[0]['vehicles'][$key]['vehicleType']]->name_i18n ?>"><span><?php echo $parser::$tanks[$data[0]['vehicles'][$key]['vehicleType']]->short_name_i18n ?></span></td>
											<td class="de_t" <?php echo $value['deathReason']==0 ? 'title="Вы уничтожили этот танк противника"' : 'style="opacity:0.2"' ?>></td>
											<td class="da_t" <?php echo $value['damageDealt'] ? 'title="Вы нанесли урона: '.$value['damageDealt'].'. Пробитий: '.$value['piercings'].'"' : 'style="opacity:0.2"' ?>><? if ($value['piercings'] > 1){ ?><div class="cr_t_sum"><?php echo $value['piercings'] ?></div><? } ?></td>
											<td class="cr_t tooltip" <?php echo $value['crits'] ? 'title="'.htmlspecialchars($critsText).'"' : 'style="opacity:0.2"' ?>><? if ($critsCount > 1){ ?><div class="cr_t_sum"><?php echo $critsCount ?></div><? } ?></td>
											<td class="hi_t" <?php echo $value['damageAssistedRadio'] ? 'title="По вашим разведданным союзники нанесли очков урона: '.$value['damageAssistedRadio'].'"' : 'style="opacity:0.2"' ?>></td>
											<td class="di_t" <?php echo $value['spotted'] ? 'title="Вы обнаружили этот танк противника"' : 'style="opacity:0.2"' ?>></td>
										</tr>
										<tr>
											<td colspan="8" class="mde_bg"></td>
										</tr>
										<? } ?>
									</table>

								</div>

							</td>
						</tr>
					</table>
					<!--Личный результат-->



				</div><!--первый таб-->

				<div id="team_result_tab" style="display:none;"><!--второй таб-->

					<?
						uasort($data[1][1], function($b, $a){
							return strcmp($a["isAlive"], $b["isAlive"]);
						});
					?>
					<script type="text/javascript">
						var stat = {};
						var vehicles = {};
						<? foreach ($data[1][0]['vehicles'] as $key=>$value){ ?>
							stat['<?php echo $key ?>'] = <?php echo json_encode($value); ?>;
						<? } ?>
						<? foreach ($data[0]['vehicles'] as $key=>$value){
							$value['image'] = str_replace('/contour', '', $parser::$tanks[$value['vehicleType']]->image);
						?>
							vehicles['<?php echo $key ?>'] = <?php echo json_encode($value); ?>;
						<? } ?>
						function closeStat()
						{
							$('.t_r_a_table tr, .t_r_o_table tr').removeClass('active');
							$('#stat_right, #stat_left').removeAttr('data-id');
							$('#stat_right, #stat_left').hide();
						}
						function switchStat(id, side)
						{
							var s = $('#'+side);
							$('.t_r_a_table tr, .t_r_o_table tr').removeClass('active');
							if (s.attr('data-id') == id)
							{
								s.removeAttr('data-id');
								s.hide();
							}
							else
							{
								s.find('.popup_result_data').css('background-image', 'url('+vehicles[id].image+')');
								s.find('.popup_result_nick').html(vehicles[id].name+(vehicles[id].clanAbbrev == '' ? '' : ' ['+vehicles[id].clanAbbrev+']'));
								if (stat[id].killerID > 0)
									s.find('.popup_result_time').html('Уничтожен ('+vehicles[stat[id].killerID].name+(vehicles[stat[id].killerID].clanAbbrev == '' ? '' : ' ['+vehicles[stat[id].killerID].clanAbbrev+']')+')');
								else
									s.find('.popup_result_time').html('Выжил');
								if (stat[id].shots > 0)
									s.find('.popup_result_shots').html(stat[id].shots).removeClass('inactiv');
								else
									s.find('.popup_result_shots').html(0).addClass('inactiv');
								if (stat[id].directHits > 0)
									s.find('.popup_result_directHits').html(stat[id].directHits+'/'+stat[id].piercings).removeClass('inactiv');
								else
									s.find('.popup_result_directHits').html('0/0').addClass('inactiv');
								if (stat[id].explosionHits > 0)
									s.find('.popup_result_explosionHits').html(stat[id].explosionHits).removeClass('inactiv');
								else
									s.find('.popup_result_explosionHits').html('0').addClass('inactiv');
								if (stat[id].damageDealt > 0)
									s.find('.popup_result_damageDealt').html(stat[id].damageDealt).removeClass('inactiv');
								else
									s.find('.popup_result_damageDealt').html('0').addClass('inactiv');
								if (stat[id].sniperDamageDealt > 0)
									s.find('.popup_result_sniperDamageDealt').html(stat[id].sniperDamageDealt).removeClass('inactiv');
								else
									s.find('.popup_result_sniperDamageDealt').html('0').addClass('inactiv');
								if (stat[id].directHitsReceived > 0)
									s.find('.popup_result_directHitsReceived').html(stat[id].directHitsReceived).removeClass('inactiv');
								else
									s.find('.popup_result_directHitsReceived').html('0').addClass('inactiv');
								if (stat[id].piercingsReceived > 0)
									s.find('.popup_result_piercingsReceived').html(stat[id].piercingsReceived).removeClass('inactiv');
								else
									s.find('.popup_result_piercingsReceived').html('0').addClass('inactiv');
								if (stat[id].noDamageDirectHitsReceived > 0)
									s.find('.popup_result_noDamageDirectHitsReceived').html(stat[id].noDamageDirectHitsReceived).removeClass('inactiv');
								else
									s.find('.popup_result_noDamageDirectHitsReceived').html('0').addClass('inactiv');
								if (stat[id].explosionHitsReceived > 0)
									s.find('.popup_result_explosionHitsReceived').html(stat[id].explosionHitsReceived).removeClass('inactiv');
								else
									s.find('.popup_result_explosionHitsReceived').html('0').addClass('inactiv');
								if (stat[id].damageBlockedByArmor > 0)
									s.find('.popup_result_damageBlockedByArmor').html(stat[id].damageBlockedByArmor).removeClass('inactiv');
								else
									s.find('.popup_result_damageBlockedByArmor').html('0').addClass('inactiv');
								if (stat[id].tkills > 0 || stat[id].tdamageDealt > 0)
									s.find('.popup_result_tkills').html(stat[id].tkills+'/'+stat[id].tdamageDealt).removeClass('inactiv');
								else
									s.find('.popup_result_tkills').html('0/0').addClass('inactiv');
								if (stat[id].spotted > 0)
									s.find('.popup_result_spotted').html(stat[id].spotted).removeClass('inactiv');
								else
									s.find('.popup_result_spotted').html('0').addClass('inactiv');
								if (stat[id].damaged > 0 || stat[id].kills > 0)
									s.find('.popup_result_damaged').html(stat[id].damaged+'/'+stat[id].kills).removeClass('inactiv');
								else
									s.find('.popup_result_damaged').html('0/0').addClass('inactiv');
								if (stat[id].damageAssistedTrack > 0)
									s.find('.popup_result_damageAssistedTrack').html(stat[id].damageAssistedTrack).removeClass('inactiv');
								else
									s.find('.popup_result_damageAssistedTrack').html('0').addClass('inactiv');
								if (stat[id].capturePoints > 0 || stat[id].droppedCapturePoints > 0)
									s.find('.popup_result_capturePoints').html(stat[id].capturePoints+'/'+stat[id].droppedCapturePoints).removeClass('inactiv');
								else
									s.find('.popup_result_capturePoints').html('0/0').addClass('inactiv');
								if (stat[id].mileage > 0)
									s.find('.popup_result_mileage').html((stat[id].mileage / 1000).toFixed(2).replace('.', ',')).removeClass('inactiv');
								else
									s.find('.popup_result_mileage').html('0').addClass('inactiv');
								
								
								$('tr[data-id='+id+']').addClass('active');
								s.attr('data-id', id);
								s.show();
							}
						}
					</script>
					<!--Командный результат-->
					<div class="replay_team_results">
						<div class="team_results_allies">

							<div class="t_r_a_title">Союзники</div>

							<table class="t_r_a_table">
								<thead class="t_r_a_sort">
									<tr>
										<th class="sort_p"></th>
										<th class="sort_n"></th>
										<th class="sort_t"></th>
										<th class="sort_d"></th>
										<th class="sort_k"></th>
										<th class="sort_e"></th>
										<th class="sort_m"></th>
									</tr>
								</thead>
                                <tbody>
									<? foreach ($data[1][1] as $key=>$value){ ?>
									<? if ($value['team'] != $data['common']['leftTeam']){ continue; } ?>
									<tr <?php echo $value['isAlive'] ? '' : 'class="tra_destroyed"' ?> data-id="<?php echo $key ?>" onClick="switchStat('<?php echo $key ?>', 'stat_right');">
										<td class="tra_p">
											<? if ($data[1][0]['players'][$data[1][0]['vehicles'][$key]['accountDBID']]['vzvod']){ ?><span><?php echo $data[1][0]['players'][$data[1][0]['vehicles'][$key]['accountDBID']]['vzvod'] ?></span><? } ?>
										</td>
										<td class="tra_n">
											<span><?php echo $value['name'].($value['clanAbbrev'] ? '['.$value['clanAbbrev'].']' : '') ?></span>
										</td>
										<td class="tra_t" style="background-image:url(<?php echo str_replace('/contour', '/small', $parser::$tanks[$value['vehicleType']]->image) ?>)" title="<?php echo $parser::$tanks[$value['vehicleType']]->name_i18n ?>">
											<span><?php echo $parser::$tanks[$value['vehicleType']]->short_name_i18n ?></span>
										</td>
										<td class="tra_d"><?php echo $data[1][0]['vehicles'][$key]['damageDealt'] ?></td>
										<td class="tra_k"><?php echo $data[1][0]['vehicles'][$key]['kills'] ?></td>
										<td class="tra_e"><?php echo $data[1][0]['vehicles'][$key]['xp'] ?></td>
										<td class="tra_m">
											<? if ($data[1][0]['vehicles'][$key]['achievementsText']){ ?>
											<span class="achievements tooltip" title="<?php echo implode('&lt;br&gt;', $data[1][0]['vehicles'][$key]['achievementsText']) ?>">
												<span class="count"><?php echo count($data[1][0]['vehicles'][$key]['achievementsText']) ?></span>
											</span>
											<? } ?>
										</td>
									</tr>
									<? } ?>
                                </tbody>
							</table>

							<div class="t_r_a_popup" style="display:none;" id="stat_left">
								<div class="tr_popup_hdr">
									<div class="popup_result_data">
										<div class="popup_result_nick"></div>
										<div class="popup_result_tank"></div>
										<div class="popup_result_time"></div>
									</div>
								</div>
								<div class="popup_table">
									<table>
										<tr>
											<td>Произведено выстрелов</td>
											<td class="popup_result_shots"></td>
										</tr>
										<tr>
											<td class="sub_inf">прямых попаданий/пробитий</td>
											<td class="popup_result_directHits"></td>
										</tr>
										<tr>
											<td class="sub_inf">осколочно-фугасных повреждений</td>
											<td class="popup_result_explosionHits"></td>
										</tr>
										<tr>
											<td>Нанесено урона</td>
											<td class="popup_result_damageDealt"></td>
										</tr>
										<tr>
											<td class="sub_inf">с дистанции свыше 300 м</td>
											<td class="popup_result_sniperDamageDealt"></td>
										</tr>
										<tr>
											<td>Получено попаданий</td>
											<td class="popup_result_directHitsReceived"></td>
										</tr>
										<tr>
											<td class="sub_inf">пробитий</td>
											<td class="popup_result_piercingsReceived"></td>
										</tr>
										<tr>
											<td class="sub_inf">не нанесших урон</td>
											<td class="popup_result_noDamageDirectHitsReceived"></td>
										</tr>
										<tr>
											<td>Получено попаданий осколками</td>
											<td class="popup_result_explosionHitsReceived"></td>
										</tr>
										<tr>
											<td>Урон, заблокированный бронёй</td>
											<td class="popup_result_damageBlockedByArmor"></td>
										</tr>
										<tr>
											<td>Урон союзникам (уничтожено/повреждений)</td>
											<td class="popup_result_tkills"></td>
										</tr>
										<tr>
											<td>Обнаружено машин противника</td>
											<td class="popup_result_spotted"></td>
										</tr>
										<tr>
											<td>Повреждено/уничтожено машин противника</td>
											<td class="popup_result_damaged"></td>
										</tr>
										<tr>
											<td>Урон, нанесённый с помощью данного игрока</td>
											<td class="popup_result_damageAssistedTrack"></td>
										</tr>
										<tr>
											<td>Очки захвата/защиты базы</td>
											<td class="popup_result_capturePoints"></td>
										</tr>
										<tr>
											<td>Пройдено километров</td>
											<td class="popup_result_mileage"></td>
										</tr>
									</table>

									<div class="popup_result_close_btn"><a href="#" onClick="closeStat(); return false;">Закрыть</a></div>

								</div>
							</div>

						</div>
						<div class="team_results_separator"></div>
						<div class="team_results_opponents">

							<div class="t_r_o_title">Противники</div>

							<table class="t_r_o_table">
								<thead class="t_r_o_sort">
									<tr>
										<th class="sort_p"></th>
										<th class="sort_n"></th>
										<th class="sort_t"></th>
										<th class="sort_d"></th>
										<th class="sort_k"></th>
										<th class="sort_e"></th>
										<th class="sort_m"></th>
									</tr>
								</thead>
								<tbody>
									<? foreach ($data[1][1] as $key=>$value){ ?>
									<? if ($value['team'] != $data['common']['rightTeam']){ continue; } ?>
									<tr <?php echo $value['isAlive'] ? '' : 'class="tra_destroyed"' ?> data-id="<?php echo $key ?>" onClick="switchStat('<?php echo $key ?>', 'stat_left');">
										<td class="tra_p">
											<? if ($data[1][0]['players'][$data[1][0]['vehicles'][$key]['accountDBID']]['vzvod']){ ?><span><?php echo $data[1][0]['players'][$data[1][0]['vehicles'][$key]['accountDBID']]['vzvod'] ?></span><? } ?>
										</td>
										<td class="tra_n">
											<span><?php echo $value['name'].($value['clanAbbrev'] ? '['.$value['clanAbbrev'].']' : '') ?></span>
										</td>
										<td class="tra_t" style="background-image:url(<?php echo str_replace('/contour', '/small', $parser::$tanks[$value['vehicleType']]->image) ?>)" title="<?php echo $parser::$tanks[$value['vehicleType']]->name_i18n ?>">
											<span><?php echo $parser::$tanks[$value['vehicleType']]->short_name_i18n ?></span>
										</td>
										<td class="tra_d"><?php echo $data[1][0]['vehicles'][$key]['damageDealt'] ?></td>
										<td class="tra_k"><?php echo $data[1][0]['vehicles'][$key]['kills'] ?></td>
										<td class="tra_e"><?php echo $data[1][0]['vehicles'][$key]['xp'] ?></td>
										<td class="tra_m">
											<? if ($data[1][0]['vehicles'][$key]['achievementsText']){ ?>
											<span class="achievements tooltip" title="<?php echo implode('&lt;br&gt;', $data[1][0]['vehicles'][$key]['achievementsText']) ?>">
												<span class="count"><?php echo count($data[1][0]['vehicles'][$key]['achievementsText']) ?></span>
											</span>
											<? } ?>
										</td>
									</tr>
									<? } ?>
								</tbody>
							</table>

							<div class="t_r_a_popup" style="display:none;" id="stat_right">
								<div class="tr_popup_hdr">
									<div class="popup_result_data">
										<div class="popup_result_nick"></div>
										<div class="popup_result_tank"></div>
										<div class="popup_result_time"></div>
									</div>
								</div>
								<div class="popup_table">
									<table>
										<tr>
											<td>Произведено выстрелов</td>
											<td class="popup_result_shots"></td>
										</tr>
										<tr>
											<td class="sub_inf">прямых попаданий/пробитий</td>
											<td class="popup_result_directHits"></td>
										</tr>
										<tr>
											<td class="sub_inf">осколочно-фугасных повреждений</td>
											<td class="popup_result_explosionHits"></td>
										</tr>
										<tr>
											<td>Нанесено урона</td>
											<td class="popup_result_damageDealt"></td>
										</tr>
										<tr>
											<td class="sub_inf">с дистанции свыше 300 м</td>
											<td class="popup_result_sniperDamageDealt"></td>
										</tr>
										<tr>
											<td>Получено попаданий</td>
											<td class="popup_result_directHitsReceived"></td>
										</tr>
										<tr>
											<td class="sub_inf">пробитий</td>
											<td class="popup_result_piercingsReceived"></td>
										</tr>
										<tr>
											<td class="sub_inf">не нанесших урон</td>
											<td class="popup_result_noDamageDirectHitsReceived"></td>
										</tr>
										<tr>
											<td>Получено попаданий осколками</td>
											<td class="popup_result_explosionHitsReceived"></td>
										</tr>
										<tr>
											<td>Урон, заблокированный бронёй</td>
											<td class="popup_result_damageBlockedByArmor"></td>
										</tr>
										<tr>
											<td>Урон союзникам (уничтожено/повреждений)</td>
											<td class="popup_result_tkills"></td>
										</tr>
										<tr>
											<td>Обнаружено машин противника</td>
											<td class="popup_result_spotted"></td>
										</tr>
										<tr>
											<td>Повреждено/уничтожено машин противника</td>
											<td class="popup_result_damaged"></td>
										</tr>
										<tr>
											<td>Урон, нанесённый с помощью данного игрока</td>
											<td class="popup_result_damageAssistedTrack"></td>
										</tr>
										<tr>
											<td>Очки захвата/защиты базы</td>
											<td class="popup_result_capturePoints"></td>
										</tr>
										<tr>
											<td>Пройдено километров</td>
											<td class="popup_result_mileage"></td>
										</tr>
									</table>

									<div class="popup_result_close_btn"><a href="#" onClick="closeStat(); return false;">Закрыть</a></div>

								</div>
							</div>

						</div>
					</div>
					<!--Командный результат-->



				</div><!--второй таб-->

				<div id="detailed_report_tab" style="display:none;"><!--третий таб-->



					<!--Подробный отчет-->
					<div class="r_n_block <?php echo $data['common']['isPremium'] ? 'global_class_noprem' : 'global_class_prem' ?>"><!--|||||||||||||||||||||||||||| Глобальный переключатель премиум - не премиум ||||||||||||||||||||||||||||-->

						<table class="r_n_table">
							<tr>
								<td class="rnt_statistics">

									<div class="rnt_title">Статистика</div>
									<?php $stat = $data[1][0]['vehicles'][$data['common']['cId']]; ?>
									<div class="rnt_statistics_wrp">
										<table>
											<tr>
												<td>Произведено выстрелов</td>
												<td<?php echo $stat['shots'] ? '' : ' class="inactiv"' ?>><?php echo $stat['shots'] ?></td>
											</tr>
											<tr>
												<td class="t_sub">прямых попаданий/пробитий</td>
												<td<?php echo $stat['directHits'] ? '' : ' class="inactiv"' ?>><?php echo $stat['directHits'].'/'.$stat['piercings'] ?></td>
											</tr>
											<tr>
												<td class="t_sub">осколочно-фугасных повреждений</td>
												<td<?php echo $stat['explosionHits'] ? '' : ' class="inactiv"' ?>><?php echo $stat['explosionHits'] ?></td>
											</tr>
											<tr>
												<td>Нанесено урона</td>
												<td<?php echo $stat['damageDealt'] ? '' : ' class="inactiv"' ?>><?php echo $stat['damageDealt'] ?></td>
											</tr>
											<tr>
												<td class="t_sub">с дистанции свыше 300 м</td>
												<td<?php echo $stat['sniperDamageDealt'] ? '' : ' class="inactiv"' ?>><?php echo $stat['sniperDamageDealt'] ?></td>
											</tr>
											<tr>
												<td>Получено попаданий</td>
												<td<?php echo $stat['directHitsReceived'] ? '' : ' class="inactiv"' ?>><?php echo $stat['directHitsReceived'] ?></td>
											</tr>
											<tr>
												<td class="t_sub">пробитий</td>
												<td<?php echo $stat['piercingsReceived'] ? '' : ' class="inactiv"' ?>><?php echo $stat['piercingsReceived'] ?></td>
											</tr>
											<tr>
												<td class="t_sub">не нанесших урон</td>
												<td<?php echo $stat['noDamageDirectHitsReceived'] ? '' : ' class="inactiv"' ?>><?php echo $stat['noDamageDirectHitsReceived'] ?></td>
											</tr>
											<tr>
												<td>Получено попаданий осколками</td>
												<td<?php echo $stat['explosionHitsReceived'] ? '' : ' class="inactiv"' ?>><?php echo $stat['explosionHitsReceived'] ?></td>
											</tr>
											<tr>
												<td>Урон, заблокированный броней</td>
												<td<?php echo $stat['damageBlockedByArmor'] ? '' : ' class="inactiv"' ?>><?php echo $stat['damageBlockedByArmor'] ?></td>
											</tr>
											<tr>
												<td>Урон союзникам (уничтожено/повреждений)</td>
												<td<?php echo $stat['tkills'] || $stat['tdamageDealt'] ? '' : ' class="inactiv"' ?>><?php echo $stat['tkills'].'/'.$stat['tdamageDealt'] ?></td>
											</tr>
											<tr>
												<td>Обнаружено машин противника</td>
												<td<?php echo $stat['spotted'] ? '' : ' class="inactiv"' ?>><?php echo $stat['spotted'] ?></td>
											</tr>
											<tr>
												<td>Повреждено/уничтожено машин противника</td>
												<td<?php echo $stat['damaged'] || $stat['kills'] ? '' : ' class="inactiv"' ?>><?php echo $stat['damaged'].'/'.$stat['kills'] ?></td>
											</tr>
											<tr>
												<td>Урон, нанесённый с помощью данного игрока</td>
												<td<?php echo $stat['damageAssistedTrack'] || $stat['damageAssistedRadio'] ? '' : ' class="inactiv"' ?>><?php echo $stat['damageAssistedTrack'] + $stat['damageAssistedRadio'] ?></td>
											</tr>
											<tr>
												<td>Очки захвата/защиты базы</td>
												<td<?php echo $stat['capturePoints'] || $stat['droppedCapturePoints'] ? '' : ' class="inactiv"' ?>><?php echo $stat['capturePoints'].'/'.$stat['droppedCapturePoints'] ?></td>
											</tr>
											<tr>
												<td>Пройдено километров</td>
												<td<?php echo $stat['mileage'] ? '' : ' class="inactiv"' ?>><?php echo number_format(floor($stat['mileage']/10)/100, 2, ',', '') ?></td>
											</tr>
										</table>
									</div>

								</td>
								<td class="rnt_credits">

									<div class="rnt_title"><span>Кредиты</span> <span>С премиумом</span></div>

									<div class="rnt_credits_wrp">

										<div class="rnt_credits_block">
											<div class="rnt_credits_noprem">

												<table>
													<tr>
														<td>Начислено за бой</td>
														<td class="p_or_np coin_silver"><?php echo number_format(floor($data[1][0]['personal']['originalCredits']), 0, '', ' ') ?></td>
														<td></td>
													</tr>
													<tr>
														<td>Штраф за урон союзникам</td>
														<?php $sum = floor($data[1][0]['personal']['creditsContributionOut']); ?>
														<td class="p_or_np coin_silver <?php echo $sum ? 'coin_minus' : '' ?>"><?php echo $sum ? '-' : '' ?><?php echo number_format($sum, 0, '', ' ') ?></td>
														<td></td>
													</tr>
													<tr>
														<td>Компенсация за урон от союзников</td>
														<td class="p_or_np coin_silver"><?php echo number_format(floor($data[1][0]['personal']['creditsContributionIn']), 0, '', ' ') ?></td>
														<td></td>
													</tr>
													<tr>
														<td>Автоматический ремонт техники</td>
														<?php $sum = floor($data[1][0]['personal']['repair']); ?>
														<td class="p_or_np coin_silver <?php echo $sum ? 'coin_minus' : '' ?>"><?php echo $sum ? '-' : '' ?><?php echo number_format($sum, 0, '', ' ') ?></td>
														<td></td>
													</tr>
													<tr>
														<td>Автопополнение боекомплекта</td>
														<?php $sum = floor($data[1][0]['personal']['autoLoadCost'][0]); ?>
														<td class="p_or_np coin_silver <?php echo $sum ? 'coin_minus' : '' ?>"><?php echo $sum ? '-' : '' ?><?php echo number_format($sum, 0, '', ' ') ?></td>
														<?php $sum = floor($data[1][0]['personal']['autoLoadCost'][1]); ?>
														<td class="p_or_np coin_gold <?php echo $sum ? 'coin_minus' : '' ?>"><?php echo $sum ? '-' : '' ?><?php echo number_format($sum, 0, '', ' ') ?></td>
													</tr>
													<tr>
														<td>Автопополнение снаряжения</td>
														<?php $sum = floor($data[1][0]['personal']['autoEquipCost'][0]); ?>
														<td class="p_or_np coin_silver <?php echo $sum ? 'coin_minus' : '' ?>"><?php echo $sum ? '-' : '' ?><?php echo number_format($sum, 0, '', ' ') ?></td>
														<?php $sum = floor($data[1][0]['personal']['autoEquipCost'][1]); ?>
														<td class="p_or_np coin_gold <?php echo $sum ? 'coin_minus' : '' ?>"><?php echo $sum ? '-' : '' ?><?php echo number_format($sum, 0, '', ' ') ?></td>
													</tr>
													<tr>
														<td>Итого:</td>
														<?php $sum = floor($data[1][0]['personal']['originalCredits']-$data[1][0]['personal']['creditsContributionOut']+$data[1][0]['personal']['creditsContributionIn']-$data[1][0]['personal']['repair']-$data[1][0]['personal']['autoLoadCost'][0]-$data[1][0]['personal']['autoEquipCost'][0]); ?>
														<td class="p_or_np coin_silver <?php echo $sum < 0 ? 'coin_minus' : '' ?>"><?php echo number_format($sum, 0, '', ' ') ?></td>
														<?php $sum = floor(-$data[1][0]['personal']['autoLoadCost'][1]-$data[1][0]['personal']['autoEquipCost'][1]); ?>
														<td class="p_or_np coin_gold <?php echo $sum < 0 ? 'coin_minus' : '' ?>"><?php echo number_format($sum, 0, '', ' ') ?></td>
													</tr>
												</table>

											</div>
											<div class="rnt_credits_prem">

												<table>
													<tr>
														<td class="p_or_np coin_silver"><?php echo number_format(floor($data[1][0]['personal']['originalCredits']*$data[1][0]['personal']['premiumCreditsFactor10']/10), 0, '', ' ') ?></td>
														<td></td>
													</tr>
													<tr>
														<?php $sum = floor($data[1][0]['personal']['creditsContributionOut']); ?>
														<td class="p_or_np coin_silver <?php echo $sum ? 'coin_minus' : '' ?>"><?php echo $sum ? '-' : '' ?><?php echo number_format($sum, 0, '', ' ') ?></td>
														<td></td>
													</tr>
													<tr>
														<td class="p_or_np coin_silver"><?php echo number_format(floor($data[1][0]['personal']['creditsContributionIn']), 0, '', ' ') ?></td>
														<td></td>
													</tr>
													<tr>
														<?php $sum = floor($data[1][0]['personal']['repair']); ?>
														<td class="p_or_np coin_silver <?php echo $sum ? 'coin_minus' : '' ?>"><?php echo $sum ? '-' : '' ?><?php echo number_format($sum, 0, '', ' ') ?></td>
														<td></td>
													</tr>
													<tr>
														<?php $sum = floor($data[1][0]['personal']['autoLoadCost'][0]); ?>
														<td class="p_or_np coin_silver <?php echo $sum ? 'coin_minus' : '' ?>"><?php echo $sum ? '-' : '' ?><?php echo number_format($sum, 0, '', ' ') ?></td>
														<?php $sum = floor($data[1][0]['personal']['autoLoadCost'][1]); ?>
														<td class="p_or_np coin_gold <?php echo $sum ? 'coin_minus' : '' ?>"><?php echo $sum ? '-' : '' ?><?php echo number_format($sum, 0, '', ' ') ?></td>
													</tr>
													<tr>
														<?php $sum = floor($data[1][0]['personal']['autoEquipCost'][0]); ?>
														<td class="p_or_np coin_silver <?php echo $sum ? 'coin_minus' : '' ?>"><?php echo $sum ? '-' : '' ?><?php echo number_format($sum, 0, '', ' ') ?></td>
														<?php $sum = floor($data[1][0]['personal']['autoEquipCost'][1]); ?>
														<td class="p_or_np coin_gold <?php echo $sum ? 'coin_minus' : '' ?>"><?php echo $sum ? '-' : '' ?><?php echo number_format($sum, 0, '', ' ') ?></td>
													</tr>
													<tr>
														<?php $sum = floor($data[1][0]['personal']['originalCredits']*$data[1][0]['personal']['premiumCreditsFactor10']/10-$data[1][0]['personal']['creditsContributionOut']+$data[1][0]['personal']['creditsContributionIn']-$data[1][0]['personal']['repair']-$data[1][0]['personal']['autoLoadCost'][0]-$data[1][0]['personal']['autoEquipCost'][0]); ?>
														<td class="p_or_np coin_silver <?php echo $sum < 0 ? 'coin_minus' : '' ?>"><?php echo number_format($sum, 0, '', ' ') ?></td>
														<?php $sum = floor(-$data[1][0]['personal']['autoLoadCost'][1]-$data[1][0]['personal']['autoEquipCost'][1]); ?>
														<td class="p_or_np coin_gold <?php echo $sum < 0 ? 'coin_minus' : '' ?>"><?php echo number_format($sum, 0, '', ' ') ?></td>
													</tr>
												</table>

											</div>
										</div>

									</div>

								</td>
							</tr>
							<tr>
								<td class="rnt_time">

									<div class="rnt_title">Время</div>

									<div class="rnt_time_wrp">
										<table>
											<tr>
												<td>Начало боя</td>
												<td><?php echo preg_replace('=^.+(\d+:\d+):\d+$=', '$1', $data[0]['dateTime']) ?></td>
											</tr>
											<tr>
												<td>Продолжительность боя</td>
												<td><?php echo floor($data[1][0]['common']['duration'] / 60) ?> мин <?php echo floor($data[1][0]['common']['duration'] % 60) ?> с</td>
											</tr>
											<tr>
												<td class="sub_inf">Время в бою до уничтожения</td>
												<td><?php echo ($data[1][1][$data['common']['cId']]['isAlive'] == 1 ? '&mdash;' : floor($data[1][0]['personal']['lifeTime'] / 60).' мин '.floor($data[1][0]['personal']['lifeTime'] % 60).' с') ?></td>
											</tr>
										</table>
									</div>

								</td>
								<td class="rnt_experience">

									<div class="rnt_title">Опыт</div>

									<div class="rnt_experience_wrp">

										<div class="rnt_experience_block">
											<div class="rnt_experience_noprem">

												<table>
													<tr>
														<td>Начислено за бой</td>
														<td class="p_or_np exp_norml"><?php echo number_format(floor($data[1][0]['personal']['originalXP']), 0, '', ' ') ?></td>
														<td class="p_or_np exp_bonus"><?php echo number_format(floor($data[1][0]['personal']['originalFreeXP']), 0, '', ' ') ?></td>
													</tr>
													<tr>
														<td>Штраф за урон союзникам</td>
														<?php $sum = floor($data[1][0]['personal']['xpPenalty']); ?>
														<td class="p_or_np exp_norml <?php echo $sum ? 'coin_minus' : '' ?>"><?php echo $sum ? '-' : '' ?><?php echo number_format($sum, 0, '', ' ') ?></td>
														<td></td>
													</tr>
													<? if ($data[1][0]['personal']['dailyXPFactor10'] > 10){ ?>
													<tr>
														<td>Награда за первую победу в день</td>
														<td class="p_or_np exp_norml">x<?php echo floor($data[1][0]['personal']['dailyXPFactor10']/10) ?></td>
														<td class="p_or_np exp_bonus">x<?php echo floor($data[1][0]['personal']['dailyXPFactor10']/10) ?></td>
													</tr>
													<? } ?>
													<tr>
														<td>Итого:</td>
														<?php $sum = floor($data[1][0]['personal']['originalXP']*$data[1][0]['personal']['dailyXPFactor10']/10-$data[1][0]['personal']['xpPenalty']); ?>
														<td class="p_or_np exp_norml"><?php echo $sum > 0 ? number_format($sum, 0, '', ' ') : 0 ?></td>
														<?php $sum = floor($data[1][0]['personal']['originalFreeXP']*$data[1][0]['personal']['dailyXPFactor10']/10); ?>
														<td class="p_or_np exp_norml"><?php echo $sum > 0 ? number_format($sum, 0, '', ' ') : 0 ?></td>
													</tr>
												</table>

											</div>
											<div class="rnt_experience_prem">

												<table>
													<tr>
														<td class="p_or_np exp_norml"><?php echo number_format(floor($data[1][0]['personal']['originalXP']*$data[1][0]['personal']['premiumXPFactor10']/10), 0, '', ' ') ?></td>
														<td class="p_or_np exp_bonus"><?php echo number_format(floor($data[1][0]['personal']['originalFreeXP']*$data[1][0]['personal']['premiumXPFactor10']/10), 0, '', ' ') ?></td>
													</tr>
													<tr>
														<?php $sum = floor($data[1][0]['personal']['xpPenalty']); ?>
														<td class="p_or_np exp_norml <?php echo $sum ? 'coin_minus' : '' ?>"><?php echo $sum ? '-' : '' ?><?php echo number_format($sum, 0, '', ' ') ?></td>
														<td></td>
													</tr>
													<? if ($data[1][0]['personal']['dailyXPFactor10'] > 10){ ?>
													<tr>
														<td class="p_or_np exp_norml">x<?php echo floor($data[1][0]['personal']['dailyXPFactor10']/10) ?></td>
														<td class="p_or_np exp_bonus">x<?php echo floor($data[1][0]['personal']['dailyXPFactor10']/10) ?></td>
													</tr>
													<? } ?>
													<tr>
														<?php $sum = floor(floor($data[1][0]['personal']['originalXP']*$data[1][0]['personal']['premiumXPFactor10']/10)*$data[1][0]['personal']['dailyXPFactor10']/10-$data[1][0]['personal']['xpPenalty']); ?>
														<td class="p_or_np exp_norml"><?php echo $sum > 0 ? number_format($sum, 0, '', ' ') : 0 ?></td>
														<?php $sum = floor(floor($data[1][0]['personal']['originalFreeXP']*$data[1][0]['personal']['premiumXPFactor10']/10)*$data[1][0]['personal']['dailyXPFactor10']/10); ?>
														<td class="p_or_np exp_norml"><?php echo $sum > 0 ? number_format($sum, 0, '', ' ') : 0 ?></td>
													</tr>
												</table>

											</div>
										</div>

									</div>

								</td>
							</tr>
						</table>

					</div>
					<!--Подробный отчет-->



				</div><!--третий таб-->



			</div><!--контейнер с табами-->

			<div class="r-border-b"></div>

		</div><!--replay_wrapper-->



    </body>
</html>