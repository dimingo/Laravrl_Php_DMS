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
 * Button
 *
 * @author sochoa
 */
class Button extends FormElement {

    const OJB_CLASS = 'com.openkm.bean.form.Button';
    
    private $transition = '';
    private $confirmation = '';
    private $style = 'Yes';
    private $validate = true;

    function __construct() {
        
    }

    public function getTransition() {
        return $this->transition;
    }

    public function getConfirmation() {
        return $this->confirmation;
    }

    public function getStyle() {
        return $this->style;
    }

    public function isValidate() {
        return $this->validate;
    }

    public function setTransition($transition) {
        $this->transition = $transition;
    }

    public function setConfirmation($confirmation) {
        $this->confirmation = $confirmation;
    }

    public function setStyle($style) {
        $this->style = $style;
    }

    public function setValidate($validate) {
        $this->validate = $validate;
    }

}

?>