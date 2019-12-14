<?php

class ActiveRecord extends CActiveRecord
{
	protected function beforeCount()
	{
		if (!Yii::app()->params['skipSiteCheck'] && $this->hasAttribute('site_id'))
			$this->dbCriteria->compare($this->tableAlias.'.site_id', Yii::app()->controller->site->id);
	}
	
	protected function beforeFind()
	{
		if (!Yii::app()->params['skipSiteCheck'] && $this->hasAttribute('site_id'))
			$this->dbCriteria->compare($this->tableAlias.'.site_id', Yii::app()->controller->site->id);
	}
	
	protected function beforeSave()
	{
		if (!Yii::app()->params['skipSiteCheck'] && $this->hasAttribute('site_id'))
			$this->site_id = Yii::app()->controller->site->id;
		return parent::beforeSave();
	}
	
	protected function beforeDelete()
	{
		if (!Yii::app()->params['skipSiteCheck'] && $this->hasAttribute('site_id'))
			$this->dbCriteria->compare('site_id', Yii::app()->controller->site->id);
		return parent::beforeDelete();
	}
	
	public function delete()
	{
		if(!$this->getIsNewRecord())
		{
			Yii::trace(get_class($this).'.delete()','system.db.ar.CActiveRecord');
			if($this->beforeDelete())
			{
				$result=$this->deleteByPk($this->getPrimaryKey(), $this->dbCriteria)>0;
				$this->afterDelete();
				return $result;
			}
			else
				return false;
		}
		else
			throw new CDbException(Yii::t('yii','The active record cannot be deleted because it is new.'));
	}
}
