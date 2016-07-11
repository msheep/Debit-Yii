<?php
class BackEndController extends Controller
{
	public function getWeekday($date){
		if($date==1){
			return  "一";
		}else if($date==2){
			return "二";
		}else if($date==3){
			return  "三";
		}else if($date==4){
			return  "四";
		}else if($date==5){
			return  "五";
		}else if($date==6){
			return "六";
		}else if($date==0){
			return "日";
		}else{
			return "";
		}
	}
	
}