<?php if($data instanceof MobileAuth){?>
<table class="table table-bordered">
<tr>
    <td style="text-align: right;">注册手机号:</td>
    <td><?php echo $data->mobile;?></td>
</tr>
<tr>
    <td style="text-align: right;">机主姓名:</td>
    <td><?php echo $data->realName;?></td>
</tr>
</table>
<a class="btn mini" href="/user/auth/type/mobile/id/<?php echo $data->userId;?>" name="auth_btn_pass">审核通过</a>
<a class="btn mini" href="/user/auth/type/mobile/id/<?php echo $data->userId;?>" name="auth_btn_nopass">审核不通过</a>
<?php }elseif($data instanceof ResidentAuth){?>
<table class="table table-bordered">
<tr>
    <td style="text-align: right;">婚姻状况:</td>
    <td><?php echo ResidentAuth::$maritalStatusArray[$data->maritalStatus];?></td>
</tr>
<tr>
    <td style="text-align: right;">居住地:</td>
    <td><?php echo $data->getFullAddress('live');?></td>
</tr>
<tr>
    <td style="text-align: right;">户口所在地:</td>
    <td><?php echo $data->getFullAddress('household');?></td>
</tr>
</table>
<a class="btn mini" href="/user/auth/type/resident/id/<?php echo $data->userId;?>" name="auth_btn_pass">审核通过</a>
<a class="btn mini" href="/user/auth/type/resident/id/<?php echo $data->userId;?>" name="auth_btn_nopass">审核不通过</a>
<?php }elseif($data instanceof IdentityAuth){?>
<table class="table table-bordered">
<tr>
    <td style="text-align: right;">真实姓名:</td>
    <td><?php echo $data->realName;?></td>
</tr>
<tr>
    <td style="text-align: right;">身份证号:</td>
    <td><?php echo $data->identityNo;?></td>
</tr>
<tr>
    <td style="text-align: right;">年龄:</td>
    <td><?php echo $data->age;?></td>
</tr>
</table>
<?php }elseif($data instanceof VideoAuth){?>
<table class="table table-bordered">
<tr>
    <td><video width="400" height="300" src="<?php echo $this->createUrl($data->videoFile->path);?>" controls="controls">
<a target="_blank" href="<?php echo $this->createUrl($data->videoFile->path);?>"><?php echo $data->videoFile->fileName;?></a>
</video></td>
</tr>
</table>
<a class="btn mini" href="/user/auth/type/video/id/<?php echo $data->userId;?>" name="auth_btn_pass">审核通过</a>
<a class="btn mini" href="/user/auth/type/video/id/<?php echo $data->userId;?>" name="auth_btn_nopass">审核不通过</a>
<?php }elseif($data instanceof EducationAuth){?>
<table class="table table-bordered">
<tr>
    <td style="text-align: right;">最高学历:</td>
    <td><?php echo EducationAuth::$degreeArray[$data->degree];?></td>
</tr>
<tr>
    <td style="text-align: right;">毕业学校:</td>
    <td><?php echo $data->school;?></td>
</tr>
<tr>
    <td style="text-align: right;">学位证书编号:</td>
    <td><?php echo $data->degreeNo;?></td>
</tr>
</table>
<a class="btn mini" href="/user/auth/type/education/id/<?php echo $data->userId;?>" name="auth_btn_pass">审核通过</a>
<a class="btn mini" href="/user/auth/type/education/id/<?php echo $data->userId;?>" name="auth_btn_nopass">审核不通过</a>
<?php }?>