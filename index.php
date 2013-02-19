<?
error_reporting(E_ALL);

$pages = ['home','docs','contact'];
$path = explode('/', $_SERVER['PATH_INFO']);
$route = $path[1];

if (in_array($route, $pages)) {
	$page = $path[1];
	require('./templates/index.html');
}

else if ($route === 'REST') {
	$rest = new RestService('GET');
	$rest->handleRawRequest($_SERVER, $_GET, $_POST);
}
?>
