<?php 
class ScriptCLICommand extends CConsoleCommand
{
		public function init()
		{
			Yii::app()->db->enableSlave=false;
		}
		
        // Demo: E:\xampp\php\php.exe E:\www\theone\scriptCLI.php scriptcli LogUnionFlow
        public function actionLogUnionFlow() {
        }
        
}
?>