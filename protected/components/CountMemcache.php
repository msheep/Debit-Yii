<?php
/**
 * Memcache 自增 ...
 * @author songkejing
 * @modify cndong
 */ 
class CountMemcache extends CMemCache {
	
	public function incr($key, $offset = 1, $defalut = 1 ) {
		if(defined('PRODUCTION')){
			$key = $this->generateUniqueKey($key);
			return $this->_cache->increment ($key, $offset);
		} else {
			$value = Yii::app()->cache->get($key);
			if($value!==FALSE){
				$value +=$offset;
				Yii::app()->cache->set($key, $value);
				return $value;
			} else Yii::app()->cache->set($key, 1);
		}
	}
	
	public function increment($key, $offset = 1, $defalut = 1, $expiry = 0 ) {
		//if(!defined('PRODUCTION')) return true;
 		$value = $this->_cache->get($key);
    	if ($value !== false) {
            $key = $this->generateUniqueKey($key);
            return $this->_cache->increment($key, $offset);
        } else {
            $expiry = 7 * 24 * 3600;
            $this->_cache->set($key, $defalut, $expiry);
            return $defalut;
        }		
	}
}
?>
