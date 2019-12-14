<?php

/**
 * Перевод не требуется
 */
class Achievements extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'achievements';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, order, section_order', 'required'),
			array('order, section_order', 'numerical', 'integerOnly'=>true),
			array('name, image, image_big, name_i18n, section, section_i18n, type', 'length', 'max'=>255),
			array('options, hero_info, description, condition', 'length', 'max'=>65535),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('name, condition, description, hero_info, image, image_big, name_i18n, order, section, section_i18n, section_order, type, options', 'safe', 'on'=>'search'),
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
			'name' => 'Название',
			'condition' => 'Условие',
			'description' => 'Описание достижения',
			'hero_info' => 'Историческая справка',
			'image' => 'Изображение',
			'image_big' => 'Изображение 180x180px',
			'name_i18n' => 'Локализованное поле name',
			'order' => 'Порядок отображения достижения',
			'section' => 'Раздел',
			'section_i18n' => 'Локализованное название раздела',
			'section_order' => 'Порядок отображения раздела',
			'type' => 'Тип',
			'options' => 'Достижения',
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

		$criteria->compare('name',$this->name,true);
		$criteria->compare('condition',$this->condition,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('hero_info',$this->hero_info,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('image_big',$this->image_big,true);
		$criteria->compare('name_i18n',$this->name_i18n,true);
		$criteria->compare('order',$this->order);
		$criteria->compare('section',$this->section,true);
		$criteria->compare('section_i18n',$this->section_i18n,true);
		$criteria->compare('section_order',$this->section_order);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('options',$this->options,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Achievements the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
