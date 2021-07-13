<?php

include '../src/openkm/OpenKM.php';

ini_set('display_errors', true);
error_reporting(E_ALL);

use openkm\OKMWebServicesFactory;
use openkm\OpenKM;

class ExampleAuth {

    const HOST = "http://localhost:8080/OpenKM/";
    const USER = "okmAdmin";
    const PASSWORD = "admin";

    private $ws;

    public function __construct() {
        $this->ws = OKMWebServicesFactory::build(self::HOST, self::USER, self::PASSWORD);
    }

    public function testGetGrantedRoles() {
        try {
            $grantedRoles = $this->ws->getGrantedRoles('/okm:root/SDK4PHP');
            foreach ($grantedRoles as $role) {
                var_dump($role);
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetGrantedUsers() {
        try {
            $grantedUsers = $this->ws->getGrantedUsers('/okm:root/SDK4PHP');
            foreach ($grantedUsers as $user) {
                var_dump($user);
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetRoles() {
        try {
            $roles = $this->ws->getRoles();
            foreach ($roles as $role) {
                var_dump($role);
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetUsers() {
        try {
            $users = $this->ws->getUsers();
            foreach ($users as $user) {
                var_dump($user);
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGrantRole() {
        try {
            // Add ROLE_USER write grants at the node but not descendants
            $this->ws->grantRole("/okm:root/SDK4PHP", "ROLE_USER", \openkm\bean\Permission::ALL_GRANTS, false);

            // Add all ROLE_ADMIN grants to the node and descendants
            $this->ws->grantRole("/okm:root/SDK4PHP", "ROLE_ADMIN", \openkm\bean\Permission::ALL_GRANTS, true);

            echo 'grant Role';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGrantUser() {
        try {
            // Add sochoa write grants at the node but not descendants
            $this->ws->grantUser("/okm:root/SDK4PHP", "sochoa", \openkm\bean\Permission::ALL_GRANTS, false);

            // Add all okmAdmin grants at the node and descendants
            $this->ws->grantUser("/okm:root/SDK4PHP", "okmAdmin", \openkm\bean\Permission::ALL_GRANTS, true);

            echo 'grant User';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testRevokeRole() {
        try {
            // Remove ROLE_USER write grants at the node but not descendants
            $this->ws->revokeRole("/okm:root/SDK4PHP", "ROLE_USER", \openkm\bean\Permission::ALL_GRANTS, false);

            // Remove all ROLE_ADMIN grants to the node and descendants
            $this->ws->revokeRole("/okm:root/SDK4PHP", "ROLE_ADMIN", \openkm\bean\Permission::ALL_GRANTS, true);

            echo 'revoke Role';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testRevokeUser() {
        try {
            // Remove sochoa write grants at the node but not descendants
            $this->ws->revokeUser("/okm:root/SDK4PHP", "sochoa", \openkm\bean\Permission::ALL_GRANTS, false);

            // Remove all okmAdmin grants at the node and descendants
            $this->ws->revokeUser("/okm:root/SDK4PHP", "okmAdmin", \openkm\bean\Permission::ALL_GRANTS, true);
            echo 'revoke User';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetRolesByUser() {
        try {
            $roles = $this->ws->getRolesByUser('okmAdmin');
            foreach ($roles as $role) {
                var_dump($role);
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetUsersByRole() {
        try {
            $users = $this->ws->getUsersByRole('ROLE_ADMIN');
            foreach ($users as $user) {
                var_dump($user);
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetMail() {
        try {
            var_dump($this->ws->getMail('okmAdmin'));
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetName() {
        try {
            var_dump($this->ws->getName('okmAdmin'));
        } catch (Exception $e) {
            var_dump($e);
        }
    }

}

$openkm = new OpenKM(); //autoload
$exampleAuth = new ExampleAuth();
$exampleAuth->testGetName();
?>