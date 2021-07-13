<?php

include '../src/openkm/OpenKM.php';

use openkm\OKMWebServicesFactory;
use openkm\OpenKM;

class ExampleProperty {

    const HOST = "http://localhost:8080/OpenKM/";
    const USER = "okmAdmin";
    const PASSWORD = "admin";

    private $ws;

    public function __construct() {
        $this->ws = OKMWebServicesFactory::build(self::HOST, self::USER, self::PASSWORD);
    }

    public function testAddCategory() {
        try {
            $this->ws->addCategory("/okm:root/SDK4PHP/logo.png", "/okm:categories/test");
            echo 'add category';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testRemoveCategory() {
        try {
            $this->ws->removeCategory("/okm:root/SDK4PHP/logo.png", "/okm:categories/test");
            echo 'remove category';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testAddKeyword() {
        try {
            $this->ws->addKeyword("/okm:root/SDK4PHP/logo.png", "test");
            echo 'add keyword';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testRemoveKeyword() {
        try {
            $this->ws->removeKeyword("/okm:root/SDK4PHP/logo.png", "test");
            echo 'remove keyword';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testSetEncryption() {
        try {
            $this->ws->setEncryption("/okm:root/SDK4PHP/logo.png", "pharase");
            echo 'Set Encryption';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testUnsetEncryption() {
        try {
            $this->ws->unsetEncryption("/okm:root/SDK4PHP/logo.png");
            echo 'unset Encryption';
        } catch (Exception $e) {
            var_dump($e);
        }
    }
    
    public function testSetSigned() {
        try {
            $this->ws->setSigned("/okm:root/SDK4PHP/logo.png",true);
            echo 'set Signed';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

}

$openkm = new OpenKM(); //autoload
$exampleProperty = new ExampleProperty();
$exampleProperty->testRemoveKeyword();
?>