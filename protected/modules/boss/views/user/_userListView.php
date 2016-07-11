<?php if($index == 0){?>
<thead>
    <tr>
        <td><input type="checkbox" id="checkall"/></td>
        <td>ID</td>
        <td>用户名</td>
        <td>注册时间</td>
        <td>账户余额</td>
        <td>欠款情况</td>
        <td>信用分值</td>
        <td>理财认证</td>
        <td>贷款认证</td>
        <td>操作</td>
    </tr>
</thead>
<tbody>
<?php }?>
<tr>
    <td><input type="checkbox" value="<?php echo $data->id?>" name="ids[]"/></td>
    <td><?php echo $data->id;?></td>
    <td><?php echo $data->userName;?></td>
    <td><?php echo $data->ctime;?></td>
    <td><?php echo $data->cashMoney;?></td>
    <td><?php echo $data->refundMoney;?></td>
    <td><?php echo $data->creditRating;?></td>
    <td><?php echo $data->getInvestStatus();?></td>
    <td><?php echo $data->getDebitStatus();?></td>
    <td><a class="btn mini" href="/user/view/id/<?php echo $data->id?>" target="_blank">查看</a></td>
</tr>