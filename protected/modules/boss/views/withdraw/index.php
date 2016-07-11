<?php 
Yii::app()->clientScript->registerCssFile('/css/idialog/default.css');
Yii::app()->getClientScript()->registerCssFile("/css/boss/font-awesome.min.css");
Yii::app()->clientScript->registerScriptFile('/js/artDialog.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/boss/withdraw.js', CClientScript::POS_END);
?>
<ul class="breadcrumb">
	<li>
		<span>提现处理</span>
	</li>
</ul>
<?php $form=$this->beginWidget('CActiveForm', array(
   'id'=>'withdrawlist-search-form',
    'method' => 'get',
    'action' => array('index'),
    'htmlOptions' => array('class'=>'well form-inline')
));
?>
<div class="search widget first">
	<div class="head"><h5 class="iSettings2">提现查询</h5></div>
		<fieldset>
			<legend>条件查询</legend>
    		<div class="area">
    			<span>开始时间：
            		<?php 
            		    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            		            'model' => $model,
            		            'attribute' => 'fromTime',
            					'language' => 'zh_cn',
            					'options' => array(
            					        'showAnim' => 'fade',
            							'dateFormat' => 'yy-mm-dd',
            							'buttonImageOnly'=>true,
            							'monthNames' => array("1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"),
            							'showMonthAfterYear' => true,
            					        'onClose' => 'js:function( selectedDate ) {$( "#'.CHtml::activeId($model,'toTime').'" ).datepicker( "option", "minDate", selectedDate );}'
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
            		            'model' => $model,
            		            'attribute' => 'toTime',
            					'language' => 'zh_cn',
            					'options' => array(
            					        'showAnim' => 'fade',
            							'dateFormat' => 'yy-mm-dd',
            							'buttonImageOnly'=>true,
            							'monthNames' => array("1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"),
            							'showMonthAfterYear' => true,
            					        'onClose' => 'js:function( selectedDate ) {$( "#'.CHtml::activeId($model,'fromTime').'" ).datepicker( "option", "minDate", selectedDate );}'
            					),
            					 'htmlOptions'=>array(
            					         'readonly'=>'readonly',
            					         'style'=>'cursor:pointer'
            				     )
            				));
            		?>
        		</span>
        		<?php echo $form->dropDownList($model,'type',WithdrawSearchForm::$typeArray);?>
    			<?php echo $form->textField($model,'condition',array('class'=>'span2','placeholder'=>'查询条件'));?>
    			<label class="checkbox">
                    <?php echo $form->checkBox($model,'pending',array('uncheckValue'=>null));?>待处理
                 </label>
                 <label class="checkbox">
                    <?php echo $form->checkBox($model,'pass',array('uncheckValue'=>null));?>已完成
                 </label>
                 <label class="checkbox">
                    <?php echo $form->checkBox($model,'refuse',array('uncheckValue'=>null));?>未通过
                 </label>
        		<input type="submit" name="search" id="search" class="btn" value="查询">
    		</div>
		</fieldset>
</div>
<?php $this->endWidget();?>
<div id="pager">
    <?php  
        $this->widget('zii.widgets.CListView', array(
            'id'=>'withdrawList',
            'dataProvider'=>$model->searchWithdrawList(),
            'itemView'=>'_withdrawListView',
            'template'=>'{items}<div class="datatable-footer">{summary}{pager}</div>',
            'summaryText'=>'<div class="total">共 <strong>{count}</strong> 条记录</div>',
        	'emptyText' => '暂无记录',
            'itemsTagName'=>'table',
            'itemsCssClass'=>'tableStatic',
            'pager'=>array(
                'class'=> 'CLinkPager',
                'cssFile'=>FALSE,
                'header'=> '',
                'firstPageLabel' => '首页',
                'prevPageLabel' => '上一页',
                'nextPageLabel' => '下一页',
                'lastPageLabel' => '末页',
                'maxButtonCount'=> '10'
             )
        ));
    ?>   
</div>
<form action="" class="form-horizontal" style="display: none;" id="check_form">
<div class="control-group">
    <label for="pass" class="control-label">请选择</label>
    <div class="controls">
      <select id="pass" name="pass">
        <option value="yes">通过</option>
        <option value="no">不通过</option>
      </select>
    </div>
</div>
<div class="control-group for-yes">
    <label for="orderNo" class="control-label">打款流水号:</label>
    <div class="controls">
      <input type="text" id="orderNo" name="orderNo" class="input-xlarge">
      <p class="help-block"></p>
    </div>
</div>
<div class="control-group for-no" style="display: none;">
    <label for="comment" class="control-label">不通过原因:</label>
    <div class="controls">
      <textarea rows="3" id="comment" name="comment" class="input-xlarge"></textarea>
      <p class="help-block"></p>
    </div>
</div>
<div class="form-actions">
    <a class="btn mini" href="#" id="withdraw_auth_pass">确认</a>
</div>
</form>
<style>
.tableStatic{
    width:100%
}
.pager .next > a, .pager .next > span{
	float:none;
}
.pager .previous > a, .pager .previous > span{
	float:none;
}
</style>