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
 * AppVersion 
 *
 * @author sochoa
 */
class AppVersion {

    const EXTENSION_PRO = "Professional";
    const EXTENSION_COM = "Community";

    private $major = "0";
    private $minor = "0";
    private $maintenance = "0";
    private $build = "0";
    private $extension;

    function __construct() {
        $this->extension = self::EXTENSION_PRO;
    }

    public function getMajor() {
        return $this->major;
    }

    public function setMajor($major) {
        $this->major = $major;
    }

    public function getMinor() {
        return $this->minor;
    }

    public function setMinor($minor) {
        $this->minor = $minor;
    }

    public function getMaintenance() {
        return $this->maintenance;
    }

    public function setMaintenance($maintenance) {
        $this->maintenance = $maintenance;
    }

    public function getBuild() {
        return $this->build;
    }

    public function setBuild($build) {
        $this->build = $build;
    }

    public function getExtension() {
        return $this->extension;
    }

    public function setExtension($extension) {
        $this->extension = $extension;
    }

    public function getVersion() {
        return $this->major . "." . $this->minor . "." . $this->maintenance;
    }

    public function toString() {
        return $this->major . "." . $this->minor . "." . $this->maintenance . " (build: " . $this->build . ")";
    }

}
?>


