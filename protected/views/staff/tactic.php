	<div style="display:none;">
		<iframe id="icon_test" src="/images/maps/svg/test.svg"></iframe>
		<iframe id="icon_ht" src="/images/maps/svg/ht.svg"></iframe>
		<iframe id="icon_ht_" src="/images/maps/svg/ht_.svg"></iframe>
		<iframe id="icon_lt" src="/images/maps/svg/lt.svg"></iframe>
		<iframe id="icon_lt_" src="/images/maps/svg/lt_.svg"></iframe>
		<iframe id="icon_mt" src="/images/maps/svg/mt.svg"></iframe>
		<iframe id="icon_mt_" src="/images/maps/svg/mt_.svg"></iframe>
		<iframe id="icon_sp" src="/images/maps/svg/sp.svg"></iframe>
		<iframe id="icon_sp_" src="/images/maps/svg/sp_.svg"></iframe>
		<iframe id="icon_td" src="/images/maps/svg/td.svg"></iframe>
		<iframe id="icon_td_" src="/images/maps/svg/td_.svg"></iframe>
	</div>



	<div style="display:none;">
		<button id="debugbtn">debug</button>
	</div>



	<div class="map-case connecting">
		<div class="nav">
			<div class="exit"><a class="send exit-tactic" onClick="window.parent.$.fancybox.close(); return false;"></a></div>
		</div>
		<div class="title">
			Соединение с сервером...
		</div>
	</div>



	<div class="map-case create" style="display:none;">

		<div class="nav">
			<div class="title">Тактический планшет</div>
			<div class="exit"><a class="send exit-tactic" onClick="window.parent.$.fancybox.close(); return false;"></a></div>
		</div>

		<div class="room-form">

			<div class="room-form-inner">

				<div class="title">
					Создать комнату
				</div>

				<div class="tactic-text">Карта:</div>

				<div class="custom-select">
					<select class="map-select create">
						<option></option>
					</select>
				</div>

				<div class="tactic-text" style="margin-top: 10px">или шаблон:</div>

				<div>
					<input type="text" name="room-serialized" style="width: 218px">
				</div>

				<div class="see-room">
					<span>Комнату видят</span>
					<input type="radio" name="room-access-create" value="clan" id="clan-c" checked><label for="clan-c">клан</label>
					<input type="radio" name="room-access-create" value="ally" id="ally-c"><label for="ally-c">альянс</label>
				</div>

				<button class="btn send create-room"><span>Создать</span></button>

			</div>

		</div>

		<div class="board">

			<div class="room-list">

				<div class="title">
					Список комнат
				</div>

				<ul>
					<li><span>Карта</span> <span>Командир</span> <span>Онлайн</span></li>
				</ul>




<!--
				<ul>
					<li><span>Карта</span> <span>Командир</span> <span>Онлайн</span></li>
					<li><span><img src="http://amigo.wclan.ru/images/maps/thumbs/31_airfield_1.jpg"></span><span><a href="#" data-creator="AngrySpike">Аэродром</a></span><span>AngrySpike</span><span>1</span></li>
					<li><span><img src="http://amigo.wclan.ru/images/maps/thumbs/31_airfield_1.jpg"></span><span><a href="#" data-creator="AngrySpike">Аэродром</a></span><span>AngrySpike</span><span>1</span></li>
				</ul>
-->




			</div>

		</div>

	</div><!-- class="map-case create" -->



	<div class="map-case room" style="display:none;">


		<div class="nav">
			<div class="title">Тактический планшет</div>
			<div class="exit"><a class="send exit-room"></a></div>
		</div>

		<div class="room-form">

			<div class="room-form-inner">

				<div class="title">
					<span class="current-map-name"></span>
				</div>

				<div class="admin-bar">

					<div class="custom-select">
						<select class="map-select update">
							<option></option>
						</select>
					</div>

					<div class="see-room">
						<span>Комнату видят</span>
						<input type="radio" name="room-access-update" value="clan" id="clan-u" checked><label for="clan-u">клан</label>
						<input type="radio" name="room-access-update" value="ally" id="ally-u"><label for="ally-u">альянс</label>
					</div>

					<button class="btn send update-room"><span>Изменить</span></button>

				</div>

				<div class="board-users gray-skin">

					<div class="list-users" style="height:100%;">
					</div>

				</div>

			</div>

		</div>


		<div class="board">

			<div class="board-map">

				<!-- спиннер --><div class="map-spinner"></div>

				<!-- КАРТА -->

				<div class="tactic-display"></div>

				<!-- /КАРТА -->
			</div>

			<div class="board-button">

				<button class="btn test" data-cmd="test"><span></span></button><!-- легкий танк -->
				<button class="btn lt-a" data-cmd="lt"><span></span></button><!-- легкий танк -->
				<button class="btn mt-a" data-cmd="mt"><span></span></button><!-- средний танк -->
				<button class="btn ht-a" data-cmd="ht"><span></span></button><!-- тяжелый танк -->
				<button class="btn td-a" data-cmd="td"><span></span></button><!-- пт-сау -->
				<button class="btn spg-a" data-cmd="sp"><span></span></button><!-- арт-сау -->
				<button class="btn lt-e" data-cmd="lt_"><span></span></button><!-- легкий танк -->
				<button class="btn mt-e" data-cmd="mt_"><span></span></button><!-- средний танк -->
				<button class="btn ht-e" data-cmd="ht_"><span></span></button><!-- тяжелый танк -->
				<button class="btn td-e" data-cmd="td_"><span></span></button><!-- пт-сау -->
				<button class="btn spg-e" data-cmd="sp_"><span></span></button><!-- арт-сау -->
				<button class="btn arrow-one" data-cmd="arrow"><span></span></button><!-- стрелка -->
				<button class="btn clear" data-cmd="clear"><span></span></button><!-- очистить карту от танков и стрелок -->

				<button class="btn save-room" style="padding: 0" title="Создать шаблон">
					<span style="font-size: 19px;">&#x1f4f7;</span>
				</button>

			</div>

			<ul class="layer-thumbs">
			</ul>

			<div class="glass" style="width: 760px; height: 627px; position: absolute; left: 0; top: 0; z-index: 100;"></div>

		</div>


	</div><!-- class="map-case room" -->