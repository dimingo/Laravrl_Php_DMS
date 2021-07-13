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

use App\Http\Controllers\sdk4php\src\openkm\bean\QueryParams;

/**
 * BaseSearch
 *
 * @author sochoa
 */
interface BaseSearch {

    public function findByContent($content);

    public function findByName($name);

    public function findByKeywords($keywords = []);

    public function find(QueryParams $queryParams);

    public function findPaginated(QueryParams $queryParams, $offset, $limit);

    public function findSimpleQueryPaginated($statement, $offset, $limit);

    public function findMoreLikeThis($uuid, $max);

    public function getKeywordMap($filter = []);

    public function getCategorizedDocuments($categoryId);

    public function saveSearch(QueryParams $params);

    public function updateSearch(QueryParams $params);

    public function getSearch($qpId);

    public function getAllSearchs();

    public function deleteSearch($qpId);
}

?>
