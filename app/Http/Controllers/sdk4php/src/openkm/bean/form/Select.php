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
 * Select
 *
 * @author sochoa
 */
class Select extends FormElement {

    const OJB_CLASS = 'com.openkm.bean.form.Select';
    const TYPE_SIMPLE = "simple";
    const TYPE_MULTIPLE = "multiple";

    private $validators = [];
    private $options = [];
    private $type = self::TYPE_SIMPLE;
    private $value = '';
    private $data = '';
    private $optionsData = '';
    private $table = '';
    private $optionsQuery = '';
    private $suggestion = '';
    private $className = '';
    private $readonly = false;

    function __construct() {
        $this->width = '150px';
    }

    public function getTable() {
        return $this->table;
    }

    public function setTable($table) {
        $this->table = $table;
    }

    public function getValidators() {
        return $this->validators;
    }

    public function getOptions() {
        return $this->options;
    }

    public function getType() {
        return $this->type;
    }

    public function getValue() {
        return $this->value;
    }

    public function getData() {
        return $this->data;
    }

    public function getOptionsData() {
        return $this->optionsData;
    }

    public function getOptionsQuery() {
        return $this->optionsQuery;
    }

    public function getSuggestion() {
        return $this->suggestion;
    }

    public function getClassName() {
        return $this->className;
    }

    public function isReadonly() {
        return $this->readonly;
    }

    public function setValidators($validators) {
        $this->validators = $validators;
    }

    public function setOptions($options) {
        $this->options = $options;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function setOptionsData($optionsData) {
        $this->optionsData = $optionsData;
    }

    public function setOptionsQuery($optionsQuery) {
        $this->optionsQuery = $optionsQuery;
    }

    public function setSuggestion($suggestion) {
        $this->suggestion = $suggestion;
    }

    public function setClassName($className) {
        $this->className = $className;
    }

    public function setReadonly($readonly) {
        $this->readonly = $readonly;
    }

}
?>

