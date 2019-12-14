<?php
/* @var $this NoticesRecipientsController */
/* @var $model NoticesRecipients */

$this->breadcrumbs=array(
	'Notices Recipients'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List NoticesRecipients', 'url'=>array('index')),
	array('label'=>'Create NoticesRecipients', 'url'=>array('create')),
	array('label'=>'Update NoticesRecipients', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete NoticesRecipients', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage NoticesRecipients', 'url'=>array('admin')),
);
?>

<h1>View NoticesRecipients #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'site_id',
		'notices_id',
		'account_id',
		'accepted',
		'agree',
		'disagree',
	),
)); ?>