<?php 
Yii::app()->clientScript->registerCssFile('/css/idialog/default.css');
Yii::app()->clientScript->registerScriptFile('/js/artDialog.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/boss/user_auth.js', CClientScript::POS_END);
?>
<ul class="breadcrumb">
	<li>
		<span>资料审核</span>
		<div class="pos-l"></div>
		<div class="pos-r"></div>
	</li>
</ul>
<?php $form=$this->beginWidget('CActiveForm', array(
   'id'=>'userauthlist-search-form',
    'method' => 'get',
    'action' => array('userAuthList'),
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
            					'name'=>'UserSearchForm[fromTime]',
            					'language' => 'zh_cn',
            					'value'	=>$model->fromTime,
            					'options' => array(
            							'showAnim' => 'fold',
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
            					'name'=>'UserSearchForm[toTime]',
            					'language' => 'zh_cn',
            					'value'	=> $model->toTime,
            					'options' => array(
            							'showAnim' => 'fold',
            							'dateFormat' => 'yy-mm-dd',
            							'buttonImageOnly'=>true,
            							'readonly' => 'readonly',
            							'monthNames' => array("1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"),
            							'showMonthAfterYear' => true,
            					        'onClose' => 'js:function( selectedDate ) {$( "#UserSearchForm_fromTime" ).datepicker( "option", "minDate", selectedDate );}'
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
    			<label class="checkbox">
                    <?php echo $form->checkBox($model,'noPass',array('uncheckValue'=>null));?>审核不通过
                 </label>
        		<input type="submit" name="search" id="search" class="btn" value="查询">
    		</div>
		</fieldset>
	</div>
    <?php $this->endWidget();?>
<div id="pager">
    <?php  
        $this->widget('zii.widgets.CListView', array(
            'id'=>'userAuthList',
            'dataProvider'=>$model->searchUserAuthList(),
            'itemView'=>'_userAuthListView',
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