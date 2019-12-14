<?php

/**
 * This is the model class for table "customs".
 *
 * The followings are the available columns in table 'customs':
 * @property string $id
 * @property string $site_id
 * @property string $name
 * @property string $text1
 * @property string $text2
 * @property string $account_id
 *
 * The followings are the available model relations:
 * @property Sites $site
 * @property Accounts $account
 */
class Customs extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, text1, text2, account_id', 'required'),
			array('site_id', 'length', 'max'=>11),
			array('name, text1', 'length', 'max'=>128),
			array('account_id', 'length', 'max'=>20),
			array('name', 'match', 'pattern'=>'=^[\w\-]+$=', 'message'=>Yii::t('wot', 'Invalid characters found.')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, site_id, name, text1, text2, account_id', 'safe', 'on'=>'search'),
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
			'account' => array(self::BELONGS_TO, 'Accounts', 'account_id'),
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
			'name' => Yii::t('wot', 'Name'),
			'text1' => Yii::t('wot', 'Header'),
			'text2' => Yii::t('wot', 'HTML Code'),
			'account_id' => Yii::t('wot', 'Author'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('text1',$this->text1,true);
		$criteria->compare('text2',$this->text2,true);
		$criteria->compare('account_id',$this->account_id,true);

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
	 * @return Customs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
