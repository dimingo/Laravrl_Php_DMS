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

namespace App\Http\Controllers\sdk4php\src\openkm\bean;

/**
 * Permission
 *
 * @author sochoa
 */
class Permission {

    const NONE = 0;     // 00000
    const READ = 1;     // 00001
    const WRITE = 2;    // 00010
    const DELETE = 4;   // 00100
    const SECURITY = 8; // 01000
    const MOVE = 16;    // 10000
    // Extended security
    const DOWNLOAD = 1024;
    const START_WORKFLOW = 2048;
    const COMPACT_HISTORY = 4096;
    const PROPERTY_GROUP = 8192;
    // All grants
    const ALL_GRANTS = 15 ;

}

?>