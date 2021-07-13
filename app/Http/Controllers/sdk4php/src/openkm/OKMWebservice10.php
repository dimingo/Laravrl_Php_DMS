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

// namespace openkm;
namespace  App\Http\Controllers\sdk4php\src\openkm;

use App\Http\Controllers\sdk4php\src\openkm\bean\Document;
use App\Http\Controllers\sdk4php\src\openkm\bean\Folder;
use App\Http\Controllers\sdk4php\src\openkm\bean\QueryParams;
use App\Http\Controllers\sdk4php\src\openkm\impl\AuthImpl;
use App\Http\Controllers\sdk4php\src\openkm\impl\DocumentImpl;
use App\Http\Controllers\sdk4php\src\openkm\impl\FolderImpl;
use App\Http\Controllers\sdk4php\src\openkm\impl\NoteImpl;
use App\Http\Controllers\sdk4php\src\openkm\impl\PropertyGroupImpl;
use App\Http\Controllers\sdk4php\src\openkm\impl\PropertyImpl;
use App\Http\Controllers\sdk4php\src\openkm\impl\RepositoryImpl;
use App\Http\Controllers\sdk4php\src\openkm\impl\SearchImpl;
use App\Http\Controllers\sdk4php\src\openkm\bean\Folder as BeanFolder;
use App\Http\Controllers\sdk4php\src\openkm\bean\QueryParams as BeanQueryParams;

/**App\Http\Controllers\sdk4php\src\
 * OKMWebservice10
 *
 * @author sochoa
 */
class OKMWebservice10 implements OKMWebservices {

    private $authImpl;
    private $docImpl;
    private $fldImpl;
    private $noteImpl;
    private $propertyGroupImpl;
    private $respositoryImpl;
    private $searchImpl;
    private $propertyImpl;

    public function __construct($host, $user, $password) {
        $this->authImpl = new AuthImpl($host, $user, $password);
        $this->docImpl = new DocumentImpl($host, $user, $password);
        $this->fldImpl = new FolderImpl($host, $user, $password);
        $this->noteImpl = new NoteImpl($host, $user, $password);
        $this->propertyGroupImpl = new PropertyGroupImpl($host, $user, $password);
        $this->propertyImpl = new PropertyImpl($host, $user, $password);
        $this->respositoryImpl = new RepositoryImpl($host, $user, $password);
        $this->searchImpl = new SearchImpl($host, $user, $password);
    }

    /**
     * Get Granted Roles
     * 
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @return array(GrantedRole) Return the granted roles of a node.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws DatabaseException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws UnknowException
     */
    public function getGrantedRoles($nodeId) {
        return $this->authImpl->getGrantedRoles($nodeId);
    }

    /**
     * Get granted users
     * 
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @return array(GrantedUser) Return the granted users of a node.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws DatabaseException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws UnknowException
     */
    public function getGrantedUsers($nodeId) {
        return $this->authImpl->getGrantedUsers($nodeId);
    }

    /**
     * Get Roles
     * 
     * @return array(string) Return the list of all the roles.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws DatabaseException
     * @throws RepositoryException
     * @throws PrincipalAdapterException
     * @throws UnknowException
     */
    public function getRoles() {
        return $this->authImpl->getRoles();
    }

    /**
     * Get Users
     * 
     * @return array(string) Return the list of all the users.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws DatabaseException
     * @throws RepositoryException
     * @throws PrincipalAdapterException
     * @throws UnknowException
     */
    public function getUsers() {
        return $this->authImpl->getUsers();
    }

    /**
     * Add role grant on a node.
     * 
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @param string $role
     * @param int $permissions
     * @param bool $recursive
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws DatabaseException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws UnknowException
     */
    public function grantRole($nodeId, $role, $permissions, $recursive) {
        $this->authImpl->grantRole($nodeId, $role, $permissions, $recursive);
    }

    /**
     * Add user grant on a node.
     * 
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @param string $user
     * @param int $permissions
     * @param bool $recursive
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws DatabaseException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws UnknowException
     */
    public function grantUser($nodeId, $user, $permissions, $recursive) {
        $this->authImpl->grantUser($nodeId, $user, $permissions, $recursive);
    }

    /**
     * Remove role grant on a node
     * 
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @param string $role
     * @param int $permissions
     * @param bool $recursive
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws DatabaseException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws UnknowException
     */
    public function revokeRole($nodeId, $role, $permissions, $recursive) {
        $this->authImpl->revokeRole($nodeId, $role, $permissions, $recursive);
    }

    /**
     * Remove user grant on a node.
     * 
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @param string $role
     * @param int $permissions
     * @param bool $recursive
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws DatabaseException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws UnknowException
     */
    public function revokeUser($nodeId, $user, $permissions, $recursive) {
        $this->authImpl->revokeUser($nodeId, $user, $permissions, $recursive);
    }

