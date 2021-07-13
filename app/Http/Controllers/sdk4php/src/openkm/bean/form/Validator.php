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

namespace openkm\bean\form;

/**
 * Validator
 *
 * @author sochoa
 */
class Validator {

    const OJB_CLASS = 'com.openkm.bean.form.Validator';
    const TYPE_REQUIRED = "req";
    const TYPE_ALPHABETIC = "alpha";
    const TYPE_DECIMAL = "dec";
    const TYPE_NUMERIC = "num";
    const TYPE_EMAIL = "email";
    const TYPE_URL = "url";
    const TYPE_MAXLENGTH = "maxlen";
    const TYPE_MINLENGTH = "minlen";
    const TYPE_LESSTHAN = "lt";
    const TYPE_GREATERTHAN = "gt";
    const TYPE_MINIMUN = "min";
    const TYPE_MAXIMUN = "max";
    const TYPE_REGEXP = "regexp";

    private $type = '';
    private $parameter = '';

    function __construct() {
        
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getParameter() {
        return $this->parameter;
    }

    public function setParameter($parameter) {
        $this->parameter = $parameter;
    }

}
?>

