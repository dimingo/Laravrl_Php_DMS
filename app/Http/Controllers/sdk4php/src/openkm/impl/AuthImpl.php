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

use App\Http\Controllers\sdk4php\src\openkm\definition\BaseAuth;
use App\Http\Controllers\sdk4php\src\openkm\bean\GrantedRole;
use App\Http\Controllers\sdk4php\src\openkm\bean\GrantedUser;
use App\Http\Controllers\sdk4php\src\openkm\util\UriHelper;
use App\Http\Controllers\sdk4php\src\Httpful\Request;
use App\Http\Controllers\sdk4php\src\Httpful\Mime;
use App\Http\Controllers\sdk4php\src\openkm\util\Status;
use App\Http\Controllers\sdk4php\src\openkm\util\TypeException;
use App\Http\Controllers\sdk4php\src\openkm\exception\PrincipalAdapterException;
use App\Http\Controllers\sdk4php\src\openkm\exception\AccessDeniedException;
use App\Http\Controllers\sdk4php\src\openkm\exception\PathNotFoundException;
use App\Http\Controllers\sdk4php\src\openkm\exception\RepositoryException;
use App\Http\Controllers\sdk4php\src\openkm\exception\DatabaseException;
use App\Http\Controllers\sdk4php\src\Httpful\Exception\ConnectionErrorException;
use App\Http\Controllers\sdk4php\src\openkm\exception\UnknowException;

/**
 * AuthImpl
 *
 * @author sochoa
 */
class AuthImpl extends ClientImpl implements BaseAuth {

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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::AUTH_GET_GRANTED_ROLES);
            $uri .= '?nodeId=' . urlencode($nodeId);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $grantedRoles = [];
                foreach ($response->body->grantedRole as $grantedRoleXML) {
                    $grantedRole = new GrantedRole();
                    $grantedRole->setPermissions((int) $grantedRoleXML->permissions);
                    $grantedRole->setRole((string) $grantedRoleXML->role);
                    $grantedRoles[] = $grantedRole;
                }
                return $grantedRoles;
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
                        } else if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::AUTH_GET_GRANTED_USERS);
            $uri .= '?nodeId=' . urlencode($nodeId);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $grantedUsers = [];
                foreach ($response->body->grantedUser as $grantedUserXML) {
                    $grantedUser = new GrantedUser();
                    $grantedUser->setPermissions((int) $grantedUserXML->permissions);
                    $grantedUser->setUser((string) $grantedUserXML->user);
                    $grantedUsers[] = $grantedUser;
                }
                return $grantedUsers;
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
                        } else if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::AUTH_GET_ROLES);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $roles = [];
                foreach ($response->body->role as $roleXML) {
                    $roles[] = (string) $roleXML;
                }
                return $roles;
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
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::AUTH_GET_USERS);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $users = [];
                foreach ($response->body->user as $userXML) {
                    $users[] = (string) $userXML;
                }
                return $users;
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
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::AUTH_GRANT_ROLE);
            $params = [];
            $params['nodeId'] = $nodeId;
            $params['role'] = $role;
            $params['permissions'] = $permissions;
            $params['recursive'] = $recursive;
            $client = Request::put($uri);
            $client->body($params, Mime::FORM);
            $client->authenticateWith($this->user, $this->password);
            $response = $client->send();
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::AUTH_GRANT_USER);
            $params = [];
            $params['nodeId'] = $nodeId;
            $params['user'] = $user;
            $params['permissions'] = $permissions;
            $params['recursive'] = $recursive;
            $client = Request::put($uri);
            $client->body($params, Mime::FORM);
            $client->authenticateWith($this->user, $this->password);
            $response = $client->send();
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::AUTH_REVOKE_ROLE);
            $params = [];
            $params['nodeId'] = $nodeId;
            $params['role'] = $role;
            $params['permissions'] = $permissions;
            $params['recursive'] = $recursive;
            $client = Request::put($uri);
            $client->body($params, Mime::FORM);
            $client->authenticateWith($this->user, $this->password);
            $response = $client->send();
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::AUTH_REVOKE_USER);
            $params = [];
            $params['nodeId'] = $nodeId;
            $params['user'] = $user;
            $params['permissions'] = $permissions;
            $params['recursive'] = $recursive;
            $client = Request::put($uri);
            $client->body($params, Mime::FORM);
            $client->authenticateWith($this->user, $this->password);
            $response = $client->send();
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::AUTH_GET_ROLES_BY_USER);
            $uri .= '/' . urlencode($user);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $roles = [];
                foreach ($response->body->role as $roleXML) {
                    $roles[] = (string) $roleXML;
                }
                return $roles;
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
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::AUTH_GET_USERS_BY_ROLE);
            $uri .= '/' . urlencode($role);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $users = [];
                foreach ($response->body->user as $userXML) {
                    $users[] = (string) $userXML;
                }
                return $users;
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
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::AUTH_GET_MAIL);
            $uri .= '/' . urlencode($user);
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
                        if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::AUTH_GET_NAME);
            $uri .= '/' . urlencode($user);
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
                        if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
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
}

?>
