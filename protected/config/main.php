<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Сайт клана',
	'language'=>'ru',
	'charset'=>'utf-8',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'************',
			'ipFilters'=>array('*'),
		),
	),

	// application components
	'components'=>array(
		'request'=>array(
			'enableCsrfValidation'=>true,
			'enableCookieValidation'=>true,
		),
		'user'=>array(
			// enable cookie-based authentication
			'class'=>'WebUser',
			'allowAutoLogin'=>true,
			'autoRenewCookie'=>true,
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'/'=>'/site/index',
				'/profile'=>'/profile/index',
				'/admin'=>'/admin/index',
				'/forum'=>'/forum/index',
				'/news/<News_page:\d+>'=>'/news/index',
				'/news'=>'/news/index',
				'/recruitment'=>'/recruitment/index',
				'/page/<id:.+>'=>'/page/index',
				'/custom/<id:.+>'=>'/custom/index',
				'/css/<style:.+>'=>'/css/index',
				'/themes/<theme:\w+>/<skin:\w+>/css/style<style:\d+>.css'=>'/css/index',
				
				'/forum/category/<id:\d+>'=>'/forum/oldCategory',
				'/forum/theme/<id:\d+>'=>'/forum/oldTheme',
				'/forum/<id:\d+>/p<ForumThemes_page:\d+>'=>'/forum/category',
				'/forum/<id:\d+>'=>'/forum/category',
				'/forum/<category_id:\d+>/<id:\d+>/p<ForumMessages_page:\d+>'=>'/forum/theme',
				'/forum/<category_id:\d+>/<id:\d+>'=>'/forum/theme',
				
				'/staff/attendance/<clan_id:\d+>/<param:\d+>'=>'/staff/attendance',
				
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'db'=>array(
			'connectionString' => 'mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname=************',
			'emulatePrepare' => true,
			'schemaCachingDuration' => 3600,
			'username' => '************',
			'password' => '************',
			'charset' => 'utf8mb4',
			// включаем профайлер
			//'enableProfiling'=>true,
			// показываем значения параметров
			//'enableParamLogging' => true,
		),
		'authManager'=>array(
			'class'=>'PhpAuthManager',
			'defaultRoles'=>array('guest'),
		),
		'cache'=>array(
			'class'=>'system.caching.CMemCache',
		),
		//'session' => array (
		//	'class'=> 'CCacheHttpSession',
		//),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		 'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CWebLogRoute',
					'levels'=>'error',
					'showInFireBug'=>true,
				),
			),
		),
		'format'=>array(
			'class'=>'Formatter',
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'clusters'=>array(
			'ru'=>array(
				'id'=>'ru',
				'application_id'=>'************',
				'url'=>'worldoftanks.ru',
				'api_url'=>'http://api.worldoftanks.ru',
				'openid_url'=>'http://ru.wargaming.net/id/',
			),
			'eu'=>array(
				'id'=>'eu',
				'application_id'=>'************',
				'url'=>'worldoftanks.eu',
				'api_url'=>'http://api.worldoftanks.eu',
				'openid_url'=>'http://eu.wargaming.net/id/',
			),
		),
		'wgr'=>0,
		'adminEmail'=>'webmaster@example.com',
		'application_id'=>'************',// wargaming application id
		'access_admin'=>'0,1,2,3,5,8', // имеют доступ в админку
		'skipSiteCheck'=>false, // отключает проверку на принадлежность к сайту в AR
		'moscow'=>10800,
		'domains'=>array('wot.pw', 'wclan.ru'),
		'admins'=>array('7208418', '2202938'),
		'languages' => array('ru'=>'Русский','en'=>'English'),
	),
);