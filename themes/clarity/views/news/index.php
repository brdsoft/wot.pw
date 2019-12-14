<?php

$this->pageTitle=Yii::t('wot', 'News');
echo Yii::app()->format->whtml($this->site->getHtml('news'), $this->site->premium_html);
