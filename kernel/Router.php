<?php


namespace SimplePHPFramework\kernel;


require __DIR__ . "/../vendor/autoload.php";

class Router
{
    private array $requests = ["GET" => [], "POST" => [], "DELETE" => [], "HEAD" => [], "PUT" => [], "PATCH" => []];

    public function __construct()
    {
    }
    /**
     * Handle The get request
     * @param string $uri
     * @param array $fn
     * @return void
     */
    public function route(string $method, string $uri, array $fn): void
    {
        if (isset($this->requests[$method])) {
            if (isset($this->requests[$method][$uri])) {
                echo "Your not allowed to add the duplicate route in the one method<br/>Duplicate URI: $uri <br /> Method: GET";
                exit;
            } else {
                $this->requests[$method][$uri] = $fn;
            }
        } else {
            echo "$method is not supported";
            exit;
        }
    }

    /**
     * Handle the Get routing
     * @return void
     */
    private function handleRequest(string $method): bool
    {
        $uri = $_SERVER["PATH_INFO"] ?? '/';
        $routeFunction = $this->requests[$method][$uri];
        // Checking the URI exists or not
        if ($routeFunction) {
            // Verify the controller
            if (!$this->checkUriFunc($routeFunction)) {
                // If the controller does not exists echo the error
                echo "The controller of this route does not found";
            } else {
                // Run the controller
                call_user_func($routeFunction);
                return true;
            }
        } else {
            // if page not found echo the error
            echo "Page not found";
            exit;
        }
    }

    /**
     * Start the Router
     * @return void
     */
    public function start(): void
    {
        $this->handleRequest($_SERVER["REQUEST_METHOD"]);
    }

    /**
     * Check the uri function exists
     * @param $func
     * @return bool
     */
    private function checkUriFunc($func): bool
    {
        $isFuncValid = is_object($func[0]);
        return $isFuncValid;
    }
}