    /**
     * Get Roles by User
     * 
     * @param string $user The user
     * @return array(string) Return the list of all the roles assigned to a user.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws DatabaseException
     * @throws RepositoryException
     * @throws PrincipalAdapterException
     * @throws UnknowException
     */
    public function getRolesByUser($user) {
        return $this->authImpl->getRolesByUser($user);
    }

    /**
     * Get Users by Role
     * 
     * @param string $role The role
     * @return array(string) Return the list of all the users who have assigned a role.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws DatabaseException
     * @throws RepositoryException
     * @throws PrincipalAdapterException
     * @throws UnknowException
     */
    public function getUsersByRole($role) {
        return $this->authImpl->getUsersByRole($role);
    }

    /**
     * Get Mail
     * 
     * @param string $user The user
     * @return string Return the mail of a valid user.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws DatabaseException
     * @throws RepositoryException
     * @throws PrincipalAdapterException
     * @throws UnknowException
     */
    public function getMail($user) {
        return $this->authImpl->getMail($user);
    }

    /**
     * Get Name
     * 
     * @param string $user The user
     * @return string Return the name of a valid user.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws DatabaseException
     * @throws RepositoryException
     * @throws PrincipalAdapterException
     * @throws UnknowException
     */
    public function getName($user) {
        return $this->authImpl->getName($user);
    }

    /**
     * Create a new document
     * 
     * @param string $docPath The path of the Document
     * @param string $content Recommend using file_get_contents — Reads entire file into a string
     * @return Document Return as a result an object Document with the properties of the created document.
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws IOException
     * @throws UnsupportMimeTypeException
     * @throws FileSizeExceededException
     * @throws UserQuotaExceededException
     * @throws VirusDetectedException
     * @throws ItemExistsException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws ExtensionException
     * @throws AutomationException
     * @throws UnknowException
     */
    public function createDocumentSimple($docPath, $content) {
        return $this->docImpl->createDocumentSimple($docPath, $content);
    }

    /**
     * Delete a document.
     * 
     * When a document is deleted is automatically moved to /okm:trash/userId folder.
     * 
     * @param string $docId The uuid or path of the Document
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws ExtensionException
     * @throws AutomationException
     * @throws UnknowException
     */
    public function deleteDocument($docId) {
        $this->docImpl->deleteDocument($docId);
    }

    /**
     * Get document properties
     * 
     * @param string $docId The uuid or path of the Document
     * @return Document Return the document properties.
     * @throws ConnectionErrorException 
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getDocumentProperties($docId) {
        return $this->docImpl->getDocumentProperties($docId);
    }

    /**
     * Get content
     * 
     * @param string $docId The uuid or path of the Document
     * @return string Retrieve document content - binary data - of the actual document version
     * @throws ConnectionErrorException 
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getContent($docId) {
        return $this->docImpl->getContent($docId);
    }

    /**
     * Get content by version
     * 
     * @param string $docId The uuid or path of the Document
     * @param string $versionId The number version of the document
     * @return string Retrieve document content - binary data - of the actual document version
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws IOException
     * @throws UnknowException
     */
    public function getContentByVersion($docId, $versionId) {
        return $this->docImpl->getContentByVersion($docId, $versionId);
    }

    /**
     * Get Document children
     * 
     * @param string $fldId The uuid or path of the Folder or a record node.
     * @return array Return a list of all documents which their parent is fldId.
     * @throws ConnectionErrorException 
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getDocumentChildren($fldId) {
        return $this->docImpl->getDocumentChildren($fldId);
    }

    /**
     * Change the name of a document.
     * 
     * @param string $docId The uuid or path of the Document
     * @param string $newName The new name for the Document
     * @return Document Returns the Document
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws ItemExistsException
     * @throws UnknowException
     */
    public function renameDocument($docId, $newName) {
        return $this->docImpl->renameDocument($docId, $newName);
    }

    /**
     * Change some document properties.
     * Variables allowed to be changed:
     * 
     *  - Title
     *  - Description
     *  - Language
     *  - Associated categories
     *  - Associated keywords
     * 
     * The parameter Language must be ISO 691-1 compliant.
     *  
     * More information at https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes.
     * 
     * Only not null and not empty variables will be take on consideration.
     * 
     * @param Document $document The Document
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws VersionException
     * @throws UnknowException
     */
    public function setProperties(Document $document) {
        $this->docImpl->setProperties($document);
    }

    /**
     * Mark the document for edition.
     * 
     * Only one user can modify a document at same time.
     * 
     * Before starting edition must do a checkout action that lock the edition process for other users and allows only to the user who has executed the action.
     * 
     * @param string $docId The uuid or path of the Document
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws UnknowException
     */
    public function checkout($docId) {
        $this->docImpl->checkout($docId);
    }

