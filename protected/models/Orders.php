<?php

class Orders extends ActiveRecord
{
	public function tableName()
	{
		return 'orders';
	}

	public $items_ru = array(
		1=>array('name'=>'Отключение рекламы','amount'=>470, 'amount_discount'=>0),
		2=>array('name'=>'Подключение собственного домена','amount'=>470, 'amount_discount'=>0),
		3=>array('name'=>'Увеличение количества кланов','amount'=>200, 'amount_discount'=>150),
		4=>array('name'=>'Редактор HTML кода','amount'=>400, 'amount_discount'=>0),
		5=>array('name'=>'Разделение кланов на роты','amount'=>200, 'amount_discount'=>0),
		6=>array('name'=>'Загрузка реплеев','amount'=>300, 'amount_discount'=>0),
		7=>array('name'=>'Чат на вашем сайте','amount'=>380, 'amount_discount'=>0),
		8=>array('name'=>'Виджет опросов','amount'=>280, 'amount_discount'=>150),
		9=>array('name'=>'Тактический планшет','amount'=>600, 'amount_discount'=>0),
		10=>array('name'=>'Продление домена','amount'=>470, 'amount_discount'=>0),
		11=>array('name'=>'Личные сообщения','amount'=>350, 'amount_discount'=>0),
		100=>array('name'=>'Пополнение баланса','amount'=>0, 'amount_discount'=>0),
	);

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('item_id, amount', 'required'),
			array('site_id', 'length', 'max'=>11),
			array('item_id, amount', 'length', 'max'=>10),
			array('m_operation_id, m_operation_ps, m_operation_date, m_operation_pay_date, m_curr, m_status, m_sign', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, site_id, item_id, amount, m_operation_id, m_operation_ps, m_operation_date, m_operation_pay_date, m_curr, m_status, m_sign', 'safe', 'on'=>'search'),
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
			'site' => array(self::BELONGS_TO, 'Sites', 'site_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'site_id' => 'Сайт',
			'item_id' => 'Услуга',
			'amount' => 'Стоимость',
			'm_operation_id' => 'Внутренний номер платежа',
			'm_operation_ps' => 'Способ оплаты',
			'm_operation_date' => 'Дата и время формирования операции',
			'm_operation_pay_date' => 'Дата и время выполнения платежа',
			'm_curr' => 'Валюта платежа',
			'm_status' => 'Статус платежа',
			'm_sign' => 'Электронная подпись',
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
		$criteria->compare('site_id',$this->site_id,true);
		$criteria->compare('item_id',$this->item_id,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('m_operation_id',$this->m_operation_id,true);
		$criteria->compare('m_operation_ps',$this->m_operation_ps,true);
		$criteria->compare('m_operation_date',$this->m_operation_date,true);
		$criteria->compare('m_operation_pay_date',$this->m_operation_pay_date,true);
		$criteria->compare('m_curr',$this->m_curr,true);
		$criteria->compare('m_status',$this->m_status,true);
		$criteria->compare('m_sign',$this->m_sign,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Orders the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
}
