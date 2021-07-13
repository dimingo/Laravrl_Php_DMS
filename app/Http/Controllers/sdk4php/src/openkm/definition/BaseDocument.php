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


namespace App\Http\Controllers\sdk4php\src\openkm\definition;

use App\Http\Controllers\sdk4php\src\openkm\bean\Document;

/**
 * BaseDocument
 *
 * @author sochoa
 */
interface BaseDocument {

    public function createDocumentSimple($docPath, $content);

    public function deleteDocument($docId);

    public function getDocumentProperties($docId);

    public function getContent($docId);

    public function getContentByVersion($docId, $versionId);

    public function getDocumentChildren($fldId);

    public function renameDocument($docId, $newName);

    public function setProperties(Document $document);

    public function checkout($docId);

    public function cancelCheckout($docId);

    public function forceCancelCheckout($docId);

    public function isCheckedOut($docId);

    public function checkin($docId, $content, $comment);

    public function getVersionHistory($docId);

    public function lock($docId);

    public function unlock($docId);

    public function forceUnlock($docId);

    public function isLocked($docId);

    public function getLockInfo($docId);

    public function purgeDocument($docId);

    public function moveDocument($docId, $dstId);

    public function copyDocument($docId, $dstId);

    public function restoreVersion($docId, $versionId);

    public function purgeVersionHistory($docId);

    public function getVersionHistorySize($docId);

    public function isValidDocument($docId);

    public function getDocumentPath($uuid);
    
}

?>
