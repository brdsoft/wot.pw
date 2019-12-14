<?php

class Recruitment extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'recruitment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('account_id, clan_id, time, name, age', 'required'),
			array('clan_id, time, status, resolution, resolution_time, age, site_id, battles, wins, re, wn8, bs, t10', 'numerical', 'integerOnly'=>true),
			array('account_id, resolution_account', 'length', 'max'=>20),
			array('name', 'length', 'max'=>32),
			array('experience', 'length', 'max'=>128),
			array('invited', 'length', 'max'=>10),
			array('about', 'length', 'max'=>1000),
			array('clan_id', 'checkClan'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, account_id, clan_id, time, status, resolution, resolution_account, resolution_time, name, age, about, invited, experience', 'safe', 'on'=>'search'),
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
			'resolutionAccount' => array(self::BELONGS_TO, 'Accounts', 'resolution_account'),
			'clan' => array(self::BELONGS_TO, 'Clans', 'clan_id'),
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
			'account_id' => Yii::t('wot', 'Account'),
			'clan_id' => Yii::t('wot', 'Clan'),
			'time' => Yii::t('wot', 'Date'),
			'status' => Yii::t('wot', 'Status'),
			'resolution' => Yii::t('wot', 'Resolution'),
			'resolution_account' => Yii::t('wot', 'Resolution by'),
			'resolution_time' => Yii::t('wot', 'Resolution date'),
			'name' => Yii::t('wot', 'First name'),
			'age' => Yii::t('wot', 'Age'),
			'about' => Yii::t('wot', 'About'),
			'experience' => Yii::t('wot', 'Experience'),
			'invited' => Yii::t('wot', 'Invited to'),
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
		$criteria->with = ['resolutionAccount', 'clan', 'account'];

		$criteria->compare('id',$this->id);
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('clan_id',$this->clan_id);
		$criteria->compare('time',$this->time);
		$criteria->compare('status',$this->status);
		$criteria->compare('resolution',$this->resolution);
		$criteria->compare('resolution_account',$this->resolution_account,true);
		$criteria->compare('resolution_time',$this->resolution_time);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('age',$this->age);
		$criteria->compare('about',$this->about,true);
		$criteria->compare('experience',$this->experience,true);
		$criteria->compare('invited',$this->invited,true);

		return new CActiveDataProvider($this, [
			'pagination'=> [
				'pageSize'=>50,
			],
			'sort'=> [
				'defaultOrder'=>"`t`.`id` DESC",
			],
			'criteria'=>$criteria,
		]);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Recruitment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function checkClan($attribute,$params)
	{
		if(!$this->hasErrors())
		{	
			if (!Clans::model()->findByPk($this->clan_id))
				$this->addError('clan_id', Yii::t('wot', 'Clan not found.'));
		}
	}
	
	protected function beforeSave()
	{
		Yii::app()->cache->set('recruitment:'.Yii::app()->controller->site->id, time());
		return parent::beforeSave();
	}
	
	protected function beforeDelete()
	{
		Yii::app()->cache->set('recruitment:'.Yii::app()->controller->site->id, time());
		return parent::beforeDelete();
	}
	
	public function getResolutionColored()
	{
		if ($this->resolution == 0)
			return Yii::t('wot', 'Pending');
		if ($this->resolution == 1)
			return '<span style="color: #494;">'.Yii::t('wot', 'Adopted').($this->invited ? ' '.Yii::t('wot', 'to').' '.$this->invited : '').'</span>';
		if ($this->resolution == 3)
			return '<span style="color: #BAAE32;">'.Yii::t('wot', 'Approved').($this->invited ? ' '.Yii::t('wot', 'to').' '.$this->invited : '').'</span>';
		if ($this->resolution == 4)
			return '<span style="color: #79A3F2;">'.Yii::t('wot', 'Invited').($this->invited ? ' '.Yii::t('wot', 'to').' '.$this->invited : '').'</span>';
		return '<span style="color: #f44;">'.Yii::t('wot', 'Declined').'</span>';
	}
}
