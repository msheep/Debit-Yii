<?php
/**
 * 我要借款控制器
 * 借贷信息相关，必须登录方可操作
 */
class DebitController extends Controller {
    //public $layout='/layouts/userLayout';
    public $uid;
    
	public function init(){
	    $this->layout = '/layouts/debitLayout';
		$this->uid = Yii::app()->user->id;
		if(!$this->uid){
		    $this->redirect('/site/login');
		}
	}
	/**
	 * 我要借款
	 */
	public function actionIndex(){
	    $this->breadcrumbs = array(
	            '我要借款'
	    );
	    $this->render('index');
	}
	
	/**
	 * 我要借款-资费说明
	 */
	public function actionFeeIntro(){
	    $this->breadcrumbs = array(
	            '我要借款'=>array('debit/index'),
	            '资费说明'
	    );
	    $this->render('feeIntro');
	}
	
	/**
	 * 我要借款-借款资格
	 */
	public function actionCondition(){
	    $this->breadcrumbs = array(
	            '我要借款'=>array('debit/index'),
	            '借款资格'
	    );
	    $this->render('condition');
	}
	
	
	//借贷信息
	public function actionApplyDebit(){
	    $this->breadcrumbs = array(
	            '我要借款'=>array('debit/index'),
	            '开始借款'
	    );
	    $debitModel = new Debit('create');
	    $userModel = User::model()->findByPk($this->uid);
	    if($userModel->getDebitStatus() != '已通过'){
	        throw new CHttpException(200, '您尚未通过验证!');
            Yii::app()->end();
	    }
	    $this->render('applyDebit',array('model'=>$debitModel));
	}
	
	//借贷产品->域名
    public function actionDomainDebit(){
        $debitId = Yii::app()->request->getParam('debitId');
        $this->checkDebitId($debitId,1);
        $debitDomainModel = new DebitDomain();
        $debitDomainModel->registrationDate = date('Y-m-d');
        $debitDomainModel->lastPayDate = date('Y-m-d');
        $debitDomainModel->deadLine = date('Y-m-d');
        
        if($_POST){
            $debitModel = new Debit();
            //检验域名的唯一性
            $hasDomain = $debitModel->checkProduct(trim($_POST['DebitDomain']['domain']),'domain');
            if($hasDomain || !empty($hasDomain)){
	            echo -2;
	            Yii::app()->end();
	        }
	        $transaction = Yii::app()->db->beginTransaction();
            foreach($_POST['DebitDomain'] as $i=>$debit){
				if(isset($debit) && in_array($i, $debitDomainModel->attributeNames())){
				    if(!is_array($debit)){
				        $debit = trim($debit);
				    }
					$debitDomainModel->$i = $debit;
				}
			}
			$debitDomainModel->debitId = $debitId;
	        try{
                $debitDomainModel->save();
                $domainId = $debitDomainModel->attributes['id'];
                $debitModel = Debit::model()->findByPk($debitId);
                $debitModel->productId = $domainId;
                $debitModel->update();
	            $transaction->commit();
                echo '1成功';	        
            }catch (Exception $e) {print_r($e);
                $transaction->rollback(); 
                echo '0失败';
            }
        }
	    $this->render('domainDebit',array('model'=>$debitDomainModel));
	}
	
