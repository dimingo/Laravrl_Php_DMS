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

use App\Http\Controllers\sdk4php\src\openkm\bean\Document;
use App\Http\Controllers\sdk4php\src\openkm\bean\Version;
use App\Http\Controllers\sdk4php\src\openkm\bean\LockInfo;
use App\Http\Controllers\sdk4php\src\openkm\definition\BaseDocument;
use App\Http\Controllers\sdk4php\src\openkm\util\UriHelper;
use app\Http\Controllers\sdk4php\src\Httpful\Request;
use App\Http\Controllers\sdk4php\src\Httpful\Mime;
use App\Http\Controllers\sdk4php\src\openkm\util\Status;
use App\Http\Controllers\sdk4php\src\openkm\util\TypeException;
use App\Http\Controllers\sdk4php\src\openkm\exception\UnsupportMimeTypeException;
use App\Http\Controllers\sdk4php\src\openkm\exception\FileSizeExceededException;
use App\Http\Controllers\sdk4php\src\openkm\exception\VirusDetectedException;
use App\Http\Controllers\sdk4php\src\openkm\exception\AccessDeniedException;
use App\Http\Controllers\sdk4php\src\openkm\exception\PathNotFoundException;
use App\Http\Controllers\sdk4php\src\openkm\exception\RepositoryException;
use App\Http\Controllers\sdk4php\src\openkm\exception\DatabaseException;
use App\Http\Controllers\sdk4php\src\Httpful\Exception\ConnectionErrorException;
use App\Http\Controllers\sdk4php\src\openkm\exception\UnknowException;
use App\Http\Controllers\sdk4php\src\openkm\exception\ItemExistsException;
use App\Http\Controllers\sdk4php\src\openkm\exception\ExtensionException;
use App\Http\Controllers\sdk4php\src\openkm\exception\AutomationException;
use App\Http\Controllers\sdk4php\src\openkm\exception\LockException;
use App\Http\Controllers\sdk4php\src\openkm\exception\UserQuotaExceededException;
use App\Http\Controllers\sdk4php\src\openkm\exception\IOException;
use App\Http\Controllers\sdk4php\src\openkm\exception\VersionException;
use App\Http\Controllers\sdk4php\src\openkm\exception\PrincipalAdapterException;

/**
 * DocumentImpl
 *
 * @author sochoa
 */
