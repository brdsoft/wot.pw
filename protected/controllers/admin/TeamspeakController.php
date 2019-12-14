<?php

class TeamspeakController extends Controller
{
	
	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete,start,stop,deleteToken,createToken',
		);
	}
	
	public function accessRules()
	{
		return array(
			array('allow',
				'roles'=>array('0','1'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}
	
	public function actionIndex()
	{
		$server = TsServers::model()->find();
		$stat = TsStat::model()->findAll(['order'=>"`id` DESC"]);
		$server_connect = false;
		$server_info = [];
		$token_list = [];
		$server_group_list = [];
		$channel_group_list = [];
		$channel_list = [];
		
		if ($server)
		{
			if ($server->connect())
				$server_connect = true;
			if ($server_connect && $server->select())
			{
				$server_info = $server->server['query']->serverInfo();
				if ($server_info['success'])
					$server_info = $server_info['data'];
				else
					$server_info = [];
					
				$token_list = $server->server['query']->tokenList();
				if ($token_list['success'])
					$token_list = $token_list['data'];
				else
					$token_list = [];
					
				$server_group_list = $server->server['query']->serverGroupList();
				if ($server_group_list['success'])
					$server_group_list = $server_group_list['data'];
				else
					$server_group_list = [];
				$tmp = [];
				foreach ($server_group_list as $key=>$value)
				{
					$tmp[$value['sgid']] = $value;
				}
				$server_group_list = $tmp;
					
				$channel_group_list = $server->server['query']->channelGroupList();
				if ($channel_group_list['success'])
					$channel_group_list = $channel_group_list['data'];
				else
					$channel_group_list = [];
				$tmp = [];
				foreach ($channel_group_list as $key=>$value)
				{
					$tmp[$value['cgid']] = $value;
				}
				$channel_group_list = $tmp;
					
				$channel_list = $server->server['query']->channelList();
				if ($channel_list['success'])
					$channel_list = $channel_list['data'];
				else
					$channel_list = [];
				$tmp = [];
				foreach ($channel_list as $key=>$value)
				{
					$tmp[$value['cid']] = $value;
				}
				$channel_list = $tmp;
			}
		}
		
		$this->render('index',[
			'server'=>$server,
			'stat'=>$stat,
			'server_info'=>$server_info,
			'server_connect'=>$server_connect,
			'token_list'=>$token_list,
			'server_group_list'=>$server_group_list,
			'channel_group_list'=>$channel_group_list,
			'channel_list'=>$channel_list,
		]);
	}
	
	public function actionCreate()
	{
	    return $this->renderText('Создание TeamSpeak сервера больше недоступно.');


		$model=new TsServers;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['TsServers']))
		{
			$model->attributes=$_POST['TsServers'];
			$model->site_id = $this->site->id; // Для проверки уникальности
			$model->port = $model->freePort;
			$model->server_id = $model->createServerId;
			$model->balance = $this->site->balance;
			$model->rate = $model->defaultRate;
			$model->time_created = time();
			$model->sid = 0;
			
			if ($model->validate())
			{
				if ($model->connect())
				{
					$result = $model->server['query']->serverCreate(['virtualserver_port'=>$model->port, 'virtualserver_name'=>'TeamSpeak server '.$this->site->url, 'virtualserver_welcomemessage'=>'Welcome to TeamSpeak server '.$this->site->url, 'virtualserver_maxclients'=>$model->freeSlots, 'virtualserver_weblist_enabled'=>0]);
					if ($result['success'])
					{
						$model->sid = $result['data']['sid'];
						
						if (($model->snapshot == 'clan' || $model->snapshot == 'alliance') && $model->select())
						{
							$snapshot = $model->server['query']->serverSnapshotCreate();
							$snapshot = explode(' ', $snapshot['data']);
							$uid = explode('=',  explode('|', $snapshot[0])[1], 2)[1];
							$key = explode('=', $snapshot[7], 2)[1];
						
							if ($model->snapshot == 'clan')
								$snapshot = file_get_contents($model->snapshot_clan);
							if ($model->snapshot == 'alliance')
								$snapshot = file_get_contents($model->snapshot_alliance);
							
							$snapshot = explode('|', $snapshot, 2);
							$snapshot[1] = preg_replace('/virtualserver_unique_identifier=[^\s]+/', 'virtualserver_unique_identifier='.$uid, $snapshot[1]);
							$snapshot[1] = preg_replace('/virtualserver_keypair=[^\s]+/', 'virtualserver_keypair='.$key, $snapshot[1]);
							$snapshot[1] = str_replace('virtualserver_created=0', 'virtualserver_created='.time(), $snapshot[1]);
							$hash = base64_encode(sha1($snapshot[1], true));
							$snapshot[0] = 'hash='.$hash;
							$snapshot = implode('|', $snapshot);
							$model->server['query']->serverSnapshotDeploy($snapshot);
							$model->select();
							$model->server['query']->serverEdit(['virtualserver_name'=>'TeamSpeak server '.$this->site->url, 'virtualserver_welcomemessage'=>'Welcome to TeamSpeak server '.$this->site->url, 'virtualserver_maxclients'=>$model->freeSlots, 'virtualserver_weblist_enabled'=>0]);
							$server_group_list = $model->server['query']->serverGroupList();
							if ($server_group_list['success'])
							{
								$server_group_list = $server_group_list['data'];
								foreach ($server_group_list as $value)
								{
									if ($value['type'] == 1 && $value['sortid'] == 1)
									{
										$model->server['query']->tokenAdd(0, $value['sgid'], 0, 'default serveradmin privilege key', []);
										break;
									}
								}
							}
						}
					}
					else
						$model->createStatus = false;
				}
				else
					$model->createStatus = false;
			}
			
			if($model->save())
				$this->redirect(array('index'));
		}
		
		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	public function actionStart()
	{
		$server = TsServers::model()->find();
		if (!$server)
			throw new CHttpException(404,'The requested page does not exist.');
		
		if ($this->site->balance <= 0)
			$this->redirect(['index']);
		if ($server->connect() && $server->server['query']->serverStart($server->sid) && $server->select() && $server->server['query']->serverEdit(['virtualserver_autostart'=>1]))
		{
			$server->time_down = 0;
			$server->update(['time_down']);
			$this->redirect(['index']);
		}
		throw new CHttpException(403,'TeamSpeak сервер в данный момент недоступен.');
	}
	
	public function actionStop()
	{
		$server = TsServers::model()->find();
		if (!$server)
			throw new CHttpException(404,'The requested page does not exist.');
		
		if ($server->connect() && $server->select() && $server->server['query']->serverEdit(['virtualserver_autostart'=>0]) && $server->server['query']->serverStop($server->sid))
		{
			if ($server->time_down == 0)
			{
				$server->time_down = time();
				$server->update(['time_down']);
			}
			$this->redirect(['index']);
		}
		throw new CHttpException(403,'TeamSpeak сервер в данный момент недоступен.');
	}
	
	public function actionDelete()
	{
		$server = TsServers::model()->find();
		if (!$server)
			throw new CHttpException(404,'The requested page does not exist.');
		
		if ($server->connect() && $server->delete())
		{
			$this->redirect(['index']);
		}
		throw new CHttpException(403,'TeamSpeak сервер в данный момент недоступен.');
	}
	
	public function actionDeleteToken()
	{
		$server = TsServers::model()->find();
		if (!$server)
			throw new CHttpException(404,'The requested page does not exist.');
		
		if ($server->connect() && $server->select() && $server->server['query']->tokenDelete(Yii::app()->request->getParam('token')))
			$this->redirect(['index']);
		throw new CHttpException(403,'Указанный токен не существует или TeamSpeak сервер в данный момент недоступен.');
	}
	
	public function actionCreateToken()
	{
		$server = TsServers::model()->find();
		if (!$server)
			throw new CHttpException(404,'The requested page does not exist.');

        if (!is_numeric(Yii::app()->request->getParam('id')))
            throw new CHttpException(403,'Указанная группа не существует или TeamSpeak сервер в данный момент недоступен. 123');

		if ($server->connect() && $server->select()){
		    $info = $server->server['query']->tokenAdd(0, Yii::app()->request->getParam('id'), 0);
            if (!empty($info['success']))
                $this->redirect(['index']);
        }
		throw new CHttpException(403,'Указанная группа не существует или TeamSpeak сервер в данный момент недоступен.');
	}

	public function actionRules()
	{
		$this->render('rules');
	}

}