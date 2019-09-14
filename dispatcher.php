<?php
//////////////////////////////////////////////////////////////////////
// dispatcher.php
//
// @usage
//
//     1. Load this file.
//
//         --------------------------------------------------
//         require_once('dispatcher.php');
//         --------------------------------------------------
//
//     2. Initialize Dispatcher class.
//
//         --------------------------------------------------
//         $dispatcher = new Dispatcher();
//         --------------------------------------------------
//
//     3. Set up HTTP routing.
//
//         --------------------------------------------------
//         // When HTTP request is GET /, index function will be called.
//         $dispatcher->SetRoute($dispatcher::REQ_METHOD_GET, '/', 'index');
//         
//         // When HTTP request is POST /login, login function in A class will be called.
//         $a = new A();
//         $dispatcher->SetRoute($dispatcher::REQ_METHOD_POST, '/login', array($a, 'login'));
//         
//         // When HTTP request is GET /account, account static function in B class will be called.
//         $dispatcher->SetRoute($dispatcher::REQ_METHOD_GET, '/account', 'B::account');
//         --------------------------------------------------
//
//     4. Handle HTTP request.
//
//         --------------------------------------------------
//         $reqUrl = $_SERVER['REQUEST_URI'];
//         $reqMethod = $_SERVER['REQUEST_METHOD'];
//         $handler = $dispatcher->Handle($reqMethod, $reqUrl);
//         if(is_null($handler)) {
//             // 404 Not Found
//         } else {
//             $handler();
//         }
//         --------------------------------------------------
//
//
// MIT License
//
// Copyright (c) 2019 noknow.info
//
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in all
// copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
// INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 
// PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
// HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
// OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
// OR THE USE OR OTHER DEALINGS IN THE SOFTW//ARE.
//////////////////////////////////////////////////////////////////////

namespace noknow\lib\http\dispatcher;

class Dispatcher {

    //////////////////////////////////////////////////////////////////////
    // Properties
    //////////////////////////////////////////////////////////////////////
    const REQ_METHOD_GET = 'GET';
    const REQ_METHOD_POST = 'POST';
    const REQ_METHOD_PUT = 'PUT';
    const REQ_METHOD_DELETE = 'DELETE';
    private $routes = [
        self::REQ_METHOD_GET => [],
        self::REQ_METHOD_POST => [],
        self::REQ_METHOD_PUT => [],
        self::REQ_METHOD_DELETE => [],
    ];
    private $version;


    //////////////////////////////////////////////////////////////////////
    // Constructor
    //////////////////////////////////////////////////////////////////////
    public function __construct() {
        $this->version = phpversion();
    }


    //////////////////////////////////////////////////////////////////////
    // Set route information.
    //////////////////////////////////////////////////////////////////////
    public function SetRoute(string $reqMethod, string $path, callable $handler): void {
        $this->routes[$reqMethod][$path] = $handler;
    }


    //////////////////////////////////////////////////////////////////////
    // Handle a HTTP request.
    //////////////////////////////////////////////////////////////////////
    public function Handle(string $reqMethod, string $reqUrl): ?callable {
        if(!($reqMethod === self::REQ_METHOD_GET ||
                $reqMethod === self::REQ_METHOD_POST ||
                $reqMethod === self::REQ_METHOD_PUT ||
                $reqMethod === self::REQ_METHOD_DELETE)) {
            return null;
        }
        foreach($this->routes[$reqMethod] as $path => $handler) {
            if($reqUrl === $path) {
                return $handler;
            }
        }
        return null;
    }

}
