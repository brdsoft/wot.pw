<?php
/* @var $this NoticesRecipientsController */
/* @var $model NoticesRecipients */

$this->breadcrumbs=array(
	'Notices Recipients'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List NoticesRecipients', 'url'=>array('index')),
	array('label'=>'Create NoticesRecipients', 'url'=>array('create')),
	array('label'=>'View NoticesRecipients', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage NoticesRecipients', 'url'=>array('admin')),
);
?>

<h1>Update NoticesRecipients <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>