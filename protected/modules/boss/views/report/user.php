<?php 
Yii::app()->getClientScript()->registerCssFile("/css/boss/font-awesome.min.css");
?>
<ul class="breadcrumb">
	<li>
		<span>会员账户</span>
	</li>
</ul>
<?php $form=$this->beginWidget('CActiveForm', array(
   'id'=>'userlist-search-form',
    'method' => 'get',
    'action' => array('user'),
    'htmlOptions' => array('class'=>'well form-inline')
));
?>
<div class="search widget first">
	<div class="head"><h5 class="iSettings2">会员查询</h5></div>
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
            							'readonly' => 'readonly',
            							'monthNames' => array("1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"),
            							'showMonthAfterYear' => true,
            					        'onClose' => 'js:function( selectedDate ) {$( "#UserSearchForm_toTime" ).datepicker( "option", "minDate", selectedDate );}'
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
            							'readonly' => 'readonly',
            							'monthNames' => array("1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"),
            							'showMonthAfterYear' => true,
            					        'onClose' => 'js:function( selectedDate ) {$( "#UserSearchForm_fromTime" ).datepicker( "option", "maxDate", selectedDate );}'
            					),
            					 'htmlOptions'=>array(
            				         'readonly'=>'readonly',
            					     'style'=>'cursor:pointer'
            				     )
            				));
            		?>
        		</span>
        		<?php echo $form->dropDownList($model,'type',UserSearchForm::$typeArray);?>
    			<?php echo $form->textField($model,'condition',array('class'=>'span2','placeholder'=>'查询条件'));?>
    			<?php echo $form->dropDownList($model,'moneyType',array_merge(array(''=>'请选择'),UserSearchForm::$moneyTypeArray),array('class'=>'span2'));?>
        		<?php echo $form->dropDownList($model,'moneyTypeOp',array_merge(array(''=>'请选择'),UserSearchForm::$moneyTypeOpArray),array('class'=>'span2'));?>
        		<?php echo $form->textField($model,'moneyTypeValue',array('class'=>'span2','placeholder'=>'金额'));?>元
        		<input type="submit" name="search" id="search" class="btn" value="查询">
        		<a class="btn mini" href="<?php echo $exportUrl;?>" target="_blank">导出</a>
    		</div>
		</fieldset>
</div>
<?php $this->endWidget();?>
<div id="pager">
    <?php  
        $this->widget('zii.widgets.CListView', array(
            'id'=>'userList',
            'dataProvider'=>$model->searchUserReportList(),
            'itemView'=>'_userReportListView',
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