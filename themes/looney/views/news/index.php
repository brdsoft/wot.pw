<?php

$this->pageTitle='Новости';
echo Yii::app()->format->whtml($this->site->getHtml('news'), $this->site->premium_html);
