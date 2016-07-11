<table>
<tr><th><?php echo CHtml::checkBox('checkAll',false);?></th><th>已读</th><th>发件人</th><th>标题</th><th>时间</th></tr>
<?php if($notifys){?>
<?php foreach($notifys as $v){?>
<tr>
    <td><?php echo CHtml::checkBox('check[]',false);?></td>
    <td><?php echo $v->isRead ? '是': '否';?></td>
    <td>好贷网</td>
    <td><?php echo $v->title;?></td>
    <td><?php echo Yii::app()->format->date($v->ctime);?></td>
</tr>
<?php }}else{?>
<tr><td colspan="100%">暂无消息!</td></tr>
<?php }?>
</table>
<?php $cg = $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
    'selectableRows' => 0,
	'columns'=>array(
	    array(
	        'class' => 'CCheckBoxColumn',
	        'checkBoxHtmlOptions' => array('name'=>'del[]'),
	        'name' => 'id',
	        'selectableRows' => 2
	    ),
	    array(
	        'name' => 'isRead',
	        'value' => '$data->isRead ? \'是\' : \'否\''
	    ),
        array(
            'name' => 'fromId',
            'value' => '$data->type ? $data->fromUser->userName :$data->fromAdmin->userName'
        ),
		array(
			'name'=>'title',
			'value'=>'$data->title'
		),
		array(
			'name'=>'ctime',
			'type'=>'date'
		),
	    array(
            'class'=>'CButtonColumn',
	        'buttons' => array(
                'update' => array(
                    'visible' => 'false'
                )
	        ),
	        'footer' => $dataProvider->totalItemCount ? '<a href="#" id="batch_del">删除</a>' : ''
	            
	    )    
	),
)); ?>

<script>
$(function(){
    $(document).on('click','#batch_del',function(){
        var $checked = $('#<?php echo $cg->id?> :checkbox[name="del[]"]:checked');
        if($checked.length > 0){
            $.ajax({
                url : '<?php echo $this->createUrl('delete',array('ajax'=>$cg->id));?>',
                type : 'post',
                data : $checked.serialize(),
                success : function(){
                	$.fn.yiiGridView.update('<?php echo $cg->id;?>');
                },
                error : function(xhr){
                    console.log(xhr);
                    alert(xhr);
                }
            

            });
        }else{
            alert('请选择一条要删除的记录');
        }
    });
})
</script>
