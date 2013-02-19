<?

class RestService {

  private $supportedMethods;

  public function __construct($supportedMethods) {
    $this->supportedMethods = $supportedMethods;
  }

  public function handleRawRequest() {
    $url = $this->getFullUrl($_SERVER);
    $method = $_SERVER['REQUEST_METHOD'];
    switch ($method) {
      case 'GET':
      case 'HEAD':
        $arguments = $_GET;
        break;
      case 'POST':
        $arguments = $_POST;
        break;
      case 'PUT':
      case 'DELETE':
        parse_str(file_get_contents('php://input'), $arguments);
        break;
    }
    $accept = $_SERVER['HTTP_ACCEPT'];
    $this->handleRequest($url, $method, $arguments, $accept);
  }

  protected function getFullUrl($server) {
    $protocol = 'http';
    $location = $server['REQUEST_URI'];
    if ($server['QUERY_STRING']) {
      $location = substr($location, 0, strrpos($location, $server['QUERY_STRING']) - 1);
    }
    return $protocol.'://'.$server['HTTP_HOST'].$location;
  }

  public function handleRequest($url, $method, $arguments, $accept) {
    switch($method) {
      case 'GET':
        $this->performGet($url, $arguments, $accept);
        break;
      case 'HEAD':
        $this->performHead($url, $arguments, $accept);
        break;
      case 'POST':
        $this->performPost($url, $arguments, $accept);
        break;
      case 'PUT':
        $this->performPut($url, $arguments, $accept);
        break;
      case 'DELETE':
        $this->performDelete($url, $arguments, $accept);
        break;
      default:
        /* 501 (Not Implemented) for any unknown methods */
        header('Allow: ' . $this->supportedMethods, true, 501);
    }
  }

  protected function methodNotAllowedResponse() {
    /* 405 (Method Not Allowed) */
    header('Allow: ' . $this->supportedMethods, true, 405);
  }

  public function performGet($url, $arguments, $accept) {
    //get callback or use default
    $callback = isset($_GET['callback']) ? $_GET['callback'] : 'callback';

    //prepare response
    $data = Array(
      'ip' => $_SERVER['REMOTE_ADDR']
    );

    //json
    header('content-type: application/json; charset=utf-8');

    //do not cache
    header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
    header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
    header( 'Cache-Control: no-store, no-cache, must-revalidate' );
    header( 'Cache-Control: post-check=0, pre-check=0', false );
    header( 'Pragma: no-cache' );

    //respond
    echo $callback . '(' . json_encode($data) . ');';
  }

  public function performHead($url, $arguments, $accept) {
    $this->methodNotAllowedResponse();
  }

  public function performPost($url, $arguments, $accept) {
    $this->methodNotAllowedResponse();
  }

  public function performPut($url, $arguments, $accept) {
    $this->methodNotAllowedResponse();
  }

  public function performDelete($url, $arguments, $accept) {
    $this->methodNotAllowedResponse();
  }

}
?>