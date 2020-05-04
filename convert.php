<?php
/*
converts [amazon_link ...] shortcodes to [amazon box="" /]
dumps a backup to csv file
100 per run to reduce db load
WARNING: Run from console via 
	#php -f convert.php
2020 by Yves Jeanrenaud
*/
//FILL IN YOURS
$sql_username ="";
$sql_password ="";
$sql_hostname ="";
$sql_db_name ="";

error_reporting(E_ALL);

$mysqli = mysqli_connect($sql_hostname, $sql_username, $sql_password); 
if (!$mysqli) {
    echo "Error: connection failed to MySQL server." . PHP_EOL;
    echo "Debug error no: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debug error msg: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

echo "Success: connection to MySQL server established!" . PHP_EOL;
echo "Host infos: " . mysqli_get_host_info($mysqli) . "<br>".PHP_EOL;

$mysqli->query("USE `".$sql_db_name."`;");
print_r($mysqli->error_list); //debug
$sql = "SELECT * FROM `wp_postmeta` WHERE `meta_value` LIKE '%[amazon_link%'  ORDER BY `meta_id` DESC LIMIT 0,100";  //0-99, next is 100,100 for 100-199

$fp = fopen('yjsqlbackup.csv', 'a+') or die($this->error_list);

$mysqli->query($sql);

print_r($mysqli->error_list);
echo "<hr>";
function compute_replacement1($groups) {
	return '[amazon box="'.$groups[2].'" /]';
}

if ($result = $mysqli->query($sql) or die ($mysqli->error_list)){
print_r($result);
fwrite($fp,"meta_id;meta_value;meta_value_new\r\n") or die ("error writting!");
	//echo "<pre>";
    while ($row = $result->fetch_row()) {
		print_r($row[0].PHP_EOL);
		
		//print_r($row[3].PHP_EOL);
		$subject=$row[3];

		$regresult = preg_replace_callback('/(\[amazon_link asins=\')([A-Z0-9]{10}(?:,[A-Z0-9]{10})*)(\' [^]]+)\]/', 'compute_replacement1', $subject);
		//echo "#############<br/>".PHP_EOL;
		//print_r("neu: ".$regresult);
		
		$statement = $mysqli->prepare("UPDATE `wp_postmeta` SET `meta_value` = ? WHERE `meta_id` = ?") or die ($statement->error_list);
		//* comment this line to set on fire
		$statement->bind_param("ss",$regresult, $row[0]) or die ($statement->error_list);
		$statement->execute() or die ($statement->error_list); //*/
	fwrite($fp,$row[0].";".str_replace("\r\n",'\\r\\n',$row[3])."\';".str_replace("\r\n",'\\r\\n',$regresult)."\'\r\n") or die ("error writting!");
    }
	/* free result set */
	$statement->close();
    $result->close();
	fclose($fp);
}

mysqli_close($mysqli);
?>