    /**
     * Cancel a document edition.
     * 
     * This action can only be done by the user who previously executed the checkout action.
     * 
     * @param string $docId The uuid or path of the Document
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws UnknowException
     */
    public function cancelCheckout($docId) {
        $this->docImpl->cancelCheckout($docId);
    }

    /**
     * Cancel a document edition.
     * 
     * This method allows to cancel edition on any document.
     * It is not mandatory execute this action by the same user who previously executed the checkout action
     * 
     *  - This action can only be done by a super user ( user with ROLE_ADMIN ).
     * 
     * @param string $docId The uuid or path of the Document
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws PrincipalAdapterException
     * @throws UnknowException
     */
    public function forceCancelCheckout($docId) {
        $this->docImpl->forceCancelCheckout($docId);
    }

    /**
     * Is checked out
     * 
     * @param string $docId The uuid or path of the Document
     * @return bool Return a boolean that indicate if the document is on edition or not.
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function isCheckedOut($docId) {
        return $this->docImpl->isCheckedOut($docId);
    }

    /**
     * Update document with new version
     * 
     * Only the user who started the edition - checkout - is allowed to update the document.
     * 
     * @param string $docId The uuid or path of the Document
     * @param string $content Recommend using file_get_contents — Reads entire file into a string
     * @param string $comment The comment for the new version the document
     * @return Version Return an object with new Version values
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws IOException
     * @throws FileSizeExceededException
     * @throws UserQuotaExceededException
     * @throws VirusDetectedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws ExtensionException
     * @throws AutomationException
     * @throws LockException
     * @throws UnknowException
     */
    public function checkin($docId, $content, $comment = '') {
        return $this->docImpl->checkin($docId, $content, $comment);
    }

    /**
     * Get Version History
     * 
     * @param string $docId The uuid or path of the Document
     * @return array Return a list of all document versions.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getVersionHistory($docId) {
        return $this->docImpl->getVersionHistory($docId);
    }

    /**
     * Lock a document
     * 
     * Only the user who locked the document is allowed to unlock.
     * A locked document can not be modified by other users.
     * 
     * @param string $docId The uuid or path of the Document
     * @return LockInfo Return an object with the Lock information.
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws UnknowException
     */
    public function lock($docId) {
        return $this->docImpl->lock($docId);
    }

    /**
     * Unlock a locked document.
     * 
     * Only the user who locked the document is allowed to unlock.
     * 
     * @param string $docId The uuid or path of the Document
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws UnknowException
     */
    public function unlock($docId) {
        $this->docImpl->unlock($docId);
    }

    /**
     * Unlock a locked document.
     * 
     * This method allows to unlcok any locked document.
     * It is not mandatory execute this action by the same user who previously executed the checkout lock action.
     *  - This action can only be done by a super user ( user with ROLE_ADMIN ).
     * 
     * @param string $docId The uuid or path of the Document
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException     
     * @throws UnknowException
     */
    public function forceUnlock($docId) {
        $this->docImpl->forceUnlock($docId);
    }

    /**
     * Is locked
     * 
     * @param string $docId The uuid or path of the Document
     * @return bool Return a boolean that indicate if the document is locked or not.
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function isLocked($docId) {
        return $this->docImpl->isLocked($docId);
    }

    /**
     * Get lock information
     * 
     * @param string $docId The uuid or path of the Document
     * @return LockInfo Return an object with the Lock information
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws UnknowException
     */
    public function getLockInfo($docId) {
        return $this->docImpl->getLockInfo($docId);
    }

    /**
     * Document is definitely removed from repository.
     * 
     * Usually you will purge documents into /okm:trash/userId - the personal trash user locations - but is possible to directly purge any document from the whole repository.
     *  - When a document is purged only will be able to be restored from a previously repository backup. The purge action remove the document definitely from the repository.
     * 
     * @param string $docId string $docId The uuid or path of the Document
     * @throws ConnectionErrorException 
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws ExtensionException
     * @throws UnknowException
     */
    public function purgeDocument($docId) {
        $this->docImpl->purgeDocument($docId);
    }

    /**
     * Move document into some folder or record.
     * 
     * @param string $docId The uuid or path of the Document
     * @param string $dstId The uuid or path of the Folder or Record
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws ExtensionException
     * @throws ItemExistsException
     * @throws AutomationException
     * @throws UnknowException
     */
    public function moveDocument($docId, $dstId) {
        $this->docImpl->moveDocument($docId, $dstId);
    }

