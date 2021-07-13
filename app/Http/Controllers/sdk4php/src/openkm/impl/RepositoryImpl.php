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

use openkm\bean\AppVersion;
use openkm\bean\Folder;
use App\Http\Controllers\sdk4php\src\openkm\definition\BaseRepository;
use App\Http\Controllers\sdk4php\src\openkm\util\UriHelper;
use App\Http\Controllers\sdk4php\src\Httpful\Request;
use App\Http\Controllers\sdk4php\src\Httpful\Mime;
use App\Http\Controllers\sdk4php\src\openkm\bean\ScriptExecutionResult;
use App\Http\Controllers\sdk4php\src\openkm\bean\SqlQueryResults;
use App\Http\Controllers\sdk4php\src\openkm\bean\SqlQueryResultColumns;
use App\Http\Controllers\sdk4php\src\openkm\bean\HqlQueryResults;
use App\Http\Controllers\sdk4php\src\openkm\util\Status;
use App\Http\Controllers\sdk4php\src\openkm\util\TypeException;
use App\Http\Controllers\sdk4php\src\openkm\exception\AccessDeniedException;
use App\Http\Controllers\sdk4php\src\openkm\exception\PathNotFoundException;
use App\Http\Controllers\sdk4php\src\openkm\exception\RepositoryException;
use App\Http\Controllers\sdk4php\src\openkm\exception\DatabaseException;
use Httpful\Exception\ConnectionErrorException;
use App\Http\Controllers\sdk4php\src\openkm\exception\UnknowException;
use App\Http\Controllers\sdk4php\src\openkm\exception\LockException;

/**
 * RepositoryImpl
 *
 * @author sochoa
 */
class RepositoryImpl extends ClientImpl implements BaseRepository {  

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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_GET_ROOT_FOLDER);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_GET_ROOT_TRASH);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_GET_ROOT_TEMPLATES);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_GET_ROOT_PERSONAL);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_GET_ROOT_MAIL);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_GET_ROOT_THESAURUS);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_GET_ROOT_CATEGORIES);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_PURGE_TRASH);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_GET_UPDATE_MESSAGE);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_GET_RESPOSITORY_UUID);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_HAS_NODE);
            $uri .= '?nodeId=' . urlencode($nodeId);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_GET_NODE_PATH);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_GET_NODE_UUID);
            $uri .= '?nodePath=' . urlencode($nodePath);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_GET_APP_VERSION);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $appVersionXML = $response->body;
                $appVersion = new AppVersion();
                $appVersion->setBuild((string) $appVersionXML->build);
                $appVersion->setExtension((string) $appVersionXML->estension);
                $appVersion->setMaintenance((string) $appVersionXML->maintenance);
                $appVersion->setMajor((string) $appVersionXML->major);
                $appVersion->setMinor((string) $appVersionXML->minor);
                return $appVersion;
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_EXECUTE_SCRIPT);
            $params = [];
            $params['script'] = $content;
            $client = Request::post($uri);
            $client->body($params, Mime::UPLOAD);
            $client->authenticateWith($this->user, $this->password);
            $client->expects(Mime::XML);
            $response = $client->send();
            if ($response->code == Status::OK) {
                $scriptExecutionResultXML = $response->body;
                $scriptExecutionResult = new ScriptExecutionResult();
                $scriptExecutionResult->setResult((string) $scriptExecutionResultXML->result);
                $scriptExecutionResult->setStderr((string) $scriptExecutionResultXML->stderr);
                $scriptExecutionResult->setStdout((string) $scriptExecutionResultXML->stdout);
                return $scriptExecutionResult;
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_EXECUTE_SQL_QUERY);
            $params = [];
            $params['query'] = $content;
            $client = Request::post($uri);
            $client->body($params, Mime::UPLOAD);
            $client->authenticateWith($this->user, $this->password);
            $client->expects(Mime::XML);
            $response = $client->send();
            if ($response->code == Status::OK) {
                $sqlQueryResults = new SqlQueryResults();
                $results = [];
                foreach ($response->body->sqlQueryResult as $sqlQueryResultColumnsXML) {
                    $sqlQueryResultColumns = new SqlQueryResultColumns();
                    $columns = [];
                    foreach ($sqlQueryResultColumnsXML->sqlQueryResultColumn as $columnsXML) {
                        $columns[] = (string) $columnsXML;
                    }
                    $sqlQueryResultColumns->setColumns($columns);
                    $results[] = $sqlQueryResultColumns;
                }
                $sqlQueryResults->setResults($results);
                return $sqlQueryResults;
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_EXECUTE_HQL_QUERY);
            $params = [];
            $params['query'] = $content;
            $client = Request::post($uri);
            $client->body($params, Mime::UPLOAD);
            $client->authenticateWith($this->user, $this->password);
            $client->expects(Mime::XML);
            $response = $client->send();
            if ($response->code == Status::OK) {
                $hqlQueryResults = new HqlQueryResults();
                $results = [];
                foreach ($response->body->hqlQueryResult as $hqlQueryResultXML) {                                                            
                    $results[] = (string)$hqlQueryResultXML;
                }
                $hqlQueryResults->setResults($results);
                return $hqlQueryResults;
            } else {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
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
