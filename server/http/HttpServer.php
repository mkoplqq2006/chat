<?php

namespace server\http;

use common\lib\exception\FileNotExistException;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;
use common\interfaces\ServerInterface;
use App;

class HttpServer implements ServerInterface
{
    /**
     * @return mixed|void
     * @throws FileNotExistException
     */
    public function run()
    {
        $server = new Server(SERVER_HOST, HTTP_SERVER_PORT);
              $configFile = BASE_ROOT . "/server/http/config/server.php";
        if(is_file($configFile)){
            $config = require BASE_ROOT . "/server/http/config/server.php";
        }else {
            throw new FileNotExistException("server config file");
        }

        $server->set($config);
        $server->on('request', function (Request $request, Response $response) {
            try {
                App::$DI->router->dispatch($request, $response);
            }catch (\Exception $e){
                $response->status($e->getCode());
                if(DEBUG){
                    $response->end($e->getMessage());
                }else{
                    $response->end();
                }
            }
        });
        App::notice("HttpServer now is running on 127.0.0.1:" . HTTP_SERVER_PORT);
        $server->start();
    }
}