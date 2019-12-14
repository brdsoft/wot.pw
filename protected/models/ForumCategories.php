<?php

class ForumCategories extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'forum_categories';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('group_id, name', 'required'),
			array('order, file_allow, file_size, site_id', 'numerical', 'integerOnly'=>true),
			array('group_id', 'length', 'max'=>11),
			array('group_id', 'groupExists'),
			array('name, file_ext', 'length', 'max'=>255),
			array('description', 'length', 'max'=>512),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, group_id, name, description, order, file_allow, file_ext, file_size', 'safe', 'on'=>'search'),
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
			'group' => array(self::BELONGS_TO, 'ForumGroups', 'group_id'),
			'themes' => array(self::HAS_MANY, 'ForumThemes', 'category_id'),
			'themesCount' => array(self::STAT, 'ForumThemes', 'category_id'),
			'messages' => array(self::HAS_MANY, 'ForumMessages', 'category_id'),
			'messagesCount' => array(self::STAT, 'ForumMessages', 'category_id'),
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
			'group_id' => Yii::t('wot', 'Group'),
			'name' => Yii::t('wot', 'Name'),
			'description' => Yii::t('wot', 'Description'),
			'order' => Yii::t('wot', 'Sort Order'),
			'file_allow' => 'Разрешить прикреплять файлы', //Устарело
			'file_ext' => 'Допустимые расширения файлов', //Устарело
			'file_size' => 'Максимальный размер файла (Кб)', //Устарело
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
		$criteria->compare('group_id',$this->group_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('order',$this->order);
		$criteria->compare('file_allow',$this->file_allow);
		$criteria->compare('file_ext',$this->file_ext,true);
		$criteria->compare('file_size',$this->file_size);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ForumCategories the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function groupExists()
	{
		if (!ForumGroups::model()->findByPk($this->group_id))
			$this->addError('group_id', Yii::t('wot', 'Group with the specified ID is not found.'));
	}
	
	public function getLastMessage()
	{	
		$onPage = 20;
		$message = ForumMessages::model()->find(array('condition'=>"`t`.`category_id` = {$this->id}", 'order'=>"`t`.`id` DESC", 'limit'=>'1'));
		if (!$message || !$message->theme)
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