    /**
     * Copy a document into some folder or record.
     * Only the binary data and the security grants are copyed to destionation, the metadata, keywords, etc. of the document are not copyed.See "extendedDocumentCopy" method for this feature.
     * 
     * @param string $docId The uuid or path of the Document
     * @param string $dstId The uuid or path of the Folder or Record
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws ExtensionException
     * @throws ItemExistsException
     * @throws AutomationException
     * @throws IOException
     * @throws UnknowException
     */
    public function copyDocument($docId, $dstId) {
        $this->docImpl->copyDocument($docId, $dstId);
    }

    /**
     * Promote previously document version to actual version.
     * 
     * @param string $docId The uuid or path of the Document
     * @param string $versionId The version of the Document
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws ExtensionException
     * @throws LockException
     * @throws UnknowException
     */
    public function restoreVersion($docId, $versionId) {
        $this->docImpl->restoreVersion($docId, $versionId);
    }

    /**
     * Purge all documents version except the actual version.
     * 
     * This action compact the version history of a document.
     * This action can not be reverted.
     * 
     * @param string $docId The uuid or path of the Document
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws ExtensionException
     * @throws LockException
     * @throws UnknowException
     */
    public function purgeVersionHistory($docId) {
        $this->docImpl->purgeVersionHistory($docId);
    }

    /**
     * Get version history size
     * 
     * @param string $docId The uuid or path of the Document
     * @return int Return the sum in bytes of all documents into documents history.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getVersionHistorySize($docId) {
        return $this->docImpl->getVersionHistorySize($docId);
    }

    /**
     * Is valid Document
     * 
     * @param string $docId The uuid or path of the Document
     * @return bool Return a boolean that indicate if the node is a document or not.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function isValidDocument($docId) {
        return $this->docImpl->isValidDocument($docId);
    }

    /**
     * Get Document path
     * 
     * @param string $uuid The uuid of the Document
     * @return string Convert document UUID to document path
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getDocumentPath($uuid) {
        return $this->docImpl->getDocumentPath($uuid);
    }

    /**
     * Create folder
     * @param Folder $fld The variable path into the parameter fld, must be initializated. It indicates the folder path into OpenKM.
     * @return Folder Create a new folder and return as a result an object Folder.
     * @throws ConnectionErrorException 
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws ItemExistsException
     * @throws ExtensionException
     * @throws AutomationException
     * @throws UnknowException
     */
    public function createFolder(BeanFolder $fld) {
        return $this->fldImpl->createFolder($fld);
    }

    /**
     * Create Folder Simple
     * @param string $fldPath Path of the Folder
     * @return Folder Create a new folder and return as a result an object Folder.
     * @throws ConnectionErrorException 
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws ItemExistsException
     * @throws ExtensionException
     * @throws AutomationException
     * @throws UnknowException
     */
    public function createFolderSimple($fldPath) {
        return $this->fldImpl->createFolderSimple($fldPath);
    }

    /**
     * Get the properties of the folder
     * @param string $fldId The uuid or path of the Folder
     * @return Folder $folder Return the folder properties
     * @throws ConnectionErrorException 
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getFolderProperties($fldId) {
        return $this->fldImpl->getFolderProperties($fldId);
    }

    /**
     * Delete Folder
     * @param string $fldId The uuid or path of the Folder
     * @throws ConnectionErrorException 
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws UnknowException
     */
    public function deleteFolder($fldId) {
        $this->fldImpl->deleteFolder($fldId);
    }

    /**
     * Rename Folder
     * @param string $fldId The uuid or path of the Folder
     * @param string $newName The new name for the folder 
     * @return \openkm\bean\Folder 
     * @throws ConnectionErrorException 
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function renameFolder($fldId, $newName) {
        return $this->fldImpl->renameFolder($fldId, $newName);
    }

    /**
     * Move folder into some folder or record.
     * @param string $fldId The uuid or path of the Folder
     * @param string $dstId The uuid or path of the Folder
     * @throws ConnectionErrorException 
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws UnknowException
     */
    public function moveFolder($fldId, $dstId) {
        $this->fldImpl->moveFolder($fldId, $dstId);
    }

    /**
     * Get Folder Children
     * @param string $fldId The uuid or path of the Folder or Record node
     * @return array Return an array of all Folder their parent is fldId
     * @throws ConnectionErrorException 
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getFolderChildren($fldId) {
        return $this->fldImpl->getFolderChildren($fldId);
    }

    /**
     * Is valid Folder
     * @param string $fldId the uuid or paht of the Folder
     * @return boolean Return a boolean that indicate if the node is a folder or not.
     * @throws ConnectionErrorException 
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function isValidFolder($fldId) {
        return $this->fldImpl->isValidFolder($fldId);
    }

    /**
     * Get Folder Path
     * @param string $uuid The uuid of de Folder
     * @return string Convert folder UUID to folder path.
     * @throws ConnectionErrorException 
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getFolderPath($uuid) {
        return $this->fldImpl->getFolderPath($uuid);
    }

    /**
     * Add note to a node
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @param string $text The text
     * @return Note Return an object Note.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws UnknowException
     */
    public function addNote($nodeId, $text) {
        return $this->noteImpl->addNote($nodeId, $text);
    }

