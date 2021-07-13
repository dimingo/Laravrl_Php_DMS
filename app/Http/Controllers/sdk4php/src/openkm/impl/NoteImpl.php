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

use Httpful\Exception\ConnectionErrorException;
use Httpful\Request;
use openkm\bean\Note;
use App\Http\Controllers\sdk4php\src\openkm\definition\BaseNote;
use openkm\exception\AccessDeniedException;
use openkm\exception\DatabaseException;
use openkm\exception\LockException;
use openkm\exception\PathNotFoundException;
use openkm\exception\RepositoryException;
use openkm\exception\UnknowException;
use openkm\util\Status;
use openkm\util\TypeException;
use openkm\util\UriHelper;

/**
 * NoteImpl
 *
 * @author sochoa
 */
class NoteImpl extends ClientImpl implements BaseNote {

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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::NOTE_ADD);
            $uri .= '?nodeId=' . $nodeId;
            $client = Request::post($uri);
            $client->body($text);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                return $this->phpNote($response->body);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::NOTE_GET);
            $uri .= '?noteId=' . $noteId;
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                return $this->phpNote($response->body);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::NOTE_DELETE);
            $uri .= '?noteId=' . $noteId;
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::NOTE_SET);
            $uri .= '?noteId=' . $noteId;
            $client = Request::put($uri);
            $client->body($text);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::NOTE_LIST);
            $uri .= '?nodeId=' . $nodeId;
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $notes = [];
                foreach ($response->body->note as $noteXML) {
                    $notes[] = $this->phpNote($noteXML);
                }
                return $notes;
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
