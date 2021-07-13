<?php

include '../src/openkm/OpenKM.php';

ini_set('display_errors', true);
error_reporting(E_ALL);

use openkm\OKMWebServicesFactory;
use openkm\OpenKM;

class ExamplePropertyGroup {

    const HOST = "http://localhost:8080/OpenKM/";
    const USER = "okmAdmin";
    const PASSWORD = "admin";

    private $ws;

    public function __construct() {
        $this->ws = OKMWebServicesFactory::build(self::HOST, self::USER, self::PASSWORD);
    }

    public function testAddGroup() {
        try {
            $this->ws->addGroup('/okm:root/SDK4PHP/logo.png', 'okg:consulting');
            echo 'Add Group';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testRemoveGroup() {
        try {
            $this->ws->removeGroup('/okm:root/SDK4PHP/logo.png', 'okg:consulting');
            echo 'Remove Group';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetGroups() {
        try {
            $propertyGroups = $this->ws->getGroups('/okm:root/SDK4PHP/logo.png');
            foreach ($propertyGroups as $propertyGroup) {
                var_dump($propertyGroup);
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetAllGroups() {
        try {
            $propertyGroups = $this->ws->getAllGroups();
            foreach ($propertyGroups as $propertyGroup) {
                var_dump($propertyGroup);
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetPropertyGroupProperties() {
        try {
            $formElements = $this->ws->getPropertyGroupProperties('/okm:root/SDK4PHP/logo.png', 'okg:consulting');
            foreach ($formElements as $formElement) {
                var_dump($formElement);
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetPropertyGroupForm() {
        try {
            $formElements = $this->ws->getPropertyGroupForm('okg:consulting');
            foreach ($formElements as $formElement) {
                var_dump($formElement);
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testSetPropertyGroupProperties() {
        try {
            // Same modification with only affected FormElement
            $formElements = [];
            $name = new \openkm\bean\form\Input();
            $name->setName("okp:consulting.name");
            $name->setValue("new value");
            $formElements[] = $name;
            $this->ws->setPropertyGroupProperties('/okm:root/SDK4PHP/logo.png', 'okg:consulting', $formElements);
            echo 'updated';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testSetPropertyGroupPropertiesSimple() {
        try {
            $properties = [];
            $properties["okp:consulting.name"] = "new value";
            $properties["okp:consulting.important"] = "true";

            $this->ws->setPropertyGroupPropertiesSimple('/okm:root/SDK4PHP/logo.png', 'okg:consulting', $properties);
            echo 'updated';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testHasGroup() {
        try {
            echo 'Have metadata group: ' . $this->ws->hasGroup('/okm:root/SDK4PHP/logo.png', 'okg:consulting');
        } catch (Exception $e) {
            var_dump($e);
        }
    }

}

$openkm = new OpenKM(); //autoload
$examplePropertyGroup = new ExamplePropertyGroup();
$examplePropertyGroup->testHasGroup();
?>