<?php 
//    Yii::app()->clientScript->registerCssFile('/js/pickbox/css/picbox.css');
//    Yii::app()->clientScript->registerScriptFile('/js/pickbox/js/picbox.js', CClientScript::POS_END);
//    Yii::app()->clientScript->registerScriptFile('/js/zxxFile.js', CClientScript::POS_END);
//    Yii::app()->clientScript->registerScriptFile('/js/uploadFile.js', CClientScript::POS_END);

    Yii::app()->clientScript->registerCssFile('/js/fancybox/jquery.fancybox-1.3.4.css');
    Yii::app()->clientScript->registerCssFile('/js/uploadify/uploadify.css');
    Yii::app()->clientScript->registerCssFile('/css/boss/debit.css');
    Yii::app()->clientScript->registerCssFile('/css/idialog/default.css');
    Yii::app()->clientScript->registerScriptFile('/js/artDialog.min.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile('/js/jquery.debit.js', CClientScript::POS_END);
    
    Yii::app()->clientScript->registerScriptFile('/js/fancybox/jquery.fancybox-1.3.4.pack.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile('/js/uploadify/jquery.uploadify-3.1.min.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile('/js/jquery.form.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile('/js/boss/jquery.debit.js', CClientScript::POS_END);
?>
<ul class="breadcrumb">
	<li><span>借贷详情表</span></li>
</ul>
<p>
	<?php if($data->status == 2){?>
    	<button class="btn btn-danger" type="button" onClick="stopDebit('<?php echo $data->id;?>')">终止借贷</button>
    	<?php if($data->realDebitMoney <= $data->debitProgress){?>
    		<button class="btn btn-danger" type="button" onClick="loanDebitMoney('<?php echo $data->id;?>')">满额放款</button>
        <?php }else if(($data->realMinDebitMoney <= $data->debitProgress) && (strtotime(Debit::getDeadline($data->vtime, $data->invitDeadline + 1)) < time())){?>
       		<button class="btn btn-danger" type="button" onClick="loanDebitMoney('<?php echo $data->id;?>')">最低限放款</button>
        <?php }?>
    <?php }?> 
</p>
<div class="widget">
    <table class="detail tableStatic" width="100%" cellspacing="0" cellpadding="0">
    	<thead>
            <tr>
                <td colspan="11" class="title">借贷人信息</td>
            </tr>
        </thead>
        <tbody>
            <tr class="table-title">
            	<td>用户id</td>
                <td>姓名</td>
                <td>联系电话</td>
                <td>邮箱</td>
                <td>会员等级</td>
                <td>信用等级</td>
                <td>现金账户</td>
                <td>冻结账户</td>
                <td>待还账户</td>
                <td>待收账户</td>
                <td>认证情况</td>
            </tr>
            <tr>
        	    <td><?php echo $data->borrowerId;?></td>
        	    <td>
        	        <?php 
        	            $userInfo = User::model()->findByPk($data->borrowerId);
        	            echo $userInfo->userName;
        	        ?>
    	        </td>
    	        <td><?php echo $userInfo->mobile;?></td>
    	        <td><?php echo $userInfo->email;?></td>
    	        <td><?php echo $userInfo->rank;?></td>
        		<td><?php echo $userInfo->creditRating;?></td>
        		<td><?php echo $userInfo->cashMoney;?></td>
        		<td><?php echo $userInfo->blockMoney;?></td>
        		<td><?php echo $userInfo->refundMoney;?></td>
        		<td><?php echo $userInfo->incomingMoney;?></td>
        		<td><a href="/" class="btn btn-mini">查看</a></td>
        	</tr>
        </tbody>
    </table>
</div>

<div class="widget">
    <table class="detail tableStatic" width="100%" cellspacing="0" cellpadding="0">
    	<thead>
            <tr>
                <td colspan="11" class="title">借贷基本信息</td>
            </tr>
        </thead>
        <tbody>
        	<tr class="table-title">
                <td>项目编号</td>
                <td>申请时间</td>
                <td>用户名</td>
                <td>抵押产品</td>
                <td>贷款金额</td>
                <td>贷款下限</td>
                <td>已筹金额（实际借贷金额）</td>
                <td>利率</td>
                <td>借款期限</td>
                <td>招标期限</td>
                <td>状态</td>
            </tr>
            <tr>
        		<td><?php echo str_pad($data->id,6,'0',STR_PAD_LEFT);?></td>
        		<td><?php echo $data->ctime;?></td>
        	    <td><?php echo $this->getUserName($data->borrowerId);?></td>
        	    <td><?php echo Debit::$catArr[$data->cat];?></td>
        	    <?php if($data->status == 0){?>
            		<td><code>¥<?php echo $data->debitMoney;?></code></td>
            		<td><code>¥<?php echo $data->debitMinMoney;?></code></td>
        		<?php }else{?>
        			<td><code>¥<?php echo $data->realDebitMoney;?></code>（<code style="text-decoration: line-through;">¥<?php echo $data->debitMoney;?></code>）</td>
        			<td><code>¥<?php echo $data->realMinDebitMoney;?></code>（<code style="text-decoration: line-through;">¥<?php echo $data->debitMinMoney;?></code>）</td>
        		<?php }?>
        		<td>
        			<?php if($data->status == 2){?>
        				<code>¥<?php echo $data->debitProgress;?>/缺¥<?php echo $data->realDebitMoney - $data->debitProgress?></code>
        			<?php }else if($data->status == 3 || $data->status == 4){?>
        				<code>¥<?php echo $data->debitProgress;?>&nbsp;达标</code>
        			<?php }else{?>
        				<code>¥<?php echo $data->debitProgress;?></code>
        			<?php }?>
        		    <?php if($data->debitProgress > 0 && $data->status == 2){?>
        		    	<progress value="<?php echo $data->debitProgress;?>" max="<?php echo $data->realDebitMoney;?>"></progress>
    		    	<?php }?>
    		    </td>
        		<td><?php echo floatval($data->debitRate);?>%</td>
        		<td><?php echo $this->monthArr[$data->debitDeadline-1];?>个月</td>
        		<td><?php echo $data->invitDeadline;?>天</td>
        		<td id="status_<?php echo $data->id;?>"><span class="label label-important"><?php echo Debit::$status[$data->status];?></span></td>
        	</tr>
            <tr class="table-title">
            	<td>手续费</td>
            	<td colspan="3">标题</td>
                <td colspan="7">贷款用途</td>
            </tr>
            <tr>
            	<td><?php echo $data->fee;?></td>
        	    <td colspan="3"><?php echo $data->title;?></td>
        	    <td colspan="7"><?php echo $data->debitPurpose;?></td>
        	</tr>
        	<tr class="table-title">
        		<td>审核客服</td>
                <td colspan="2">客服审核时间</td>
                <td colspan="2">用户审核时间</td>
                <td colspan="6">备注信息</td>
        	</tr>
        	<tr>
        		<td id="kefu_<?php echo $data->id;?>"><?php echo $data->operatorId == 0?'—':$this->getAdminUserName($data->operatorId);?></td>
        		<td colspan="2"><?php echo $data->kvtime=='0000-00-00 00:00:00' ? '—':$data->kvtime;?></td>
        		<td colspan="2"><?php echo $data->vtime=='0000-00-00 00:00:00' ? '—':$data->vtime;?></td>
        		<td colspan="8">
        			<?php if($data->status != 0){
        				if($data->kefuInfo != ''){
        					echo "审核备注：".$data->kefuInfo.'；';
        				}
        			    if($data->userRejectInfo != '' && $data->status == -3){
        					echo "用户拒绝原因：".$data->userRejectInfo.'（时间：'.$data->utime.'）；';
        				}
        			    if($data->kefuStopInfo != '' && $data->status == -4){
        					echo "客服中止借贷原因：".$data->kefuStopInfo.'（客服:'.$this->getAdminUserName($data->stopId).'；时间：'.$data->stime.'）；';
        				}
        	        }else{?>
        	      		  暂无
        	        <?php }?>
        		</td>
        	</tr>
        </tbody>
    </table>
</div>

<div class="widget">
    <table class="detail tableStatic" width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td colspan="5" class="title"><?php echo Debit::$catArr[$data->cat];?>详情</td>
            </tr>
        </thead>
        <?php 
            if($data->productId == 0){?>
            <tbody>
                <tr>
            		<td colspan="5" >暂未提交！</td>
            	</tr>
            </tbody>
        <?php }else{?>
        	<tbody>
                <?php if($data->cat == 1){
                	$domainInfo = $data->domainInfo;?>
            	<tr>
            		<td class="text-title"><span>域名</span></td>
    				<td class="text">
        				<span class="label label-warning"><?php echo $domainInfo->domain;?></span>
        				（<span>后缀名：<?php echo DebitDomain::$suffix[$domainInfo->suffix];?></span>）
    				</td>
				</tr>
				<tr>
            		<td class="text-title"><span>拥有者</span></td>
    				<td class="text">
    					<span><?php echo $domainInfo->owner;?></span>
    				</td>
				</tr>
				<tr>
            		<td class="text-title"><span>注册者</span></td>
    				<td class="text">
    					<span><?php echo $domainInfo->register;?></span>
    				</td>
				</tr>
				<tr>
            		<td class="text-title"><span>注册时间</span></td>
    				<td class="text">
    					<span><?php echo $domainInfo->registrationDate;?></span>
    				</td>
				</tr>
				<tr>
            		<td class="text-title"><span>最近一次续费时间</span></td>
    				<td class="text">
    					<span><?php echo $domainInfo->registrationDate;?></span>
    				</td>
				</tr>
				<tr>
            		<td class="text-title"><span>过期时间</span></td>
    				<td class="text">
    					<span><?php echo $domainInfo->registrationDate;?></span>
    				</td>
            	</tr>
            	<?php }else if($data->cat == 2){
            	    $propertyInfo = $data->propertyInfo;?>  
    	    	<tr>
        	    	<td rowspan="8">
        	    		<strong>基本信息</strong><br/>
        	    		（<?php echo DebitProperty::$propertyType[$propertyInfo->type];?>）
    	    		</td>
    	    		<td class="text-title"><span>所在地</span></td>
        	    	<td class="text">
    					<span class="part"><?php echo Province::getProvinceName($propertyInfo->provinceId).Province::getCityName($propertyInfo->cityId).Province::getAreaName($propertyInfo->areaId).$propertyInfo->address;?></span>
    				</td>
        	    </tr>
            	<tr>
            		<td class="text-title"><span>土地证号</span></td>
    				<td class="text">
    					<span class="part"><font class="label label-warning"><?php echo $propertyInfo->landCertificateId;?></font></span>
    					<span class="part">所有人：<?php echo $propertyInfo->landOwner;?></span>
					    <?php 
					        if($propertyInfo->landBothOwner != ''){
					            $landBothOwner = json_decode($propertyInfo->landBothOwner,true);
					            $owners = array();
					            if(!empty($landBothOwner)){
    					            foreach($landBothOwner as $owner){
    					                $owners[] = $owner[1].'（'.DebitProperty::$relation[$owner[0]].'关系）';
    					            }
    					            echo '<span class="part">共有人：'.implode('、',$owners).'</span>';
					            }else{
					                echo '<span class="part">共有人：无';
					            }
					        }
			             ?>
				        </span>
    				</td>
				</tr>
				<tr>
					<td class="text-title"><span>房产证号</span></td>
    				<td class="text">
    					<span class="part"><font class="label label-warning"><?php echo $propertyInfo->houseCertificateId;?></font></span>
    					<span class="part">所有人：<?php echo $propertyInfo->houseOwner;?></span>
					    <?php 
					        if($propertyInfo->houseBothOwner != ''){
					            $houseBothOwner = json_decode($propertyInfo->houseBothOwner,true);
					            $owners = array();
					            if(!empty($houseBothOwner)){
    					            foreach($houseBothOwner as $owner){
    					                $owners[] = $owner[1].'（'.DebitProperty::$relation[$owner[0]].'关系）';
    					            }
    					            echo '<span class="part">共有人：'.implode('、',$owners).'</span>';
					            }else{
					                echo '<span class="part">共有人：无';
					            }
					        }
			             ?>
				        </span>
    				</td>
				</tr>
            	<tr>
            		<td class="text-title"><span>购置信息</span></td>
            		<td class="text">
    					<span class="part">于<?php echo date('Y年m月',strtotime($propertyInfo->buyDate));?>购买</span>
    					<span class="part">建筑面积<?php echo $propertyInfo->totalArea;?>m²</span>
    					<span class="part">使用面积<?php echo $propertyInfo->useArea;?>m²</span>
    					<span class="part">¥<?php echo $propertyInfo->perCost;?>元/m²</span>
    					<span class="part">共¥<?php echo $propertyInfo->totalArea * $propertyInfo->perCost;?>元</span>
    				</td>
				</tr>
				<tr>
					<td class="text-title"><span>产权年限</span></td>
					<td class="text">
    					<span class="part"><?php echo $propertyInfo->ageLimit;?>年</span>
    				</td>
				</tr>
				<tr>
					<td class="text-title"><span>所在楼层/总楼层</span></td>
					<td class="text">
    					<span class="part"><?php echo $propertyInfo->floor.'楼 / 共'.$propertyInfo->allFloor;?>层</span>
    				</td>
				</tr>
            	<tr>
            		<td class="text-title"><span>物业费（元/m²）</span></td>
    				<td class="text">
    					<span class="part">¥<?php echo $propertyInfo->cleanFee;?></span>
    				</td>
				</tr>
				<tr>
					<td class="text-title"><span>描述信息</span></td>
        	    	<td class="text">
        	    		<span class="part"><?php echo $propertyInfo->transport ? '交通：'.$propertyInfo->transport : '';?></span>
        	    		<span class="part"><?php echo $propertyInfo->aroundSupport ? '周边设施：'.$propertyInfo->aroundSupport : '';?></span>
        	    		<span class="part"><?php echo $propertyInfo->buildingDesc ? '建筑描述：'.$propertyInfo->buildingDesc : '';?></span>
        	    		<span class="part"><?php echo $propertyInfo->moreInfo ? '补充说明：'.$propertyInfo->moreInfo : '';?></span>
        	    	</td>
        	    </tr>
        	    <?php if($propertyInfo->type != 2){?>
            	    <tr>
            	    	<td rowspan="7">
            	    		<strong><?php echo DebitProperty::$propertyType[$propertyInfo->type];?>具体信息</strong><br/>
            	    		（<?php if($propertyInfo->type != 3){
            	    		    $house = $propertyInfo->house; 
            	    		    if($propertyInfo->type == 1){
            	    		        echo DebitProperty::$houseProperty[$house->houseType];
            	    		    }
            	    		}?>）
            	    	</td>
            	    	<td class="text-title"><span>产权性质</span></td>
            	    	<td class="text">
        					<span><?php echo $propertyInfo->type == 1 ? DebitProperty::$propertyCharacter[$house->housePropertyType] : '商住两用';?></span>
            	    	</td>
            	    </tr>
            	    <tr>
            	    	<td class="text-title"><span>房屋信息</span></td>
            	    	<td class="text">
        					<span class="part">房型：<?php echo DebitProperty::$buildingType[$house->buildingType];?></span>
        					<span class="part"><?php echo $house->roomNum;?>室<?php echo $house->hallNum;?>厅<?php echo $house->toiletNum;?>卫</span>
        					<span class="part"><?php echo DebitProperty::$towards[$house->houseToward];?>朝向</span>
        					<span class="part"><?php echo DebitProperty::$ifhave[$house->isLift];?>电梯</span>
        					<span class="part"><?php echo DebitProperty::$ifhave[$house->carport];?>车位</span>
        					<span class="part">目前<?php echo DebitProperty::$houseStatus[$house->status];?></span>
            	    	</td>
            	    </tr>
            	    <tr>
            	    	<td class="text-title"><span>额外面积</span></td>
            	    	<td class="text">
        					<span class="part">赠送面积：<?php echo $house->houseGiveArea;?>m²</span>
        					<span class="part">地下室面积：<?php echo $house->houseBasementArea;?>m²</span>
            	    	</td>
            	    </tr>
            	    <tr>
            	    	<td class="text-title"><span>装修情况</span></td>
            	    	<td class="text">
        					<span class="part"><?php echo DebitProperty::$fitment[$house->fitment];?></span>
    						<?php if($house->fitment != 5){?>
    							<span class="part">装修完成时间：<?php echo DebitProperty::$fitmentTime[$house->fitmentTime];?></span>
    							<span class="part">装修金额：<?php echo $house->fitmentMoney;?>元</span>
        	                <?php }?>
            	    	</td>
            	    </tr>
        	    	<tr>
        	    		<td class="text-title"><span>购置信息</span></td>
            	    	<td class="text">
        					<span class="part"><?php echo $house->isSecondHand == 1 ? '非二手房' : '二手房';?></span>
        					<span class="part"><?php echo $house->isFirstBuy == 1 ? '买房家庭首次购房' : '非买房家庭首次购房';?></span>
        					<span class="part"><?php echo $house->isFiveYear == 1 ? '购置满5年' : '购置不满5年';?></span>
            	    	</td>
            	    </tr>
            	    <tr>
            	    	<td class="text-title"><span>其他信息</span></td>
            	    	<td class="text">
        					<span class="part"><?php echo DebitProperty::$houseLight[$house->houseLight];?></span>
        					<span class="part"><?php echo DebitProperty::$houseLandscape[$house->houseLandscape];?></span>
        					<span class="part"><?php echo DebitProperty::$houseLocation[$house->houseLocation];?></span>
        					<span class="part"><?php echo DebitProperty::$houseNoise[$house->houseNoise];?></span>
        					<span class="part">厌恶设施<?php echo DebitProperty::$hateFacility[$house->hateFacility];?></span>
        					<?php 
        					    if($house->hateFacility != 3){
        					        $hateFacility = explode(',',$house->hateFactor);
        					        $hateFacilityName = array();
        					        foreach($hateFacility as $val){
        					            $hateFacilityName[] = DebitProperty::$hateFactor[$val];
        					        }
        					    }
        					    echo '（'.implode('、',$hateFacilityName).'）';
        					?>
        					</span>
        					<?php 
        					    if($house->support != ''){
        					        $houseSupport = explode(',',$house->support);
        					        $houseSupportName = array();
        					        foreach($houseSupport as $val){
        					            $houseSupportName[] = DebitProperty::$houseSupport[$val];
        					        }
        					    }
        					    echo '<span class="part">小区配套：'.implode('、',$houseSupportName).'</span>';
        					?>
            	    	</td>
            	    </tr>
        	    <?php }else{
        	        $business = $propertyInfo->business;?>
        	        <tr>
            	    	<td rowspan="6">
            	    		<strong><?php echo DebitProperty::$propertyType[$propertyInfo->type];?>具体信息</strong><br/>
            	    		（<?php echo DebitProperty::$businessType[$business->businessType];?>）
            	    	</td>
            	    	<td class="text-title"><span><?php echo DebitProperty::$businessType[$business->businessType];?>类型</span></td>
            	    	<td class="text">
            	    		<?php if($business->businessType == 1){?>
        						<span class="part"><?php echo DebitProperty::$shopType[$business->shopType];?></span>
        						<span class="part"><?php echo DebitProperty::$shopFaceType[$business->shopFaceType];?></span>
        					<?php }else if($business->businessType == 2){?>
        						<span class="part"><?php echo DebitProperty::$officeType[$business->officeType];?></span>
        						<span class="part"><?php echo DebitProperty::$rank[$business->officeRank];?></span>
        					<?php }else if($business->businessType == 3){?>
        						<span class="part"><?php echo DebitProperty::$plantType[$business->plantType];?></span>
        					<?php }?>
            	    	</td>
            	    </tr>
            	    <tr>
            	    	<td class="text-title"><span>楼盘名称</span></td>
            	    	<td class="text">
        					<span class="part"><?php echo $business->housesName;?></span>
            	    	</td>
            	    </tr>
            	    <tr>
            	    	<td class="text-title"><span>状态名称</span></td>
            	    	<td class="text">
        					<span class="part"><?php echo DebitProperty::$shopStatus[$business->status];?></span>
            	    	</td>
            	    </tr>
            	    <?php 
                	    if($business->businessType == 1 && $business->manageType != ''){
                	        $manageType = explode(',',$business->manageType);
    				        $manageTypeName = array();
    				        foreach($manageType as $val){
    				            $manageTypeName[] = DebitProperty::$manageType[$val];
    				        }?>
    				        <tr>
    				        	<td><span>可经营类型</span></td>
                    	    	<td class="text">
                					<span class="part"><?php echo implode('、',$manageTypeName);?></span>
                    	    	</td>
                    	    </tr>
                	<?php }?>
            	    <?php 
                	    if($business->support != ''){
                	        $bussinesSupportType = explode(',',$business->support);
    				        $bussinesSupportName = array();
    				        foreach($bussinesSupportType as $val){
    				            $bussinesSupportName[] = DebitProperty::$bussinesSupport[$val];
    				        }?>
    				        <tr>
    				        	<td class="text-title"><span>配套设施</span></td>
                    	    	<td class="text">
                					<span class="part"><?php echo implode('、',$bussinesSupportName);?></span>
                    	    	</td>
                    	    </tr>
                	<?php }?>
            	   
        	    <?php }?>
        	    	<tr>
            	   		<td colspan="2">
            	   			<?php if(isset($propertyInfo->image)){?>
                				<?php foreach($propertyInfo->image as $image){?>
                				<div class="span3 thumbnail">
                					<a href="<?php echo $image->path;?>" rel="lightbox-group1" title="房产照片">
                        				<img height="100px" src="<?php echo Yii::app()->easyImage->thumbSrcOf($image->path);?>">
                        				<legend>房产照片</legend>
                        			</a>
                    			</div>
                				<?php }?>
                			<?php }else{?>
                			无
                			<?php }?>
            	   		</td>
            	   </tr>
                <?php }else if($data->cat == 3){
                    $carInfo = DebitCar::model()->findByPk($data->productId);
                ?>
            	<tr>
            		<td class="text-title"><span>车型</span></td>
            		<td class="text">
        			 	<span class="label label-warning part"><?php echo $carInfo->addCar == ''? Car::getCarInfo($carInfo->brandId, $carInfo->seriesId, $carInfo->carId):$carInfo->addCar;?></span>
        			 	<span class="part"><?php echo $carInfo->color;?></span>
        			 	<span class="part"><?php echo DebitCar::$output[$carInfo->output];?></span>
        			 	<span class="part"><?php echo DebitCar::$gearBox[$carInfo->gearBox];?></span>
        			 	<span class="part"><?php echo DebitCar::$carType[str_pad($carInfo->carType,2,'0',STR_PAD_LEFT)];?></span>
        			</td>
    			</tr>
    			<tr>
        			<td class="text-title"><span>出厂时间</span></td>
        			<td class="text">
        				<span><?php echo $carInfo->productDate;?></span>
        			</td>
    			</tr>
    			<tr>
        			<td class="text-title"><span>发动机号</span></td>
        			<td class="text">
        				<span><?php echo $carInfo->engineNumber;?></span>
        			</td>
        		</tr>
    			<tr>
        			<td class="text-title"><span>车辆所在地</span></td>
        			<td class="text">
        				<span><?php echo Province::getProvinceName($carInfo->provinceId).'&nbsp;&nbsp;'.Province::getCityName($carInfo->cityId);?></span>
        			</td>
            	</tr>
    			<tr>
        			<td class="text-title"><span>车牌信息</span></td>
        			<td class="text">
        				<span><?php echo DebitCar::$carBelong[$carInfo->platesBelong].$carInfo->plates;?>（<?php echo $carInfo->carOwner;?>）</span>
        			</td>
    			</tr>
    			<tr>
        			<td class="text-title"><span>行驶里程数</span></td>
        			<td class="text">
        				<span><?php echo $carInfo->mileage;?>公里 </span>
    			</tr>
    			<tr>
        			<td class="text-title"><span>事故情况</span></td>
        			<td class="text">
        				<span><?php echo DebitCar::$accident[$carInfo->accident];?></span>
    			</tr>
    			<tr>
        			<td class="text-title"><span>首次上牌时间</span></td>
        			<td class="text">
        				<span><?php echo $carInfo->registDate == '-1' ? '90年以及前' : $carInfo->registDate;?></span>
        			</td>
    			</tr>
    			<tr>
        			<td class="text-title"><span>购置时间与价格</span></td>
        			<td class="text">
        				<span class="part">购买于<?php echo $carInfo->buyDate == '-1' ? '90年以及前' : $carInfo->buyDate;?></span>
        				<span>成交价<?php echo $carInfo->cost;?>万元 （目前裸车价：<?php echo Car::model()->findByPk($carInfo->carId)->price == '0.00' ? '暂无保教' : Car::model()->findByPk($carInfo->carId)->price.'万元';?>）</span>
    				</td>
    			</tr>
    			<tr>
        			<td class="text-title"><span>交强险截止日期</span></td>
        			<td class="text">
        				<span><?php echo $carInfo->insuranceDate == '-1' ? '90年以及前' : $carInfo->insuranceDate;?></span>
        			</td>
            	</tr>
    			<tr>
        			<td class="text-title"><span>下次验车日期</span></td>
    				<td class="text">
        				<span><?php echo $carInfo->auditDate == '-1' ? '90年以及前' : $carInfo->auditDate;?></span>
    				</td>
    			</tr>
    			<tr>
        			<td class="text-title"><span>车船使用税有效期</span></td>
        			<td class="text">
        				<span><?php echo $carInfo->taxDate == '-1' ? '已过期' : $carInfo->taxDate;?></span>
				</tr>
    			<tr>
        			<td class="text-title"><span>证件信息</span></td>
        			<td class="text">
        				<span>登记证<?php echo DebitCar::$status[$carInfo->registration];?>；行驶证<?php echo DebitCar::$status[$carInfo->drivingPermit];?>；购车发票<?php echo DebitCar::$status[$carInfo->receipt];?></span>
        			</td>
            	</tr>
            	<tr>
            		<td class="text-title"><span>图片信息</span></td>
            		<td>
            		    <?php if(isset($carInfo->registrImage->path)){?>
            		    <div class="span3 thumbnail">
            		    	<a href="<?php echo $carInfo->registrImage->path;?>" rel="lightbox-group1" title="登记证">
                    			<img height="100px" src="<?php echo Yii::app()->easyImage->thumbSrcOf($carInfo->registrImage->path);?>">
                    			<legend>登记证</legend>
                			</a>
            			</div>
            			<?php }?>
            			<?php if(isset($carInfo->driveImage->path)){?>
            			<div class="span3 thumbnail">
            				<a href="<?php echo $carInfo->driveImage->path;?>" rel="lightbox-group1" title="行驶证">
                				<img height="100px" src="<?php echo $carInfo->driveImage->path;?>">
                				<legend>行驶证</legend>
                			</a>
            			</div>
            			<?php }?>
            			<?php if(isset($carInfo->receiptImage->path)){?>
            			<div class="span3 thumbnail">
            				<a href="<?php echo $carInfo->receiptImage->path;?>" rel="lightbox-group1" title="购车发票">
                				<img height="100px" src="<?php echo $carInfo->receiptImage->path;?>">
                				<legend>购车发票</legend>
                			</a>
            			</div>
            			<?php }?>
            			<?php if(isset($carInfo->carImage)){?>
            				<?php foreach($carInfo->carImage as $carImage){?>
            				<div class="span3 thumbnail">
            					<a href="<?php echo $carImage->path;?>" rel="lightbox-group1" title="车照片">
                    				<img height="100px" src="<?php echo Yii::app()->easyImage->thumbSrcOf($carImage->path);?>">
                    				<legend>车照片</legend>
                    			</a>
                			</div>
            				<?php }?>
            			<?php }?>
            		</td>
            	</tr>
            
            <?php }?>
            </tbody>
        <?php }?>
    </table>
</div>

<?php if($data->debitFinancingRecord){?>
<div class="widget">
    <table class="detail tableStatic" width="100%" cellspacing="0" cellpadding="0">
    	<thead>
            <tr>
                <td colspan="11" class="title">投资记录</td>
            </tr>
        </thead>
        <tbody>
        	<tr class="table-title">
            	<td>姓名</td>
                <td>金额</td>
                <td>占投资总额比</td>
                <td width="20%">时间</td>
            </tr>
            <?php foreach($data->debitFinancingRecord as $record){?>
            	<tr>
            		<td><a href=''><?php echo User::model()->findByPk($record->lenderId)->userName;?></a></td>
            		<td>¥<?php echo $record->debitMoney?></td>
            		<td><?php echo sprintf("%.4f", $record->debitMoney / $data->debitProgress) * 100?>%</td>
            		<td><?php echo $record->ctime?></td>
            	</tr>
            <?php }?>
        </tbody>
    </table>
</div>
<?php }?>
<?php if($data->debitRepayRecord){?>
<div class="widget">
    <table class="detail tableStatic" width="100%" cellspacing="0" cellpadding="0">
    	<thead>
            <tr>
                <td colspan="11" class="title">还款记录</td>
            </tr>
        </thead>
        <tbody>
        	<tr class="table-title">
            	<td>还款日期</td>
                <td>金额</td>
                <td>状态</td>
                <td>实际还款时间</td>
                <td>具体收款人</td>
                <td>四舍五入差额</td>
            </tr>
            <?php foreach($data->debitRepayRecord as $repayRecord){?>
            	<tr>
            		<td><?php echo $repayRecord->repayDate;?></td>
            		<td><code>¥<?php $totalRepay = Debit::getMonthRepay($data->realDebitMoney, $data->debitDeadline, $data->debitRate);echo $totalRepay;?></code></td>
            		<td><span class="label label-important"><?php echo DebitRepayRecord::$status[$repayRecord->status];?></span></td>
            		<td>
            		    <?php if($repayRecord->status != 0 && $repayRecord->status != 4){
            		            echo $financingRecord->utime;
            		        }else{
            		            echo "—";
            		        }
                        ?>
            		</td>
            		<td>
            			<?php $realTotalRepay = 0;
            			    foreach($data->debitFinancingRecord as $financingRecord){
            			        $monthRepay = Debit::getMonthRepay($data->realDebitMoney, $data->debitDeadline, $data->debitRate, $financingRecord->debitMoney);
            			        $realTotalRepay += $monthRepay;
        			    ?>
            			<?php echo User::model()->findByPk($financingRecord->lenderId)->userName;?>：¥<?php echo $monthRepay;?><br/>
            			<?php }?>
            		</td>
            		<td>
            		    <?php 
            		        if($totalRepay > $realTotalRepay){
            		            $balance = $totalRepay - $realTotalRepay;
            		            echo "+".sprintf("%.2f", $balance);
            		        }else if($totalRepay == $realTotalRepay){
            		            echo 0;
            		        }else{
            		            $balance = $realTotalRepay - $totalRepay;
            		            echo "-".sprintf("%.2f", $balance);
            		        }
        		        ?>
            		
            		</td>
            	</tr>
            <?php }?>
        </tbody>
    </table>
</div>
<?php }?>

<?php if($data->status == 0 && $data->productId != 0){?>
<div class="widget">
	<form id="kefu-form" method="post" action="/debit/examineDebit" enctype="multipart/form-data">
		<input type="hidden" id="debitId" value="<?php echo $_GET['debitId'];?>">
        <table class="detail tableStatic" width="100%" cellspacing="0" cellpadding="0">
        	<thead>
                <tr>
                    <td colspan="2" class="title">审核结果</td>
                </tr>
            </thead>
            <tbody class="pass">
            	<tr>
    				<td class="pass-left">抵押品估值：</td>
    				<td class="pass-right"><input type="text" id="productPrice" name="DebitProperty[productPrice]"> 元</td>
				</tr>
				<tr>
    				<td class="pass-left">核准贷款金额：</td>
    				<td class="pass-right"><input type="text" id="realDebitMoney" name="DebitProperty[realDebitMoney]"> 元</td>
				</tr>
				<tr>
    				<td class="pass-left">核准最低贷款金额：</td>
    				<td class="pass-right"><input type="text" id="realMinDebitMoney" name="DebitProperty[realMinDebitMoney]"> 元</td>
				</tr>
				<tr>
    				<td class="pass-left">补充说明：</td>
    				<td class="pass-right"><textarea rows="3" cols="20" id="kefuInfo" name="DebitProperty[kefuInfo]"></textarea></td>
				</tr>
				<tr>
    				<td class="pass-left">上传合同：</td>
    				<td class="pass-right"><input id="file_upload" name="file_upload" type="file" multiple="true"></td>
				</tr>
			</tbody>
			<tbody class="nopass">
                <tr>
                    <td class="pass-left">不通过理由：</td>
                    <td class="pass-right"><textarea rows="3" cols="20" id="noPassInfo" name="DebitProperty[noPassInfo]"></textarea></td>
                </tr>
            </tbody>
            <tbody>
                <tr class="table-title">
                	<td class="pass-result" colspan="2">
                		<button class="btn btn-primary" type="button" onClick="pass()"><i class="icon-ok icon-white"></i><span class="pass-word">通过</span></button>
                		<button class="btn btn-danger" type="button" onClick="noPass()"><i class="icon-remove icon-white"></i><span class="nopass-word">不通过</span></button>
    				</td>
    			</tr>
            </tbody>
        </table>
    </form>
</div>
<?php }else if($data->status != 0 && $data->productId != 0){?>
<div class="widget">
    <table class="tableStatic" width="100%" cellspacing="0" cellpadding="0">
    	<thead>
            <tr>
                <td class="title">合同附件</td>
            </tr>
        </thead>
        <tbody>
        	<tr>
				<td>
				<?php if(isset($data->agreeImage)){?>
    				<?php foreach($data->agreeImage as $agreeImage){?>
    				<div class="span3 thumbnail">
    					<a href="<?php echo $agreeImage->path;?>" rel="lightbox-group2" title="合同">
            				<img height="100px" src="<?php echo Yii::app()->easyImage->thumbSrcOf($agreeImage->path);?>">
            				<legend><?php echo $agreeImage->fileName;?></legend>
            			</a>
        			</div>
    				<?php }?>
    			<?php }?>
				</td>
			</tr>
		</tbody>
    </table>
</div>	
<?php }?>
<script type="text/javascript">
    $(document).ready(function() {
       	$("a[rel=lightbox-group1],a[rel=lightbox-group2]").fancybox({
      		'transitionIn'		: 'elastic',
      		'transitionOut'		: 'elastic',
      		'titlePosition' 	: 'over',
      		'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
      			return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
      		}
      	});
        $('#file_upload').uploadify({
            'formData'	:{'<?php echo Yii::app()->session->sessionName;?>':'<?php echo Yii::app()->session->sessionID;?>'},
            'swf'		: '/js/uploadify/uploadify.swf',
            'uploader'  : '/debit/uploadImage',
            'cancelImg' : '/js/uploadify-v3.1/uploadify-cancel.png',
            'auto'      : true,
            'removeTimeout'	: '1',
            'fileTypeDesc'	: '图片文件',
            'fileTypeExts'	: '*.gif;*.jpg;*.png;*.jpeg',
            'fileSizeLimit'	: '1MB',  
            'buttonText'	: '上传合同图片',
            'multi'	: true,	
            'progressData': 'percentage',
            'removeCompleted': false,
            'onUploadSuccess' : function(file, data, response) {
            	$('#kefu-form').data(file.id,data);
            	data = eval( "(" + data + ")" );
            	var imgUrl = data['path']+'/'+data['filename'];
            	var html = '<div style="text-align:center"><a style="display:block;" id="pic_'+file.id+'" href="'+ imgUrl +'" rel="lightbox-group2"><img class="upload_image" src="'+ imgUrl +'"/></a></div>';
            	$('#' + file.id).append(html);
            	$('#' + file.id).addClass('thumbnail');
            	$('#' + file.id).find('.uploadify-progress').remove();
            	$("a[rel=lightbox-group2]").fancybox({
            		'transitionIn'		: 'elastic',
            		'transitionOut'		: 'elastic',
            		'titlePosition' 	: 'over',
            		'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
            			return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
            		}
            	});
            }
        });
        
        $('.cancel').die().live('click',function(){
        	var fid = $.trim($(this).parent('.uploadify-queue-item').attr('id'));
			$('#kefu-form').removeData(fid);
			$(this).parent('.uploadify-queue-item').remove();
        })

//        $('.title').toggle(function(){
//			$(this).parent().parent().parent('table').find('tbody').slideDown('3');
//        },function(){
//        	$(this).parent().parent().parent('table').find('tbody').slideUp('3');
//        })
    	
    });
</script>