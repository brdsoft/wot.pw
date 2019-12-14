<?php
class Formatter extends CFormatter
{
	private $_htmlPurifier;

	public function getMHtmlPurifier()
	{
		if($this->_htmlPurifier===null)
		$this->_htmlPurifier=new MHtmlPurifier;
		$this->_htmlPurifier->options=$this->htmlPurifierOptions;
		return $this->_htmlPurifier;
	}

	public function formatMhtml($value)
	{
		$widgets = [ //      id,  class,    is_free
			'REPLAY' =>       [  10, 'WReplay',      0 ],
		];

		$name = '[0-9A-Za-z_]+';
		$val = '[^\"\s\]]+|\"(\\\"|[^"])*\"';
		$param = '\s+('.$name.')\s*=\s*('.$val.')';

		$value = preg_replace('=<iframe src\="(http:)?//(www\.)?youtube\.com/embed/([\w\-]+)/?".+?></iframe>=s', '[youtube=$3]', $value);
		$value = preg_replace('=<iframe src\="(http:)?//(www\.)?twitch\.tv/([\w\-]+)/embed/?".+?></iframe>=s', '[twitch=$3]', $value);
		$value = preg_replace('=<iframe src\="(http:)?//api\.cybergame\.tv/p/embed\.php\?c\=([\w\-]+).+?></iframe>=s', '[cybergame=$2]', $value);
		$value = $this->getMHtmlPurifier()->purify($value);
		$value = preg_replace('=\[youtube\=([\w\-]+)\]=', '<div class="responsive-video"><iframe src="http://www.youtube.com/embed/$1" width="100%" height="350" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>', $value);
		$value = preg_replace('=\[twitch\=([\w\-]+)\]=', '<iframe src="http://www.twitch.tv/$1/embed" width="100%" height="350"></iframe>', $value);
		$value = preg_replace('=\[cybergame\=([\w\-]+)\]=', '<iframe src="http://api.cybergame.tv/p/embed.php?c=$1&w=100pc&h=350&type=embed" width="100%" height="350"></iframe>', $value);

		preg_match_all('~\{([A-Za-z_]+)(('.$param.')*)\s*\}~m', $value, $m, PREG_OFFSET_CAPTURE);

		foreach ($m as $key => $val)
			$m[$key] = array_reverse($val);

		foreach ($m[1] as $key => $wname) {
			if (isset($widgets[$wname[0]]) && ($widgets[$wname[0]][2] ||
					in_array($widgets[$wname[0]][0], Yii::app()->controller->site->premium_widgets)))
			{
				preg_match_all("~$param~", $m[2][$key][0], $p);
				$params = [];

				foreach ($p[1] as $i => $pkey) {
					$params[$pkey] = $p[2][$i];
					if ($params[$pkey][0] == '"')
						$params[$pkey] = str_replace('\"', '"', substr($params[$pkey], 1, strlen($params[$pkey]) - 2));

				}

				$content = Yii::app()->controller->widget($widgets[$wname[0]][1], ['params' => $params], true);

				//exit(print_r($value,1));
				$value = substr_replace($value, $content, $m[0][$key][1], mb_strlen($m[0][$key][0], 'cp1251'));
			}
		}

		return $value;
	}

	public function formatWhtml($value, $keep_html = true) {
		$widgets = [ //      id,  class,    is_free
			'CHAT' =>       [  1, 'WChat',      0 ],
			'MENU' =>       [  3, 'WMenu',      1 ],
			'MENU_BLOCK' => [  4, 'WMenuBlock', 1 ],
			'ADMIN_MENU' => [  5, 'WAdminMenu', 1 ],
			'VK' =>         [  6, 'WVK',        1 ],
			'STATIC' =>     [  7, 'WStatic',    1 ],
			'NEWS' =>       [  8, 'WNews',      1 ],
			'TWITCH' =>     [  9, 'WTwitch',    1 ],
			'ONLINE' =>     [ 11, 'WOnline',    1 ],
			'POLL' =>     	[ 12, 'WPoll',   	  0 ],
			'THEMES' =>     [ 13, 'WThemes', 	  1 ],
			'DONATE' =>     [ 14, 'WDonate', 	  1 ],
			// 'WPMSG' =>     [ 15, 'WPmsg', 	  0 ], // 15 зарезирвирован но для использования недоступен
		];

		$conf = [
			'SITE_NAME' => nl2br($this->mhtml(Config::all('site_name')->value)),
			'SLOGAN' => nl2br($this->mhtml(Config::all('site_slogan')->value)),
			'DOMAIN' => $_SERVER['SERVER_NAME'],
		];

		$name = '[0-9A-Za-z_]+';
		$val = '[^\"\s\]]+|\"(\\\"|[^"])*\"';
		$param = '\s+('.$name.')\s*=\s*('.$val.')';

		preg_match_all('~\{([A-Za-z_]+)(('.$param.')*)\s*\}~m', $value, $m, PREG_OFFSET_CAPTURE);

		if (!$keep_html) $value = '';

		foreach ($m as $key => $val)
			$m[$key] = array_reverse($val);

		foreach ($m[1] as $key => $wname) {
			if (isset($conf[$wname[0]]) || isset($widgets[$wname[0]]) && ($widgets[$wname[0]][2] ||
					in_array($widgets[$wname[0]][0], Yii::app()->controller->site->premium_widgets)))
			{
				preg_match_all("~$param~", $m[2][$key][0], $p);
				$params = [];

				foreach ($p[1] as $i => $pkey) {
					$params[$pkey] = $p[2][$i];
					if ($params[$pkey][0] == '"')
						$params[$pkey] = str_replace('\"', '"', substr($params[$pkey], 1, strlen($params[$pkey]) - 2));
				}

				$content = isset($conf[$wname[0]]) ? $conf[$wname[0]] :
					Yii::app()->controller->widget($widgets[$wname[0]][1], ['params' => $params], true);

				//if (preg_match('~чч~u', $m[0][$key][0])) {
				//	var_dump($m[0][$key][0], strlen($m[0][$key][0]), mb_strlen($m[0][$key][0]), mb_strlen($m[0][$key][0], 'cp1251')); exit; // very strange!
				//}

				if ($keep_html)
					$value = substr_replace($value, $content, $m[0][$key][1], mb_strlen($m[0][$key][0], 'cp1251'));
				else
					$value = $content.$value;
			}
		}

		return $value;
	}
}
