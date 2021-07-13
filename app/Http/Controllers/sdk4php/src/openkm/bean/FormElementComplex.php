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
 * FormElementComplex
 *
 * @author pherrera
 */
class FormElementComplex {

    private $objClass;
    private $label;
    private $name;
    private $width;
    private $height;
    private $type;
    private $value;
    private $transition = "";
    private $readonly;
    private $options = [];
    private $validators = [];

    function __construct() {
        
    }

    public function getObjClass() {
        return $this->objClass;
    }

    public function setObjClass($objClass) {
        $this->objClass = $objClass;
    }

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

    public function getWidth() {
        return $this->width;
    }

    public function setWidth($width) {
        $this->width = $width;
    }

    public function getHeight() {
        return $this->height;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getTransition() {
        return $this->transition;
    }

    public function setTransition($transition) {
        $this->transition = $transition;
    }

    public function isReadonly() {
        return $this->readonly;
    }

    public function setReadonly($readonly) {
        $this->readonly = $readonly;
    }

    public function getOptions() {
        return $this->options;
    }

    public function setOptions($options) {
        $this->options = $options;
    }

    public function getValidators() {
        return $this->validators;
    }

    public function setValidators($validators) {
        $this->validators = $validators;
    }

    public function toString() {
        return "{" . "label=" . $this->label . ", name=" . $this->name . ", width=" . $this->width . ", height=" . $this->height . ", objClass=" . $this->objClass . ", type=" . $this->type . ", value=" . $this->value . ", readonly=" . $this->readonly . ", options=" . $this->options . ", validators=" . $this->validators . "}";
    }

}
