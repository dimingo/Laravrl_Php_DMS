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

// namespace App\Http\Controllers\sdk4php\src\openkm\bean;

namespace App\Http\Controllers\sdk4php\src\openkm\bean;

/**
 * Node
 *
 * @author sochoa
 */
class Node {

    const AUTHOR = 'okm:author';
    const NAME = 'okm:name';

    protected $created;
    protected $path;
    protected $author;
    protected $permissions = 0;
    protected $uuid;
    protected $subscribed = false;
    protected $subscriptors = [];
    protected $keywords = [];
    protected $categories = [];
    protected $notes = [];

    public function getCreated() {
        return $this->created;
    }

    public function setCreated($created) {
        $this->created = $created;
    }

    public function getPath() {
        return $this->path;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }

    public function getPermissions() {
        return $this->permissions;
    }

    public function setPermissions($permissions) {
        $this->permissions = $permissions;
    }

    public function getUuid() {
        return $this->uuid;
    }

    public function setUuid($uuid) {
        $this->uuid = $uuid;
    }

    public function isSubscribed() {
        return $this->subscribed;
    }

    public function setSubscribed($subscribed) {
        $this->subscribed = $subscribed;
    }

    public function getSubscriptors() {
        return $this->subscriptors;
    }

    public function setSubscriptors($subscriptors) {
        $this->subscriptors = $subscriptors;
    }

    public function getKeywords() {
        return $this->keywords;
    }

    public function setKeywords($keywords) {
        $this->keywords = $keywords;
    }

    public function getCategories() {
        return $this->categories;
    }

    public function setCategories($categories) {
        $this->categories = $categories;
    }

    public function getNotes() {
        return $this->notes;
    }

    public function setNotes($notes) {
        $this->notes = $notes;
    }

}

?>
