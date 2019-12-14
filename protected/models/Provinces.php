<?php

/**
 * Модель устарела и будет удалена
 */
class Provinces extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'provinces';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, name, arena_name, prime_time, attacked, revenue, occupancy_time, combats_running, clan_id', 'required'),
			array('prime_time, attacked, revenue, occupancy_time, combats_running, clan_id', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>10),
			array('name, arena_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, arena_name, prime_time, attacked, revenue, occupancy_time, combats_running, clan_id', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'arena_name' => 'Карта',
			'prime_time' => 'Прайм',
			'attacked' => 'Атакована',
			'revenue' => 'Доход',
			'occupancy_time' => 'Время владения',
			'combats_running' => 'Идут бои',
			'clan_id' => 'Клан',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('arena_name',$this->arena_name,true);
		$criteria->compare('prime_time',$this->prime_time);
		$criteria->compare('attacked',$this->attacked);
		$criteria->compare('revenue',$this->revenue);
		$criteria->compare('occupancy_time',$this->occupancy_time);
		$criteria->compare('combats_running',$this->combats_running);
		$criteria->compare('clan_id',$this->clan_id);

		return new CActiveDataProvider($this, array(
			'pagination'=>array(
				'pageSize'=>100,
			),
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Provinces the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
