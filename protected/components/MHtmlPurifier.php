<?php

class MHtmlPurifier extends CHtmlPurifier
{
	private $_purifier;
	
	protected function createNewHtmlPurifierInstance()
	{
		$this->_purifier=new HTMLPurifier($this->getOptions());
		$this->_purifier->config->set('Cache.SerializerPath',Yii::app()->getRuntimePath());
		$def = $this->_purifier->config->getHTMLDefinition(true);
		$def->addAttribute('a', 'target', 'Enum#_blank,_self,_target,_top');
		return $this->_purifier;
	}
}