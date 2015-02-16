<?php
date_default_timezone_set('America/Los_Angeles');
echo "<br>";
echo "Timezone:" . date_default_timezone_get();
echo "<br>";
 	
echo "date:" . date("Y-m-d H:i:s");
echo "<br>";
echo "time:" . time();
echo "<br>";
$at = mktime(10,30,20, date("n"), date("j"), date("Y"));
echo "mktime:" . mktime(10,30,20, date("n"), date("j"), date("Y"));
echo "<br>";

echo "<br>";
date_default_timezone_set('Asia/Shanghai');

echo "date:" . date("Y-m-d H:i:s");
echo "<br>";
echo "time:" . time();
echo "<br>";
$et = mktime(10,30,20, date("n"), date("j"), date("Y"));
echo "mktime:" . mktime(10,30,20, date("n"), date("j"), date("Y"));
echo "<br>";

echo "diff:" . ($at - $et)/3600;

echo "<br>";
echo "<br>";
echo "Timezone:" . date_default_timezone_get();
echo "<br>";
?>
