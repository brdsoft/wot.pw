<?php

/**
 * This is the model class for table "polls".
 *
 * The followings are the available columns in table 'polls':
 * @property string $id
 * @property string $site_id
 * @property string $account_id
 * @property string $time
 * @property string $enabled
 * @property string $access
 * @property string $question
 * @property string $answer1
 * @property string $answer2
 * @property string $answer3
 * @property string $answer4
 * @property string $answer5
 * @property string $answer6
 * @property string $answer7
 * @property string $answer8
 * @property string $answer9
 * @property string $answer10
 *
 * The followings are the available model relations:
 * @property Sites $site
 * @property Accounts $account
 * @property PollsAnswers[] $pollsAnswers
 */
class Polls extends ActiveRecord
{
	public $accessAllowed = [];
	
	public function __construct($scenario='insert')
	{
		 $this->accessAllowed = [
			'0'=>Yii::t('wot', 'All authorized users'),
			'1'=>Yii::t('wot', 'Only our clans members'),
		];
		return parent::__construct($scenario);
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'polls';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('account_id, time, question, answer1, answer2', 'required'),
			array('site_id', 'length', 'max'=>11),
			array('account_id', 'length', 'max'=>20),
			array('time', 'length', 'max'=>10),
			array('enabled, access', 'length', 'max'=>1),
			array('question, answer1, answer2, answer3, answer4, answer5, answer6, answer7, answer8, answer9, answer10', 'length', 'max'=>255),
			array('access', 'in', 'range'=>array_keys($this->accessAllowed), 'message'=>Yii::t('wot', 'Access is incorrect.')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, site_id, account_id, time, enabled, access, question, answer1, answer2, answer3, answer4, answer5, answer6, answer7, answer8, answer9, answer10', 'safe', 'on'=>'search'),
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
			'answers' => array(self::HAS_MANY, 'PollsAnswers', 'poll_id'),
			'answersCount' => array(self::STAT, 'PollsAnswers', 'poll_id'),
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
			'account_id' => 'Автор',
			'time' => 'Дата создания',
			'enabled' => 'Принимать ответы',
			'access' => 'Могут голосовать',
			'question' => 'Вопрос',
			'answer1' => 'Ответ 1',
			'answer2' => 'Ответ 2',
			'answer3' => 'Ответ 3',
			'answer4' => 'Ответ 4',
			'answer5' => 'Ответ 5',
			'answer6' => 'Ответ 6',
			'answer7' => 'Ответ 7',
			'answer8' => 'Ответ 8',
			'answer9' => 'Ответ 9',
			'answer10' => 'Ответ 10',
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
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('enabled',$this->enabled,true);
		$criteria->compare('access',$this->access,true);
		$criteria->compare('question',$this->question,true);
		$criteria->compare('answer1',$this->answer1,true);
		$criteria->compare('answer2',$this->answer2,true);
		$criteria->compare('answer3',$this->answer3,true);
		$criteria->compare('answer4',$this->answer4,true);
		$criteria->compare('answer5',$this->answer5,true);
		$criteria->compare('answer6',$this->answer6,true);
		$criteria->compare('answer7',$this->answer7,true);
		$criteria->compare('answer8',$this->answer8,true);
		$criteria->compare('answer9',$this->answer9,true);
		$criteria->compare('answer10',$this->answer10,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Polls the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function calculateAnswers()
	{
		$count = $this->answersCount;
		
		$criteria=new CDbCriteria;
		$criteria->select = "COUNT(`id`) `count`, ROUND(COUNT(`id`) / {$count} *100) `percent`, `answer`";
		$criteria->group = "`answer`";
		$criteria->condition = "`poll_id` = '{$this->id}'";
		$criteria->index = 'answer';
		
		return PollsAnswers::model()->findAll($criteria);
	}
	
	public function getAnswerAccounts($answer)
	{
		$answers = PollsAnswers::model()->with(['account'=>['select'=>'id, nickname']])->findAll(['condition'=>"`poll_id` = '{$this->id}' AND `answer` = '{$answer}'", 'together'=>true]);
		$accounts = [];
		foreach ($answers as $value)
		{
			$accounts[] = CHtml::link($value->account->nickname, ['/profile/account', 'id'=>$value->account->id]);
		}
		return implode(', ', $accounts);
	}
}
