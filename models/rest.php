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
    header('content-type: application/json; charset=utf-8');
    $data = json_encode($_SERVER['REMOTE_ADDR']);
    $callback = isset($_GET['callback']) ? $_GET['callback'] : 'callback';
    echo $callback . '(' . $data . ');';
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