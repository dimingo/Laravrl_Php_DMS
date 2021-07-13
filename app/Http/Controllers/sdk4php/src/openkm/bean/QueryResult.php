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

use openkm\bean\Node;

/**
 * QueryResult
 *
 * @author sochoa
 */
class QueryResult {

    private $node;
    private $attachment;
    private $excerpt;
    private $score;

    public function getNode() {
        return $this->node;
    }

    public function setNode(Node $node) {
        $this->node = $node;
    }

    public function isAttachment() {
        return $this->attachment;
    }

    public function setAttachment($attachment) {
        $this->attachment = $attachment;
    }

    public function getExcerpt() {
        return $this->excerpt;
    }

    public function setExcerpt($excerpt) {
        $this->excerpt = $excerpt;
    }

    public function getScore() {
        return $this->score;
    }

    public function setScore($score) {
        $this->score = $score;
    }

    public function toString() {
        return "{node=" . $this->node . ", attachment=" . $this->attachment . ", excerpt=" . $this->excerpt . ", score=" . $this->score . "}";
    }

}

?>