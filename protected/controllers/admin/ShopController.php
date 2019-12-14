<?php

class ShopController extends Controller
{
	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + activate',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'roles'=>array('0', '1'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$this->render('index',[
			'orders'=>Orders::model(),
		]);
	}

	public function actionFaq()
	{
		$this->render('faq',[
		]);
	}

	public function actionActivate()
	{
		$id = Yii::app()->request->getParam('id', false);
		if (!$id)
			$this->redirect(['index']);
		
		$orders = Orders::model();
		
		if (!isset($orders->items_ru[$id]))
			$this->redirect(['index']);
		
		$amount = $orders->items_ru[$id]['amount_discount'] ? $orders->items_ru[$id]['amount_discount'] : $orders->items_ru[$id]['amount'];
		if ($amount > $this->site->balance)
			$this->redirect(['index']);
		
		if ($id == 1)
		{
			$this->site->premium_reklama = 1;
			$this->addProfit($orders->items_ru[$id]['name'], 'Looney', $amount*0.3);
		}
		if ($id == 2)
		{
			$this->site->premium_domain = 'Услуга активирована';
			$this->addProfit($orders->items_ru[$id]['name'], 'Looney', $amount*0.3);
		}
		if ($id == 3)
		{
			$this->site->premium_clans += 10;
			$this->addProfit($orders->items_ru[$id]['name'], 'Looney', $amount*0.3);
		}
		if ($id == 4)
		{
			$this->site->premium_html = 1;
			$this->addProfit($orders->items_ru[$id]['name'], 'Nommyde', $amount*0.5);
			$this->addProfit($orders->items_ru[$id]['name'], 'Looney', $amount*0.5*0.3);
		}
		if ($id == 5)
		{
			$this->site->premium_companies = 1;
			$this->addProfit($orders->items_ru[$id]['name'], 'Looney', $amount*0.3);
		}
		if ($id == 6)
		{
			$a = $this->site->premium_widgets;
			$a[] = 10;
			$this->site->premium_widgets = $a;
			$this->addProfit($orders->items_ru[$id]['name'], 'Looney', $amount*0.4);
		}
		if ($id == 7)
		{
			$a = $this->site->premium_widgets;
			$a[] = 1;
			$this->site->premium_widgets = $a;
			$this->addProfit($orders->items_ru[$id]['name'], 'Nommyde', $amount*0.5);
			$this->addProfit($orders->items_ru[$id]['name'], 'Looney', $amount*0.5*0.3);
		}
		if ($id == 8)
		{
			$a = $this->site->premium_widgets;
			$a[] = 12;
			$this->site->premium_widgets = $a;
			$this->addProfit($orders->items_ru[$id]['name'], 'Looney', $amount*0.4);
		}
		if ($id == 9)
		{
			$this->site->premium_tactic = 1;
			$this->addProfit($orders->items_ru[$id]['name'], 'Nommyde', $amount*0.4);
			$this->addProfit($orders->items_ru[$id]['name'], 'Looney', $amount*0.6*0.3);
		}
		if ($id == 10)
		{
			$this->site->premium_domain = 'Услуга активирована';
			$this->addProfit($orders->items_ru[$id]['name'], 'Looney', $amount);
		}
		if ($id == 11)
		{
			$a = $this->site->premium_widgets;
			$a[] = 15;
			$this->site->premium_widgets = $a;
			//$this->addProfit($orders->items_ru[$id]['name'], 'Nommyde', $amount*0.4);
			//$this->addProfit($orders->items_ru[$id]['name'], 'Looney', $amount*0.4*0.3);
		}
		
		$this->site->balance -= $amount;
		$this->site->save();
		
		$cost = new Costs;
		$cost->item_id = $id;
		$cost->amount = $amount;
		$cost->time = time();
		$cost->save();
		
		$this->redirect(['index']);
	}
	
	private function addProfit($item, $name, $amount)
	{
		$amount = number_format($amount, 2, '.', '');
		$profit = Profits::model()->findByPk($name);
		$profit->debt = $profit->debt + $amount;
		$profit->sum = $profit->sum + $amount;
		$profit->update(array('debt', 'sum'));
		
		$headers= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n";
		$headers .= "From: wot.pw <shop@wot.pw>\r\n";
		
		$message = '
			<p>Активирована услуга "'.$item.'".</p>
			<p>Твой профит: '.$amount.' руб.</p>
		';
		
		mail($profit->email, 'Активирована услуга "'.$item.'"', $message, $headers);		
	}
	
}
