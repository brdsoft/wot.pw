<?php

class ForumMessages extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'forum_messages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('theme_id, account_id, message, time', 'required'),
			array('site_id', 'numerical', 'integerOnly'=>true),
			array('theme_id, category_id', 'length', 'max'=>11),
			array('account_id, updated_account_id', 'length', 'max'=>20),
			array('theme_id', 'themeExists'),
			array('account_id', 'accountExists'),
			array('time, updated_time', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, theme_id, category_id, account_id, message, time', 'safe', 'on'=>'search'),
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
			'files' => array(self::HAS_MANY, 'ForumFiles', 'message_id'),
			'category' => array(self::BELONGS_TO, 'ForumCategories', 'category_id'),
			'theme' => array(self::BELONGS_TO, 'ForumThemes', 'theme_id'),
			'account' => array(self::BELONGS_TO, 'Accounts', 'account_id'),
			'updated_account' => array(self::BELONGS_TO, 'Accounts', 'updated_account_id'),
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
			'theme_id' => Yii::t('wot', 'Theme'),
			'account_id' => Yii::t('wot', 'Account'),
			'message' => Yii::t('wot', 'Message'),
			'time' => Yii::t('wot', 'Time'),
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
		$criteria->compare('theme_id',$this->theme_id,true);
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('time',$this->time,true);

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
	 * @return ForumMessages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function accountExists()
	{
		if (!Accounts::model()->findByPk($this->account_id))
			$this->addError('account_id',Yii::t('wot', 'Account with specified ID is not found.'));
	}
	
	public function themeExists()
	{
		$theme = ForumThemes::model()->findByPk($this->theme_id);
		if (!$theme)
			$this->addError('theme_id',Yii::t('wot', 'Theme with specified ID is not found.'));
		else
			$this->category_id = $theme->category_id;
	}
	
	public function init()
	{
		$this->account_id = Yii::app()->user->id;
		$this->time = time();
	}
	
	public function getAdminLinks()
	{
		$links = '';
		if (Yii::app()->user->account && (Yii::app()->user->id == $this->account_id || Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('8')))
		{
			$links .= CHtml::link(Yii::t('wot', 'Update'), '#', array('onClick'=>'return editMessage(\''.$this->id.'\');'));
		}
		if (Yii::app()->user->account && (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('8')))
		{
			$links .= CHtml::ajaxLink(Yii::t('wot', 'Delete'), '', array('type'=>'post', 'success'=>'function(html){jQuery("#messages").html(jQuery(html).find("#messages").html());}', 'data'=>array('action'=>'delete', 'message_id'=>$this->id, 'YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken)), array('id'=>'amessage'.$this->id, 'confirm'=>Yii::t('wot', 'Are you sure?')));
		}
		$links .= CHtml::link(Yii::t('wot', 'Quote'), '#', array('onClick'=>'return answerMessage(\''.$this->id.'\');'));
		return $links;
	}
	
	public function getSiteUrl()
	{
		if (Yii::app()->controller->site->id != 168)
			return '';
		if (!$this->account->clan_id)
			return '';
		Yii::app()->params['skipSiteCheck'] = true;
		$clan = Clans::model()->with('site')->findByPk($this->account->clan_id);
		Yii::app()->params['skipSiteCheck'] = false;
		if (!$clan)
			return '';
		return '<div class="role" style="padding-top: 0; margin-top: -10px;"><a target="_blank" href="http://'.$clan->site->url.'">'.$clan->site->url.'</a></div>';
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
