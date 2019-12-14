<?php

/**
 * Перевод не требуется
 */
class Tanks extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tanks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, level, nation, nation_i18n, is_premium, name, name_i18n, type', 'required'),
			array('id, level, is_premium', 'numerical', 'integerOnly'=>true),
			array('nation, nation_i18n, name, name_i18n, type, image, short_name_i18n', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, level, nation, nation_i18n, is_premium, name, name_i18n, type, image', 'safe', 'on'=>'search'),
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
			'id' => ' 	  Идентификатор техники',
			'level' => 'Уровень',
			'nation' => 'Нация',
			'nation_i18n' => 'Локализованное поле nation',
			'is_premium' => 'Премиум техника',
			'name' => 'Название',
			'name_i18n' => 'Локализованное поле name',
			'type' => 'Тип',
			'image' => 'Картинка',
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
		$criteria->compare('level',$this->level);
		$criteria->compare('nation',$this->nation,true);
		$criteria->compare('nation_i18n',$this->nation_i18n,true);
		$criteria->compare('is_premium',$this->is_premium);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('name_i18n',$this->name_i18n,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('image',$this->image,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tanks the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