	//借贷产品->房产
	public function actionPropertyDebit(){
	    $debitId = Yii::app()->request->getParam('debitId');
	    $this->checkDebitId($debitId,2);
	    
	    //设置初始值
	    $debitPropertyModel = new DebitProperty();
	    $debitPropertyModel->isLoan = 0;
	    $debitPropertyModel->isRent = 0;
	    $debitPropertyModel->buyDate = date('Y',strtotime('-1 year')).'-01';
	    $debitPropertyModel->loanBegin = date('Y',strtotime('-1 year')).'-01';
	    $debitPropertyModel->loanEnd = date('Y-m');
	    $debitPropertyModel->rentBegin = date('Y',strtotime('-1 year')).'-01-01';
	    $debitPropertyModel->rentEnd = date('Y-m-d');
	    
	    $houseModel = new DebitPropertyHouse();
	    $houseModel->isSecondHand = 0;
	    $houseModel->fitment = 1;
	    $houseModel->houseLight = 1;
	    $houseModel->houseLandscape = 1;
	    $houseModel->houseLocation = 1;
	    $houseModel->houseNoise = 1;
	    $houseModel->carport = 0;
	    $houseModel->hateFacility = 1;
	    
	    $businessModel = new DebitPropertyBusiness();
	    $businessModel->businessType = 1;
	    
	    $fileModel = new File();

	    if($_POST){
	        $debitModel = new Debit();
	        //检验地产证与房产证的唯一性
	        $hasLand = $debitModel->checkProduct(trim($_POST['DebitProperty']['landCertificateId']),'propertyLand');
	        $hasHouse = $debitModel->checkProduct(trim($_POST['DebitProperty']['houseCertificateId']),'propertyHouse');
	        if($hasLand || !empty($hasLand) || $hasHouse || !empty($hasHouse)){
	            echo -2;
	            Yii::app()->end();
	        }
	        $transaction = Yii::app()->db->beginTransaction();
            try{
                if($_POST['DebitProperty']){
                    foreach($_POST['DebitProperty'] as $i=>$debit){
        				if(isset($debit) && in_array($i, $debitPropertyModel->attributeNames())){
        				    if(!is_array($debit)){
        				        $debit = trim($debit);
        				    }
        					$debitPropertyModel->$i = $debit;
        				}
        			}
                    $debitPropertyModel->debitId = $debitId;

                    $landBothOwner = array();
                    if(!empty($_POST['DebitProperty']['landBothOwner'])){
                        foreach($_POST['DebitProperty']['landBothOwner'] as $owner){
                            if(trim($owner[1]) != ''){
                                $landBothOwner[] = $owner;
                            }
                        }
                    }
                    $debitPropertyModel->landBothOwner = json_encode($landBothOwner);
                    
                    $houseBothOwner = array();
                    if(!empty($_POST['DebitProperty']['houseBothOwner'])){
                        foreach($_POST['DebitProperty']['houseBothOwner'] as $owner){
                            if(trim($owner[1]) != ''){
                                $houseBothOwner[] = $owner;
                            }
                        }
                    }
                    $debitPropertyModel->houseBothOwner = json_encode($houseBothOwner);
                    
                    if($_POST['DebitProperty']['isLoan'] == 0){
                        $debitPropertyModel->loanBegin = '0000-00-00';
                        $debitPropertyModel->loanEnd = '0000-00-00';
                        $debitPropertyModel->loanPercent = '0';
                    }else{
                        $debitPropertyModel->loanBegin = $_POST['DebitProperty']['loanBegin'].'-01';
                        $debitPropertyModel->loanEnd = $_POST['DebitProperty']['loanEnd'].'-01';
                    }
                    if($_POST['DebitProperty']['isRent'] == 0){
                        $debitPropertyModel->rentBegin = '0000-00-00';
                        $debitPropertyModel->rentEnd = '0000-00-00';
                        $debitPropertyModel->rentMoney = '0';
                    }
                    $debitPropertyModel->buyDate = $_POST['DebitProperty']['buyDate'].'-01';
                    
                    if($debitPropertyModel->validate()){
        	            $debitPropertyModel->save();
        	            $productId = $debitPropertyModel->attributes['id'];
        	            //更新借贷表中的产品id字段
                        $debitModel = Debit::model()->findByPk($debitId);
    	                $debitModel->productId = $productId;
    	                $debitModel->update();
    	                
    	                //存入住宅表
        	            if($_POST['DebitProperty']['type'] == 1 || $_POST['DebitProperty']['type'] == 3){
                            if($_POST['DebitPropertyHouse']){
                                foreach($_POST['DebitPropertyHouse'] as $x=>$house){
                    				if(isset($house) && in_array($x, $houseModel->attributeNames())){
                        				if(!is_array($house)){
                    				        $house = trim($house);
                    				    }
                    					$houseModel->$x = $house;
                    				}
                    			}
                                $houseModel->relatedDebitId = $debitId;
                                $houseModel->relatedProductId = $productId;
                                if(!empty($_POST['DebitPropertyHouse']['support'])){
                                    $houseModel->support = implode(',', $_POST['DebitPropertyHouse']['support']);
                                }
                                
                                if($_POST['DebitPropertyHouse']['hateFacility'] == 3){
                                    $houseModel->hateFactor = '';
                                }else{
                                    if(!empty($_POST['DebitPropertyHouse']['hateFactor'])){
                                        $houseModel->hateFactor = implode(',', $_POST['DebitPropertyHouse']['hateFactor']);
                                    }
                                }
                                if($_POST['DebitPropertyHouse']['fitment'] == 5){
                                    $houseModel->fitmentMoney = '0';
                                    $houseModel->fitmentTime = '0';
                                }
                                if($houseModel->validate()){
                    	            $houseModel->save();
                    	        }
                	        }
                            if($_POST['DebitPropertyHouse']){
                                $houseModel->relatedDebitId = $debitId;
                                $houseModel->relatedProductId = $productId;
                                $houseModel->userId = $this->uid;
                                if($houseModel->validate()){
                    	            $houseModel->save();
                    	        }
                	        }
                	    //存入商品房表
        	            }else if($_POST['DebitProperty']['type'] == 2){
        	                if($_POST['DebitPropertyBusiness']){
            	                foreach($_POST['DebitPropertyBusiness'] as $y=>$business){
                    				if(isset($business) && in_array($y, $businessModel->attributeNames())){
                    				    if(!is_array($business)){
                    				        $business = trim($business);
                    				    }
                    					$businessModel->$y = $business;
                    				}
                    			}
                    			$businessModel->relatedDebitId = $debitId;
                                $businessModel->relatedProductId = $productId;
                                if($_POST['DebitPropertyBusiness']['businessType'] != 1){
                                    $businessModel->manageType = '';
                                }else{
                                    if(!empty($_POST['DebitPropertyBusiness']['manageType'])){
                                        $businessModel->manageType = implode(',', $_POST['DebitPropertyBusiness']['manageType']);
                                    }
                                }
        	                    if(!empty($_POST['DebitPropertyBusiness']['support'])){
                                    $businessModel->support = implode(',', $_POST['DebitPropertyBusiness']['support']);
                                }
        	                    if($businessModel->validate()){
                    	            $businessModel->save();
                    	        }
        	                }
        	            }
        	            
        	            //保存图片
        	            $fileModel->category = 4;
        	            $fileModel->relateId = $productId;
        	            $fileModel->fileAttribute = 'path';
        	            $fileModel->batchSave();
        	        }else{
        	            print_r($debitPropertyModel->getErrors());
        	        }
    	        }
                $transaction->commit();
                echo '1成功';
            }catch (Exception $e) {PTrack($e);
                $transaction->rollback(); 
                echo '0失败';
            }
	        
	    }
	    
	    $this->render('propertyDebit',array('model'=>$debitPropertyModel,'house'=>$houseModel,'business'=>$businessModel,'file'=>$fileModel));
	}
	
