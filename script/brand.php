<?php
$query = mysql_connect("localhost","root","") or die('数据库链接失败');
mysql_select_db('thor');
mysql_query('set names utf8');
$brandStr = 'A 奥迪,9,A 奥克斯,12,A 阿尔法·罗密欧,92,A 阿斯顿·马丁,97,A 安驰,116,A 阿尔特,151,A AC Schnitzer,180,B 奔驰,2,B 宝马,3,B 标致,5,B 北汽制造,14,B 比亚迪,15,B 本田,26,B 奔腾,59,B 宝龙,68,B 保时捷,82,B 宾利,85,B 别克,127,B 布加迪,135,B 宝骏,157,B 北京汽车,163,B 北汽威旺,168,B 巴博斯,172,B 保斐利,184,C 长安微车,20,C 长城,21,C 昌河,129,C 长安轿车,136,C 长安商用,159,D 大众,8,D 东风,27,D 东南,29,D 大迪,66,D 大发,69,D 大宇,106,D 道奇,113,D 东风风行,115,D 东风风神,141,D 大通,165,D DS,179,D 底特律电动车,192,D 东风风度,197,D 东风校车系列,198,F 丰田,7,F 福特,17,F 菲亚特,40,F 富奇,61,F 福迪,67,F 法拉利,91,F 福田,128,F 福达,154,F 菲斯克,164,F Faralli Mazzanti,187,F 弗那萨利,190,F 飞驰商务车,199,G 广汽吉奥,63,G GMC,109,G 光冈,110,G 广汽日野,133,G 广汽,147,G 观致汽车,182,H 哈飞,31,H 海马,32,H 华普,44,H 汇众,45,H 黄海,52,H 红旗,58,H 航天圆通,70,H 悍马,108,H 华泰,112,H 汉江,119,H 黑豹,124,H 华阳,130,H 海马商用车,149,H 海格,170,H 恒天汽车,181,H 哈弗,196,J 吉普,4,J 吉利,34,J 江淮,35,J 江铃,37,J 江南,38,J 金杯,39,J 金龙联合,57,J 金程,74,J 捷豹,98,J 吉林江北,121,J 济南汽车,122,J 吉利帝豪,143,J 吉利全球鹰,144,J 吉利英伦,148,J 九龙,152,J 金旅客车,161,J 俊风,177,K 克莱斯勒,51,K 凯迪拉克,107,K 科尼塞克,145,K 开瑞,150,K 卡尔森,188,L 铃木,16,L 陆风,36,L 力帆,76,L 劳斯莱斯,80,L 路特斯,83,L 兰博基尼,86,L 蓝旗亚,90,L 雷克萨斯,94,L 林肯,95,L 路虎,96,L 雷诺,99,L 罗孚,101,L 莲花,146,L 猎豹汽车,153,L 理念,166,L 蓝海房车,200,M 马自达,18,M 美亚,55,M MG,79,M MINI,81,M 迈巴赫,88,M 玛莎拉蒂,93,M 牡丹汽车,123,M 迈凯轮,183,M 摩根,201,N 纳智捷,155,O 讴歌,84,O 欧宝,104,O 欧朗,171,P 旁蒂克,105,P 帕加尼,185,P PGO,191,Q 起亚,28,Q 奇瑞,42,Q 庆铃,43,Q 启辰,156,R 日产,30,R 荣威,78,R 瑞麒,142,S 斯柯达,10,S 三菱,25,S 双环,50,S 顺旅,73,S Smart,89,S 双龙,102,S 萨博,103,S 斯巴鲁,111,S 三星,117,S 世爵,137,S SPIRRA,162,S 陕汽通家,169,S 首望,173,S 绅宝,195,T 天马,54,T 通田,56,T 田野,120,T 塔菲克,139,T 特拉蒙塔纳,160,T 腾势,175,T TESLA,189,T 泰赫雅特,202,W 沃尔沃,19,W 万丰,46,W 五菱,48,W 万通,125,W 五十铃,132,W 威麟,140,W 威兹曼,186,X 雪铁龙,6,X 现代,13,X 雪佛兰,49,X 新雅途,62,X 新大地,65,X 新凯,71,X 西雅特,87,X 星客特,174,Y 依维柯,41,Y 仪征,47,Y 一汽,53,Y 永源,75,Y 英菲尼迪,100,Y 云豹,118,Y 云雀,126,Y 野马汽车,138,Y 友谊客车,176,Y 宇通,178,Y 英特诺帝,193,Y 扬州亚星客车,194,Z 中兴,33,Z 中华,60,Z 中客华北,64,Z 中顺,72,Z 众泰,77,Z 中欧,167,Z 之诺,203,Z 中通客车,204';
$brand = array();
$brandId = array();
$insert_cont = array();
$brand = explode(',',$brandStr);
for($i=0;$i<count($brand)-1;$i++){
	if($i%2 == 0){
		$brandId[] = $brand[$i+1];
		$insert_cont[] = '("'.$brand[$i].'",'.$brand[$i+1].',0,0,0)';
	}
}
$sql = 'INSERT INTO p2p_car_1(`name`,`key`,`fid`,`ffid`,`level`) VALUES '.implode(',',$insert_cont);
$result = mysql_query($sql);

