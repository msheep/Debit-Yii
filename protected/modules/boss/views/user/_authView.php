<div class="row-fluid">
    <div class="span6">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>真实姓名:</td>
                    <td><?php echo $user->identityAuthItem ? $user->identityAuthItem->realName : '未填写';?></td>
                    <td><?php echo $user->getAuthStatus('identity');?></td>
                </tr>
                <tr>
                    <td>身份证号:</td>
                    <td><?php echo $user->identityAuthItem ? $user->identityAuthItem->identityNo : '未填写';?></td>
                    <td><?php echo $user->getAuthStatus('identity');?></td>
                </tr>
                <tr>
                    <td>年&nbsp;&nbsp;龄:</td>
                    <td><?php echo $user->identityAuthItem ? $user->identityAuthItem->age : '未填写';?></td>
                    <td><?php echo $user->getAuthStatus('identity');?></td>
                </tr>
                <tr>
                    <td>性&nbsp;&nbsp;别:</td>
                    <td><?php echo $user->identityAuthItem ? $user->identityAuthItem->sex : '未填写';?></td>
                    <td><?php echo $user->getAuthStatus('identity');?></td>
                </tr>
                <tr>
                    <td>婚姻状况:</td>
                    <td><?php echo $user->residentAuthItem ? ResidentAuth::$maritalStatusArray[$user->residentAuthItem->maritalStatus] : '未填写';?></td>
                    <td><?php echo $user->getAuthStatus('resident');?></td>
                </tr>
                <tr>
                    <td>最高学历:</td>
                    <td><?php echo $user->educationAuthItem ? EducationAuth::$degreeArray[$user->educationAuthItem->degree] : '未填写';?></td>
                    <td><?php echo $user->getAuthStatus('education');?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="span6">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>手机号码:</td>
                    <td><?php echo $user->mobileAuthItem ? $user->mobileAuthItem->mobile : '未填写';?></td>
                    <td><?php echo $user->getAuthStatus('mobile');?></td>
                </tr>
                <tr>
                    <td>居住地:</td>
                    <td><?php echo $user->residentAuthItem ? $user->residentAuthItem->getFullAddress('live') : '未填写';?></td>
                    <td><?php echo $user->getAuthStatus('resident');?></td>
                </tr>
                <tr>
                    <td>户口所在地:</td>
                    <td><?php echo $user->residentAuthItem ? $user->residentAuthItem->getFullAddress('household') : '未填写';?></td>
                    <td><?php echo $user->getAuthStatus('resident');?></td>
                </tr>
                <tr>
                    <td>固定电话:</td>
                    <td><?php echo $user->residentAuthItem ? $user->residentAuthItem->telphone : '未填写';?></td>
                    <td><?php echo $user->getAuthStatus('resident');?></td>
                </tr>
                <tr>
                    <td>常用邮箱:</td>
                    <td><?php echo $user->residentAuthItem ? $user->residentAuthItem->email : '未填写';?></td>
                    <td><?php echo $user->getAuthStatus('resident');?></td>
                </tr>
                <tr>
                    <td>视频资料:</td>
                    <td>
                    <?php if($user->videoAuthItem){?>
                        <video width="400" height="300" src="<?php echo $this->createUrl($user->videoAuthItem->videoFile->path);?>" controls="controls">
                            <a target="_blank" href="<?php echo $this->createUrl($user->videoAuthItem->videoFile->path);?>"><?php echo $user->videoAuthItem->videoFile->fileName;?></a>
                        </video>
                    <?php }else{ echo '暂无提交';}?>
                    </td>
                    <td><?php echo $user->getAuthStatus('video');?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
