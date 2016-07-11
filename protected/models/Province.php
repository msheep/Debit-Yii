<?php
class Province extends BaseModel {
    
    public function loadInit($params = array()){}
	
    //获取省列表
    public function province(){
        $cacheKey = 'province';
        $results = Yii::app()->cache->get($cacheKey);
        if(empty($results) || $results===false){
            $sql = 'SELECT provinceId,province 
    				FROM p2p_area_province';
            $command = Yii::app()->db->createCommand($sql);
    		$results = $command->queryAll();
    		Yii::app()->cache->set($cacheKey, $results, 3600*24*30);
        }
        return $results;
    }
    
    //获取市列表
    public function city($provinceId){
        $cacheKey = 'city_'.$provinceId;
        $results = Yii::app()->cache->get($cacheKey);
        if(empty($results) || $results===false){
            $sql = 'SELECT cityId,city 
    				FROM p2p_area_city
    				WHERE fid = :provinceId';
            $command = Yii::app()->db->createCommand($sql);
            $command -> bindParam(':provinceId', $provinceId, PDO::PARAM_INT);
    		$results = $command->queryAll();
    		Yii::app()->cache->set($cacheKey, $results, 3600*24*30);
        }
        return $results;
    }
    
    //获取区列表
    public function area($cityId){
        $cacheKey = 'area_'.$cityId;
        $results = Yii::app()->cache->get($cacheKey);
        if(empty($results) || $results===false){
            $sql = 'SELECT areaId,area 
    				FROM p2p_area
    				WHERE fid = :cityId';
            $command = Yii::app()->db->createCommand($sql);
            $command -> bindParam(':cityId', $cityId, PDO::PARAM_INT);
    		$results = $command->queryAll();
    		Yii::app()->cache->set($cacheKey, $results, 3600*24*30);
        }
        return $results;
    }
    
    public static function getProvinceName($provinceId){
        $cacheKey = 'province_name_'.$provinceId;
        $results = Yii::app()->cache->get($cacheKey);
        //(empty($results) || $results===false){
            $sql = 'SELECT province 
    				FROM p2p_area_province
    				WHERE provinceId = :provinceId LIMIT 1';
            $command = Yii::app()->db->createCommand($sql);
            $command -> bindParam(':provinceId', $provinceId, PDO::PARAM_INT);
    		$results = $command->queryScalar();
    		Yii::app()->cache->set($cacheKey, $results, 3600*24*30);
        //}
        return $results;
    }
    
    public static function getCityName($cityId){
        $cacheKey = 'city_name_'.$cityId;
        $results = Yii::app()->cache->get($cacheKey);
        //if(empty($results) || $results===false){
            $sql = 'SELECT city 
    				FROM p2p_area_city
    				WHERE cityId = :cityId LIMIT 1';
            $command = Yii::app()->db->createCommand($sql);
            $command -> bindParam(':cityId', $cityId, PDO::PARAM_INT);
    		$results = $command->queryScalar();
    		Yii::app()->cache->set($cacheKey, $results, 3600*24*30);
        //}
        return $results;
    }
    
    public static function getAreaName($areaId){
        $cacheKey = 'area_name_'.$areaId;
        $results = Yii::app()->cache->get($cacheKey);
        if(empty($results) || $results===false){
            $sql = 'SELECT area 
    				FROM p2p_area
    				WHERE areaId = :areaId LIMIT 1';
            $command = Yii::app()->db->createCommand($sql);
            $command -> bindParam(':areaId', $areaId, PDO::PARAM_INT);
    		$results = $command->queryScalar();
    		Yii::app()->cache->set($cacheKey, $results, 3600*24*30);
        }
        return $results;
    }
    /**
     * 获取完整地区信息的
     * @param string $provinceId
     * @param string $cityId
     * @param string $areaId
     */
    public static function getFullAreaName($provinceId,$cityId = null,$areaId = null){
        if(empty($provinceId)){
            return null;
        }
        $cacheKey = 'full_area_name_'.$provinceId.$cityId.$areaId;
        $results = Yii::app()->cache->get($cacheKey);
        if(empty($results) || $results===false){
            $sql = 'SELECT provinceId,province,cityId,city,area,areaId FROM p2p_area_province p 
                    LEFT JOIN p2p_area_city c ON p.provinceId = c.fid AND c.cityId = :cityId 
                    LEFT JOIN p2p_area a ON a.fid = c.cityId and a.areaId = :areaId 
                    WHERE p.provinceId = :provinceId';
            $command = Yii::app()->db->createCommand($sql);
            $command -> bindParam(':areaId', $areaId, PDO::PARAM_INT);
            $command -> bindParam(':cityId', $cityId, PDO::PARAM_INT);
            $command -> bindParam(':provinceId', $provinceId, PDO::PARAM_INT);
            $results = $command->queryRow();
            if($results){
                Yii::app()->cache->set($cacheKey, $results, 3600*24*30);
            }
        }
        return $results;
    }
}