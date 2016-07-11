<?php  
    $this->widget('zii.widgets.CListView', array(
        'id'=>'debitList',
        'dataProvider'=>$dataProvider,
        'itemView'=>$itemView,
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