<?php

include '../src/openkm/OpenKM.php';

ini_set('display_errors', true);
error_reporting(E_ALL);

use openkm\OKMWebServicesFactory;
use openkm\OpenKM;
use openkm\bean\AppVersion;
use openkm\bean\SqlQueryResults;
use openkm\bean\SqlQueryResultColumns;

class ExampleRepository {

    const HOST = "http://localhost:8080/OpenKM/";
    const USER = "okmAdmin";
    const PASSWORD = "admin";

    private $ws;

    public function __construct() {
        $this->ws = OKMWebServicesFactory::build(self::HOST, self::USER, self::PASSWORD);
    }

    public function testGetRoolFolder() {
        try {
            $folders = $this->ws->getRootFolder();
            var_dump($folders);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetTrashFolder() {
        try {
            $folders = $this->ws->getTrashFolder();
            var_dump($folders);
        } catch (Exception $e) {
            var_dump($e);
        }
    }
    
    public function testGetTemplatesFolder() {
        try {
            $folders = $this->ws->getTemplatesFolder();
            var_dump($folders);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetPersonalFolder() {
        try {
            $folders = $this->ws->getPersonalFolder();
            var_dump($folders);
        } catch (Exception $e) {
            var_dump($e);
        }
    }
    
    public function testGetMailFolder() {
        try {
            $folders = $this->ws->getMailFolder();
            var_dump($folders);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetThesaurusFolder() {
        try {
            $folders = $this->ws->getThesaurusFolder();
            var_dump($folders);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetCategoriesFolder() {
        try {
            $folders = $this->ws->getCategoriesFolder();
            var_dump($folders);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testPurgeTrash() {
        try {
            $this->ws->purgeTrash();
            echo 'correct';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetUpdateMessage() {
        try {
            var_dump($this->ws->getUpdateMessage());
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetRepositoryUuid() {
        try {
            var_dump($this->ws->getRepositoryUuid());
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testHasNode() {
        try {
            echo 'Exists node: ' . $this->ws->hasNode('dfdabdb0f-7ff8-4832-9e43-8bc96fc1c9a5');
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetNodePath() {
        try {
            var_dump($this->ws->getNodePath('adabdb0f-7ff8-4832-9e43-8bc96fc1c9a5'));
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetNodeUuid() {
        try {
            var_dump($this->ws->getNodeUuid('/okm:root/SDK4PHP/logo.png'));
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetAppVersion() {
        try {
            $appVersion = $this->ws->getAppVersion();
            var_dump($appVersion);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testExecuteScript() {
        try {
            $fileName = dirname(__FILE__) . '/files/test.bsh';
            $scriptExecutionResult = new \openkm\bean\ScriptExecutionResult();
            $scriptExecutionResult = $this->ws->executeScript(file_get_contents($fileName));
            var_dump($scriptExecutionResult->getResult());
            var_dump($scriptExecutionResult->getStdout());
            if ($scriptExecutionResult->getStderr() != '') {
                echo "Error happened";
                var_dump($scriptExecutionResult->getStderr());
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testExecuteSqlQuery() {
        try {
            $fileName = dirname(__FILE__) . '/files/test.sql';
            $sqlQueryResults = new SqlQueryResults();
            $sqlQueryResults = $this->ws->executeSqlQuery(file_get_contents($fileName));
            foreach ($sqlQueryResults->getResults() as $sqlQueryResultColumns) {
                $columns = $sqlQueryResultColumns->getColumns();
                var_dump('uuid: ' . $columns[0] . ' name: ' . $columns[1]);
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testExecuteHqlQuery() {
        try {
            $fileName = dirname(__FILE__) . '/files/testhql.sql';
            $hqlQueryResults = new \openkm\bean\HqlQueryResults();
            $hqlQueryResults = $this->ws->executeHqlQuery(file_get_contents($fileName));
            foreach ($hqlQueryResults->getResults() as $hqlQueryResult) {
                var_dump($hqlQueryResult);
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

}

$openkm = new OpenKM(); //autoload
$exampleRepository = new ExampleRepository();
$exampleRepository->testHasNode();
?>