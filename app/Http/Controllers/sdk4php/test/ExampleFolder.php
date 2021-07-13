<?php

include '../src/openkm/OpenKM.php';

ini_set('display_errors', true);
error_reporting(E_ALL);

use openkm\OKMWebservices;
use openkm\OKMWebServicesFactory;
use openkm\OpenKM;
use openkm\bean\Folder;

class ExampleFolder {

    const HOST = "http://localhost:8080/OpenKM/";
    const USER = "okmAdmin";
    const PASSWORD = "admin";

    private $ws;

    public function __construct() {
        $this->ws = OKMWebServicesFactory::build(self::HOST, self::USER, self::PASSWORD);
    }

    public function testCreateFolder() {
        try {
            $fld = new Folder();
            $fld->setPath("/okm:root/SDK4PHP/test");
            $folder = $this->ws->createFolder($fld);
            var_dump($folder);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testCreateFolderSimple() {
        try {
            $folder = $this->ws->createFolderSimple("/okm:root/SDK4PHP/test");
            var_dump($folder);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetFolderProperties() {
        try {
            $folder = $this->ws->getFolderProperties("/okm:root/SDK4PHP/test");
            var_dump($folder);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testDeleteFolder() {
        try {
            $this->ws->deleteFolder("/okm:root/SDK4PHP/test");
            echo 'delete folder';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testRenameFolder() {
        try {
            // Exists folder /okm:root/SDK4PHP/test
            $this->ws->renameFolder("/okm:root/SDK4PHP/test", "renamedFolder");
            // Folder has renamed to /okm:root/SDK4PHP/renamedFolder
            echo 'rename Folder';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testMoveFolder() {
        try {
            // Exists folder /okm:root/SDK4PHP/test
            $this->ws->moveFolder("/okm:root/SDK4PHP/test", "/okm:root/SDK4PHP/tmp");
            // Folder has moved to /okm:root/SDK4PHP/tmp/test
            echo 'move Folder';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetFolderChildren() {
        try {
            $folders = $this->ws->getFolderChildren("/okm:root/SDK4PHP");
            foreach ($folders as $folder) {
                var_dump($folder);
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testIsValidFolder() {
        try {
            // Return false
            var_dump($this->ws->isValidFolder("/okm:root/SDK4PHP/logo.png"));
            // Return true
            var_dump($this->ws->isValidFolder("/okm:root/SDK4PHP"));
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetFolderPath() {
        try {
            var_dump($this->ws->getFolderPath("e72e7f16-1496-497f-bc64-a352eb39b1e9"));
        } catch (Exception $e) {
            var_dump($e);
        }
    }

}

$openkm = new OpenKM(); //autoload
$exampleFolder = new ExampleFolder();
$exampleFolder->testGetFolderPath();
?>