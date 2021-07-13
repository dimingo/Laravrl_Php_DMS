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

use App\Http\Controllers\sdk4php\src\openkm\definition\BaseProperty;
use App\Http\Controllers\sdk4php\src\openkm\util\UriHelper;
use App\Http\Controllers\sdk4php\src\Httpful\Request;
use App\Http\Controllers\sdk4php\src\Httpful\Mime;
use App\Http\Controllers\sdk4php\src\openkm\util\Status;
use App\Http\Controllers\sdk4php\src\openkm\util\TypeException;
use App\Http\Controllers\sdk4php\src\openkm\exception\AccessDeniedException;
use App\Http\Controllers\sdk4php\src\openkm\exception\PathNotFoundException;
use App\Http\Controllers\sdk4php\src\openkm\exception\RepositoryException;
use App\Http\Controllers\sdk4php\src\openkm\exception\DatabaseException;
use Httpful\Exception\ConnectionErrorException;
use App\Http\Controllers\sdk4php\src\openkm\exception\UnknowException;
use App\Http\Controllers\sdk4php\src\openkm\exception\LockException;
use App\Http\Controllers\sdk4php\src\openkm\exception\VersionException;

/**
 * PropertyImpl
 *
 * @author sochoa
 */
class PropertyImpl extends ClientImpl implements BaseProperty {  

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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::PROPERTY_ADD_CATEGORY);
            $uri .= '?nodeId=' . urlencode($nodeId) . '&catId=' . urlencode($catId);
            $client = Request::post($uri);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::PROPERTY_REMOVE_CATEGORY);
            $uri .= '?nodeId=' . urlencode($nodeId) . '&catId=' . urlencode($catId);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::PROPERTY_ADD_KEYWORD);
            $uri .= '?nodeId=' . urlencode($nodeId) . '&keyword=' . urlencode($keyword);
            $client = Request::post($uri);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::PROPERTY_REMOVE_KEYWORD);
            $uri .= '?nodeId=' . urlencode($nodeId) . '&keyword=' . urlencode($keyword);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::PROPERTY_SET_ENCRYPTION);
            $uri .= '?nodeId=' . urlencode($nodeId) . '&cipherName=' . urlencode($cipherName);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::PROPERTY_UNSET_ENCRYPTION);
            $uri .= '?nodeId=' . urlencode($nodeId);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::PROPERTY_SET_SIGNED);
            $uri .= '?nodeId=' . urlencode($nodeId) . '&signed=' . urlencode($signed);
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

}

?>
