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
 * ProposedQuerySent
 *
 * @author sochoa
 */
class ProposedQuerySent {

    private $id;
    private $from;
    private $to;
    private $user;
    private $comment;
    private $sentDate;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getFrom() {
        return $this->from;
    }

    public function setFrom($from) {
        $this->from = $from;
    }

    public function getTo() {
        return $this->to;
    }

    public function setTo($to) {
        $this->to = $to;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function getSentDate() {
        return $this->sentDate;
    }

    public function setSentDate($sentDate) {
        $this->sentDate = $sentDate;
    }

    public function toString() {
        return "{id=" . $this->id . ", from=" . $this->from . ", to=" . $this->to . ", user=" . $this->user . ", sentDate=" . $this->sentDate == null ? null : $this->sentDate->getTime() . "}";
    }

}

?>
