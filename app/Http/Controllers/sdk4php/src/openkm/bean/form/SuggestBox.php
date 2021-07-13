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
 * SuggestBox
 *
 * @author sochoa
 */
class SuggestBox extends FormElement {

    const OJB_CLASS = 'com.openkm.bean.form.SuggestBox';
    
    private $validators = [];
    private $value = '';
    private $data = '';    
    private $readonly = false;
    private $table = '';
    private $dialogTitle = '';
    private $filterQuery = '';
    private $valueQuery = '';
    private $filterMinLen = 0;

    function __construct() {
        
    }
    
    public function getValidators() {
        return $this->validators;
    }

    public function getValue() {
        return $this->value;
    }

    public function getData() {
        return $this->data;
    }

    public function isReadonly() {
        return $this->readonly;
    }

    public function getTable() {
        return $this->table;
    }

    public function getDialogTitle() {
        return $this->dialogTitle;
    }

    public function getFilterQuery() {
        return $this->filterQuery;
    }

    public function getValueQuery() {
        return $this->valueQuery;
    }

    public function getFilterMinLen() {
        return $this->filterMinLen;
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

    public function setTable($table) {
        $this->table = $table;
    }

    public function setDialogTitle($dialogTitle) {
        $this->dialogTitle = $dialogTitle;
    }

    public function setFilterQuery($filterQuery) {
        $this->filterQuery = $filterQuery;
    }

    public function setValueQuery($valueQuery) {
        $this->valueQuery = $valueQuery;
    }

    public function setFilterMinLen($filterMinLen) {
        $this->filterMinLen = $filterMinLen;
    }


}
?>

