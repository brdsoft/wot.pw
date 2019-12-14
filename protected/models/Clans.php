<?php

/**
 * This is the model class for table "clans".
 *
 * The followings are the available columns in table 'clans':
 * @property integer $id
 * @property integer $site_id
 * @property integer $parser_info_time
 * @property integer $parser_accounts_time
 * @property integer $parser_tanks_time
 * @property integer $parser_battles_time
 * @property integer $parser_provinces_time
 * @property string $abbreviation
 * @property string $name
 * @property integer $order
 */
class Clans extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'clans';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id, site_id, parser_famepoints_time, parser_info_time, parser_battles_time, parser_provinces_time, parser_accounts_time, parser_tanks_time, parser_ivanerr_time, parser_teamspeak_time, ivanerr_rating, order', 'numerical', 'integerOnly'=>true),
			array('ivanerr_power', 'numerical'),
			array('id', 'clanExists', 'on'=>'admin'),
			array('id', 'clansLimit', 'on'=>'admin'),
			array('id', 'clansCheck', 'on'=>'admin'),
			array('abbreviation', 'length', 'max'=>5),
			array('name', 'length', 'max'=>255),
			array('cluster', 'length', 'max'=>64),
			array('order', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, abbreviation, name, parser_famepoints_time, parser_info_time, parser_accounts_time, parser_tanks_time, parser_battles_time, parser_provinces_time', 'safe', 'on'=>'search'),
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
			'accounts'=>array(self::HAS_MANY, 'Accounts', 'clan_id', 'index'=>'id'),
			'accountsSorted'=>array(self::HAS_MANY, 'Accounts', 'clan_id', 'index'=>'id', 'with'=>['sort']),
			'accountsOrdered'=>array(self::HAS_MANY, 'Accounts', 'clan_id', 'index'=>'id', 'order'=>'`nickname`'),
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
			'abbreviation' => Yii::t('wot', 'Clantag'),
			'name' => Yii::t('wot', 'Name'),
			'parser_info_time' => Yii::t('wot', 'Information updated'),
			'parser_accounts_time' => Yii::t('wot', 'Accounts updated'),
			'parser_tanks_time' => Yii::t('wot', 'Tanks updated'),
			'parser_battles_time' => Yii::t('wot', 'Battles updated'),
			'parser_provinces_time' => Yii::t('wot', 'Provinces updated'),
			'parser_famepoints_time' => Yii::t('wot', 'Fame Points updated'),
			'order' => Yii::t('wot', 'Sort Order'),
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
		$criteria->compare('parser_famepoints_time',$this->parser_famepoints_time);
		$criteria->compare('parser_info_time',$this->parser_info_time);
		$criteria->compare('parser_accounts_time',$this->parser_accounts_time);
		$criteria->compare('parser_tanks_time',$this->parser_tanks_time);
		$criteria->compare('parser_battles_time',$this->parser_battles_time);
		$criteria->compare('parser_provinces_time',$this->parser_provinces_time);
		$criteria->compare('abbreviation',$this->abbreviation,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('`order`',$this->order,true);

		return new CActiveDataProvider($this, array(
			'pagination'=>array(
				'pageSize'=>50,
			),
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>"`order`",
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Clans the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function clanExists()
	{
		if ($this->hasErrors('id'))
			return;
		Yii::app()->params['skipSiteCheck'] = true;
		if ($this->findByPk($this->id))
		{
			$this->addError('id', Yii::t('wot', 'The clan with the specified ID has already been created or assigned to another site. If you believe this is an error, please contact us and we will solve this problem.'));
			return;
		}
		Yii::app()->params['skipSiteCheck'] = false;
		$clans = WGApi::getClansInfo($this->id);
		if (!$clans || empty($clans[$this->id]))
			$this->addError('id', Yii::t('wot', 'The clan with the specified ID is not found. Please try again later.'));
		else
		{
			$this->abbreviation = $clans[$this->id]['tag'];
			$this->name = $clans[$this->id]['name'];
		}
	}
	
	public function clansLimit()
	{
		$count = $this->countByAttributes(array('site_id'=>Yii::app()->controller->site->id));
		if ($count >= Yii::app()->controller->site->premium_clans)
			$this->addError('name', Yii::t('wot', 'The maximum number of clans has reached.'));
	}
	
	public function clansCheck()
	{
		if ($this->hasErrors('id'))
			return;
		$check = @json_decode(Yii::app()->controller->site->check, true);
		if (!isset($check[$this->abbreviation]))
			return;
		
		if (Yii::app()->controller->site->url == '7kazak7.wot.pw')
			return;
		if (Yii::app()->controller->site->url == 'wotbs.ru')
			return;
		if (Yii::app()->controller->site->url == 'stat.wot.pw') //До 1 июня
			return;
		if (Yii::app()->controller->site->url == 'forze.wot.pw') //До 1 июня
			return;
		if (Yii::app()->controller->site->url == 'lngst.ru') //До 1 июня
			return;
		if (Yii::app()->controller->site->url == 'xn--80aeirbbdtomneq5kf1nk.xn--j1amh') //Вечно
			return;
		if (Yii::app()->controller->site->url == 'xn--80aaad6bgpyret8b.xn--p1ai') //Вечно
			return;
		if (Yii::app()->controller->site->url == 'xn--29-ilctt.xn--p1ai') //Вечно
			return;
		
		$data = WGApi::checkClan($this, Yii::app()->controller->site->url);
		if ($data < 3)
			$this->addError('id', Yii::t('wot', 'This clan was formerly on this site. Repeated addition is possible only after specifying the links from the clan page on official game site to any page on this site.'));
	}
	
	protected function beforeSave()
	{
		Yii::app()->cache->set('clans', time());
		return parent::beforeSave();
	}
	
	protected function beforeDelete()
	{
		Yii::app()->cache->set('clans', time());
		return parent::beforeDelete();
	}
	
	public function resetAccounts()
	{
		foreach (Accounts::model()->findAllByAttributes(['clan_id'=>$this->id]) as $value)
		{
			$value->reset();
			$value->save();
		}
	}
	
	public function getAccountsStat()
	{
		if (empty($this->accountsSorted))
			return false;
		
		$accounts = [];
		$stat = ['count'=>0, 'bs'=>0, 're'=>0, 'wn8'=>0, 'wins'=>0];
		foreach ($this->accountsSorted as $key=>$value)
		{
			$stat1 = Yii::app()->controller->redis->hGetAll('a:'.$key);
			if (!empty($stat1))
				$accounts[$key] = $stat1;
			else
			{
				$accounts[$key]['b_at'] = 0;
				$accounts[$key]['wins'] = 0;
				$accounts[$key]['bs'] = 0;
				$accounts[$key]['wn8'] = 0;
				$accounts[$key]['re'] = 0;
				$accounts[$key]['t_cu'] = 0;
				$accounts[$key]['b_al'] = 0;
				$accounts[$key]['b_cl'] = 0;
				$accounts[$key]['b_co'] = 0;
				$accounts[$key]['b_te'] = 0;
				$accounts[$key]['b_sd'] = 0;
				$accounts[$key]['b_ss'] = 0;
				$accounts[$key]['b_ga'] = 0;
				$accounts[$key]['b_gc'] = 0;
				$accounts[$key]['b_gm'] = 0;
				$accounts[$key]['b_ra'] = 0;
				$accounts[$key]['t8'] = 0;
				$accounts[$key]['t10'] = 0;
			}
			$accounts[$key]['id'] = $value->id;
			$accounts[$key]['nickname'] = $value->nickname;
			$accounts[$key]['city'] = $value->city;
			$accounts[$key]['region'] = $value->region;
			$accounts[$key]['since'] = $value->since;
			$accounts[$key]['role'] = $value->role;
			$accounts[$key]['role_order'] = $value->sort->role_order;
			
			$stat['count']++;
			$stat['bs']+=$accounts[$key]['bs'];
			$stat['re']+=$accounts[$key]['re'];
			$stat['wn8']+=$accounts[$key]['wn8'];
			$stat['wins']+=$accounts[$key]['wins'];
		}
		$stat['bs']/=$stat['count'];
		$stat['re']/=$stat['count'];
		$stat['wn8']/=$stat['count'];
		$stat['wins']/=$stat['count'];
		
		return ['accounts'=>$accounts, 'stat'=>$stat];
	}
}
