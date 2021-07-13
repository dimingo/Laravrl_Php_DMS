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
 * ScriptExecutionResult
 *
 * @author sochoa
 */
class ScriptExecutionResult {
    
    private $result;
    private $stderr;
    private $stdout;
    
    public function getResult() {
        return $this->result;
    }

    public function getStderr() {
        return $this->stderr;
    }

    public function getStdout() {
        return $this->stdout;
    }

    public function setResult($result) {
        $this->result = $result;
    }

    public function setStderr($stderr) {
        $this->stderr = $stderr;
    }

    public function setStdout($stdout) {
        $this->stdout = $stdout;
    }


}

