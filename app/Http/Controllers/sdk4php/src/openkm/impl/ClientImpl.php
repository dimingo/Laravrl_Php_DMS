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

namespace App\Http\Controllers\sdk4php\src\openkm\impl;

use App\Http\Controllers\sdk4php\src\Httpful\Mime;
use App\Http\Controllers\sdk4php\src\Httpful\Request;
use App\Http\Controllers\sdk4php\src\openkm\bean\Document;
use App\Http\Controllers\sdk4php\src\openkm\bean\Folder;
use App\Http\Controllers\sdk4php\src\openkm\bean\LockInfo;
use App\Http\Controllers\sdk4php\src\openkm\bean\Version;
use App\Http\Controllers\sdk4php\src\openkm\bean\Note;
use App\Http\Controllers\sdk4php\src\openkm\bean\ContentInfo;
use App\Http\Controllers\sdk4php\src\openkm\bean\PropertyGroup;
use App\Http\Controllers\sdk4php\src\openkm\bean\FormElementComplex;
use App\Http\Controllers\sdk4php\src\openkm\bean\form\Option;
use App\Http\Controllers\sdk4php\src\openkm\bean\form\Validator;
use App\Http\Controllers\sdk4php\src\openkm\bean\QueryParams;
use App\Http\Controllers\sdk4php\src\openkm\bean\Entry;
use App\Http\Controllers\sdk4php\src\openkm\bean\QueryResult;

class ClientImpl {

    protected $host;
    protected $user;
    protected $password;

    /**
     * Construct
     */
    public function __construct($host, $user, $password) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * getClient
     */
    public function getClient(Request $client) {
        $client->sends(Mime::XML);
        $client->expects(Mime::XML);
        $client->authenticateWith($this->user, $this->password);
        return $client->send();
    }

    /**
     * getClientWithUploadResponse
     */
    public function getClientWithUploadResponse(Request $client) {
        $client->sends(Mime::XML);
        $client->expects(Mime::UPLOAD);
        $client->authenticateWith($this->user, $this->password);
        return $client->send();
    }

    /**
     * getClientWithHTMLResponse
     */
    public function getClientWithHTMLResponse(Request $client) {
        $client->sends(Mime::XML);
        $client->expects(Mime::HTML);
        $client->authenticateWith($this->user, $this->password);
        return $client->send();
    }

    /**
     * getClientWithPlainResponse     
     */
    public function getClientWithPlainResponse(Request $client) {
        $client->sends(Mime::XML);
        $client->expects(Mime::PLAIN);
        $client->authenticateWith($this->user, $this->password);
        return $client->send();
    }

    public function phpDocument($documentXML) {
        $document = new Document();
        $document->setAuthor((string) $documentXML->author);
        $document->setCreated((string) $documentXML->created);
        $document->setPath((string) $documentXML->path);
        $document->setPermissions((int) $documentXML->permissions);
        $document->setSubscribed((string) $documentXML->subscribed);
        $document->setUuid((string) $documentXML->uuid);

        //Version        
        $document->setActualVersion($this->phpVersion($documentXML->actualVersion));

        $document->setCheckedOut((string) $documentXML->checkedOut);
        $document->setConvertibleToDxf((string) $documentXML->convertibleToDxf);
        $document->setConvertibleToPdf((string) $documentXML->convertibleToPdf);
        $document->setConvertibleToSwf((string) $documentXML->convertibleToSwf);
        $document->setDescription((string) $documentXML->description);
        $document->setLanguage((string) $documentXML->language);
        $document->setLastModified((string) $documentXML->lastModified);

        //LockInfo        
        $document->setLockInfo($this->phpLockInfo($documentXML->lockInfo));

        $document->setLocked((string) $documentXML->locked);
        $document->setMimeType((string) $documentXML->mimeType);
        $document->setSigned((string) $documentXML->signed);
        $document->setTitle((string) $documentXML->title);

        //categories
        $categories = [];
        foreach ($documentXML->categories as $categoryXML) {
            $categories[] = $this->phpFolder($categoryXML);
        }
        $document->setCategories($categories);
        //keywords
        $keywords = [];
        foreach ($documentXML->keywords as $keywordXML) {
            $keywords[] = (string) $keywordXML;
        }
        $document->setKeywords($keywords);
        //notes
        $notes = [];
        foreach ($documentXML->notes as $noteXML) {
            $notes[] = $this->phpNote($noteXML);
        }
        $document->setNotes($notes);
        $subscriptors = [];
        foreach ($documentXML->subscriptors as $subscriptor) {
            $subscriptors[] = $subscriptor;
        }
        $document->setSubscriptors($subscriptors);
        return $document;
    }

