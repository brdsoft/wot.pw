<?php

/**
 * This is the model class for table "files".
 *
 * The followings are the available columns in table 'files':
 * @property integer $id
 * @property string $account_id
 * @property string $name
 * @property integer $size
 * @property integer $time
 *
 * The followings are the available model relations:
 * @property Accounts $account
 */
class Files extends CActiveRecord
{
	public $file;
	public $filesPath = '/upload';
	public $targets = [];
	protected $_target = 'editor';
	
	public function init()
	{
		$this->targets = array(
			'news'=>array(
				'name'=>'news',
				'maxSize'=>10485760,
				'resize'=>500,
				'quality'=>88,
				'types'=>'jpg, jpeg, gif, png',
				'message'=>Yii::t('wot', 'Select the image (JPG, GIF or PNG, <10 MB).'),
				'autoInsert'=>true,
				'insertTypes'=>array(
					array(
						'pattern'=>'/^jpg|jpeg|gif|png$/i',
						'onClick'=>'window.parent.$("#"+id).val(data.replace(/.+\//, "")); window.parent.$("#"+id+"_preview img").attr("src", data); window.parent.$("#"+id+"_preview").show(); window.parent.$.fancybox.close();',
					),
				),
			),
			'favicon'=>array(
				'name'=>'favicon',
				'maxSize'=>102400,
				'resize'=>16,
				'quality'=>99,
				'types'=>'png',
				'message'=>Yii::t('wot', 'Select the image (PNG, 16x16, <100 KB).'),
				'autoInsert'=>true,
				'insertTypes'=>array(
					array(
						'pattern'=>'/^png$/i',
						'onClick'=>'window.opener.$("#"+id).val(data.replace(/.+\//, "")); window.opener.$("#"+id+"_preview img").attr("src", data); window.opener.$("#"+id+"_preview").show();',
					),
				),
			),
			'editor'=>array(
				'name'=>'editor',
				'maxSize'=>10485760,
				'resize'=>1400,
				'quality'=>85,
				'types'=>'jpg, jpeg, gif, png, zip, rar, 7z, wotreplay',
				'message'=>Yii::t('wot', 'Select the file (jpg, gif, png, zip, rar, 7z or wotreplay, <10 MB).'),
				'autoInsert'=>false,
				'insertTypes'=>array(
					'image'=>array(
						'pattern'=>'/^jpg|jpeg|gif|png$/i',
						'title'=>Yii::t('wot', 'Insert image in editor'),
						'onClick'=>'window.opener.tinymce.activeEditor.insertContent("<img src=\""+data+"\" alt=\"\">")',
					),
					'file'=>array(
						'pattern'=>'/^zip|rar|7z$/i',
						'title'=>Yii::t('wot', 'Insert link in editor'),
						'onClick'=>'window.opener.tinymce.activeEditor.insertContent("<a href=\""+data+"\">"+data.replace(/.+\//, "")+"</a>")',
					),
					'replay'=>array(
						'pattern'=>'/^wotreplay$/i',
						'title'=>in_array(10, Yii::app()->controller->site->premium_widgets) ? Yii::t('wot', 'Insert replay in editor') : Yii::t('wot', 'Insert link in editor'),
						'onClick'=>in_array(10, Yii::app()->controller->site->premium_widgets) ? 'window.opener.tinymce.activeEditor.insertContent("{REPLAY file=\""+data.replace(/.+\//, "")+"\"}")' : 'window.opener.tinymce.activeEditor.insertContent("<a href=\""+data+"\">"+data.replace(/.+\//, "")+"</a>")',
					),
				),
			),
			'standalone'=>array(
				'name'=>'standalone',
				'maxSize'=>10485760,
				'resize'=>false,
				'quality'=>false,
				'types'=>'jpg, jpeg, gif, png, zip, rar, 7z, wotreplay',
				'message'=>Yii::t('wot', 'Select the file (jpg, gif, png, zip, rar, 7z or wotreplay, <10 MB).'),
				'autoInsert'=>false,
				'insertTypes'=>array(),
			),
		);
	}
	
	public function getTarget()
	{
		return $this->targets[$this->_target];
	}
	
	public function setTarget($target)
	{
		if (isset($this->targets[$target]))
			return $this->_target = $target;
		return false;
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'files';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('account_id, name, size', 'required'),
			array('size, time', 'numerical', 'integerOnly'=>true),
			array('account_id', 'length', 'max'=>20),
			array('name', 'length', 'max'=>64),
			array('file', 'file', 'types'=>$this->target['types'], 'maxSize' => $this->target['maxSize'], 'safe'=>false),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, account_id, name, size', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('wot', 'ID'),
			'account_id' => Yii::t('wot', 'Account'),
			'name' => Yii::t('wot', 'Name'),
			'size' => Yii::t('wot', 'Size'),
			'time' => Yii::t('wot', 'Date'),
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
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('size',$this->size);
		$criteria->compare('time',$this->time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Files the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getUrl()
	{
		return preg_replace('=^([\w]{2})([\w]{2}).+=', $this->filesPath.'/$1/$2/'.$this->name, $this->name);
	}
	
	public function link($name)
	{
		return preg_replace('=^([\w]{2})([\w]{2}).+=', $this->filesPath.'/$1/$2/'.$name, $name);
	}
	
	public function getPath()
	{
		return preg_replace('=^([\w]{2})([\w]{2}).+=', Yii::getPathOfAlias('webroot').$this->filesPath.'/$1/$2/'.$this->name, $this->name);
	}
	
	public function getRandomName()
	{
		return md5(uniqid(rand(),1));
	}
	
	public function createDir()
	{
		$a = preg_replace('=^([\w]{2})([\w]{2}).+=', '/$1', $this->name);
		$b = preg_replace('=^([\w]{2})([\w]{2}).+=', '/$2', $this->name);
		@mkdir(Yii::getPathOfAlias('webroot').$this->filesPath.$a, 0777);
		@mkdir(Yii::getPathOfAlias('webroot').$this->filesPath.$a.$b, 0777);
		return true;
	}
	
	public function process()
	{
		Yii::import('application.vendor.WideImage.WideImage');
		
		if (preg_match('/^jpg|jpeg|gif|png$/i', $this->file->extensionName) && ($this->target['resize'] || $this->target['quality']))
		{
			try {
				$w = WideImage::load($this->path);
				if ($this->target['resize'])
					$w = $w->resize($this->target['resize'], $this->target['resize'], 'inside', 'down');
				if ($this->target['quality'] && preg_match('/^jpg|jpeg$/i', $this->file->extensionName))
					$w->saveToFile($this->path, $this->target['quality']);
				else
					$w->saveToFile($this->path);
			}
			catch (Exception $e)
			{
				return false;
			}
		}
		return true;
	}
}
