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

use App\Http\Controllers\sdk4php\src\openkm\definition\BasePropertyGroup;
use App\Http\Controllers\sdk4php\src\openkm\util\UriHelper;
use App\Http\Controllers\sdk4php\src\openkm\util\BeanHelper;
use App\Http\Controllers\sdk4php\src\Httpful\Request;
use App\Http\Controllers\sdk4php\src\openkm\util\Status;
use App\Http\Controllers\sdk4php\src\openkm\util\TypeException;
use App\Http\Controllers\sdk4php\src\openkm\exception\AccessDeniedException;
use App\Http\Controllers\sdk4php\src\openkm\exception\PathNotFoundException;
use App\Http\Controllers\sdk4php\src\openkm\exception\RepositoryException;
use App\Http\Controllers\sdk4php\src\openkm\exception\DatabaseException;
use App\Http\Controllers\sdk4php\src\Httpful\Exception\ConnectionErrorException;
use App\Http\Controllers\sdk4php\src\openkm\exception\UnknowException;
use App\Http\Controllers\sdk4php\src\openkm\exception\ExtensionException;
use App\Http\Controllers\sdk4php\src\openkm\exception\AutomationException;
use App\Http\Controllers\sdk4php\src\openkm\exception\LockException;
use App\Http\Controllers\sdk4php\src\openkm\exception\IOException;
use App\Http\Controllers\sdk4php\src\openkm\exception\ParseException;
use App\Http\Controllers\sdk4php\src\openkm\exception\NoSuchGroupException;
use App\Http\Controllers\sdk4php\src\openkm\exception\NoSuchPropertyException;
use App\Http\Controllers\sdk4php\src\openkm\util\FormatUtil;

/**
 * PropertyGroupImpl
 *
 * @author sochoa
 */