    /**
     * Retrieves the note.
     * 
     * @param string $noteId The noteId is an UUID. The object Node have a variable named path, in that case the path contains an UUID.
     * @return Note Return un object Note.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws UnknowException
     */
    public function getNote($noteId) {
        return $this->noteImpl->getNote($noteId);
    }

    /**
     * Delete a note
     * 
     * @param string $noteId The noteId is an UUID. The object Node have a variable named path, in that case the path contains an UUID.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws UnknowException
     */
    public function deleteNote($noteId) {
        $this->noteImpl->deleteNote($noteId);
    }

    /**
     * Change the note text.
     * 
     * @param string $noteId The noteId is an UUID. The object Node have a variable named path, in that case the path contains an UUID.
     * @param string $text The text
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws UnknowException
     */
    public function setNote($noteId, $text) {
        $this->noteImpl->setNote($noteId, $text);
    }

    /**
     * Retrieve a list of all notes of a node.
     * 
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @return array Return an array of all notes of a node.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException     
     * @throws UnknowException
     */
    public function listNotes($nodeId) {
        return $this->noteImpl->listNotes($nodeId);
    }

    /**
     * Add an empty metadata group to a node
     * 
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @param string $grpName The grpName should be a valid Metadata group name.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws LockException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws ExtensionException
     * @throws AutomationException
     * @throws UnknowException
     */
    public function addGroup($nodeId, $grpName) {
        $this->propertyGroupImpl->addGroup($nodeId, $grpName);
    }

    /**
     * Remove a metadata group of a node.
     * 
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @param string $grpName The grpName should be a valid Metadata group name.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws NoSuchGroupException
     * @throws PathNotFoundException
     * @throws LockException
     * @throws DatabaseException
     * @throws RepositoryException
     * @throws ExtensionException
     * @throws UnknowException
     */
    public function removeGroup($nodeId, $grpName) {
        $this->propertyGroupImpl->removeGroup($nodeId, $grpName);
    }

    /**
     * Get Groups
     * 
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @return array(PropertyGroup) Retrieve a list of metadata groups assigned to a node.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws IOException
     * @throws PathNotFoundException
     * @throws ParseException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getGroups($nodeId) {
        return $this->propertyGroupImpl->getGroups($nodeId);
    }

    /**
     * Get all groups
     * 
     * @return array(PropertyGroup) Retrieve a list of all metadata groups set into the application.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws IOException
     * @throws ParseException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getAllGroups() {
        return $this->propertyGroupImpl->getAllGroups();
    }

    /**
     * Get Property Group Properties
     * The method is usually used to display form elements with its values to be shown or changed by used.
     * 
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @param string $grpName The grpName should be a valid Metadata group name.
     * @return array(FormElement) Retrieve a list of all metadata group elements and its values of a node.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws IOException
     * @throws ParseException
     * @throws NoSuchGroupException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getPropertyGroupProperties($nodeId, $grpName) {
        return $this->propertyGroupImpl->getPropertyGroupProperties($nodeId, $grpName);
    }

    /**
     * Get Property Group Form
     * 
     * The method is usually used to display empty form elements for creating new metadata values.
     * 
     * @param string $grpName The grpName should be a valid Metadata group name.
     * @return array(FormElement) Retrieve a list of all metadata group elements and its values of a node.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws IOException
     * @throws ParseException
     * @throws NoSuchGroupException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getPropertyGroupForm($grpName) {
        return $this->propertyGroupImpl->getPropertyGroupForm($grpName);
    }

    /**
     * Change the metadata group values of a node.
     * 
     * Is not mandatory set into parameter ofeList all FormElement, is enought with the formElements you wish to change its values.
     * 
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @param string $grpName The grpName should be a valid Metadata group name.
     * @param array $formElements An array of the FormElement
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws IOException
     * @throws ParseException
     * @throws NoSuchPropertyException
     * @throws NoSuchGroupException
     * @throws LockException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws ExtensionException
     * @throws AutomationException
     * @throws UnknowException
     */
    public function setPropertyGroupProperties($nodeId, $grpName, $formElements = []) {
        $this->propertyGroupImpl->setPropertyGroupProperties($nodeId, $grpName, $formElements);
    }

