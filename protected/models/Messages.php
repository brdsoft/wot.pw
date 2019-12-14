<?php

class Messages extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'messages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, object_id, time, account_id, message', 'required'),
			array('site_id, object_id, time', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>32),
			array('message', 'length', 'max'=>1000),
			array('account_id', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, object_id, time, account_id, message', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'account' => array(self::BELONGS_TO, 'Accounts', 'account_id'),
			'site' => array(self::BELONGS_TO, 'Sites', 'site_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('wot', 'ID'),
			'site_id' => Yii::t('wot', 'Site'),
			'name' => Yii::t('wot', 'Theme'),
			'object_id' => Yii::t('wot', 'Object ID'),
			'time' => Yii::t('wot', 'Date'),
			'account_id' => Yii::t('wot', 'Account'),
			'message' => Yii::t('wot', 'Message'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('object_id',$this->object_id);
		$criteria->compare('time',$this->time);
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('message',$this->message,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Messages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function beforeSave()
	{
		Yii::app()->cache->set('messages', time());
		return parent::beforeSave();
	}
	
	protected function beforeDelete()
	{
		Yii::app()->cache->set('messages', time());
		return parent::beforeDelete();
	}
	
	public function getAdminLinks()
	{
		$links = '';
		if (Yii::app()->user->account && (Yii::app()->user->id == $this->account->id || Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('8')))
		{
			$links .= CHtml::ajaxLink(Yii::t('wot', 'Delete'), '', array('type'=>'post', 'success'=>'function(html){jQuery(".messages-grid").html(jQuery(html).find(".messages-grid").html());}', 'data'=>array('action'=>'delete', 'message_id'=>$this->id, 'YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken)), array('id'=>'amessage'.$this->id,));
		}
		return $links;
	}
}
