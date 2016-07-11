<?php
return CMap::mergeArray(
	require(dirname(__FILE__).'/thor.php'),
	array(
        		'defaultController'=>'main/site',
				'components'=>array(
				        'user'=>array(
				        // enable cookie-based authentication
				                'class'=>'FrontCWebUser',
				                'allowAutoLogin'=>false,
				                'loginUrl'=>'/site/login'
				        ),
						'errorHandler'=>array(
								'errorAction'=>'main/site/error',
						),
						'urlManager'=>array(
									'rules'=>array(
										'debit/apply'=>'main/debit/applyDebit',
										'debit/domain/<debitId:\d+>'=>'main/debit/domainDebit',
                						'debit/property/<debitId:\d+>'=>'main/debit/propertyDebit',
                						'debit/car/<debitId:\d+>'=>'main/debit/carDebit',
										'invest/<debitId:\d+>'=>'main/invest/debitDetail',
										'accountCenter/debitDetail/<debitId:\d+>'=>'main/accountCenter/debitDetail',
										'<controller:.+>/<action:.+>/*' => 'main/<controller>/<action>',
									),
						),
				        'format' => array(
				                'dateFormat' => 'Y-m-d',
				                'datetimeFormat' => 'Y-m-d H:i:s',
				                'timeFormat' => 'H:i:s'
				        ),
				),
	)
);
?>