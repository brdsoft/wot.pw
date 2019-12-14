<?php

class RecruitmentController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionOne($id)
	{
		$recruitment=Recruitment::model()->with('account')->findByPk($id);
		if($recruitment===null)
			throw new CHttpException(404,'Заявка с указанным ID не найдена.');
		
		if (isset($_GET['resolution']) && preg_match('=^1|2|3|4$=', $_GET['resolution']) && (Yii::app()->user->checkAccess('0') || Yii::app()->user->checkAccess('1') || Yii::app()->user->checkAccess('4') || Yii::app()->user->checkAccess('8')))
		{
			$recruitment->status = 1;
			$recruitment->resolution = $_GET['resolution'];
			$recruitment->resolution_account = Yii::app()->user->id;
			$recruitment->resolution_time = time();
			$recruitment->invited = isset($_GET['invited']) ? $_GET['invited'] : '';
			$recruitment->save();
		}
		
		$accountStat = $recruitment->account->stat;
		
		$clanStat = $recruitment->clan->getAccountsStat();
		if (!$clanStat)
			throw new CHttpException(403,'Невозможно собрать статистику клана.');
		
		$stat = [];
		
		if ($clanStat['stat']['bs'] > 0 && $accountStat['bs'] > 0 && $accountStat['bs'] >= $clanStat['stat']['bs'])
			$stat['bs'] = '(на '.round(($accountStat['bs']/$clanStat['stat']['bs']-1)*100).'% больше чем среднее по клану)';
		elseif ($clanStat['stat']['bs'] > 0 && $accountStat['bs'] > 0 && $accountStat['bs'] < $clanStat['stat']['bs'])
			$stat['bs'] = '(на '.round((1-$accountStat['bs']/$clanStat['stat']['bs'])*100).'% меньше чем среднее по клану)';
		else
			$stat['bs'] = '';
			
		if ($clanStat['stat']['re'] > 0 && $accountStat['re'] > 0 && $accountStat['re'] >= $clanStat['stat']['re'])
			$stat['re'] = '(на '.round(($accountStat['re']/$clanStat['stat']['re']-1)*100).'% больше чем среднее по клану)';
		elseif ($clanStat['stat']['re'] > 0 && $accountStat['re'] > 0 && $accountStat['re'] < $clanStat['stat']['re'])
			$stat['re'] = '(на '.round((1-$accountStat['re']/$clanStat['stat']['re'])*100).'% меньше чем среднее по клану)';
		else
			$stat['re'] = '';
		
		if ($clanStat['stat']['wn8'] > 0 && $accountStat['wn8'] > 0 && $accountStat['wn8'] >= $clanStat['stat']['wn8'])
			$stat['wn8'] = '(на '.round(($accountStat['wn8']/$clanStat['stat']['wn8']-1)*100).'% больше чем среднее по клану)';
		elseif ($clanStat['stat']['wn8'] > 0 && $accountStat['wn8'] > 0 && $accountStat['wn8'] < $clanStat['stat']['wn8'])
			$stat['wn8'] = '(на '.round((1-$accountStat['wn8']/$clanStat['stat']['wn8'])*100).'% меньше чем среднее по клану)';
		else
			$stat['wn8'] = '';
		
		$this->render('one', array('recruitment'=>$recruitment, 'stat'=>$stat));
	}

	public function actionAdd()
	{
		if (isset($_GET['status']) && $_GET['status'] == 'ok')
		{
			$this->render('add',array('action'=>'ok'));
			return;
		}
		if (Yii::app()->user->isGuest || !Yii::app()->user->account)
		{
			$this->render('add',array('action'=>'guest'));
			return;
		}
		if (Config::all('recruitment_access')->value == 'noclan' && !empty(Yii::app()->user->account->clan_id))
		{
			$this->render('add',array('action'=>'clan'));
			return;
		}
		if (Recruitment::model()->find("`account_id` = '".Yii::app()->user->account->id."' AND `status` = '0'"))
		{
			$this->render('add',array('action'=>'alredy'));
			return;
		}
		$model=new Recruitment;
    if(isset($_POST['Recruitment']))
    {
			$ban = Bans::model()->findByAttributes(['account_id'=>Yii::app()->user->id]);
			if ($ban)
				throw new CHttpException(403, Yii::t('wot', 'Access denied. You are banned until').' '.Yii::app()->dateFormatter->formatDateTime($ban->expire+Yii::app()->params["moscow"], 'long', 'short'));
			
			$stat = Yii::app()->user->account->stat;
			
			$model->attributes = $_POST['Recruitment'];
			$model->account_id = Yii::app()->user->id;
			$model->time = time();
			
			$model->battles = $stat['b_al'];
			$model->wins = round($stat['wins']);
			$model->re = round($stat['re']);
			$model->wn8 = round($stat['wn8']);
			$model->bs = round($stat['bs']);
			$model->t10 = round($stat['t10']);
			
			if($model->validate())
			{
				$model->save();
				$this->redirect(array('/recruitment/add', 'status'=>'ok'));
			}
    }
		$this->render('add',array('action'=>'form', 'model'=>$model));
	}

	public function actionStat()
	{
		if (!Yii::app()->user->checkAccess('0') && !Yii::app()->user->checkAccess('1') && !Yii::app()->user->checkAccess('4') && !Yii::app()->user->checkAccess('8'))
			throw new CHttpException(403,'Доступ к данному разделу имеют только администратор, модератор и кадровик');
		$model = new StatForm;
    if(isset($_POST['StatForm']))
    {
			$model->attributes = $_POST['StatForm'];
			if($model->validate())
			{
				
			}
    }
		$this->render('stat', array('model'=>$model));
	}
}