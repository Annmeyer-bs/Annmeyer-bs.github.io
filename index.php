<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Прогноз погод</title>
<link rel="stylesheet" href="main.css" type="text/css" charset="utf-8">
<meta name="decription" content="Prognoz pogod,prognoz,pogod">
<meta name="keywords" content="Prognoz pogod,prognoz,pogod">
<meta name="viewport" content="Prognoz pogod,prognoz,pogod">
<link href="1.ico" rel="shortcut icon" type="image/x-icon">
  <link rel="stylesheet" href="font-awesome.min.css">
</head>
<body>

 
<div id="wrapper">
<div id="content">
<header>
<div id="logo">
<a href="https://hannamyr.github.io/" title="Na glavnyu">
<img src="19.png" title="hannamyr.github.io" alt="hannamyr.github.io"><br>
<span>Прогноз погоды</span>
</a>
</div>
<div id="about">

<a href="" title=""></a>
</div>
<div id="reg_auth">
<a href="" title="">
<div id="btn">

</div>
</a>
</div>
</header>
  <nav>
    
 <?php
 $city = 'Zaporiz’ka Oblast’';
 $mode = 'xml'; 
 $units = 'metric'; 
 $lang = 'ru'; 
 $cnt = 5; 
 $appid = 'b603413be60f38c43d99fca02b6bfa15'; 
 $hoursplus = 4; 
 
 header('Content-Type: text/html; charset=utf-8'); 
// header("x-api-key: $appid");
 $url = //URL для запроса
  'http://api.openweathermap.org/data/2.5/forecast/daily?'.
  "appid=$appid&q=$city&mode=$mode&units=$units&cnt=$cnt&lang=$lang";
 $data = @file_get_contents ($url); 
 if ($data) {
  $xmldata = new SimplexmlElement($data); 
  $city= $xmldata->location->name;
  $latitude = round(xml_attribute($xmldata->location->location, 'latitude'),2);
  $longitude = round(xml_attribute($xmldata->location->location, 'longitude'),2);
  $sunrise = get_time_from_xml(xml_attribute($xmldata->sun, 'rise'),$hoursplus);
  $sunset = get_time_from_xml(xml_attribute($xmldata->sun, 'set'),$hoursplus); 
  echo "<div class=\"wr\">$city ($latitude;$longitude);<br>\n Восход: ".$sunrise." Закат: ".$sunset."<br>\n</div>";
  $w = Array('Вс','Пн','Вт','Ср','Чт','Пт','Сб'); 
  $winds = Array( //коды ветров
   'N'=>'С', 'NNW'=>'С/СЗ', 'NW'=>'СЗ', 'WNW'=>'З/СЗ',
   'W'=>'З', 'WSW'=>'З/ЮЗ', 'SW'=>'ЮЗ', 'SSW'=>'Ю/ЮЗ',
   'S'=>'Ю', 'SSE'=>'Ю/ЮВ', 'SE'=>'ЮВ', 'ESE'=>'В/ЮВ',
   'E'=>'В', 'ENE'=>'В/СВ', 'NE'=>'СВ', 'NNE'=>'С/СВ'
   );
  for ($i=0; $i<count($xmldata->forecast->time); $i++) { 
   $day = xml_attribute($xmldata->forecast->time[$i], 'day');
   $dt = date('d.m',strtotime($day));
   $wd = $w[date('w',strtotime($day))]; 
   echo "<div class=\"wrl\">$dt.', '.$wd.': '<br>\n</div>";  

   $min = round(xml_attribute($xmldata->forecast->time[$i]->temperature, 'min'),0);
   if ($min>0) $min = '+'.$min;
   $max = round(xml_attribute($xmldata->forecast->time[$i]->temperature, 'max'),0);
   if ($max>0) $max = '+'.$max;
   if ($min!=$max) $temperature = $min.'/'.$max;
   else  $temperature = $min;
   echo "<div style='font-size:18px; font-weight: bold;'> $temperature'&deg;'</div>"; 
   $name = xml_attribute($xmldata->forecast->time[$i]->symbol, 'name');
   echo "<div style='font-size:18px; font-weight: bold;'> $name</div>"; 
   $wind = xml_attribute($xmldata->forecast->time[$i]->windDirection, 'code');
   if (array_key_exists($wind,$winds)) 
  echo ' <font size="4">ветер:</font> '.$winds[$wind].', '.
     round(xml_attribute($xmldata->forecast->time[$i]->windSpeed, 'mps'),0).' м/с';
   $pressure = xml_attribute($xmldata->forecast->time[$i]->pressure, 'value');
   $pressure = round($pressure * 0.75006375541921,0);
   if ($pressure) echo ', <font size="4">давление:</font> '.$pressure.' мм';
   $humidity = round(xml_attribute($xmldata->forecast->time[$i]->humidity, 'value'),0);
   if ($humidity) echo ', <font size="4">влажность:</font> '.$humidity.'%';
   //$clouds = round(xml_attribute($xmldata->forecast->time[$i]->clouds, 'all'),0);
   //echo ', облачность '.$clouds.'%';
   echo "<br>\n";
  } //конец for по дням
/*
  //отладочная печать всего XML:
  echo '<pre>'; print_r($xmldata); echo '</pre>';
*/
 } //конец if ($data)
 else {
  echo "Сервис (временно?) недоступен";
 }

 function xml_attribute($object, $attribute) { 
  if (isset($object[$attribute])) return (string) $object[$attribute];
 }

 function get_time_from_xml($time,$correct) { 
  
  $correct = ($correct>=0?'+':'-').abs($correct);
  return date('H:i',strtotime($correct." hours", strtotime($time)));
 }

?>


  <?php
if(isset($_GET['submit'])) {
$data = "data.json";
$json_string = json_encode($GET, JSON_PRETTY_PRINT);
file_put_contents($data, $json_string, FILE_APPEND);
};
?>

  </nav>
</div>
 
<footer>
<div id="site_name">
<span>Прогноз погоды</span> 
</div>
<div id="clear"></div>
<div id="footer_menu">


<a href="" title=""></a>
</div>


<div id="rights">
<a href="">  &copy; <?=date ('Y')?></a>
</div>
  <div id="social">

<a href="https://www.facebook.com/ann.rozhestvennay" title="Facebook"><i class="fa fa-facebook-official" aria-hidden="true"></i>
</a>

  
</footer>
</div>
</body>
</html>
