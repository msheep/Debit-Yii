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
				),
	)
);
?>