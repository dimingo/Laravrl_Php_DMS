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

use App\Http\Controllers\sdk4php\src\openkm\definition\BaseSearch;
use App\Http\Controllers\sdk4php\src\openkm\bean\QueryParams;
use App\Http\Controllers\sdk4php\src\openkm\bean\Note;
use App\Http\Controllers\sdk4php\src\openkm\bean\Document;
use App\Http\Controllers\sdk4php\src\openkm\bean\LockInfo;
use App\Http\Controllers\sdk4php\src\openkm\bean\Version;
use App\Http\Controllers\sdk4php\src\openkm\bean\Folder;
use App\Http\Controllers\sdk4php\src\openkm\bean\ResultSet;
use App\Http\Controllers\sdk4php\src\openkm\bean\QueryResult;
use App\Http\Controllers\sdk4php\src\openkm\bean\Entry;
use App\Http\Controllers\sdk4php\src\openkm\bean\KeywordMap;
use App\Http\Controllers\sdk4php\src\openkm\util\UriHelper;
use App\Http\Controllers\sdk4php\src\Httpful\Request;
use App\Http\Controllers\sdk4php\src\Httpful\Mime;
use App\Http\Controllers\sdk4php\src\openkm\util\Status;
use App\Http\Controllers\sdk4php\src\openkm\util\TypeException;
use App\Http\Controllers\sdk4php\src\openkm\exception\AccessDeniedException;
use App\Http\Controllers\sdk4php\src\openkm\exception\RepositoryException;
use App\Http\Controllers\sdk4php\src\openkm\exception\DatabaseException;
use App\Http\Controllers\sdk4php\src\Httpful\Exception\ConnectionErrorException;
use App\Http\Controllers\sdk4php\src\openkm\exception\UnknowException;
use App\Http\Controllers\sdk4php\src\openkm\exception\IOException;
use App\Http\Controllers\sdk4php\src\openkm\exception\ParseException;

/**
 * SearchImpl
 *
 * @author sochoa
 */