class PropertyGroupImpl extends ClientImpl implements BasePropertyGroup {    

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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::PROPERTY_GROUP_ADD_GROUP);
            $uri .= '?nodeId=' . urlencode($nodeId) . '&grpName=' . urlencode($grpName);
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
                        if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::PROPERTY_GROUP_REMOVE_GROUP);
            $uri .= '?nodeId=' . urlencode($nodeId) . '&grpName=' . urlencode($grpName);
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
                        if ($error == TypeException::NO_SUCH_GROUP_EXCEPTION) {
                            throw new NoSuchGroupException($error . ': ' . $msg);
                        } else if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::REPOSITORY_EXCEPTION) {
                            throw new RepositoryException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::PROPERTY_GROUP_GET_GROUPS);
            $uri .= '?nodeId=' . urlencode($nodeId);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $propertyGroups = [];
                foreach ($response->body->propertyGroup as $propertyGroupXML) {
                    $propertyGroups[] = $this->phpPropertyGroup($propertyGroupXML);
                }
                return $propertyGroups;
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
                        } else if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::PARSE_EXCEPTION) {
                            throw new ParseException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::PROPERTY_GROUP_GET_ALL_GROUPS);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $propertyGroups = [];
                foreach ($response->body->propertyGroup as $propertyGroupXML) {
                    $propertyGroups[] = $this->phpPropertyGroup($propertyGroupXML);
                }
                return $propertyGroups;
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
                        } else if ($error == TypeException::PARSE_EXCEPTION) {
                            throw new ParseException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::PROPERTY_GROUP_GET_PROPERTIES);
            $uri .= '?nodeId=' . urlencode($nodeId) . '&grpName=' . urlencode($grpName);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $formElements = [];
                foreach ($response->body->formElementComplex as $formElementComplexXML) {
                    $formElements[] = BeanHelper::copyToFormElement($this->phpFormElementComplex($formElementComplexXML));
                }
                return $formElements;
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
                        } else if ($error == TypeException::PARSE_EXCEPTION) {
                            throw new ParseException($error . ': ' . $msg);
                        } else if ($error == TypeException::NO_SUCH_GROUP_EXCEPTION) {
                            throw new NoSuchGroupException($error . ': ' . $msg);
                        } else if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::PROPERTY_GROUP_GET_PROPERTY_GROUP_FORM);
            $uri .= '?grpName=' . urlencode($grpName);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $formElements = [];
                foreach ($response->body->formElementComplex as $formElementComplexXML) {
                    $formElements[] = BeanHelper::copyToFormElement($this->phpFormElementComplex($formElementComplexXML));
                }
                return $formElements;
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
                        } else if ($error == TypeException::PARSE_EXCEPTION) {
                            throw new ParseException($error . ': ' . $msg);
                        } else if ($error == TypeException::NO_SUCH_GROUP_EXCEPTION) {
                            throw new NoSuchGroupException($error . ': ' . $msg);
                        } else if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::PROPERTY_GROUP_SET_PROPERTIES);
            $uri .= '?nodeId=' . urlencode($nodeId) . '&grpName=' . urlencode($grpName);
            $client = Request::put($uri);
            $formElementsComplex = [];
            foreach ($formElements as $formElement) {
                $formElementsComplex[] = BeanHelper::copyToFormElementComplex($formElement);
            }
            $formElementsComplexXML = new \SimpleXMLElement('<formElementsComplex></formElementsComplex>');
            foreach ($formElementsComplex as $formElementComplex) {
                $formElementComplexXML = $formElementsComplexXML->addChild('formElementComplex');
                $formElementComplexXML->addChild('height', $formElementComplex->getHeight());
                $formElementComplexXML->addChild('label', $formElementComplex->getLabel());
                $formElementComplexXML->addChild('name', $formElementComplex->getName());
                $formElementComplexXML->addChild('objClass', $formElementComplex->getObjClass());
                $formElementComplexXML->addChild('readonly', $formElementComplex->isReadonly());
                $formElementComplexXML->addChild('type', $formElementComplex->getType());
                $formElementComplexXML->addChild('value', $formElementComplex->getValue());
                $formElementComplexXML->addChild('width', $formElementComplex->getWidth());
                foreach ($formElementComplex->getOptions() as $option) {
                    $optionXML = $formElementComplexXML->addChild('options');
                    $optionXML->addChild('label', $option->getLabel());
                    $optionXML->addChild('selected', $option->isSelected());
                    $optionXML->addChild('value', $option->getValue());
                }
                foreach ($formElementComplex->getValidators() as $validator) {
                    $validatorXML = $formElementComplexXML->addChild('validators');
                    $validatorXML->addChild('type', $validator->getType());
                    $validatorXML->addChild('parameter', $validator->getParameter());
                }
            }
            $client->body($formElementsComplexXML->asXML());
            $response = $this->getClient($client);
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::IO_EXCEPTION) {
                            throw new IOException($error . ': ' . $msg);
                        } else if ($error == TypeException::PARSE_EXCEPTION) {
                            throw new ParseException($error . ': ' . $msg);
                        } else if ($error == TypeException::NO_SUCH_PROPERTY_EXCEPTION) {
                            throw new NoSuchPropertyException($error . ': ' . $msg);
                        } else if ($error == TypeException::NO_SUCH_GROUP_EXCEPTION) {
                            throw new NoSuchGroupException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::PROPERTY_GROUP_SET_PROPERTIES_SIMPLE);
            $uri .= '?nodeId=' . urlencode($nodeId) . '&grpName=' . urlencode($grpName);
            $client = Request::put($uri);
            $simplePropertiesGroupXML = new \SimpleXMLElement('<simplePropertiesGroup></simplePropertiesGroup>');
            foreach ($properties as $key => $value) {
                $simplePropertyGroupXML = $simplePropertiesGroupXML->addChild('simplePropertyGroup');
                $simplePropertyGroupXML->addChild('name', $key);
                $simplePropertyGroupXML->addChild('value', FormatUtil::cleanXSS($value));
            }
            $client->body($simplePropertiesGroupXML->asXML());
            $response = $this->getClient($client);
            if ($response->code != Status::NO_CONTENT) {
                if ($response->code == Status::UNAUTHORIZED) {
                    throw new AccessDeniedException($response->body, $response->code);
                } else if ($response->code == Status::INTERNAL_SERVER_ERROR) {
                    $position = strpos($response->body, ':');
                    if ($position) {
                        $error = substr($response->body, 0, $position);
                        $msg = substr($response->body, $position + 1, strlen($response->body));
                        if ($error == TypeException::IO_EXCEPTION) {
                            throw new IOException($error . ': ' . $msg);
                        } else if ($error == TypeException::PARSE_EXCEPTION) {
                            throw new ParseException($error . ': ' . $msg);
                        } else if ($error == TypeException::NO_SUCH_PROPERTY_EXCEPTION) {
                            throw new NoSuchPropertyException($error . ': ' . $msg);
                        } else if ($error == TypeException::NO_SUCH_GROUP_EXCEPTION) {
                            throw new NoSuchGroupException($error . ': ' . $msg);
                        } else if ($error == TypeException::LOCK_EXCEPTION) {
                            throw new LockException($error . ': ' . $msg);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::PROPERTY_GROUP_HAS_GROUP);
            $uri .= '?nodeId=' . urlencode($nodeId) . '&grpName=' . urlencode($grpName);
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
                        } else if ($error == TypeException::PATH_NOT_FOUND_EXCEPTION) {
                            throw new PathNotFoundException($error . ': ' . $msg);
                        } else if ($error == TypeException::DATABASE_EXCEPTION) {
                            throw new DatabaseException($error . ': ' . $msg);
                        } else if ($error == TypeException::IO_EXCEPTION) {
                            throw new IOException($error . ': ' . $msg);
                        } else if ($error == TypeException::PARSE_EXCEPTION) {
                            throw new ParseException($error . ': ' . $msg);
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
