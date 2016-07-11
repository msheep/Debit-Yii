<?php if($index == 0){?>
<thead>
    <tr>
        <td><input type="checkbox" id="checkall"/></td>
        <td>ID</td>
        <td>用户名</td>
        <td>注册时间</td>
        <td>手机实名认证</td>
        <td>身份认证</td>
        <td>户籍认证</td>
        <td>学历认证</td>
        <td>视频认证</td>
        <td>操作</td>
    </tr>
</thead>
<?php }?>
<tr>
    <td><input type="checkbox" name="ids[]" value="<?php echo $data->id;?>"/></td>
    <td><?php echo $data->id;?></td>
    <td><?php echo $data->userName;?></td>
    <td><?php echo $data->ctime;?></td>
    <td>
    <?php if($data->getAuthStatus('mobile') === 1){?>
    <a class="btn mini" href="/user/auth/type/mobile/id/<?php echo $data->id;?>" name="auth_btn">审核</a>
    <?php }else{?>
    <?php echo $data->getAuthStatus('mobile');?>
    <?php }?>
    </td>
    <td>
    <?php if($data->getAuthStatus('identity') === 1){?>
    <a class="btn mini" href="/user/auth/type/identity/id/<?php echo $data->id;?>" name="auth_btn">审核</a>
    <?php }else{?>
    <?php echo $data->getAuthStatus('identity');?>
    <?php }?>
    </td>
    <td>
    <?php if($data->getAuthStatus('resident') === 1){?>
    <a class="btn mini" href="/user/auth/type/resident/id/<?php echo $data->id;?>" name="auth_btn">审核</a>
    <?php }else{?>
    <?php echo $data->getAuthStatus('resident');?>
    <?php }?>
    </td>
    <td>
    <?php if($data->getAuthStatus('education') === 1){?>
    <a class="btn mini" href="/user/auth/type/education/id/<?php echo $data->id;?>" name="auth_btn">审核</a>
    <?php }else{?>
    <?php echo $data->getAuthStatus('education');?>
    <?php }?>
    </td>
    <td>
    <?php if($data->getAuthStatus('video') === 1){?>
    <a class="btn mini" href="/user/auth/type/video/id/<?php echo $data->id;?>" name="auth_btn">审核</a>
    <?php }else{?>
    <?php echo $data->getAuthStatus('video');?>
    <?php }?>
    </td>
    <td>
    <a class="btn mini" href="/user/authView/id/<?php echo $data->id?>" target="_blank">查看</a>
    </td>
</tr>