    //借贷产品->车辆
    public function actionCarDebit(){
        $debitId = Yii::app()->request->getParam('debitId');
        $this->checkDebitId($debitId,3);
        $debitCarModel = new DebitCar();
        $debitCarModel->gearBox = 0;
        $debitCarModel->registration = 1;
        $debitCarModel->drivingPermit = 1;
        $debitCarModel->receipt = 1;
        $fileModel = new File();
        if($_POST){
	        $debitModel = new Debit();
	        //检验汽车发动机号的唯一性
	        $hasProduct = $debitModel->checkProduct(trim($_POST['DebitCar']['engineNumber']),'car');
	        if($hasProduct || !empty($hasProduct)){
	            echo -2;
	            Yii::app()->end();
	        }
            $transaction = Yii::app()->db->beginTransaction();
            try{
                foreach($_POST['DebitCar'] as $i=>$debit){
    				if(isset($debit) && in_array($i, $debitCarModel->attributeNames())){
    				    if(!is_array($debit)){
    				        $debit = trim($debit);
    				    }
    					$debitCarModel->$i = $debit;
    				}
    			}
    			$debitCarModel->userId = $this->uid;
    			$debitCarModel->debitId = $debitId;
    			$debitCarModel->productDate = $_POST['DebitCar']['productYear'].'-'.$_POST['DebitCar']['productMonth'].'-01';
    			$debitCarModel->buyDate = $_POST['DebitCar']['buyYear'].'-'.$_POST['DebitCar']['buyMonth'].'-01';
    			$debitCarModel->registDate = $_POST['DebitCar']['registYear'].'-'.$_POST['DebitCar']['registMonth'].'-01';
    			$debitCarModel->auditDate = $_POST['DebitCar']['auditYear'].'-'.$_POST['DebitCar']['auditMonth'].'-01';
    			$debitCarModel->insuranceDate = $_POST['DebitCar']['insuranceYear'].'-'.$_POST['DebitCar']['insuranceMonth'].'-01';
    			if($debitCarModel->validate()){
    			    //存入对应的车贷产品表
    			    $debitCarModel->save();
    	            $productId = $debitCarModel->attributes['id'];
    	            
                    //更新借贷表中的产品id字段
                    $debitModel = Debit::model()->findByPk($debitId);
	                $debitModel->productId = $productId;
	                $debitModel->update();
    			}
    			//存入图片
    			if(isset($_FILES['File']['name']['registr'])){
    			    $fileModel->category = 5;
    	            $fileModel->relateId = $productId;
    	            $fileModel->fileAttribute = "registr";
    	            $fileModel->batchSave();
    			}
                if(isset($_FILES['File']['name']['drive'])){
    			    $fileModel->category = 6;
    	            $fileModel->relateId = $productId;
    	            $fileModel->fileAttribute = "drive";
    	            $fileModel->batchSave();
    			}
                if(isset($_FILES['File']['name']['receipt'])){
    			    $fileModel->category = 7;
    	            $fileModel->relateId = $productId;
    	            $fileModel->fileAttribute = "receipt";
    	            $fileModel->batchSave();
    			}
                if(isset($_FILES['File']['name']['car'])){
    			    $fileModel->category = 8;
    	            $fileModel->relateId = $productId;
    	            $fileModel->fileAttribute = "car";
    	            $fileModel->batchSave();
    			}
                $transaction->commit();
                echo 1;
            }catch (Exception $e) {
                $transaction->rollback(); 
                echo 0;
            }
        }   
	    $this->render('carDebit',array('model'=>$debitCarModel,'file'=>$fileModel));
	}
	
