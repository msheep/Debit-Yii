<?php
/**
 * FrontCWebUser class file
 *
 * @author lizheng
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 */

class FrontCWebUser extends CWebUser{
    
    //实现afterLogin方法
    protected function afterLogin($fromCookie = false)
    {
        //设置上次登录时间，本次登录时间
        User::model()->updateByPk($this->id,array('lastLoginTime'=>new CDbExpression('currentLoginTime'),'currentLoginTime'=>new CDbExpression('NOW()')));
    }
}