    /**
     * Parse the XML a class
     * @param  \SimpleXMLElement $folderXML
     * @return \openkm\bean\Folder
     */
    public function phpFolderComplete($folderXML) {
        $folder = $this->phpFolder($folderXML);
        $categories = [];
        foreach ($folderXML->categories as $categoryXML) {
            $categories[] = $this->phpFolder($categoryXML);
        }
        $folder->setCategories($categories);
        $keywords = [];
        foreach ($folderXML->keywords as $keywordXML) {
            $keywords[] = (string) $keywordXML;
        }
        $folder->setKeywords($keywords);
        $notes = [];
        foreach ($folderXML->notes as $noteXML) {
            $notes[] = $this->phpNote($noteXML);
        }
        $folder->setNotes($notes);
        $subscriptors = [];
        foreach ($folderXML->subscriptors as $subscriptor) {
            $subscriptors[] = $subscriptor;
        }
        $folder->setSubscriptors($subscriptors);
        return $folder;
    }

    public function phpVersion($versionXML) {
        $version = new Version();
        $version->setActual((string) $versionXML->actual);
        $version->setAuthor((string) $versionXML->author);
        $version->setChecksum((string) $versionXML->checksum);
        $version->setComment((string) $versionXML->comment);
        $version->setCreated((string) $versionXML->created);
        $version->setName((string) $versionXML->name);
        $version->setSize((string) $versionXML->size);
        return $version;
    }

    public function phpLockInfo($lockInfoXML) {
        $lockInfo = new LockInfo();
        $lockInfo->setNodePath((string) $lockInfoXML->nodePath);
        $lockInfo->setOwner((string) $lockInfoXML->owner);
        $lockInfo->setToken((string) $lockInfoXML->token);
        return $lockInfo;
    }

    public function phpFolder($folderXML) {
        $folder = new Folder();
        $folder->setAuthor((string) $folderXML->author);
        $folder->setCreated((string) $folderXML->created);
        $folder->setPath((string) $folderXML->path);
        $folder->setPermissions((int) $folderXML->permissions);
        $folder->setSubscribed((string) $folderXML->subscribed);
        $folder->setUuid((string) $folderXML->uuid);
        $folder->setHasChildren((string) $folderXML->hasChildren);
        return $folder;
    }

    public function phpContentInfo($contentInfoXML) {
        $contentInfo = new ContentInfo();
        $contentInfo->setFolders((int) $contentInfoXML->folders);
        $contentInfo->setDocuments((int) $contentInfoXML->documents);
        $contentInfo->setMails((int) $contentInfoXML->mails);
        $contentInfo->setRecords((int) $contentInfoXML->records);
        $contentInfo->setSize((int) $contentInfoXML->size);
        return $contentInfo;
    }

    public function phpNote($noteXML) {
        $note = new Note();
        $note->setAuthor((string) $noteXML->author);
        $note->setDate((string) $noteXML->date);
        $note->setPath((string) $noteXML->path);
        $note->setText((string) $noteXML->text);
        return $note;
    }

    private function phpPropertyGroup($propertyGroupXML) {
        $propertyGroup = new PropertyGroup();
        $propertyGroup->setLabel((string) $propertyGroupXML->label);
        $propertyGroup->setName((string) $propertyGroupXML->name);
        if ((string) $propertyGroupXML->readonly == 'true') {
            $propertyGroup->setReadonly(true);
        } else {
            $propertyGroup->setReadonly(false);
        }
        if ($propertyGroupXML->visible == 'true') {
            $propertyGroup->setVisible(true);
        } else {
            $propertyGroup->setVisible(false);
        }
        return $propertyGroup;
    }

