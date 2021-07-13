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

namespace App\Http\Controllers\sdk4php\src\openkm\util;

use App\Http\Controllers\sdk4php\src\openkm\bean\FormElementComplex;
use App\Http\Controllers\sdk4php\src\openkm\bean\form\FormElement;
use App\Http\Controllers\sdk4php\src\openkm\bean\form\Button;
use App\Http\Controllers\sdk4php\src\openkm\bean\form\CheckBox;
use App\Http\Controllers\sdk4php\src\openkm\bean\form\Input;
use App\Http\Controllers\sdk4php\src\openkm\bean\form\Option;
use App\Http\Controllers\sdk4php\src\openkm\bean\form\Select;
use App\Http\Controllers\sdk4php\src\openkm\bean\form\SuggestBox;
use App\Http\Controllers\sdk4php\src\openkm\bean\form\Text;
use App\Http\Controllers\sdk4php\src\openkm\bean\form\TextArea;

/**
 * BeanHelper
 *
 * @author sochoa
 */
class BeanHelper {

    /**
     * Conversion from FormElement to FormElementComplex
     */
    public static function copyToFormElementComplex($fe) {
        $fec = new FormElementComplex();
        $fec->setHeight($fe->getHeight());
        $fec->setWidth($fe->getWidth());
        $fec->setLabel($fe->getLabel());
        $fec->setName($fe->getName());
        if ($fe instanceof Input) {
            $input = $fe;
            $fec->setType($input->getType());
            $fec->setValue($input->getValue());
            $fec->setValidators($input->getValidators());
            if ($input->isReadonly()) {
                $fec->setReadonly('true');
            } else {
                $fec->setReadonly('false');
            }
            $fec->setObjClass(Input::OJB_CLASS);
        } else if ($fe instanceof SuggestBox) {
            $suggestBox = $fe;
            $fec->setValue($suggestBox->getValue());
            $fec->setValidators($suggestBox->getValidators());
            if ($suggestBox->isReadonly()) {
                $fec->setReadonly('true');
            } else {
                $fec->setReadonly('false');
            }
            $fec->setObjClass(SuggestBox::OJB_CLASS);
        } else if ($fe instanceof TextArea) {
            $textArea = $fe;
            $fec->setValue($textArea->getValue());
            $fec->setValidators($textArea->getValidators());
            if ($textArea->isReadonly()) {
                $fec->setReadonly('true');
            } else {
                $fec->setReadonly('false');
            }
            $fec->setObjClass(TextArea::OJB_CLASS);
        } else if ($fe instanceof CheckBox) {
            $checkBox = $fe;
            if ($checkBox->isValue()) {
                $fec->setValue('true');
            } else {
                $fec->setValue('false');
            }
            $fec->setValidators($checkBox->getValidators());
            if ($checkBox->isReadonly()) {
                $fec->setReadonly('true');
            } else {
                $fec->setReadonly('false');
            }
            $fec->setObjClass(CheckBox::OJB_CLASS);
        } else if ($fe instanceof Select) {
            $select = $fe;
            $fec->setType($select->getType());
            $options = [];
            foreach ($select->getOptions() as $o) {
                $option = new Option();
                $option->setLabel($o->getLabel());
                $option->setValue($o->getValue());
                if ($o->isSelected()) {
                    $option->setSelected('true');
                } else {
                    $option->setSelected('false');
                }
                $options[] = $option;
            }
            $fec->setOptions($options);
            $fec->setValidators($select->getValidators());
            if ($select->isReadonly()) {
                $fec->setReadonly('true');
            } else {
                $fec->setReadonly('false');
            }
            $fec->setObjClass(Select::OJB_CLASS);
        } else if ($fe instanceof Button) {
            $button = $fe;
            $fec->setTransition($button->getTransition());
            $fec->setObjClass(Button::OJB_CLASS);
        } else if ($fe instanceof Text) {
            $fec->setObjClass(Text::OJB_CLASS);
        }
        return $fec;
    }

    /**
     * Conversion from FormElementComplex to FormElement.
     */
    public static function copyToFormElement(FormElementComplex $fec) {         
        $fe = new FormElement();
        if (Input::OJB_CLASS == $fec->getObjClass()) {
            $fe = new Input();
            $fe->setValue($fec->getValue());
            $fe->setReadonly($fec->isReadonly());

            if ($fec->getType() != '') {
                $fe->setType($fec->getType());
            }
            $fe->setValidators($fec->getValidators());
        } else if (SuggestBox::OJB_CLASS == $fec->getObjClass()) {
            $fe = new SuggestBox();
            $fe->setValue($fec->getValue());
            $fe->setReadonly($fec->isReadonly());
            $fe->setValidators($fec->getValidators());
        } else if (TextArea::OJB_CLASS == $fec->getObjClass()) {
            $fe = new TextArea();
            $fe->setValue($fec->getValue());
            $fe->setReadonly($fec->isReadonly());
            $fe->setValidators($fec->getValidators());
        } else if (CheckBox::OJB_CLASS == $fec->getObjClass()) {
            $fe = new CheckBox();
            if ($fec->getValue() == 'true') {
                $fe->setValue(true);
            } else {
                $fe->setValue(false);
            }
            $fe->setReadonly($fec->isReadonly());
            $fe->setValidators($fec->getValidators());
        } else if (Select::OJB_CLASS == $fec->getObjClass()) {
            $fe = new Select();
            $fe->setOptions($fec->getOptions());
            $fe->setReadonly($fec->isReadonly());
            if ($fec->getType() != '') {
                $fe->setType($fec->getType());
            }
            $fe->setValidators($fec->getValidators());
        } else if (Button::OJB_CLASS == $fec->getObjClass()) {
            $fe = new Button();
            $fe->setTransition($fec->getTransition());
        }

        if ($fec->getHeight() != '') {
            $fe->setHeight($fec->getHeight());
        }

        if ($fec->getWidth() != '') {
            $fe->setWidth($fec->getWidth());
        }

        if ($fec->getLabel() != '') {
            $fe->setLabel($fec->getLabel());
        }

        if ($fec->getName() != '') {
            $fe->setName($fec->getName());
        }
        return $fe;
    }

}
