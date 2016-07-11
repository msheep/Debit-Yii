<?php 
    Yii::app()->clientScript->registerCssFile('/css/idialog/default.css');
    Yii::app()->clientScript->registerScriptFile('/js/artDialog.min.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile('/js/jquery.debit.js', CClientScript::POS_END);
?>

<ul>
    <li>
    	<a href="/accountCenter/debitList">投资中的列表</a>
    </li>
    <li>
    	<a href="/accountCenter/debitList/list/1">借款中的列表</a>
    </li>
    <li>
    	<a href="/accountCenter/debitList/list/2">近期完成的列表</a>
    </li>
    <li>
    	<a href="/accountCenter/debitList/list/3">账户变动记录查询</a>
    </li>
</ul>
<?php if(isset($_GET['list']) && trim($_GET['list']) == 2){?>
<form action="/accountCenter/debitList" method="get" id="search">
	<select id="type" name="type" onChange="submit()">
		<option value="0" <?php if(!isset($_GET['type']) || $_GET['type']==0){?>selected<?php }?>>不限</option>
		<option value="1" <?php if(isset($_GET['type']) && $_GET['type']==1){?>selected<?php }?>>借出</option>
		<option value="2" <?php if(isset($_GET['type']) && $_GET['type']==2){?>selected<?php }?>>借入</option>
	</select>
	<ul>
        <li><a onClick="setHistory(0);" href="javascript:void(0)">近三个月项目</a></li>
        <li><a onClick="setHistory(1);" href="javascript:void(0)">三个月之前项目</a></li>
	</ul>
	<input type="hidden" id="list" name="list" value="2">
	<input type="hidden" id="history" name="history" value="<?php echo isset($_GET['history']) ? trim($_GET['history']) : 0;?>">
</form>
<?php }?>
<div id="pager">    
    <?php  
        $this->widget('zii.widgets.CListView', array(
            'id'=>'debitList',
            'dataProvider'=>$dp,
            'itemView'=>'_debitList',
            'template'=>'{items}<div class="datatable-footer">{pager}{summary}</div>',
            'summaryText'=>'<div class="total">共 {count} 条记录</div>',
        	'emptyText' => '暂无记录',
            'itemsTagName'=>'table',
        	'itemsCssClass'=>'tableStatic',
        	'pagerCssClass'=>'paginate',
        	'ajaxUpdate'=>FALSE,
        	'cssFile'=>FALSE,
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
	width: 800px;
}
.tableStatic td, .tableStatic th{
	border: 1px solid black;
}
</style>
<script>
function setHistory(type){
	var history = $.trim($('#history').val());
	if(history != type){
		$('#history').val(type);
		$('#search').submit();
	}
}
function submit(){
	$('#search').submit();
}

</script>