<?php

/**
 * This is the model class for table "forum_themes".
 *
 * The followings are the available columns in table 'forum_themes':
 * @property string $id
 * @property string $site_id
 * @property string $category_id
 * @property string $account_id
 * @property string $name
 * @property string $time
 * @property integer $fixed
 * @property integer $closed
 *
 * The followings are the available model relations:
 * @property ForumMessages[] $forumMessages
 * @property Accounts $account
 * @property ForumCategories $category
 */
class ForumThemes extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'forum_themes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category_id, account_id, name, time', 'required'),
			array('fixed, closed, site_id', 'numerical', 'integerOnly'=>true),
			array('category_id, time', 'length', 'max'=>11),
			array('account_id', 'length', 'max'=>20),
			array('category_id', 'categoryExists'),
			array('account_id', 'accountExists'),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, category_id, account_id, name, time, fixed, closed', 'safe', 'on'=>'search'),
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
			'messages' => array(self::HAS_MANY, 'ForumMessages', 'theme_id'),
			'account' => array(self::BELONGS_TO, 'Accounts', 'account_id'),
			'category' => array(self::BELONGS_TO, 'ForumCategories', 'category_id'),
			'messagesCount' => array(self::STAT, 'ForumMessages', 'theme_id'),
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
			'category_id' => Yii::t('wot', 'Forum'),
			'account_id' => Yii::t('wot', 'Account'),
			'name' => Yii::t('wot', 'Name'),
			'time' => Yii::t('wot', 'Time'),
			'fixed' => Yii::t('wot', 'Fixed'),
			'closed' => Yii::t('wot', 'Closed'),
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
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('fixed',$this->fixed);
		$criteria->compare('closed',$this->closed);

		return new CActiveDataProvider($this, array(
			'pagination'=>array(
				'pageSize'=>50,
			),
			'sort'=>array(
				'defaultOrder'=>"`time` DESC",
			),
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ForumThemes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function accountExists()
	{
		if (!Accounts::model()->findByPk($this->account_id))
			$this->addError('account_id', Yii::t('wot', 'Account with specified ID is not found.'));
	}
	
	public function categoryExists()
	{
		if (!ForumCategories::model()->findByPk($this->category_id))
			$this->addError('category_id', Yii::t('wot', 'Forum with specified ID is not found.'));
	}
	
	public function init()
	{
		$this->account_id = Yii::app()->user->id;
		$this->time = time();
		$this->description = '';
	}
	
	public function getLastMessage()
	{
		$onPage = 20;
		$message = ForumMessages::model()->find(array('condition'=>"`t`.`theme_id` = {$this->id}", 'order'=>"`t`.`id` DESC", 'limit'=>'1'));
		if (!$message)
			return Yii::t('wot', 'None');
		return date('d.m.Y H:i', $message->time+Yii::app()->params["moscow"]).'<br>'.CHtml::link($message->account->nickname, array('/profile/account', 'id'=>$message->account_id), ['class'=>'user-post']).' '.CHtml::link('<span class="icon-point-right"></span>', array('/forum/theme', 'category_id'=>$message->theme->category_id, 'id'=>$message->theme->id, 'ForumMessages_page'=>ceil($message->theme->messagesCount/$onPage), '#'=>'message'.$message->id), ['class'=>'last-post']);
	}
	
	protected function beforeSave()
	{
		Yii::app()->cache->set('forum:'.Yii::app()->controller->site->id, time());
		return parent::beforeSave();
	}
	
	protected function beforeDelete()
	{
		Yii::app()->cache->set('forum:'.Yii::app()->controller->site->id, time());
		return parent::beforeDelete();
	}
}
