<table class="table table-bordered">
<thead>
          <tr>
            <th>ID</th>
            <th>用户名</th>
            <th>注册时间</th>
            <th>上次登录时间</th>
            <th>登录次数</th>
            <th>信用分值</th>
            <th>理财账户</th>
            <th>贷款账户</th>
            <th>账户余额</th>
          </tr>
        </thead>
<tbody>
<tr>
            <td><?php echo $user->id; ?></td>
            <td><?php echo $user->userName; ?></td>
            <td><?php echo $user->ctime; ?></td>
            <td><?php echo $user->lastLoginTime; ?></td>
            <td></td>
            <td><?php echo $user->creditRating; ?></td>
            <td><?php echo $user->getInvestStatus(); ?></td>
            <td><?php echo $user->getDebitStatus(); ?></td>
            <td><?php echo $user->cashMoney; ?></td>
          </tr>
</tbody>
</table>
<div class="row-fluid">
    <div class="span8">
        <fieldset>
              <legend>账户验证信息：</legend>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>身份认证:</td>
                                <td><?php echo $user->identityAuthItem ? $user->identityAuthItem->realName.'&nbsp'.$user->identityAuthItem->identityNo : '暂未提交'; ?></td>
                            </tr>
                            <tr>
                                <td>手机实名认证:</td>
                                <td><?php echo $user->mobileAuthItem ? $user->mobileAuthItem->realName.'&nbsp'.$user->mobileAuthItem->mobile : '暂未提交'; ?></td>
                            </tr>
                            <tr>
                                <td>户籍认证:</td>
                                <td><?php echo $user->residentAuthItem ? '淡定' : '暂未提交'; ?></td>
                            </tr>
                            <tr>
                                <td>学籍认证:</td>
                                <td><?php echo $user->educationAuthItem ? '淡定' : '暂未提交'; ?></td>
                            </tr>
                            <tr>
                                <td>视频认证:</td>
                                <td>
                                <?php if($user->videoAuthItem){?>
                                <video width="400" height="300" src="<?php echo $this->createUrl($user->videoAuthItem->videoFile->path);?>" controls="controls">
                                    <a target="_blank" href="<?php echo $this->createUrl($user->videoAuthItem->videoFile->path);?>"><?php echo $user->videoAuthItem->videoFile->fileName;?></a>
                                </video>
                                <?php }else{ echo '暂无提交';}?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
        </fieldset>
    </div>
    <div class="span4">
        <fieldset>
          <legend>抵押资产信息：</legend>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>房产:</td>
                            <td><?php echo $user->debitProductPropertys;?></td>
                        </tr>
                        <tr>
                            <td>域名:</td>
                            <td><?php echo $user->debitProductDomains?></td>
                        </tr>
                        <tr>
                            <td>车辆:</td>
                            <td><?php echo $user->debitProductCars;?></td>
                        </tr>
                    </tbody>
                </table>
        </fieldset>
    </div>
</div>


