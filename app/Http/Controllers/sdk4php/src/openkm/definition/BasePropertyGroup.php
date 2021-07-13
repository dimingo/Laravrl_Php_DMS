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
 * BasePropertyGroup
 * 
 * @author sochoa
 */
interface BasePropertyGroup {

    public function addGroup($nodeId, $grpName);

    public function removeGroup($nodeId, $grpName);

    public function getGroups($nodeId);

    public function getAllGroups();

    public function getPropertyGroupProperties($nodeId, $grpName);  
    
    public function getPropertyGroupForm($grpName);

    public function setPropertyGroupProperties($nodeId, $grpName, $formElements = []);

    public function setPropertyGroupPropertiesSimple($nodeId, $grpName, $properties = []);

    public function hasGroup($nodeId, $grpName);
    
}

?>
