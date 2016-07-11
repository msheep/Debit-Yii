<?php if($index == 0){?>
<thead>
    <tr>
        <td>提现流水号</td>
        <td>用户ID</td>
        <td>用户名</td>
        <td>提现金额</td>
        <td>提现手续费</td>
        <td>提现方式</td>
        <td>账号</td>
        <td>状态</td>
        <td>创建时间</td>
        <td>操作</td>
    </tr>
</thead>
<tbody>
<?php }?>
<tr>
    <td><?php echo $data->id;?></td>
    <td><?php echo $data->userId;?></td>
    <td><?php echo $data->applyUser->userName;?></td>
    <td><?php echo $data->money;?></td>
    <td><?php echo $data->fee;?></td>
    <td><?php echo $data->typeName;?></td>
    <td><?php echo $data->account;?></td>
    <td><?php echo WithdrawApply::$statusArray[$data->status];?></td>
    <td><?php echo $data->ctime?></td>
    <td>
    <?php if($data->status == 0){?>
    <a class="btn mini" href="/withdraw/checkApply/id/<?php echo $data->id?>" name="withdraw_auth">审核</a>
    <?php }else{?>
    <?php echo $data->utime;?>
    <?php }?>
    </td>
</tr>