    //借贷产品页的检验
    public function checkDebitId($debitId,$cat){
        $userModel = User::model()->findByPk($this->uid);
        if($userModel->getDebitStatus() != '已通过'){
	        throw new CHttpException(200, '您尚未通过验证!');
            Yii::app()->end();
	    }
        if(!$debitId){
            throw new CHttpException(200, '未找到相关借贷申请!');
            Yii::app()->end();
        }else{
            $debitModel = Debit::model()->findByPk($debitId);
            if(!$debitModel || !isset($debitModel)){
                throw new CHttpException(200, '该借贷申请不存在!');
                Yii::app()->end();
            }
            if($debitModel->borrowerId != $this->uid){
                throw new CHttpException(200, '您无权操作该借贷信息!');
                Yii::app()->end();
            }
            if($debitModel->cat != $cat){
                throw new CHttpException(200, '借贷产品错误!');
                Yii::app()->end();
            }
            if($debitModel->productId != 0){
                throw new CHttpException(200, '该条借贷信息已经存在，请到会员中心进行查看!');
                Yii::app()->end();
            }
        }
    }
	
	//插入debit表
	public function actionSubmitDebit(){
	    $userModel = User::model()->findByPk($this->uid);
	    if($userModel->getDebitStatus() != '已通过'){
	        throw new CHttpException(200, '您尚未通过验证!');
            Yii::app()->end();
	    }
	    $debitModel = new Debit('create');
	    //借款金额必须是100的倍数
	    if(trim($_POST['debitMoney']) % 100 != 0){
	        echo -1;
            Yii::app()->end();
	    }
	    if(trim($_POST['debitMinMoney']) % 100 != 0){
	        echo -1;
            Yii::app()->end();
	    }
	    //最低借款金额不能大于借款金额
	    if(trim($_POST['debitMoney']) > 20000){
	        if(trim($_POST['debitMinMoney']) > 0 && trim($_POST['debitMinMoney']) > trim($_POST['debitMoney'])){
                echo -1;
                Yii::app()->end();
	        }
	    }else{
	        $_POST['debitMinMoney'] = trim($_POST['debitMoney']);
	    }
	    if ($_POST) {
	        $items = $debitModel->getAttributes();
			foreach($items as $i=>$item){
				if(isset($_POST[$i])){
					$debitModel->$i = $_POST[$i];
				}
			}
	        $debitModel->borrowerId = $this->uid;
	        if($debitModel->validate()){
	            if($debitModel->save()){
                    echo $debitModel->attributes['id'];
	            }else{
	                echo 0;
	            }
	        }else{
	            echo -1;
	        }
	    }else{
	        echo -2;
	    }
	}
	
