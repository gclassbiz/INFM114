<?php
set_time_limit(0);
 
function getmicrotime() {
   list($usec, $sec) = explode(" ",microtime());
   return ((float)$usec + (float)$sec);
} 
 
$time_start = getmicrotime();

 
//define algorithm of hash
define('HASH_ALGO', 'md5');
define('DEBUG', false);
 
//From - TO password
define('MIN_LENGTH', 1);
define('MAX_LENGTH', 4);
 
$charset = 'abcdef0123456789';
$charset = 'abcdefghijklmnopqrstuvwxyz';
$charset .= '0123456789';
$charset .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charset .= '~`!@#$%^&*()-_\/\'";:,.+=<>? ';
$str_length = strlen($charset);
 
 
if ($_SERVER["argc"] < 2) {
  echo "Usage: cracker.php <hash>\n";
  exit;
}else{
  $hash_password = $_SERVER["argv"][1];
}

//Overload
$hash_password = md5('#41');
 
function pwd_check($password)
{
        global $hash_password, $time_start, $plain;     
		if(DEBUG == true) {
			echo "Trying ".$password.PHP_EOL;
		}
        if (hash(HASH_ALGO, $password) == $hash_password) {
			echo "\n\n" . "Password found! Result: ".$hash_password.":".$password . "\n\n";
			$time_end = getmicrotime();
			$time = $time_end - $time_start; 
			echo "Found in " . $time . " seconds\n";
			exit;
        }
}
 
 
function doRecurse($width, $position, $current_string)
{
        global $charset, $str_length;
 
        for ($i = 0; $i < $str_length; ++$i) {
                if ($position  < $width - 1) {
                        doRecurse($width, $position + 1, $current_string . $charset[$i]);
                }
                pwd_check($current_string . $charset[$i]);
        }
}
 
echo "Target: " . $hash_password. "\n";
for ($i = MIN_LENGTH; $i <= MAX_LENGTH; $i++) {
        echo "\n" . "Bruting for length: " .$i." chars";
        $time_check = getmicrotime();
        $time = number_format($time_check - $time_start,2);
		$howmany=$time*$str_length;
        echo "\n" . "Runtime: " . $time . " seconds\nNext would take around: ".$howmany."sec.\n";
        doRecurse($i, 0, '');
}
 
echo "No password found\r\n";
?>
