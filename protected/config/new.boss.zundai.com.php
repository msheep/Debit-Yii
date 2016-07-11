<?php 
return CMap::mergeArray(
	require(dirname(__FILE__).'/thor.php'),
	array(
        		'defaultController'=>'boss/site',
				'components'=>array(
						'errorHandler'=>array(
								'errorAction'=>'/boss/site/error',
						),
						'urlManager'=>array(
								'rules'=>array(
									'debit/detail/<debitId:\d+>'=>'boss/debit/detail',
									'<controller:.+>/<action:.+>/*' => 'boss/<controller>/<action>',
								),
						),
						'db'=>array(
                                'class' => 'DbConnectionMan', // 'class' => 'system.db.CDbConnection',
                                'connectionString' => 'mysql:host=localhost;dbname=thor',
                                //'schemaCachingDuration'=>432000, //60*60*12
                                'emulatePrepare' => true,
                                'enableProfiling' => true,
                                'username' => 'root',
								'password' => 'sukai!2#',
                                'charset' => 'utf8',
                                'tablePrefix' => 'p2p_',
                        ),
                        'dbTest'=>array(
								'class' => 'DbConnectionMan', // 'class' => 'system.db.CDbConnection',
								'connectionString' => 'mysql:host=10.4.3.20;dbname=thor',
								'schemaCachingDuration'=>432000, //60*60*12
								'emulatePrepare' => true,
								'enableProfiling' => true,
								'username' => 'root',
								'password' => '',
								'charset' => 'utf8',
								'tablePrefix' => 'p2p_',
						),
				),
	)
);
?>