    /**
     * Change the metadata group values of a node.
     * 
     * Is not mandatory set into properties parameter all fields values, is enought with the fields you wish to change its values.
     * 
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @param string $grpName The grpName should be a valid Metadata group name.
     * @param array $properties An array
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws IOException
     * @throws ParseException
     * @throws NoSuchPropertyException
     * @throws NoSuchGroupException
     * @throws LockException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws ExtensionException
     * @throws AutomationException
     * @throws UnknowException
     */
    public function setPropertyGroupPropertiesSimple($nodeId, $grpName, $properties = []) {
        $this->propertyGroupImpl->setPropertyGroupPropertiesSimple($nodeId, $grpName, $properties);
    }

    /**
     * Has Group
     * 
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @param string $grpName The grpName should be a valid Metadata group name.
     * @return bool Return a boolean that indicate if the node has or not a metadata group.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws IOException
     * @throws ParseException
     * @throws NoSuchGroupException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function hasGroup($nodeId, $grpName) {
        return $this->propertyGroupImpl->hasGroup($nodeId, $grpName);
    }

    /**
     * Get Root Folder
     * @return Folder Return the object Folder of node "/okm:root"
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getRootFolder() {
        return $this->respositoryImpl->getRootFolder();
    }

    /**
     * Get Trash Folder
     * The returned folder will be the user trash folder.
     * For example if the method is executed by "okmAdmin" user then the folder returned will be "/okm:trash/okmAdmin".
     * 
     * @return Folder Return the object Folder of node "/okm:trash/{userId}"
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getTrashFolder() {
        return $this->respositoryImpl->getTrashFolder();
    }

    /**
     * Get Templates Folder
     * @return Folder Return the object Folder of node "/okm:templates"
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getTemplatesFolder() {
        return $this->respositoryImpl->getTemplatesFolder();
    }

    /**
     * Get Personal Folder
     * The returned folder will be the user personal folder.
     * For example if the method is executed by "okmAdmin" user then the folder returned will be "/okm:personal/okmAdmin".
     * 
     * @return Folder Return the object Folder of node "/okm:personal/{userId}"
     * @throws ConnectionErrorException when unable to parse or communicate with the server
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getPersonalFolder() {
        return $this->respositoryImpl->getPersonalFolder();
    }

    /**
     * Get Mail Folder
     * 
     * The returned folder will be the user mail folder.
     * For example if the method is executed by "okmAdmin" user then the folder returned will be "/okm:mail/okmAdmin".
     * 
     * @return Folder Return the object Folder of node "/okm:mail/{userId}"
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getMailFolder() {
        return $this->respositoryImpl->getMailFolder();
    }

    /**
     * Get Thesaurus Folder
     * @return Folder Return the object Folder of node "/okm:thesaurus"
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getThesaurusFolder() {
        return $this->respositoryImpl->getThesaurusFolder();
    }

    /**
     * Get Categories Folder
     * @return Folder Return the object Folder of node "/okm:categories"
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getCategoriesFolder() {
        return $this->respositoryImpl->getCategoriesFolder();
    }

    /**
     * Purge Trash
     * 
     * Definitively remove from repository all nodes into "/okm:trash/{userId}"
     * 
     * For example if the method is executed by "okmAdmin" user then the purged trash will be "/okm:trash/okmAdmin".
     * 
     * When a node is purged only will be able to be restored from a previously repository backup. The purge action remove the node definitely from the repository.
     * 
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws UnknowException
     */
    public function purgeTrash() {
        $this->respositoryImpl->purgeTrash();
    }

