<?php 
Yii::app()->clientScript->registerCssFile('/css/idialog/default.css');
Yii::app()->clientScript->registerCssFile('/css/boss/debit.css');
Yii::app()->clientScript->registerScriptFile('/js/artDialog.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/boss/jquery.debit.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile("/css/boss/font-awesome.min.css");
?>

<ul class="breadcrumb">
	<li><span>借贷产品审核列表</span></li>
</ul>
	
<form name="searchFrom" action="/debit/productList" method="get" id="searchFrom"> <!-- onSubmit="return ajaxSearch()" -->
	<div class="search widget first">
		<fieldset>
			<legend>条件查询</legend>
    		<div class="area">
    			<span>开始时间：
            		<?php 
            		    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            					'name'=>'dateBegin',
            					'language' => 'zh_cn',
            					'value'	=>$dateBegin,
            					'options' => array(
            							'showAnim' => 'fold',
            							'dateFormat' => 'yy-mm-dd',
            							'buttonImageOnly'=>true,
            							'readonly' => 'readonly',
            							'monthNames' => array("1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"),
            							'showMonthAfterYear' => true
            					),
            					'htmlOptions'=>array(
            							'readonly'=>'readonly',
            							'style'=>'cursor:pointer'
            					)
            				));
            		?>
    			</span>
        		<span>结束时间：
            		<?php 
            		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
            					'name'=>'dateEnd',
            					'language' => 'zh_cn',
            					'value'	=> $dateEnd,
            					'options' => array(
            							'showAnim' => 'fold',
            							'dateFormat' => 'yy-mm-dd',
            							'buttonImageOnly'=>true,
            							'readonly' => 'readonly',
            							'monthNames' => array("1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"),
            							'showMonthAfterYear' => true
            					),
            					 'htmlOptions'=>array(
            				         'readonly'=>'readonly',
            					     'style'=>'cursor:pointer'
            				     )
            				));
            		?>
        		</span>
        		<span>
            		<label class="checkbox inline">
                    	<input type="checkbox" value="0" name="checkStatus[]" class="status" <?php if(isset($_GET['checkStatus']) && in_array(0,$_GET['checkStatus'])){?>checked<?php }?>>待审核
                    </label>
                    <label class="checkbox inline">
                    	<input type="checkbox" value="1" name="checkStatus[]" class="status" <?php if(isset($_GET['checkStatus']) && in_array(1,$_GET['checkStatus'])){?>checked<?php }?>>审核通过
                    </label>
                    <label class="checkbox inline">
                    	<input type="checkbox" value="2" name="checkStatus[]" class="status" <?php if(isset($_GET['checkStatus']) && in_array(2,$_GET['checkStatus'])){?>checked<?php }?>>已发布
                    </label>
                    <label class="checkbox inline">
                    	<input type="checkbox" value="-2" name="checkStatus[]" class="status" <?php if(isset($_GET['checkStatus']) && in_array(-2,$_GET['checkStatus'])){?>checked<?php }?>>未通过
                    </label>
                    <label class="checkbox inline">
                    	<input type="checkbox" value="-3" name="checkStatus[]" class="status" <?php if(isset($_GET['checkStatus']) && in_array(-3,$_GET['checkStatus'])){?>checked<?php }?>>用户驳回
                    </label>
        		</span>
        	
        		<span>
            		<label class="checkbox inline">
                    	<input type="checkbox" value="1" name="checkCat[]" <?php if(isset($_GET['checkCat']) && in_array(1,$_GET['checkCat'])){?>checked<?php }?>>域名贷
                    </label>
                    <label class="checkbox inline">
                    	<input type="checkbox" value="2" name="checkCat[]" <?php if(isset($_GET['checkCat']) && in_array(2,$_GET['checkCat'])){?>checked<?php }?>>房产贷
                    </label>
                     <label class="checkbox inline">
                    	<input type="checkbox" value="3" name="checkCat[]" <?php if(isset($_GET['checkCat']) && in_array(3,$_GET['checkCat'])){?>checked<?php }?>>车辆贷
                    </label>
        		</span>

        
    			<select class="add-on" id="condition" name="condition">
    				<option value="1" <?php if(isset($_GET['condition']) && $_GET['condition']==1){?>selected<?php }?>>会员ID</option>
    				<option value="2" <?php if(isset($_GET['condition']) && $_GET['condition']==2){?>selected<?php }?>>用户名</option>
    				<option value="3" <?php if(isset($_GET['condition']) && $_GET['condition']==3){?>selected<?php }?>>借款标题</option>
    				<option value="4" <?php if(isset($_GET['condition']) && $_GET['condition']==3){?>selected<?php }?>>借款ID</option>
    			</select>
    			<input class="span2" id="prependedInput" name="searchCondition" type="text" <?php if(isset($_GET["searchCondition"]) && $_GET["searchCondition"]){?>value="<?php echo $_GET["searchCondition"];?>"<?php }else{?>placeholder="查询条件"<?php }?> >
        		<input type="submit" name="search" id="search" class="btn" value="查询">
    		</div>
		</fieldset>
	</div>
</form>
<div id="pager">   
    <?php
        $this->renderPartial('_ajaxListView', array('dataProvider' => $dataProvider,'itemView'=>'_productList',));
    ?> 
</div>