	//插入对应的抵押产品表(废弃)
    public function actionSubmitProductDebit(){
	    if ($_POST && trim($_POST['type'])) {
	        $type = trim($_POST['type']);
	        if($type == 'domain'){
	            $product = trim($_POST['domain']);
	            $cat = 1;
	            $productModel = new DebitDomain();
	        }else if($type == 'property'){
	            $product = trim($_POST['propertyCertificateId']);
	            $cat = 2;
	            $productModel = new DebitProperty();
	        }else if($type == 'car'){
	            $product = trim($_POST['carId']);
	            $cat = 3;
	            $productModel = new DebitCar();
	        }
	        //查看该借贷是否有效，是否借贷人为本人，是否该借贷已经存在借贷产品信息
	        $debitId = trim($_POST['debitId']);
	        $debitModel = Debit::model()->findByPk($debitId);
	        if(!isset($debitModel) || !$debitModel || $debitModel->borrowerId != $this->uid || $debitModel->cat != $cat){
	            echo -1;
	            Yii::app()->end();
	        }
	        if($debitModel->productId != 0){
	            echo -5;
	            Yii::app()->end();
	        }
	        //查看该借贷产品是否处于借贷中状态
	        $debitModel2 = new Debit();
	        $hasProduct = $debitModel2->checkProduct($product,$type);
	        if($hasProduct || !empty($hasProduct)){
	            echo -2;
	            Yii::app()->end();
	        }
	        //取得各个传值
	        $items = $productModel->getAttributes();
			foreach($items as $i=>$item){
				if(isset($_POST[$i])){
					$productModel->$i = $_POST[$i];
				}
			}
			//验证
	        if($productModel->validate()){
	            //开启事务，存入domian表->productId存入debit表
	            $transaction = Yii::app()->db->beginTransaction();
	            try{
                    $productModel->userId = $this->uid;
	                $productModel->save();
	                $productId = $productModel->attributes['id'];
	                $debitModel->productId = $productId;
	                $debitModel->save();
	                $transaction->commit();
	                echo 1;
                }catch (Exception $e) {
                    $transaction->rollback(); 
                    echo 0;
                }
	        }else{
	            echo -3;
	        }
	    }else{
	        echo -4;
	    }
	}
	
