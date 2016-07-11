<?php 
class Car extends BaseModel {
    
    public function tableName() {
		return '{{car}}';
	}
	
    public function loadInit($params = array()){}
	
    //获取品牌
    public function getBrand(){
        $cacheKey = 'brand';
        $results = Yii::app()->cache->get($cacheKey);
        //if(empty($results) || $results===false){
            $sql = 'SELECT id,`key`,name 
    				FROM p2p_car
    				WHERE level = 0';
            $command = Yii::app()->db->createCommand($sql);
    		$results = $command->queryAll();
    		Yii::app()->cache->set($cacheKey, $results, 3600*24*30);
        //}
        return $results;
    }
    
    //获取车系
    public function getSeries($brandId){
        $cacheKey = 'series_'.$brandId;
        $results = Yii::app()->cache->get($cacheKey);
        //if(empty($results) || $results===false){
            $sql = 'SELECT id,`key`,name,level
    				FROM p2p_car
    				WHERE ffid = :brandId AND (level = 1 OR level = 2)';
            $command = Yii::app()->db->createCommand($sql);
            $command -> bindParam(':brandId', $brandId, PDO::PARAM_INT);
    		$results = $command->queryAll();
    		Yii::app()->cache->set($cacheKey, $results, 3600*24*30);
        //}
        return $results;
    }
    
    //获取区车型
    public function getCar($seriesId){
        $cacheKey = 'car_'.$seriesId;
        $results = Yii::app()->cache->get($cacheKey);
        //if(empty($results) || $results===false){
            $sql = 'SELECT id,`key`,name,level
    				FROM p2p_car
    				WHERE ffid = :seriesId AND (level = 3 OR level = 4)';
            $command = Yii::app()->db->createCommand($sql);
            $command -> bindParam(':seriesId', $seriesId, PDO::PARAM_INT);
    		$results = $command->queryAll();
    		Yii::app()->cache->set($cacheKey, $results, 3600*24*30);
        //}
        return $results;
    }
    
    public static function getCarInfo($brandId,$seriesId,$carId){
        $carInfo = '';
        if(self::model()->findByPk($brandId)){
            $carInfo .= substr(self::model()->findByPk($brandId)->name,1).'&nbsp;&nbsp;';
        }
        if(self::model()->findByPk($seriesId)){
            $carInfo .= self::model()->findByPk($seriesId)->name.'&nbsp;&nbsp;';
        }
        if(self::model()->findByPk($carId)){
            $carInfo .= self::model()->findByPk($carId)->name.'&nbsp;&nbsp;';
        }
        return $carInfo;
    }
    
}