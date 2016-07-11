<?php
/*
 * 辅助小功能
 */
class AssistController extends Controller {
    //利率计算器
    public function actionCalculator(){
        $this->layout = '/layouts/debitLayout';
        $this->breadcrumbs = array(
                '我要借款'=>array('debit/index'),
                '利率说明'
        );
        $this->render('calculator');
    }
}