	//检查借贷产品是否重复
	public function actionCheckProduct(){
	    $product = Yii::app()->request->getParam('product');
	    $type = Yii::app()->request->getParam('type');
	    if($product && $type){
	        $debitModel = new Debit;
	        $hasProduct = $debitModel->checkProduct($product,$type);
	        if($hasProduct || !empty($hasProduct)){
	            echo 1;
	        }else{
	            echo 0;
	        }
	    }else{
	        echo -1;
	    }
	}
	
	//选择省市区
	public function actionSelectCity(){
	    $type = Yii::app()->request->getParam('type');
	    if(!$type){
	        echo 0;
	        Yii::app()->end();
	    }
	    $provinceModel = new Province();
	    $html = '';
	    switch($type){
	        case 'province':
	            $province = array();
	            $province = $provinceModel->province();
	            if(!empty($province)){
	                foreach($province as $p){
	                    $html .= '<option value="'.$p['provinceId'].'">'.$p['province'].'</option>';
	                }
	                echo $html;
	            }else{
	                echo 0;
	            }
	            break;
	        case 'city':
	            $provinceId = Yii::app()->request->getParam('provinceId');
	            if(!$provinceId){
	                echo 0;
	                Yii::app()->end();
	            }
	            $city = array();
	            $city = $provinceModel->city($provinceId);
	            if(!empty($city)){
	                foreach($city as $c){
	                    $html .= '<option title="'.$provinceId.'" value="'.$c['cityId'].'">'.$c['city'].'</option>';
	                }
	                echo $html;
	            }else{
	                echo 0;
	            }
	            break;
	        case 'area':
	            $cityId = Yii::app()->request->getParam('cityId');
	            if(!$cityId){
	                echo 0;
	                Yii::app()->end();
	            }
	            $area = array();
	            $area = $provinceModel->area($cityId);
	            if(!empty($area)){
	                foreach($area as $a){
	                    $html .= '<option title="'.$cityId.'" value="'.$a['areaId'].'">'.$a['area'].'</option>';
	                }
	                echo $html;
	            }else{
	                echo 0;
	            }
	            break;
	    }
	}
	
    //处理车型
	public function actionSelectCar(){
	    $type = Yii::app()->request->getParam('type');
	    if(!$type){
	        echo 0;
	        Yii::app()->end();
	    }
	    $carModel = new Car();
	    $html = '';
	    switch($type){
	        case 'brand':
	            $brand = array();
	            $brand = $carModel->getBrand();
	            if(!empty($brand)){
	                foreach($brand as $b){
	                    $html .= '<option value="'.$b['id'].'">'.$b['name'].'</option>';
	                }
	                echo $html;
	            }else{
	                echo 0;
	            }
	            break;
	        case 'series':
	            $brandId = Yii::app()->request->getParam('brandId');
	            if(!$brandId){
	                echo 0;
	                Yii::app()->end();
	            }
	            $series = array();
	            $series = $carModel->getSeries($brandId);
	            if(!empty($series)){
	                foreach($series as $s){
	                    if($s['level'] == 1){
	                        $html .= '<option style="border-bottom:1px dashed #ccc;color:#666;font-style: italic;" disabled>'.$s['name'].'</option>';
	                    }else{
	                        $html .= '<option title="'.$brandId.'" value="'.$s['id'].'">'.$s['name'].'</option>';
	                    }
	                }
	                echo $html;
	            }else{
	                echo 0;
	            }
	            break;
	        case 'car':
	            $seriesId = Yii::app()->request->getParam('seriesId');
	            if(!$seriesId){
	                echo 0;
	                Yii::app()->end();
	            }
	            $car = array();
	            $car = $carModel->getCar($seriesId);
	            if(!empty($car)){
	                foreach($car as $c){
	                    if($c['level'] == 3){
	                        $html .= '<option style="border-bottom:1px dashed #ccc;color:#666;font-style: italic;" disabled>'.$c['name'].'</option>';
	                    }else{
	                        $html .= '<option title="'.$seriesId.'" value="'.$c['id'].'">'.$c['name'].'</option>';
	                    }
	                }
	                echo $html;
	            }else{
	                echo 0;
	            }
	            break;
	    }
	}
	
}