<?php 
    Yii::app()->clientScript->registerScriptFile('/js/jquery.debit.js', CClientScript::POS_END);
?>
<div>
    <label for="debit_money">借款金额：</label>
    <input id="debit_money" type="text" name="debit_money" onblur="checkMoney('debit_money')" value="">元
    <div id="debit_money_em_" class="errorMessage" style="display: none;"></div>
</div>

<div>
    <label for="debit_time">借款期限：</label>
    <select id="debit_time" name="debit_time">
    <?php 
        $debitRate = array();
        for($i=1; $i<13; $i++){
    ?>
    	<option value="<?php echo $i;?>"><?php echo $i;?>个月</option>
    <?php }?>
    </select>
    <div id="debit_time_em_" class="errorMessage" style="display: none;"></div>
</div>

<div>
	<label>还款方式：</label><span>等额本息</span>
</div>

<div>
	<label>利率类型：</label><span>年利率</span>
</div>

<div>
    <label for="debit_rate">利率：</label>
    <input id="debit_rate" type="text" name="debit_rate" onblur="checkRate('debit_rate')" value="">%
    <div id="debit_rate_em_" class="errorMessage" style="display: none;"></div>
</div>

<div id="calculate_result">
</div>
<input type="button" value="计算" name="calculate" id="calculate" onclick="calculate('debit_money','debit_time','debit_rate','calculate_result')">

<a href="/debit/apply">立即借款</a>