class DocumentImpl extends ClientImpl implements BaseDocument {

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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_CREATE_SIMPLE);
            $params = [];
            $params['docPath'] = $docPath;
            $params['content'] = $content;
            $client = Request::post($uri);
            $client->body($params, Mime::UPLOAD);
            $client->authenticateWith($this->user, $this->password);
            $client->expects(Mime::XML);
            $response = $client->send();
            if ($response->code == Status::OK) {
                return $this->phpDocument($response->body);
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::IO_EXCEPTION) {
                            throw new IOException($error . ': ' . $msg);
                        } else if ($error == TypeException::UNSUPPORTED_MIME_TYPE_EXCEPTION) {
                            throw new UnsupportMimeTypeException($error . ': ' . $msg);
                        } else if ($error == TypeException::FILE_SIZE_EXCEEDED_EXCEPTION) {
                            throw new FileSizeExceededException($error . ': ' . $msg);
                        } else if ($error == TypeException::USER_QUOTA_EXCEEDED_EXCEPTION) {
                            throw new UserQuotaExceededException($error . ': ' . $msg);
                        } else if ($error == TypeException::VIRUS_DETECTED_EXCEPTION) {
                            throw new VirusDetectedException($error . ': ' . $msg);
                        } else if ($error == TypeException::ITEM_EXISTS_EXCEPTION) {
                            throw new ItemExistsException($error . ': ' . $msg);
                        } else if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::EXTENSION_EXCEPTION) {
                            throw new ExtensionException($error . ': ' . $msg);
                        } else if ($error == TypeException::AUTOMATION_EXCEPTION) {
                            throw new AutomationException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_DELETE);
            $uri .= '?docId=' . urlencode($docId);
            $client = Request::delete($uri);
            $response = $this->getClient($client);
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
                        } else if ($error == TypeException::EXTENSION_EXCEPTION) {
                            throw new ExtensionException($error . ': ' . $msg);
                        } else if ($error == TypeException::AUTOMATION_EXCEPTION) {
                            throw new AutomationException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_GET_PROPERTIES);
            $uri .= '?docId=' . urlencode($docId);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                return $this->phpDocument($response->body);
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_GET_CONTENT);
            $uri .= '?docId=' . urlencode($docId);
            $client = Request::get($uri);
            $response = $this->getClientWithUploadResponse($client);
            if ($response->code == Status::OK) {
                return $response->body;
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::IO_EXCEPTION) {
                            throw new IOException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_GET_CONTENT_BY_VERSION);
            $uri .= '?docId=' . urlencode($docId) . '&versionId=' . urlencode($versionId);
            $client = Request::get($uri);
            $response = $this->getClientWithUploadResponse($client);
            if ($response->code == Status::OK) {
                return $response->body;
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::IO_EXCEPTION) {
                            throw new IOException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_GET_CHILDREN);
            $uri .= '?fldId=' . urlencode($fldId);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $documents = [];
                foreach ($response->body->document as $documentXML) {
                    $documents[] = $this->phpDocument($documentXML);
                }
                return $documents;
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_RENAME);
            $uri .= '?docId=' . urlencode($docId) . '&newName=' . urlencode($newName);
            $client = Request::put($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                return $this->phpDocument($response->body);
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::ITEM_EXISTS_EXCEPTION) {
                            throw new ItemExistsException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_SET_PROPERTIES);
            $client = Request::put($uri);
            $documentXML = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><document></document>');
            $documentXML->addChild('author', $document->getAuthor());
            $documentXML->addChild('created', $document->getCreated());            
            $documentXML->addChild('path', $document->getPath());
            $documentXML->addChild('permissions', $document->getPermissions());
            if ($document->isSubscribed() == 1) {
                $documentXML->addChild('subscribed', 'true');
            } else {
                $documentXML->addChild('subscribed', 'false');
            }
            $documentXML->addChild('uuid', $document->getUuid());
            if ($document->getActualVersion() != null) {
                //version
                $versionXML = $documentXML->addChild('actualVersion');
                $versionXML->addChild('actual', $document->getActualVersion()->getActual());
                $versionXML->addChild('author', $document->getActualVersion()->getAuthor());
                $versionXML->addChild('checksum', $document->getActualVersion()->getChecksum());
                $versionXML->addChild('created', $document->getActualVersion()->getCreated());
                $versionXML->addChild('name', $document->getActualVersion()->getName());
                $versionXML->addChild('size', $document->getActualVersion()->getSize());
            }
            if ($document->isCheckedOut() == 1) {
                $documentXML->addChild('checkedOut', 'true');
            } else {
                $documentXML->addChild('checkedOut', 'false');
            }
            if ($document->isConvertibleToDxf() == 1) {
                $documentXML->addChild('convertibleToDxf', 'true');
            } else {
                $documentXML->addChild('convertibleToDxf', 'false');
            }
            if ($document->isConvertibleToPdf() == 1) {
                $documentXML->addChild('convertibleToPdf', 'true');
            } else {
                $documentXML->addChild('convertibleToPdf', 'false');
            }
            if ($document->isConvertibleToSwf() == 1) {
                $documentXML->addChild('convertibleToSwf', 'true');
            } else {
                $documentXML->addChild('convertibleToSwf', 'false');
            }

            $documentXML->addChild('title', $document->getTitle());
            $documentXML->addChild('description', $document->getDescription());
            $documentXML->addChild('language', $document->getLanguage());
            $documentXML->addChild('lastModified', $document->getLastModified());
            if ($document->getLockInfo() != null) {
                //LockInfo
                $lockInfoXML = $documentXML->addChild('lockInfo');
                $lockInfoXML->addChild('nodePath', $document->getLockInfo()->getNodePath());
                $lockInfoXML->addChild('owner', $document->getLockInfo()->getOwner());
                $lockInfoXML->addChild('token', $document->getLockInfo()->getToken());
            }

            if ($document->isLocked() == 1) {
                $documentXML->addChild('locked', 'true');
            } else {
                $documentXML->addChild('locked', 'false');
            }
            $documentXML->addChild('mimeType', $document->getMimeType());
            if ($document->isSigned() == 1) {
                $documentXML->addChild('signed', 'true');
            } else {
                $documentXML->addChild('signed', 'false');
            }

            foreach ($document->getCategories() as $category) {
                $categoryXML = $documentXML->addChild('categories');
                $categoryXML->addChild('author', $category->getAuthor());
                $categoryXML->addChild('created', $category->getCreated());                
                $categoryXML->addChild('path', $category->getPath());
                $categoryXML->addChild('permissions', $category->getPermissions());
                if ($category->isSubscribed() == 1) {
                    $categoryXML->addChild('subscribed', 'true');
                } else {
                    $categoryXML->addChild('subscribed', 'false');
                }
                $categoryXML->addChild('uuid', $category->getUuid());
                if ($category->isHasChildren() == 1) {
                    $categoryXML->addChild('hasChildren', 'true');
                } else {
                    $categoryXML->addChild('hasChildren', 'false');
                }                
            }
            foreach ($document->getKeywords() as $keyword) {
                $documentXML->addChild('keywords', $keyword);
            }

            foreach ($document->getNotes() as $note) {
                $noteXML = $documentXML->addChild('notes');
                $noteXML->addChild('author', $note->getAuthor());
                $noteXML->addChild('date', $note->getDate());
                $noteXML->addChild('path', $note->getPath());
                $noteXML->addChild('text', $note->getText());
            }
            foreach ($document->getSubscriptors() as $subscriptor) {
                $documentXML->addChild('subscriptors', $subscriptor);
            }
            $client->body($documentXML->asXML());
            $response = $this->getClient($client);
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
                        } else if ($error == TypeException::VERSION_EXCEPTION) {
                            throw new VersionException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_CHECKOUT);
            $uri .= '?docId=' . urlencode($docId);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_CANCEL_CHECKOUT);
            $uri .= '?docId=' . urlencode($docId);
            $client = Request::put($uri);
            $response = $this->getClient($client);
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_FORCE_CANCEL_CHECKOUT);
            $uri .= '?docId=' . urlencode($docId);
            $client = Request::put($uri);
            $response = $this->getClient($client);
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
                        } else if ($error == TypeException::PRINCIPAL_ADAPTER_EXCEPTION) {
                            throw new PrincipalAdapterException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_IS_CHECKOUT);
            $uri .= '?docId=' . urlencode($docId);
            $client = Request::get($uri);
            $response = $this->getClientWithHTMLResponse($client);
            if ($response->code == Status::OK) {
                if ($response->body == 'true') {
                    return true;
                } else {
                    return false;
                }
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_CHECKIN);
            $params = [];
            $params['docId'] = $docId;
            $params['content'] = $content;
            $params['comment'] = $comment;
            $client = Request::post($uri);
            $client->body($params, Mime::UPLOAD);
            $client->authenticateWith($this->user, $this->password);
            $client->expects(Mime::XML);
            $response = $client->send();
            if ($response->code == Status::OK) {
                return $this->phpVersion($response->body);
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::IO_EXCEPTION) {
                            throw new IOException($error . ': ' . $msg);
                        } else if ($error == TypeException::FILE_SIZE_EXCEEDED_EXCEPTION) {
                            throw new FileSizeExceededException($error . ': ' . $msg);
                        } else if ($error == TypeException::USER_QUOTA_EXCEEDED_EXCEPTION) {
                            throw new UserQuotaExceededException($error . ': ' . $msg);
                        } else if ($error == TypeException::VIRUS_DETECTED_EXCEPTION) {
                            throw new VirusDetectedException($error . ': ' . $msg);
                        } else if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::EXTENSION_EXCEPTION) {
                            throw new ExtensionException($error . ': ' . $msg);
                        } else if ($error == TypeException::AUTOMATION_EXCEPTION) {
                            throw new AutomationException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_GET_VERSION_HISTORY);
            $uri .='?docId=' . urlencode($docId);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $versions = [];
                foreach ($response->body->version as $versionXML) {
                    $versions[] = $this->phpVersion($versionXML);
                }
                return $versions;
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_LOCK);
            $uri .= '?docId=' . urlencode($docId);
            $client = Request::put($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                return $this->phpLockInfo($response->body);
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_UNLOCK);
            $uri .= '?docId=' . urlencode($docId);
            $client = Request::put($uri);
            $response = $this->getClient($client);
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_FORCE_UNLOCK);
            $uri .= '?docId=' . urlencode($docId);
            $client = Request::put($uri);
            $response = $this->getClient($client);
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_IS_LOCKED);
            $uri .= '?docId=' . urlencode($docId);
            $client = Request::get($uri);
            $response = $this->getClientWithHTMLResponse($client);
            if ($response->code == Status::OK) {
                if ($response->body == 'true') {
                    return true;
                } else {
                    return false;
                }
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_GET_LOCKINFO);
            $uri .= '?docId=' . urlencode($docId);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                return $this->phpLockInfo($response->body);
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_PURGE);
            $uri .= '?docId=' . urlencode($docId);
            $client = Request::put($uri);
            $response = $this->getClient($client);
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
                        } else if ($error == TypeException::EXTENSION_EXCEPTION) {
                            throw new ExtensionException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_MOVE);
            $uri .= '?docId=' . urlencode($docId) . '&dstId=' . urlencode($dstId);
            $client = Request::put($uri);
            $response = $this->getClient($client);
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
                        } else if ($error == TypeException::EXTENSION_EXCEPTION) {
                            throw new ExtensionException($error . ': ' . $msg);
                        } else if ($error == TypeException::ITEM_EXISTS_EXCEPTION) {
                            throw new ItemExistsException($error . ': ' . $msg);
                        } else if ($error == TypeException::AUTOMATION_EXCEPTION) {
                            throw new AutomationException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_COPY);
            $uri .= '?docId=' . urlencode($docId) . '&dstId=' . urlencode($dstId);
            $client = Request::put($uri);
            $response = $this->getClient($client);
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::EXTENSION_EXCEPTION) {
                            throw new ExtensionException($error . ': ' . $msg);
                        } else if ($error == TypeException::ITEM_EXISTS_EXCEPTION) {
                            throw new ItemExistsException($error . ': ' . $msg);
                        } else if ($error == TypeException::AUTOMATION_EXCEPTION) {
                            throw new AutomationException($error . ': ' . $msg);
                        } else if ($error == TypeException::IO_EXCEPTION) {
                            throw new IOException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_RESTORE_VERSION);
            $uri .= '?docId=' . urlencode($docId) . '&versionId=' . urlencode($versionId);
            $client = Request::put($uri);
            $response = $this->getClient($client);
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::EXTENSION_EXCEPTION) {
                            throw new ExtensionException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_PURGE_VERSION_HISTORY);
            $uri .= '?docId=' . urlencode($docId);
            $client = Request::put($uri);
            $response = $this->getClient($client);
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::EXTENSION_EXCEPTION) {
                            throw new ExtensionException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_GET_VERSION_HISTORY_SIZE);
            $uri .= '?docId=' . urlencode($docId);
            $client = Request::get($uri);
            $response = $this->getClientWithHTMLResponse($client);
            if ($response->code == Status::OK) {
                return (int) $response->body;
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_IS_VALID);
            $uri .= '?docId=' . urlencode($docId);
            $client = Request::get($uri);
            $response = $this->getClientWithHTMLResponse($client);
            if ($response->code == Status::OK) {
                if ($response->body == 'true') {
                    return true;
                } else {
                    return false;
                }
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::DOCUMENT_GET_PATH);
            $uri .= '/' . urlencode($uuid);
            $client = Request::get($uri);
            $response = $this->getClientWithHTMLResponse($client);
            if ($response->code == Status::OK) {
                return (string) $response->body;
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else {
                            throw new UnknowException($error . ': ' . $msg);
                        }
                    } else {
                        throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                    }
                } else {
                    throw new UnknowException("HTTP error code " . $response->code . ": " . $response->body);
                }
            }
        } catch (ConnectionErrorException $cee) {
            throw new ConnectionErrorException('ConnectionErrorException: ' . $cee->getMessage());
        }
    }   

}

?>
