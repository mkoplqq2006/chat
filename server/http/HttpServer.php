<?php

namespace server\http;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;
use common\interfaces\ServerInterface;
use App;

class HttpServer implements ServerInterface
{
    public function run()
    {
        $server = new Server('127.0.0.1', HTTP_SERVER_PORT);
        $config = require BASE_ROOT . "/server/http/config/server.php";
        $server->set($config);
        $server->on('request', function (Request $request, Response $response) {
            try {
                //简单请求设置跨域
                $response->header("Access-Control-Allow-Origin","*");
                App::$DI->router->dispatch($request, $response);
            }catch (\Exception $e){
                $response->status(500);
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