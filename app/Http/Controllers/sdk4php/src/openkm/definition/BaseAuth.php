<?php

/**
 * OpenKM, Open Knowledge Management System S.L.  (http://www.openkm.com)
 * Copyright (c) 2006-2018
 *
 * No bytes were intentionally harmed during the development of this application.
 *
 * This program is commercial software; you can use it under the terms of the
 * EULA - OpenKM SDK End User License Agreement as published by OpenKM Knowledge Management System S.L.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * EULA - OpenKM SDK End User License Agreement for more details:
 * http://docs.openkm.com/kcenter/view/licenses/eula-openkm-sdk-end-user-license-agreement.html
 */

namespace App\Http\Controllers\sdk4php\src\openkm\definition;

/**
 * BaseAuth
 * 
 * @author sochoa
 */
interface BaseAuth {
    
    public function getGrantedRoles($nodeId);

    public function getGrantedUsers($nodeId);

    public function getRoles();

    public function getUsers();

    public function grantRole($nodeId, $role, $permissions, $recursive);

    public function grantUser($nodeId, $user, $permissions, $recursive);

    public function revokeRole($nodeId, $role, $permissions, $recursive);

    public function revokeUser($nodeId, $user, $permissions, $recursive);

    public function getUsersByRole($role);

    public function getRolesByUser($user);

    public function getMail($user);

    public function getName($user);

}

?>
