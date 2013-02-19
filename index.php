<?
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$pages = ['home','docs','contact'];
$docs = ['bootstrap'];

$allowed = array_merge($pages,$docs);

$path = explode('/', $_SERVER['PATH_INFO']);
$route = $path[1];

/*route to the REST service*/
if ($route === 'REST') {
	require('./models/rest.php');
	$rest = new RestService('GET');
	$rest->handleRawRequest();
}
/*or render the website*/
else {
	$page = 'home';
	if (in_array($route, $allowed)) {
		$page = $path[1];
	}
	require('./templates/fragments/header.html');
	require('./templates/'.$page.'.html');
	require('./templates/fragments/footer.html');
}
?>