$sql = 'SELECT DISTINCT id,`key` FROM p2p_car_1 WHERE level=0';
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result)){
	$brandNew[] = $row;
}

// header
$userAgent = array(
		'Mozilla/5.0 (Windows NT 6.1; rv:22.0) Gecko/20100101 Firefox/22.0', // FF 22
		'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36', // Chrome 27
		'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)', // IE 9
		'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', // IE 8
		'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', // IE 7
		'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.1 (KHTML, like Gecko) Maxthon/4.1.0.4000 Chrome/26.0.1410.43 Safari/537.1', // Maxthon 4
		'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', // 2345 2
		'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; QQBrowser/7.3.11251.400)', // QQ 7
		'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E; SE 2.X MetaSr 1.0)', // Sougo 4
		'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0) LBBROWSER', //  liebao 4
		);

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:22.0) Gecko/20100101 Firefox/22.0");
curl_setopt($ch, CURLOPT_REFERER, "http://hqn.jschina.com.cn/prop.asp?id=1975");
curl_setopt($ch, CURLOPT_TIMEOUT, 16);
curl_setopt($ch, CURLOPT_ENCODING ,'gzip');

$a = array_chunk($brandNew,30);
foreach($a as $k=>$v){
	foreach ($v as $key => $value) {
		$queryUrl = "http://pg.ucar.cn/ajax/carinfojs.ashx?carbrandid=".$value['key'];
		
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent[rand(0, count($userAgent)-1)]);
		curl_setopt($ch, CURLOPT_URL, $queryUrl);
		curl_setopt($ch, CURLOPT_PROXY, null);
		// 伪造IP头
		$ip = rand(27, 64).".".rand(100,200).".".rand(2, 200).".".rand(2, 200);
		$headerIp = array("X-FORWARDED-FOR:{$ip}", "CLIENT-IP:{$ip}");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headerIp);

		// 读取数据
		$res = @curl_exec($ch);
		$res = preg_replace('/var ucar_carserial\=/','',$res);
		$result = json_decode($res,true);
		
		$group = array();
		foreach($result as $r){
			$group[$r['GroupName']][] = $r;
		}
		foreach($group as $g=>$r){
			$sql = 'INSERT INTO p2p_car_1(`name`,`key`,`fid`,`ffid`,`level`) VALUES ("'.$g.'",0,0,'.$value['id'].',1)';
			$result = mysql_query($sql);
			$getID = mysql_insert_id();
			$insertVal = array();
			foreach($r as $b){
				$insertVal[] = '("'.$b['Text'].'","'.$b['Value'].'","'.$getID.'",'.$value['id'].',2)';
			}
			$sql = 'INSERT INTO p2p_car_1(`name`,`key`,`fid`,`ffid`,`level`) VALUES '.implode(',',$insertVal);
			$result = mysql_query($sql);
		}
		echo '-';
		sleep(0.5);
	}
}