    /**
     * Get Update Message
     * 
     * There's an official OpenKM update message service available at Internet what based on your locally OpenKM version retrieve information messages. 
     * 
     * The most common message is that a new OpenKM version has been released.
     * 
     * @return String Retrieve a message from OpenKM official message service at Internet based on your locally OpenKM server version.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getUpdateMessage() {
        return $this->respositoryImpl->getUpdateMessage();
    }

    /**
     * Get Repository uuid
     * @return String Retrieve installation unique identifier.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getRepositoryUuid() {
        return $this->respositoryImpl->getRepositoryUuid();
    }

    /**
     * Has node
     * @param string $nodeId The value of the parameter nodeId can be a valid UUID or path.
     * @return boolean Return a node that indicate if a node exists or not.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function hasNode($nodeId) {
        return $this->respositoryImpl->hasNode($nodeId);
    }

    /**
     * Get node path     
     * Convert node UUID to path.
     * 
     * @param string $uuid The uuid of the node
     * @return string Return path of the node
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getNodePath($uuid) {
        return $this->respositoryImpl->getNodePath($uuid);
    }

    /**
     * Get node uuid
     * Convert node path to UUID.
     * 
     * @param string $nodePath The path of the node
     * @return string Return uuid of the node
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getNodeUuid($nodePath) {
        return $this->respositoryImpl->getNodeUuid($nodePath);
    }

    /**
     * Get App Version
     * @return AppVersion Return information about OpenKM version.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getAppVersion() {
        return $this->respositoryImpl->getAppVersion();
    }

    /**
     * Execute an script
     * 
     * The local script - test.bsh - used in the sample below:
     * 
     *  import com.openkm.bean.*;
     *  import com.openkm.api.*;
     *
     *  for (Folder fld : OKMFolder.getInstance().getChildren(null,"/okm:root")) {
     *      print(fld+"\n");
     *  }
     *  // Some value can also be returned as string
     *  return "some result";
     *
     * This action can only be done by a super user ( user with ROLE_ADMIN ).
     * 
     * @param string $content Recommend using file_get_contents — Reads entire file into a string
     * @return ScriptExecutionResult 
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function executeScript($content) {
        return $this->respositoryImpl->executeScript($content);
    }

    /**
     * Execute SQL sentences.
     * 
     * The test.sql script used in the sample below:
     * 
     *  SELECT NBS_UUID, NBS_NAME FROM OKM_NODE_BASE LIMIT 10;
     * 
     * The SQL script can only contains a single SQL sentence.
     * 
     * This action can only be done by a super user ( user with ROLE_ADMIN ).
     * 
     * @param string $content Recommend using file_get_contents — Reads entire file into a string
     * @return SqlQueryResults 
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function executeSqlQuery($content) {
        return $this->respositoryImpl->executeSqlQuery($content);
    }

    /**
     * Execute HQL sentences.
     * 
     * The testhql.sql script used in the sample below:
     * 
     *  SELECT uuid, name from NodeBase where name = 'okm:root';
     * 
     * The HQL script can only contains a single HQL sentence.
     * 
     * This action can only be done by a super user ( user with ROLE_ADMIN ).
     * 
     * @param string $content Recommend using file_get_contents — Reads entire file into a string
     * @return HqlQueryResults 
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function executeHqlQuery($content) {
        return $this->respositoryImpl->executeHqlQuery($content);
    }

    /**
     * Find by Content
     * 
     * The method only search among all documents, it not takes in consideration any other kind of nodes.
     * 
     * @param string $content
     * @return array(QueryResult) Return a list of QueryResults filtered by the value of the content parameter.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws IOException
     * @throws ParseException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function findByContent($content) {
        return $this->searchImpl->findByContent($content);
    }

    /**
     * Find by Name
     * 
     * The method only search among all documents, it not takes in consideration any other kind of nodes.
     * 
     * @param string $name
     * @return array(QueryResult) Return a list of QueryResults filtered by the value of the content parameter.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws IOException
     * @throws ParseException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function findByName($name) {
        return $this->searchImpl->findByName($name);
    }

    /**
     * Find by keywords
     * 
     * The method only search among all documents, it not takes in consideration any other kind of nodes.
     * 
     * @param array $keywords
     * @return array(QueryResult) Return a list of QueryResults filtered by the value of the content parameter.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws IOException
     * @throws ParseException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function findByKeywords($keywords = []) {
        return $this->searchImpl->findByKeywords($keywords);
    }

    /**
     * Find
     * 
     * @param QueryParams $queryParams
     * @return array(QueryResult) Return a list of QueryResults filtered by the value of the content parameter.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws IOException
     * @throws ParseException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function find(BeanQueryParams $queryParams) {
        return $this->searchImpl->find($queryParams);
    }

    /**
     * Find paginated
     * 
     * The parameter "limit" and "offset" allow you to retrieve just a portion of the results of a query.
     * 
     * @param QueryParams $queryParams
     * @param int $offset The parameter "limit" is used to limit the number of results returned.
     * @param int $limit The parameter "offset" says to skip that many results before the begining to return results.
     * @return ResultSet Return a list of paginated results filtered by the values of the queryParams parameter.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws IOException
     * @throws ParseException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function findPaginated(BeanQueryParams $queryParams, $offset, $limit) {
        return $this->searchImpl->findPaginated($queryParams, $offset, $limit);
    }

    /**
     * Find simple query paginated
     * 
     * @param string $statement The syntax to use in the statement parameter is the pair 'field:value'. For example: "name:grial" is filtering field name by word grial.
     * @param int $offset The parameter "limit" is used to limit the number of results returned.
     * @param int $limit The parameter "offset" says to skip that many results before the begining to return results.
     * @return ResultSet Return a list of paginated results filtered by the values of the statement parameter.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws IOException
     * @throws ParseException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function findSimpleQueryPaginated($statement, $offset, $limit) {
        return $this->searchImpl->findSimpleQueryPaginated($statement, $offset, $limit);
    }

    /**
     * Find more like this
     * 
     * The method can only be used with documents.
     * 
     * @param string $uuid The uuid of the Document
     * @param int $max The max value is used to limit the number of results returned.
     * @return ResultSet Return a list of documents that are considered similar by search engine.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws IOException
     * @throws ParseException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function findMoreLikeThis($uuid, $max) {
        return $this->searchImpl->findMoreLikeThis($uuid, $max);
    }

    /**
     * Get keyword map
     * 
     * @param array $filter
     * @return array(KeywordMap) Return a array of the KeywordMap with its count value filtered by other keywords.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getKeywordMap($filter = []) {
        return $this->searchImpl->getKeywordMap($filter);
    }

    /**
     * Get Categorized Documents
     * 
     * @param string $categoryId The uuid or path of the Category
     * @return array(Document) Retrieve a list of all documents related with a category.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getCategorizedDocuments($categoryId) {
        return $this->searchImpl->getCategorizedDocuments($categoryId);
    }

    /**
     * Save a search parameters.
     * 
     * @param QueryParams $params The variable queryName of the parameter params, should have to be initialized.
     * @return int Returns the id of the saved search
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function saveSearch(BeanQueryParams $params) {
        return $this->searchImpl->saveSearch($params);
    }

    /**
     * Update a previously saved search parameters.
     * 
     * Only can be updated a saved search created by the same user user who's executing the method.
     * 
     * @param QueryParams $params
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function updateSearch(BeanQueryParams $params) {
        $this->searchImpl->updateSearch($params);
    }

    /**
     * Get saved search parameters.
     * 
     * Only can be retrieved a saved search created by the same user who's executing the method.
     * 
     * @param int $qpId The id of the saved search
     * @return QueryParams Return the object queryParams
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getSearch($qpId) {
        return $this->searchImpl->getSearch($qpId);
    }

    /**
     * Get All Searchs
     * 
     * Only will be retrieved the list of the saved searches created by the same user who's executing the method.
     * 
     * @return array(QueryParam) Retrieve an list of all saved search parameters.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function getAllSearchs() {
        return $this->searchImpl->getAllSearchs();
    }

    /**
     * Delete a saved search parameters.
     * 
     * Only can be deleted a saved search created by the same user user who's executing the method.
     * 
     * @param int $qpId The id of the saved search
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws UnknowException
     */
    public function deleteSearch($qpId) {
        $this->searchImpl->deleteSearch($qpId);
    }

