<?php include ("init.php");?>

<?php 

print (exec('whoami')).'<br>';
print (passthru('./fileserver.sh')).'<br>';

list_autotext ('28032020.txt');

?>