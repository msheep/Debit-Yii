<dl class="rbox">
        <dt class="title"><span>去买呀服务</span></dt>
        <dd class="cont">
                <ul class="services">
                        <li><a href="#"><i class="r_train"></i>火车票查&nbsp;&nbsp;&nbsp;询</a></li>
                        <li><a href="#"><i class="r_moment"></i>时刻表查&nbsp;&nbsp;&nbsp;询</a></li>
                        <li><a href="#"><i class="r_trips"></i>车&nbsp;&nbsp;&nbsp;次查&nbsp;&nbsp;&nbsp;询</a></li>
                        <li><a href="#"><i class="r_ticket"></i>代售点查&nbsp;&nbsp;&nbsp;询</a></li>
                </ul>
        </dd>
</dl><!--/rbox-->
<?php
        $flagShow = 0; 
        if (isset($_GET['from']) && isset($_GET['to'])) {
                $cityFrom = TianQi::getCityInfo($_GET['from']);
                $cityTo = TianQi::getCityInfo($_GET['to']);     
                if ($cityFrom || $cityTo) {
                        $flagShow = 1;
                }
        }
        $date = date('Y-m-d');                                                  // 日期?当日:到站                                         
        if (isset($_GET['date'])) { $date = $_GET['date']; }
        if (strtotime($date) - time() > 3600*24*13) $date = date('Y-m-d', strtotime('+13 days', time()));
		
        $cityFrom = array('city' => $cityFrom, 'date' => $date);
        $cityTo = array('city' => $cityTo, 'date' => $date);
        $weekarray = array("日","一","二","三","四","五","六");  
 
if ($flagShow): ?>
<!-- 能查到天气时，显示天气状况 -->
<div class="weather">
    <dl>
        <dt>天气提醒</dt>
        <dd id="weatherFrom"></dd>
        <dd id="weatherTo"></dd>
                <script type="text/javascript">
                        var cityFrom = {};
                        var cityTo = {};
                        <?php 
                                echo "var flagShow = ".$flagShow.';';
                                echo "cityFrom.city = '".$cityFrom['city']."';";
                                echo "cityFrom.date = '".$cityFrom['date']."';";
                                echo "cityTo.city = '".$cityTo['city']."';";
                                echo "cityTo.date = '".$cityTo['date']."';";
                         ?>
                         $(document).ready(function(){
                                $.ajax({
                                        async:true,
                                        cache:false,
                                        timeout: 5000,
                                        dataType: 'json',
                                        type: "POST",
                                        url: '/train/getWeather',
                                        data:cityFrom,
                                        contentType: "application/x-www-form-urlencoded; charset=utf-8",
                                        success:function(response, textStatus){
                                                if (response&&response.city) {
                                                        var res = '<span class="white">出发:</span><span class="yellow"><a style="color:#e9be9f" target="_blank" href="'+ "#" +'">' + cityFrom.city + '</a></span>'+
                                '<p><?php echo date('Y年m月d号', strtotime($cityFrom['date'])); ?> 星期<?php echo $weekarray[date('w', strtotime($cityFrom['date']))]; ?></p><div class="pic"><img src="http://www.qumaiya.com/images/weather1/day/' + response.day +'.png" /><img src="http://www.qumaiya.com/images/weather1/night/' + response.night + '.png" /></div>'+
                                '<p>'+response.tem1+'℃~'+response.tem2+'℃ / '+response.windState+'</p>';
                                        $(".weather #weatherFrom").append(res);
                                                };
                                        }
                                })
                                $.ajax({
                                        async:true,
                                        cache:false,
                                        timeout: 5000,
                                        dataType: 'json',
                                        type: "POST",
                                        url: '/train/getWeather',
                                        data:cityTo,
                                        contentType: "application/x-www-form-urlencoded; charset=utf-8",
                                        success:function(response, textStatus){
                                                if (response&&response.city) {
                                                        var res = '<span class="white">到达:</span><span class="yellow"><a target="_blank" style="color:#e9be9f" href="'+ "#"+'">' + cityTo.city + '</a></span>'+
                                '<p><?php echo date('Y年m月d号', strtotime($cityTo['date'])); ?> 星期<?php echo $weekarray[date('w', strtotime($cityTo['date']))]; ?></p><div class="pic"><img src="http://www.qumaiya.com/images/weather1/day/' + response.day +'.png" /><img src="http://www.qumaiya.com/images/weather1/night/' + response.night + '.png" /></div>'+
                                '<p>'+response.tem1+'℃~'+response.tem2+'℃ / '+response.windState+'</p>';
                                        $(".weather #weatherTo").append(res);
                                                };
                                        }
                                })
                         });
                </script>
        </dl>
</div><!--/weather-->   
<?php endif ?>


<dl class="rbox advertise">
        <dt class="title"><span>精彩推荐</span></dt>
        <dd class="cont">
            <script type="text/javascript" >BAIDU_CLB_SLOT_ID = "545200";</script>
            <script type="text/javascript" src="http://cbjs.baidu.com/js/o.js"></script>
        </dd>
</dl><!--/rbox-->
<div class="rbox hotel">
        <div class="title"><span>酒店预订</span></div>
        <ul>
                <li>
                        <div class="pic"><img src="/images/hotel_01.jpg" /></div>
                        <div class="rt">
                                <span class="red">&yen;698</span>
                                <div class="h"><a target="_blank" href="#">北京京伦饭店</a></div>
                                <p style="line-height:1.5">北京京伦饭店汇聚当今世界的先进设施和中华民族的殷勤……</p>
                        </div>
                </li>
                <li>
                        <div class="pic"><img src="/images/hotel_01.jpg" /></div>
                        <div class="rt">
                                <span class="red">&yen;538</span>
                                <div class="h"><a target="_blank" href="#">北京二十一世纪饭店</a></div>
                                <p style="line-height:1.5">北京二十一世纪饭店地处燕莎商圈，步行可至燕莎友谊……</p>
                        </div>
                </li>
        </ul>
</div>