class SearchImpl extends ClientImpl implements BaseSearch {

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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_FIND_BY_CONTENT);
            $uri .= '?content=' . urlencode($content);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $queryResults = [];
                foreach ($response->body->queryResult as $queryResultXML) {
                    $queryResults[] = $this->phpQueryResult($queryResultXML);
                }
                return $queryResults;
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_FIND_BY_NAME);
            $uri .= '?name=' . urlencode($name);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $queryResults = [];
                foreach ($response->body->queryResult as $queryResultXML) {
                    $queryResults[] = $this->phpQueryResult($queryResultXML);
                }
                return $queryResults;
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_FIND_BY_KEYWORDS);
            for ($i = 0; $i < count($keywords); $i++) {
                if ($i == 0) {
                    $uri .= '?keyword=' . urlencode($keywords[$i]);
                } else {
                    $uri .= '&keyword=' . urlencode($keywords[$i]);
                }
            }
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $queryResults = [];
                foreach ($response->body->queryResult as $queryResultXML) {
                    $queryResults[] = $this->phpQueryResult($queryResultXML);
                }
                return $queryResults;
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
    public function find(QueryParams $queryParams) {
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_FIND);
            $uri = $this->makeUri($queryParams, $uri);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $queryResults = [];
                foreach ($response->body->queryResult as $queryResultXML) {
                    $queryResults[] = $this->phpQueryResult($queryResultXML);
                }
                return $queryResults;
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
    public function findPaginated(QueryParams $queryParams, $offset, $limit) {
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_FIND_PAGINATED);
            $uri = $this->makeUri($queryParams, $uri);
            $uri .= '&offset=' . $offset . '&limit=' . $limit;
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $resulSetXML = $response->body;
                $resultSet = new ResultSet();
                $resultSet->setTotal((int) $resulSetXML->total);
                $results = [];
                foreach ($resulSetXML->results as $queryResultXML) {
                    $results[] = $this->phpQueryResult($queryResultXML);
                }
                $resultSet->setResults($results);
                return $resultSet;
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_FIND_SIMPLE_QUERY_PAGINATED);
            $uri .= '?statement=' . urlencode($statement) . '&offset=' . $offset . '&limit=' . $limit;
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $resulSetXML = $response->body;
                $resultSet = new ResultSet();
                $resultSet->setTotal($resulSetXML->total);
                $results = [];
                foreach ($resulSetXML->results as $queryResultXML) {
                    $results[] = $this->phpQueryResult($queryResultXML);
                }
                $resultSet->setResults($results);
                return $resultSet;
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_FIND_SIMPLE_QUERY_PAGINATED);
            $uri .= '/' . urlencode($uuid) . '/' . $max;
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $resulSetXML = $response->body;
                $resultSet = new ResultSet();
                $resultSet->setTotal($resulSetXML->total);
                $results = [];
                foreach ($resulSetXML->results as $queryResultXML) {
                    $results[] = $this->phpQueryResult($queryResultXML);
                }
                $resultSet->setResults($results);
                return $resultSet;
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_GET_KEYWORD_MAP);
            for ($i = 0; $i < count($filter); $i++) {
                if ($i == 0) {
                    $uri .= '?filter=' . urlencode($filter[$i]);
                } else {
                    $uri .= '&filter=' . urlencode($filter[$i]);
                }
            }
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $keywordMaps = [];
                foreach ($response->body->keywordMap as $keywordMapXML) {
                    $keywordMap = new KeywordMap();
                    $keywordMap->setKeyword((string) $keywordMapXML->keyword);
                    $keywordMap->setOccurs((int) $keywordMapXML->occurs);
                    $keywordMaps[] = $keywordMap;
                }
                return $keywordMaps;
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_GET_CATEGORIZED_DOCUMENTS);
            $uri .= '/' . urlencode($categoryId);
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
    public function saveSearch(QueryParams $params) {
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_SAVE_SEARCH);
            $client = Request::post($uri);
            $queryParamsXML = new \SimpleXMLElement('<queryParams></queryParams>');
            $queryParamsXML->addChild('author', $params->getAuthor());
            $queryParamsXML->addChild('content', $params->getContent());
            if ($params->isDashboard() == 1) {
                $queryParamsXML->addChild('dashboard', 'true');
            } else {
                $queryParamsXML->addChild('dashboard', 'false');
            }
            $queryParamsXML->addChild('domain', $params->getDomain());
            $queryParamsXML->addChild('id', $params->getId());
            $queryParamsXML->addChild('lastModifiedFrom', $params->getLastModifiedFrom());
            $queryParamsXML->addChild('lastModifiedTo', $params->getLastModifiedTo());
            $queryParamsXML->addChild('mailFrom', $params->getMailFrom());
            $queryParamsXML->addChild('mailSubject', $params->getMailSubject());
            $queryParamsXML->addChild('mailTo', $params->getMailTo());
            $queryParamsXML->addChild('mimeType', $params->getMimeType());
            $queryParamsXML->addChild('name', $params->getName());
            $queryParamsXML->addChild('operator', $params->getOperator());
            $queryParamsXML->addChild('path', $params->getPath());
            $queryParamsXML->addChild('queryName', $params->getQueryName());
            $queryParamsXML->addChild('user', $params->getUser());
            foreach ($params->getCategories() as $category) {
                $queryParamsXML->addChild('categories', $category);
            }
            foreach ($params->getKeywords() as $keyword) {
                $queryParamsXML->addChild('keywords', $keyword);
            }
            $propertiesXML = $queryParamsXML->addChild('properties');
            foreach ($params->getProperties() as $entry) {
                $entryXML = $propertiesXML->addChild('entry');
                $entryXML->addChild('key', $entry->getKey());
                $entryXML->addChild('value', $entry->getValue());
            }
            $client->body($queryParamsXML->asXML());
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
    public function updateSearch(QueryParams $params) {
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_UPDATE_SEARCH);
            $client = Request::put($uri);
            $queryParamsXML = new \SimpleXMLElement('<queryParams></queryParams>');
            $queryParamsXML->addChild('author', $params->getAuthor());
            $queryParamsXML->addChild('content', $params->getContent());
            if ($params->isDashboard() == 1) {
                $queryParamsXML->addChild('dashboard', 'true');
            } else {
                $queryParamsXML->addChild('dashboard', 'false');
            }
            $queryParamsXML->addChild('domain', $params->getDomain());
            $queryParamsXML->addChild('id', $params->getId());
            $queryParamsXML->addChild('lastModifiedFrom', $params->getLastModifiedFrom());
            $queryParamsXML->addChild('lastModifiedTo', $params->getLastModifiedTo());
            $queryParamsXML->addChild('mailFrom', $params->getMailFrom());
            $queryParamsXML->addChild('mailSubject', $params->getMailSubject());
            $queryParamsXML->addChild('mailTo', $params->getMailTo());
            $queryParamsXML->addChild('mimeType', $params->getMimeType());
            $queryParamsXML->addChild('name', $params->getName());
            $queryParamsXML->addChild('operator', $params->getOperator());
            $queryParamsXML->addChild('path', $params->getPath());
            $queryParamsXML->addChild('queryName', $params->getQueryName());
            $queryParamsXML->addChild('user', $params->getUser());
            foreach ($params->getCategories() as $category) {
                $queryParamsXML->addChild('categories', $category);
            }
            foreach ($params->getKeywords() as $keyword) {
                $queryParamsXML->addChild('keywords', $keyword);
            }
            $propertiesXML = $queryParamsXML->addChild('properties');
            foreach ($params->getProperties() as $entry) {
                $entryXML = $propertiesXML->addChild('entry');
                $entryXML->addChild('key', $entry->getKey());
                $entryXML->addChild('value', $entry->getValue());
            }
            $client->body($queryParamsXML->asXML());
            $response = $this->getClient($client);
            if ($response->code != Status::NO_CONTENT) {
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_GET_SEARCH);
            $uri .= '/' . urlencode($qpId);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                return $this->phpQueryParams($response->body);
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_GET_ALL_SEARCH);
            $client = Request::get($uri);
            $response = $this->getClient($client);
            if ($response->code == Status::OK) {
                $queriesParams = [];
                foreach ($response->body->queryParams as $queryParamsXML) {
                    $queriesParams[] = $this->phpQueryParams($queryParamsXML);
                }
                return $queriesParams;
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
        try {
            $uri = UriHelper::getUri($this->host, UriHelper::REPOSITORY_DELETE_SEARCH);
            $uri .= '/' . urlencode($qpId);
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

    public function makeUri(QueryParams $queryParams, $uri) {
        $uri .= '?domain=' . $queryParams->getDomain();
        if ($queryParams->getContent() != "") {
            $uri .= '&content=' . urlencode($queryParams->getContent());
        }
        if ($queryParams->getName() != '') {
            $uri .= '&name=' . urlencode($queryParams->getName());
        }
        if ($queryParams->getPath() != '') {
            $uri .= '&path=' . urlencode($queryParams->getPath());
        }
        foreach ($queryParams->getKeywords() as $keyword) {
            if ($keyword != '') {
                $uri .= '&keyword=' . urlencode($keyword);
            }
        }
        foreach ($queryParams->getCategories() as $category) {
            if ($category != '') {
                $uri .= '&category=' . urlencode($category);
            }
        }
        foreach ($queryParams->getProperties() as $key => $value) {
            $uri .= '&property=' . $key . '=' . urlencode($value);
        }
        if ($queryParams->getAuthor() != '') {
            $uri .= '&author=' . urlencode($queryParams->getAuthor());
        }
        if ($queryParams->getMimeType() != '') {
            $uri .= '&mimeType=' . urlencode($queryParams->getMimeType());
        }
        if ($queryParams->getLastModifiedFrom() != '') {
            $uri .= '&lastModifiedFrom=' . $queryParams->getLastModifiedFrom();
        }
        if ($queryParams->getLastModifiedTo() != '') {
            $uri .= '&lastModifiedTo=' . $queryParams->getLastModifiedTo();
        }
        if ($queryParams->getMailSubject() != '') {
            $uri .= '&mailSubject=' . urlencode($queryParams->getMailSubject());
        }
        if ($queryParams->getMailFrom() != '') {
            $uri .= '&mailFrom=' . $queryParams->getMailFrom();
        }
        if ($queryParams->getMailTo() != '') {
            $uri .= '&mailTo=' . $queryParams->getMailTo();
        }
        return $uri;
    }
    
}

?>
