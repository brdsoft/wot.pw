<?php
/* @var $this NoticesRecipientsController */
/* @var $model NoticesRecipients */

$this->breadcrumbs=array(
	'Notices Recipients'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List NoticesRecipients', 'url'=>array('index')),
	array('label'=>'Manage NoticesRecipients', 'url'=>array('admin')),
);
?>

<h1>Create NoticesRecipients</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>