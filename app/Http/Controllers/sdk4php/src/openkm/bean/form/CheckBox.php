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
 * Checkbox
 *
 * @author sochoa
 */
class CheckBox extends FormElement {

    const OJB_CLASS = 'com.openkm.bean.form.CheckBox';

    private $validators = [];
    private $value = false;
    private $data = '';
    private $readonly = false;

    function __construct() {
        
    }

    /**
     * getValidators
     * @return array
     */
    public function getValidators() {
        return $this->validators;
    }

    /**
     * isValue
     * @return bool
     */
    public function isValue() {
        return $this->value;
    }

    public function getData() {
        return $this->data;
    }

    /**
     * isReadonly
     * @return bool
     */
    public function isReadonly() {
        return $this->readonly;
    }

    public function setValidators($validators) {
        $this->validators = $validators;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function setReadonly($readonly) {
        $this->readonly = $readonly;
    }

}

?>