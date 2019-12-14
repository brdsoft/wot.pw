<?php

class Sites extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sites';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('url, account_id, time_created, time_modified, style_id', 'required'),
			array('time_created, time_modified, premium_reklama, premium_clans, premium_companies, premium_html, banned, check_time, premium_replays, premium_tactic, check_life_time, css_disabled, reset', 'numerical', 'integerOnly'=>true),
			array('url, cluster, language', 'length', 'max'=>64),
			array('account_id', 'length', 'max'=>20),
			array('balance', 'numerical'),
			array('style_id', 'exist', 'className'=>'Styles', 'attributeName'=>'id', 'message'=>Yii::t('wot', 'The selected template is not found.')),
			array('css', 'length', 'max'=>1000000),
			array('check, header, sidebar, index, footer, news', 'length', 'max'=>65535),
			array('premium_widgets', 'length', 'max'=>2000),
			array('premium_domain, favicon', 'length', 'max'=>255),
			array('favicon', 'match', 'pattern'=>'/^\w{32}\.\w+$/', 'message'=>Yii::t('wot', 'Wrong file name.')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, url, account_id, time_created, time_modified, style_id', 'safe', 'on'=>'search'),
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
			'style' => array(self::BELONGS_TO, 'Styles', 'style_id'),
			'clans' => array(self::HAS_MANY, 'Clans', 'site_id', 'order'=>'RAND()'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('wot', 'ID'),
			'url' => Yii::t('wot', 'URL'),
			'account_id' => Yii::t('wot', 'Account'),
			'time_created' => Yii::t('wot', 'Created'),
			'time_modified' => Yii::t('wot', 'Updated'),
			'style_id' => Yii::t('wot', 'Template'),
			'css' => Yii::t('wot', 'Alternative CSS'),
			'premium_reklama' => Yii::t('wot', 'Ads disabled'),
			'premium_clans' => Yii::t('wot', 'Clans limit'),
			'premium_domain' => Yii::t('wot', 'Domain status'),
			'premium_widgets' => Yii::t('wot', 'Premium widgets'),
			'premium_html' => Yii::t('wot', 'HTML editor'),
			'sidebar' => $this->premium_html ? Yii::t('wot', 'Sidebar HTML') : Yii::t('wot', 'Sidebar widgets'),
			'index' => $this->premium_html ? Yii::t('wot', 'Home page HTML') : Yii::t('wot', 'Home page widgets'),
			'header' => Yii::t('wot', 'Header HTML'),
			'footer' => Yii::t('wot', 'Footer HTML'),
			'news' => $this->premium_html ? Yii::t('wot', 'News page HTML') : Yii::t('wot', 'News page widgets'),
			'banned' => Yii::t('wot', 'Banned'),
			'favicon' => Yii::t('wot', 'Site icon (favicon)'),
			'language' => 'Language/Язык',
			'css_disabled' => Yii::t('wot', 'Template CSS'),
			'reset' => Yii::t('wot', 'Reset site to default settings'),
			'balance' => Yii::t('wot', 'Site balance'),
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
		$criteria->compare('url',$this->url,true);
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('time_created',$this->time_created);
		$criteria->compare('time_modified',$this->time_modified);
		$criteria->compare('style_id',$this->style_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sites the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getIsPremium()
	{
		return $this->premium_reklama;
	}

	protected function beforeSave()
	{
		Yii::app()->cache->set('sites', time());
		return parent::beforeSave();
	}

	protected function beforeDelete()
	{
		Yii::app()->cache->set('sites', time());
		return parent::beforeDelete();
	}

	protected function afterConstruct() {
		$this->deserialize();
		return parent::afterConstruct();
	}

	protected function beforeValidate() {
		$this->serialize();
		return parent::beforeValidate();
	}

	protected function afterFind() {
		$this->deserialize();
		return parent::afterFind();
	}

	protected function deserialize() {
		$this->premium_widgets = json_decode($this->premium_widgets);
	}

	protected function serialize() {
		$this->premium_widgets = json_encode($this->premium_widgets);
	}
	
	public function getThemeConfig()
	{
		$config_main = json_decode(file_get_contents('themes/config.json'), true);
		$config_theme = json_decode(file_get_contents('themes/'.$this->style->theme.'/'.$this->style->skin.'/config.json'), true);
		return array_replace_recursive($config_main, $config_theme);
	}

	public function getHtml($name) {
		$themeConfig = $this->getThemeConfig();
		return $this->$name == '' ? $themeConfig[$this->language]['html_'.$name] : $this->$name;
	}
	
	public function reset()
	{
		$themeConfig = $this->getThemeConfig();
		$themeConfig = $themeConfig[$this->language];
		
		Config::model()->deleteAllByAttributes(['site_id' => $this->id]);
		foreach ($themeConfig['config'] as $key=>$value)
		{
			$config = new Config;
			$config->attributes = $value;
			$config->name = $key;
			$config->save();
		}
		
		MenuTop::model()->deleteAllByAttributes(['site_id' => $this->id]);
		foreach ($themeConfig['menu_top'] as $key=>$value)
		{
			$menu_top = new MenuTop;
			$menu_top->attributes = $value;
			$menu_top->save();
		}
		
		MenuLeft::model()->deleteAllByAttributes(['site_id' => $this->id]);
		foreach ($themeConfig['menu_left'] as $key=>$value)
		{
			$menu_left = new MenuLeft;
			$menu_left->attributes = $value;
			$menu_left->save();
		}
		
		Pages::model()->deleteAllByAttributes(['site_id' => $this->id, 'name'=>'diplomat']);
		Pages::model()->deleteAllByAttributes(['site_id' => $this->id, 'name'=>'history']);
		Pages::model()->deleteAllByAttributes(['site_id' => $this->id, 'name'=>'requirements']);
		Pages::model()->deleteAllByAttributes(['site_id' => $this->id, 'name'=>'sponsor']);
		Pages::model()->deleteAllByAttributes(['site_id' => $this->id, 'name'=>'teamspeak']);
		Pages::model()->deleteAllByAttributes(['site_id' => $this->id, 'name'=>'textonmain']);
		foreach ($themeConfig['pages'] as $key=>$value)
		{
			$page = new Pages;
			$page->attributes = $value;
			$page->name = $key;
			$page->time = time();
			$page->account_id = $this->account_id;
			$page->save();
		}
		
		$this->reset = 0;
		$this->update(['reset']);
	}
}
