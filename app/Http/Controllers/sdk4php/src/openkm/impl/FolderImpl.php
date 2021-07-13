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

use App\Http\Controllers\sdk4php\src\openkm\bean\Folder;
use App\Http\Controllers\sdk4php\src\openkm\bean\Note;
use App\Http\Controllers\sdk4php\src\openkm\bean\ContentInfo;
use App\Http\Controllers\sdk4php\src\openkm\definition\BaseFolder;
use App\Http\Controllers\sdk4php\src\openkm\util\UriHelper;
use App\Http\Controllers\sdk4php\src\Httpful\Request;
use App\Http\Controllers\sdk4php\src\Httpful\Mime;
use App\Http\Controllers\sdk4php\src\App\Http\Controllers\sdk4php\srcopenkm\util\Status;
use App\Http\Controllers\sdk4php\src\App\Http\Controllers\sdk4php\srcopenkm\util\TypeException;
use App\Http\Controllers\sdk4php\src\App\Http\Controllers\sdk4php\srcopenkm\exception\AccessDeniedException;
use App\Http\Controllers\sdk4php\src\App\Http\Controllers\sdk4php\srcopenkm\exception\PathNotFoundException;
use App\Http\Controllers\sdk4php\src\App\Http\Controllers\sdk4php\srcopenkm\exception\RepositoryException;
use App\Http\Controllers\sdk4php\src\App\Http\Controllers\sdk4php\srcopenkm\exception\DatabaseException;
use App\Http\Controllers\sdk4php\srcHttpful\Exception\ConnectionErrorException;
use App\Http\Controllers\sdk4php\srcopenkm\exception\UnknowException;
use App\Http\Controllers\sdk4php\srcopenkm\exception\ItemExistsException;
use App\Http\Controllers\sdk4php\srcopenkm\exception\ExtensionException;
use App\Http\Controllers\sdk4php\srcopenkm\exception\AutomationException;
use App\Http\Controllers\sdk4php\srcopenkm\exception\LockException;
use App\Http\Controllers\sdk4php\srcopenkm\exception\UserQuotaExceededException;
use App\Http\Controllers\sdk4php\srcopenkm\exception\IOException;
use App\Http\Controllers\sdk4php\srcopenkm\exception\VersionException;

/**
 * FolderImpl
 *
 * @author sochoa
 */
class FolderImpl extends ClientImpl implements BaseFolder {

    /**
     * Create folder
     * @param  Folder $fld The variable path into the parameter fld, must be initializated. It indicates the folder path into OpenKM.
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
    public function createFolder(Folder $fld) {
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::FOLDER_CREATE);
            $client = Request::post($uri);
            $folderXML = new \SimpleXMLElement('<folder></folder>');
            $folderXML->addChild('author', $fld->getAuthor());
            $folderXML->addChild('created', $fld->getCreated());            
            $folderXML->addChild('path', $fld->getPath());
            $folderXML->addChild('permissions', $fld->getPermissions());
            $folderXML->addChild('subscribed', $fld->isSubscribed());
            $folderXML->addChild('uuid', $fld->getUuid());
            $folderXML->addChild('hasChildren', $fld->isHasChildren());
            if ($fld->isSubscribed() == 1) {
                $folderXML->addChild('subscribed', 'true');
            } else {
                $folderXML->addChild('subscribed', 'false');
            }
            $folderXML->addChild('uuid', $fld->getUuid());
            if ($fld->isHasChildren() == 1) {
                $folderXML->addChild('hasChildren', 'true');
            } else {
                $folderXML->addChild('hasChildren', 'false');
            }            
            foreach ($fld->getCategories() as $category) {
                $categoryXML = $folderXML->addChild('categories');
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

            foreach ($fld->getKeywords() as $keyword) {
                $folderXML->addChild('keywords', $keyword);
            }

            foreach ($fld->getNotes() as $note) {
                $noteXML = $folderXML->addChild('notes');
                $noteXML->addChild('author', $note->getAuthor());
                $noteXML->addChild('date', $note->getDate());
                $noteXML->addChild('path', $note->getPath());
                $noteXML->addChild('text', $note->getText());
            }
            foreach ($fld->getSubscriptors() as $subscriptor) {
                $folderXML->addChild('subscriptors', $subscriptor);
            }
            $client->body($folderXML->asXML());
            $response = $this->getClient($client);
            if ($response->code == App\Http\Controllers\sdk4php\src\App\Http\Controllers\sdk4php\src::OK) {
                return $this->phpFolderComplete($response->body);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::FOLDER_CREATE_SIMPLE);
            $client = Request::post($uri);
            $client->body($fldPath);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                return $this->phpFolderComplete($response->body);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::FOLDER_GET_PROPERTIES);
            $uri .= '?fldId=' . urlencode($fldId);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                return $this->phpFolderComplete($response->body);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::FOLDER_DELETE);
            $uri .= '?fldId=' . urlencode($fldId);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::FOLDER_RENAME);
            $uri .= '?fldId=' . urlencode($fldId) . '&newName=' . urlencode($newName);
            $client = Request::put($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                return $this->phpFolderComplete($response->body);
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
     * Move folder into some folder or record.
     * @param string $fldId The uuid or path of the Folder
     * @param string $dstId The uuid or path of the Folder or Record
     * @throws ConnectionErrorException 
     * @throws AccessDeniedException
     * @throws PathNotFoundException
     * @throws RepositoryException
     * @throws DatabaseException
     * @throws LockException
     * @throws UnknowException
     */
    public function moveFolder($fldId, $dstId) {
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::FOLDER_MOVE);
            $uri .= '?fldId=' . urlencode($fldId) . '&dstId=' . urlencode($dstId);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::FOLDER_GET_CHILDREN);
            $uri .= '?fldId=' . urlencode($fldId);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $folders = [];
                foreach ($response->body->folder as $folderXML) {
                    $folders[] = $this->phpFolderComplete($folderXML);
                }
                return $folders;
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::FOLDER_IS_VALID);
            $uri .= '?fldId=' . urlencode($fldId);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::FOLDER_GET_PATH);
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

}

?>