    /**
     * Set a relation between a category and a node.
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @param string $catId The uuid or path of de category folder
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws VersionException
     * @throws UnknowException
     */
    public function addCategory($nodeId, $catId) {
        $this->propertyImpl->addCategory($nodeId, $catId);
    }

    /**
     * Remove a relation between a category and a node.
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @param string $catId The uuid or path of de category folder
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws VersionException
     * @throws UnknowException
     */
    public function removeCategory($nodeId, $catId) {
        $this->propertyImpl->removeCategory($nodeId, $catId);
    }

    /**
     * Add a keyword and a node
     * The keyword should be a single word without spaces, formats allowed:
     *    - "test"
     *    - "two_words" ( the character "_" is used for the junction ).    
     * Also we suggest you to add keyword in lowercase format, because OpenKM is case sensitive.
     * 
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @param string $keyword The keyword
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws VersionException
     * @throws UnknowException
     */
    public function addKeyword($nodeId, $keyword) {
        $this->propertyImpl->addKeyword($nodeId, $keyword);
    }

    /**
     * Remove a keyword from a node.
     * @param string $nodeId The uuid or path of the document, folder, mail or record
     * @param type $keyword The keyword
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws VersionException
     * @throws UnknowException
     */
    public function removeKeyword($nodeId, $keyword) {
        $this->propertyImpl->removeKeyword($nodeId, $keyword);
    }

    /**
     * Mark a document as an encripted binary data into the repository
     * 
     * This method does not perform any kind of encryption, simply mark into the database that a document is encrypted.
     * 
     * @param string $nodeId The uuid or path of the document
     * @param string $cipherName The cipher name saves information about the encription mechanism.
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws VersionException
     * @throws UnknowException
     */
    public function setEncryption($nodeId, $cipherName) {
        $this->propertyImpl->setEncryption($nodeId, $cipherName);
    }

    /**
     * Mark a document as a normal binary data into repository.
     * 
     * This method does not perform any kind of uncryption, simply mark into the database that a document has been uncrypted.
     * 
     * @param string $nodeId The uuid or path of the document
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws VersionException
     * @throws UnknowException
     */
    public function unsetEncryption($nodeId) {
        $this->propertyImpl->unsetEncryption($nodeId);
    }

    /**
     *  This method does not perform any kind of digital signature process, simply mark into the database that a document is signed.
     * 
     * @param string $nodeId The uuid or path of the document
     * @param boolean $signed Mark a document as signed or unsigned binary data into the repository
     * @throws ConnectionErrorException
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws VersionException
     * @throws UnknowException
     */
    public function setSigned($nodeId, $signed) {
        $this->propertyImpl->setSigned($nodeId, $signed);
    }

}

?>
