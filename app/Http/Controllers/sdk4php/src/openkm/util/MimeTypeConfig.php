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
 * MimeTypeConfig
 *
 * @author sochoa
 */
class MimeTypeConfig {

    // MIME types => NOTE Keep on sync with default.sql
    const MIME_UNDEFINED = "application/octet-stream";
    // Application
    const MIME_RTF = "application/rtf";
    const MIME_PDF = "application/pdf";
    const MIME_ZIP = "application/zip";
    const MIME_POSTSCRIPT = "application/postscript";
    const MIME_MS_WORD = "application/msword";
    const MIME_MS_EXCEL = "application/vnd.ms-excel";
    const MIME_MS_POWERPOINT = "application/vnd.ms-powerpoint";
    const MIME_MS_WORD_2007 = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
    const MIME_MS_EXCEL_2007 = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
    const MIME_MS_POWERPOINT_2007 = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
    const MIME_OO_TEXT = "application/vnd.oasis.opendocument.text";
    const MIME_OO_SPREADSHEET = "application/vnd.oasis.opendocument.spreadsheet";
    const MIME_OO_PRESENTATION = "application/vnd.oasis.opendocument.presentation";
    const MIME_SWF = "application/x-shockwave-flash";
    const MIME_JAR = "application/x-java-archive";
    const MIME_EPUB = "application/epub+zip";
    // Image
    const MIME_DXF = "image/vnd.dxf";
    const MIME_DWG = "image/vnd.dwg";
    const MIME_TIFF = "image/tiff";
    const MIME_JPEG = "image/jpeg";
    const MIME_GIF = "image/gif";
    const MIME_PNG = "image/png";
    const MIME_BMP = "image/bmp";
    const MIME_PSD = "image/x-psd";
    const MIME_ICO = "image/x-ico";
    const MIME_PBM = "image/pbm";
    const MIME_SVG = "image/svg+xml";
    // Video
    const MIME_MP4 = "video/mp4";
    const MIME_MPEG = "video/mpeg";
    const MIME_FLV = "video/x-flv";
    const MIME_WMV = "video/x-ms-wmv";
    const MIME_AVI = "video/x-msvideo";
    // Text
    const MIME_HTML = "text/html";
    const MIME_TEXT = "text/plain";
    const MIME_XML = "text/xml";
    const MIME_CSV = "text/csv";
    const MIME_CSS = "text/css";
    // Language
    const MIME_SQL = "text/x-sql";
    const MIME_JAVA = "text/x-java";
    const MIME_SCALA = "text/x-scala";
    const MIME_PYTHON = "text/x-python";
    const MIME_GROOVY = "text/x-groovy";
    const MIME_DIFF = "text/x-diff";
    const MIME_PASCAL = "text/x-pascal";
    const MIME_CSHARP = "text/x-csharp";
    const MIME_CPP = "text/x-c++";
    const MIME_APPLESCRIPT = "text/applescript";
    const MIME_SH = "application/x-shellscript";
    const MIME_BSH = "application/x-bsh";
    const MIME_PHP = "application/x-php";
    const MIME_PERL = "application/x-perl";
    const MIME_JAVASCRIPT = "application/javascript";
    const MIME_AS3 = "application/x-font-truetype";
    // Mail
    const MIME_OUTLOOK = "application/vnd.ms-outlook";
    const MIME_EML = "message/rfc822";

}
