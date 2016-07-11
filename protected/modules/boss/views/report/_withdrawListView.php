<?php if($index == 0){?>
<thead>
    <tr>
        <td>ID</td>
        <td>用户名</td>
        <td>提现金额</td>
        <td>提现手续费</td>
        <td>提现方式</td>
        <td>账号</td>
        <td>提现时间</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
</thead>
<?php }?>
<tr>
    <td><?php echo $data->id;?></td>
    <td><?php echo $data->applyUser->userName;?></td>
    <td><?php echo $data->money;?></td>
    <td><?php echo $data->fee;?></td>
    <td><?php echo WithdrawApply::$typeArray[$data->type];?></td>
    <td><?php echo $data->account;?></td>
    <td><?php echo $data->ctime?></td>
    <td><?php echo WithdrawApply::$statusArray[$data->status];?></td>
    <td><a class="btn mini" href="javascript:;" name="withdraw_view" data='<?php echo json_encode($data->attributes);?>'>查看</a></td>
</tr>