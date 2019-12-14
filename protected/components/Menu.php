<?php

Yii::import('zii.widgets.CMenu');

class Menu extends CMenu
{
	protected function isItemActive($item,$route)
	{
		if(!isset($item['url']))
			return false;
		
		if (is_array($item['url']))
		{
			$url = $item['url'][0];
			if (stripos($route, trim($url, '/')) === 0)
			{
				if (isset($route[strlen(trim($url, '/'))]) && !preg_match('=^[/?#]$=', $route[strlen(trim($url, '/'))]))
					return false;
				return true;
			}
		}
		else
		{
			$url = $item['url'];
			if (stripos(Yii::app()->request->requestUri, $url) === 0)
			{
				if (isset(Yii::app()->request->requestUri[strlen($url)]) && !preg_match('=^[/?#]$=', Yii::app()->request->requestUri[strlen($url)]))
					return false;
				return true;
			}
		}
		
		return false;
	}
}