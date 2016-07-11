<?php if($index == 0){?>
<thead>
    <tr>
        <td>ID</td>
        <td>用户名</td>
        <td>可用余额</td>
        <td>冻结金额</td>
        <td>待还金额</td>
        <td>待收金额</td>
        <td>注册时间</td>
        <td>最后变动时间</td>
    </tr>
</thead>
<tbody>
<?php }?>
<tr>
    <td><?php echo $data->id;?></td>
    <td><?php echo $data->userName;?></td>
    <td><?php echo $data->cashMoney;?></td>
    <td><?php echo $data->blockMoney;?></td>
    <td><?php echo $data->refundMoney;?></td>
    <td><?php echo $data->incomingMoney;?></td>
    <td><?php echo $data->ctime;?></td>
    <td><?php echo $data->utime;?></td>
</tr>
