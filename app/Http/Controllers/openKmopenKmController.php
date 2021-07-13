<?php

namespace App\Http\Controllers;

use App\Http\Controllers\sdk4php\src\openkm\OpenKM;



use App\Http\Controllers\sdk4php\src\openkm\OKMWebservices;

use App\Http\Controllers\sdk4php\src\openkm\bean\Folder;
use App\Http\Controllers\sdk4php\src\openkm\OKMWebServicesFactory;
use Exception;
use Illuminate\Http\Request;

@include '../src/openkm/OpenKM.php';

ini_set('display_errors', true);
error_reporting(E_ALL);

class openKmopenKmController extends Controller
{
    //contructor
    // public function __construct($host, $user, $password)
    // {
    //     $this->host = "http://192.168.1.193:8080/";
    //     $this->user = "okmAdmin";
    //     $this->password = "admin";v
    // }

    
    const HOST = "http://192.168.1.193:8080/";
    const USER = "okmAdmin";
    const PASSWORD = "admin";

    private $ws;

    public function __construct() {
        $this->ws = OKMWebServicesFactory::build(self::HOST, self::USER, self::PASSWORD);
    }

    public function testCreateFolderSimple() {
        try {
            $folder = $this->ws->createFolderSimple("/okm:root/SDK4PHP/test");
            var_dump($folder);
        } catch (Exception $e) {
            var_dump($e);
        }

    }
}

