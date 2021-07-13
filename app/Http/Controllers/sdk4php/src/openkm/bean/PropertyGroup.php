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
 * PropertyGroup
 *
 * @author sochoa
 */
class PropertyGroup {

    const GROUP = 'okg';
    const GROUP_URI = 'http://www.openkm.org/group/1.0';
    const GROUP_PROPERTY = 'okp';
    const GROUP_PROPERTY_URI = 'http://www.openkm.org/group/property/1.0';

    private $label = "";
    private $name = "";
    private $visible = true;
    private $readonly = false;

    public function getLabel() {
        return $this->label;
    }

    public function setLabel($label) {
        $this->label = $label;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function isVisible() {
        return $this->visible;
    }

    public function setVisible($visible) {
        $this->visible = $visible;
    }

    public function isReadonly() {
        return $this->readonly;
    }

    public function setReadonly($readonly) {
        $this->readonly = $readonly;
    }

    public function toString() {
        return "{label=" . $this->label . ", name=" . $this->name . ", visible=" . $this->visible . ", readonly=" . $this->readonly . "}";
    }

}
?>

