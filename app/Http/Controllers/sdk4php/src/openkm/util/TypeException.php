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


namespace App\Http\Controllers\sdk4php\src\openkm\util;

/**
 * Status
 *
 * @author sochoa
 */
class TypeException {
    
    const ACCESS_DENIED_EXCEPTION = 'AccessDeniedException';  
    
    const AUTOMATION_EXCEPTION = 'AutomationException';
    
    const CONVERSION_EXCEPTION ="ConversionException";
    
    const DATABASE_EXCEPTION = 'DatabaseException';

    const DOCUMENT_EXCEPTION = 'DocumentException';
    
    const DOCUMENT_TEMPLATE_EXCEPTION = 'DocumentTemplateException';
    
    const EXTENSION_EXCEPTION = 'ExtensionException';
    
    const FILE_SIZE_EXCEEDED_EXCEPTION = 'FileSizeExceededException';
    
    const IO_EXCEPTION = 'IOException';    
    
    const ITEM_EXISTS_EXCEPTION = 'ItemExistsException';
    
    const LOCK_EXCEPTION = 'LockException';
    
    const NO_SUCH_GROUP_EXCEPTION = 'NoSuchGroupException';
    
    const NO_SUCH_PROPERTY_EXCEPTION = 'NoSuchPropertyException';
    
    const PARSE_EXCEPTION = 'ParseException';
    
    const PATH_NOT_FOUND_EXCEPTION = 'PathNotFoundException';
    
    const PRINCIPAL_ADAPTER_EXCEPTION = 'PrincipalAdapterException';
    
    const REPOSITORY_EXCEPTION = 'RepositoryException';
    
    const SUGGESTION_EXCEPTION = 'SuggestionException';
    
    const TEMPLATE_EXCEPTION = 'TemplateException';
    
    const UNKNOW_EXCEPTION = 'UnknowException';
    
    const UNSUPPORTED_MIME_TYPE_EXCEPTION = 'UnsupportedMimeTypeException';
    
    const USER_QUOTA_EXCEEDED_EXCEPTION = 'UserQuotaExceededException';
    
    const VERSION_EXCEPTION = 'VersionException';
    
    const VIRUS_DETECTED_EXCEPTION = 'VirusDetectedException';
    
    const WEB_SERVICE_EXCEPTION = 'WebServiceException';
    
}

?>