    public function phpFormElementComplex($formElementComplexXML) {
        $formElementComplex = new FormElementComplex();
        $formElementComplex->setObjClass((string) $formElementComplexXML->objClass);
        $formElementComplex->setLabel((string) $formElementComplexXML->label);
        $formElementComplex->setName((string) $formElementComplexXML->name);
        $formElementComplex->setWidth((string) $formElementComplexXML->width);
        $formElementComplex->setHeight((string) $formElementComplexXML->height);
        $formElementComplex->setType((string) $formElementComplexXML->type);
        $formElementComplex->setValue((string) $formElementComplexXML->value);
        if (!empty($formElementComplex->transition)) {
            $formElementComplex->setTransition((string) $formElementComplex->transition);
        }
        if ($formElementComplexXML->readonly == 'true') {
            $formElementComplex->setReadonly(true);
        } else {
            $formElementComplex->setReadonly(false);
        }
        $options = [];
        foreach ($formElementComplexXML->options as $optionXML) {
            $option = new Option();
            $option->setLabel((string) $optionXML->label);
            $option->setValue((string) $optionXML->value);
            if ($optionXML->selected == 'true') {
                $option->setSelected(true);
            } else {
                $option->setSelected(false);
            }
            $options[] = $option;
        }
        $formElementComplex->setOptions($options);
        $validators = [];
        foreach ($formElementComplexXML->validators as $validatorXML) {
            $validator = new Validator();
            $validator->setType((string) $validatorXML->type);
            $validator->setParameter((string) $validatorXML->parameter);
            $validators[] = $validator;
        }
        $formElementComplex->setValidators($validators);
        return $formElementComplex;
    }

    public function phpQueryParams($queryParamsXML) {
        $queryParams = new QueryParams();
        $queryParams->setAuthor((string) $queryParamsXML->author);
        $queryParams->setContent((string) $queryParamsXML->content);
        $queryParams->setDashboard((string) $queryParamsXML->dashboard);
        $queryParams->setDomain((int) $queryParamsXML->domain);
        $queryParams->setId((int) $queryParamsXML->id);
        $queryParams->setLastModifiedFrom((string) $queryParamsXML->lastModifiedFrom);
        $queryParams->setLastModifiedTo((string) $queryParamsXML->lastModifiedTo);
        $queryParams->setMailFrom((string) $queryParamsXML->mailFrom);
        $queryParams->setMailSubject((string) $queryParamsXML->mailSubject);
        $queryParams->setMailTo((string) $queryParamsXML->mailTo);
        $queryParams->setMimeType((string) $queryParamsXML->mimeType);
        $queryParams->setName((string) $queryParamsXML->name);
        $queryParams->setOperator((string) $queryParamsXML->operator);
        $queryParams->setPath((string) $queryParamsXML->path);
        $queryParams->setQueryName((string) $queryParamsXML->queryName);
        $queryParams->setUser((string) $queryParamsXML->user);
        $categories = [];
        foreach ($queryParamsXML->categories as $category) {
            $categories[] = (string) $category;
        }
        $queryParams->setCategories($categories);
        $keywords = [];
        foreach ($queryParamsXML->keywords as $keyword) {
            $keywords[] = (string) $keyword;
        }
        $queryParams->setKeywords($keywords);
        $properties = [];
        foreach ($queryParamsXML->properties->entry as $entryXML) {
            $entry = new Entry();
            $entry->setKey((string) $entryXML->key);
            $entry->setValue((string) $entryXML->value);
            $properties[] = $entry;
        }
        $queryParams->setProperties($properties);
        return $queryParams;
    }

    public function phpQueryResult($queryResultXML) {
        $queryResult = new QueryResult();
        $queryResult->setAttachment((string) $queryResultXML->attachment);
        $queryResult->setExcerpt((string) $queryResultXML->excerpt);
        if (!empty($queryResultXML->node)) {
            if (empty($queryResultXML->node->hasChildren)) {
                $queryResult->setNode($this->phpDocument($queryResultXML->node));
            } else {
                $queryResult->setNode($this->phpFolderComplete($queryResultXML->node));
            }
        }
        $queryResult->setScore((int) $queryResultXML->score);
        return $queryResult;
    }

}
