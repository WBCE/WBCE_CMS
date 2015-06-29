//:Create a random oneliner on your page
//:Use: [[OneLiner]]. The file with the oneliner data is located in /modules/droplets/example/oneliners.txt
$line = file (dirname(__FILE__)."/example/oneliners.txt");shuffle($line);return $line[0]; 