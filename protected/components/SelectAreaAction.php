<?php
/**
 * 选择区域action类
 * @author Administrator
 *
 */
class SelectAreaAction extends CAction{
    
    /**
     * Runs the action.
     * @param string $type 请求类型：province，city，area
     * @param string parentId 父节点id
     * @param int $returnOption 1:option,0:json
     */
    public function run($type,$parentId = 0,$returnOption = 1)
    {
        $html = '';
        $jsonData = '';
        $provinceModel = new Province();
        switch ($type){
            case 'province':{
                $provinces = $provinceModel->province();
                if($returnOption && !empty($provinces)){
                    foreach($provinces as $v){
                        $html .= "<option value='{$v['provinceId']}'>{$v['province']}</option>";
                    }
                }elseif(!$returnOption){
                    $jsonData = CJSON::encode($provinces);
                }
                break;
            }
            case 'city':{
                $citys = $provinceModel->city($parentId);
                if($returnOption && !empty($citys)){
                    foreach($citys as $v){
                        $html .= "<option value='{$v['cityId']}'>{$v['city']}</option>";
                    }
                }elseif(!$returnOption){
                    $jsonData = CJSON::encode($citys);
                }
                break;
            }
            case 'area':{
                $areas = $provinceModel->area($parentId);
                if($returnOption && !empty($areas)){
                    foreach($areas as $v){
                        $html .= "<option value='{$v['areaId']}'>{$v['area']}</option>";
                    }
                }elseif(!$returnOption){
                    $jsonData = CJSON::encode($areas);
                }
                break;
            }
        }
        if($returnOption){
            echo $html;
        }else{
            echo $jsonData;
        }
    }
    
    
}