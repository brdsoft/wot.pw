<?php

/**
 * Перевод не требуется
 */
class Provinces2 extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'provinces2';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, arena_i18n, arena_id, clan_id, map_id, neighbors, primary_region, prime_time, province_i18n, revenue, status, updated_at, vehicle_max_level, regions', 'required'),
			array('prime_time, revenue, updated_at, vehicle_max_level', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>64),
			array('arena_i18n, arena_id, primary_region, province_i18n, status', 'length', 'max'=>255),
			array('clan_id', 'length', 'max'=>20),
			array('map_id', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, arena_i18n, arena_id, clan_id, map_id, neighbors, primary_region, prime_time, province_i18n, revenue, status, updated_at, vehicle_max_level, regions', 'safe', 'on'=>'search'),
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
			'map' => array(self::BELONGS_TO, 'Maps', 'map_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'arena_i18n' => 'Название карты',
			'arena_id' => 'Идентификатор карты',
			'clan_id' => 'Владелец провинции',
			'map_id' => 'Идентификатор Глобальной карты',
			'neighbors' => 'Соседние провинции',
			'primary_region' => 'Основной регион',
			'prime_time' => 'Прайм-тайм',
			'province_i18n' => 'Название провинции',
			'revenue' => 'Суточный доход с провинции',
			'status' => 'Вид провинции',
			'updated_at' => 'Дата обновления информации о провинциях на карте',
			'vehicle_max_level' => 'Максимальный уровень техники',
			'regions' => 'Регионы',
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
		$criteria->compare('arena_i18n',$this->arena_i18n,true);
		$criteria->compare('arena_id',$this->arena_id,true);
		$criteria->compare('clan_id',$this->clan_id,true);
		$criteria->compare('map_id',$this->map_id,true);
		$criteria->compare('neighbors',$this->neighbors,true);
		$criteria->compare('primary_region',$this->primary_region,true);
		$criteria->compare('prime_time',$this->prime_time);
		$criteria->compare('province_i18n',$this->province_i18n,true);
		$criteria->compare('revenue',$this->revenue);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('updated_at',$this->updated_at);
		$criteria->compare('vehicle_max_level',$this->vehicle_max_level);
		$criteria->compare('regions',$this->regions,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Provinces2 the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
