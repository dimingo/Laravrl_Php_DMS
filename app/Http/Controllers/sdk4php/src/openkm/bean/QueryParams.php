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
 * QueryParams
 *
 * @author sochoa
 */
class QueryParams {

    const DOCUMENT = 1;
    const FOLDER = 2;
    
    const _AND = 'and';
    const _OR = 'or';

    private $id;
    private $queryName;
    private $user;
    private $name;    
    private $keywords = [];
    private $categories = [];
    private $content;
    private $mimeType;
    private $author;    
    private $path;
    private $lastModifiedFrom;
    private $lastModifiedTo;
    private $mailSubject;
    private $mailFrom;
    private $mailTo;
    private $statementQuery;
    private $statementType;
    private $dashboard;
    private $domain;
    private $operator;
    private $properties = [];
    private $shared = [];
    private $proposedSent = [];
    private $proposedReceived = [];

    function __construct() {
        $this->domain = self::DOCUMENT;
        $this->operator = self::_AND;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getQueryName() {
        return $this->queryName;
    }

    public function setQueryName($queryName) {
        $this->queryName = $queryName;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
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

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getMimeType() {
        return $this->mimeType;
    }

    public function setMimeType($mimeType) {
        $this->mimeType = $mimeType;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }

    public function getPath() {
        return $this->path;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function getLastModifiedFrom() {
        return $this->lastModifiedFrom;
    }

    public function setLastModifiedFrom($lastModifiedFrom) {
        $this->lastModifiedFrom = $lastModifiedFrom;
    }

    public function getLastModifiedTo() {
        return $this->lastModifiedTo;
    }

    public function setLastModifiedTo($lastModifiedTo) {
        $this->lastModifiedTo = $lastModifiedTo;
    }

    public function getMailSubject() {
        return $this->mailSubject;
    }

    public function setMailSubject($mailSubject) {
        $this->mailSubject = $mailSubject;
    }

    public function getMailFrom() {
        return $this->mailFrom;
    }

    public function setMailFrom($mailFrom) {
        $this->mailFrom = $mailFrom;
    }

    public function getMailTo() {
        return $this->mailTo;
    }

    public function setMailTo($mailTo) {
        $this->mailTo = $mailTo;
    }

    public function getStatementQuery() {
        return $this->statementQuery;
    }

    public function setStatementQuery($statementQuery) {
        $this->statementQuery = $statementQuery;
    }

    public function getStatementType() {
        return $this->statementType;
    }

    public function setStatementType($statementType) {
        $this->statementType = $statementType;
    }

    public function isDashboard() {
        return $this->dashboard;
    }

    public function setDashboard($dashboard) {
        $this->dashboard = $dashboard;
    }

    public function getDomain() {
        return $this->domain;
    }

    public function setDomain($domain) {
        $this->domain = $domain;
    }

    public function getOperator() {
        return $this->operator;
    }

    public function setOperator($operator) {
        $this->operator = $operator;
    }

    public function getProperties() {
        return $this->properties;
    }

    public function setProperties($properties) {
        $this->properties = $properties;
    }

    public function getShared() {
        return $this->shared;
    }

    public function setShared($shared) {
        $this->shared = $shared;
    }

    public function getProposedSent() {
        return $this->proposedSent;
    }

    public function setProposedSent($proposedSent) {
        $this->proposedSent = $proposedSent;
    }

    public function getProposedReceived() {
        return $this->proposedReceived;
    }

    public function setProposedReceived($proposedReceived) {
        $this->proposedReceived = $proposedReceived;
    }

    public function toString() {
        return "{id=" . $this->id . ", queryName=" . $this->queryName . ", user=" . $this->user . ", name=" . $this->name . ", title=" . $this->title . ", keywords=" . $this->keywords . ", categories=" . $this->categories . ", content=" . $this->content . ", mimeType=" . $this->mimeType . ", language=" . $this->language . ", author=" . $this->author . ", path=" . $this->path . ", dashboard=" . $this->dashboard . ", domain=" . $this->domain . ", operator=" . $this->operator . ", shared=" . $this->shared . ", proposedSent=" . $this->proposedSent . ", proposedReceived=" . $this->proposedReceived . ", statementQuery=" . $this->statementQuery . ", statementType=" . $this->statementType . ", lastModifiedFrom=" . $this->lastModifiedFrom == null ? null : $this->lastModifiedFrom->getTime() . ", lastModifiedTo=" . $this->lastModifiedTo == null ? null : $this->lastModifiedTo->getTime() . "}";
    }

}
?>

