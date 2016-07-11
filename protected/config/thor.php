<?php
// uncomment the following to define a path alias
//Yii::setPathOfAlias('upload','./upload');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
        'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
        'name'=>'好贷网',

        // preloading 'log' component
        'preload'=>array('log'),

        // autoloading model and component classes
        'import'=>array(
                'application.models.*',
                'application.components.*',
				'application.extensions.*',
        ),

        'modules'=>array(
                'main','boss',
                'gii'=>array(
                        'class'=>'system.gii.GiiModule',
                        'password'=>'123456',
                        // If removed, Gii defaults to localhost only. Edit carefully to taste.
                        'ipFilters'=>array('127.0.0.1','::1'),
                ),
        ),

        // application components
        'components'=>array(
                'user'=>array(
                // enable cookie-based authentication
                        'allowAutoLogin'=>false
                ),
                // uncomment the following to enable URLs in path-format

                'urlManager'=>array(
                        'urlFormat'=>'path',
                        'showScriptName' => false,
                ),

                'cache'=> array(
                        'class' => 'CFileCache',
                        'directoryLevel' => 2
                ),

                'fileCache'=> array(
                        'class' => 'CFileCache',
                ),

                'db'=>array(
                        'class' => 'DbConnectionMan', // 'class' => 'system.db.CDbConnection',
                        'connectionString' => 'mysql:host=localhost;dbname=thor',
                        //'schemaCachingDuration'=>432000, //60*60*12
                        'emulatePrepare' => true,
                        'enableProfiling' => true,
                        'username' => 'root',
                        'password' => '',
                        'charset' => 'utf8',
                        'tablePrefix' => 'p2p_',
                ),
                'log'=>array(
                        'class'=>'CLogRouter',
                        'routes'=>array(
                                array(
                                        'class'=>'CFileLogRoute',
                                        'levels'=>'error, warning, info,trace',
                                ),
                                //uncomment the following to show log messages on web pages
                                /*array(
                                        'class'=>'CProfileLogRoute',
                                        'levels'=>'error, warning, info,trace',
                                ),*/
                        ),
                ),
                'easyImage' => array(
    		        'class' => 'application.extensions.easyimage.EasyImage',
    		    ),
        ),

        // application-level parameters that can be accessed
// using Yii::app()->params['paramName']
        'params'=>array(
        // this is used in contact page
                'adminEmail'=>'wangbendong@meiti.com',
                'keyword'=>'好贷网',
                'des'=>'好贷网',
                'uploadPath' => '/upload',
                'withdrawFee' => 0.5,//提现手续费0.5%
                'adminId' => 1,
                'securityQuestions' => array(
                        1 => '我父亲的名字?',
                        2 => '我母亲的名字?',
                        3 => '我小学的名字?'
                )
        )
);