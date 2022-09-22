<?php
/*
 *  webpa-lti - WebPA module to add LTI support
 *  Copyright (C) 2020  Stephen P Vickers
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along
 *  with this program; if not, write to the Free Software Foundation, Inc.,
 *  51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 *  Contact: stephen@spvsoftwareproducts.com
 */

###
###  Generate configuration information for Canvas platforms
###

require_once('../../includes/inc_global.php');
require_once(__DIR__ . '/vendor/autoload.php');
require_once(__DIR__ . '/setting.php');

$url = APP__WWW . '/mod/' . LTI_MODULE_NAME . '/';
$domain = APP__WWW;
$pos = strpos($domain, '://');
if ($pos !== false) {
    $domain = substr($domain, $pos + 3);
}
$pos = strpos($domain, '/');
if ($pos !== false) {
    $domain = substr($domain, 0, $pos);
}

if (!isset($_GET['json'])) {
    $xml = <<< EOD
<?xml version="1.0" encoding="UTF-8"?>
<cartridge_basiclti_link xmlns="http://www.imsglobal.org/xsd/imslticc_v1p0"
                         xmlns:blti = "http://www.imsglobal.org/xsd/imsbasiclti_v1p0"
                         xmlns:lticm ="http://www.imsglobal.org/xsd/imslticm_v1p0"
                         xmlns:lticp ="http://www.imsglobal.org/xsd/imslticp_v1p0"
                         xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"
                         xsi:schemaLocation = "http://www.imsglobal.org/xsd/imslticc_v1p0 http://www.imsglobal.org/xsd/lti/ltiv1p0/imslticc_v1p0.xsd
    http://www.imsglobal.org/xsd/imsbasiclti_v1p0 http://www.imsglobal.org/xsd/lti/ltiv1p0/imsbasiclti_v1p0.xsd
    http://www.imsglobal.org/xsd/imslticm_v1p0 http://www.imsglobal.org/xsd/lti/ltiv1p0/imslticm_v1p0.xsd
    http://www.imsglobal.org/xsd/imslticp_v1p0 http://www.imsglobal.org/xsd/lti/ltiv1p0/imslticp_v1p0.xsd">
  <blti:title>WebPA</blti:title>
  <blti:description>Access to WebPA assessments using LTI</blti:description>
  <blti:icon>{$url}icon32.gif</blti:icon>
  <blti:launch_url>{$url}index.php</blti:launch_url>
  <blti:extensions platform="canvas.instructure.com">
    <lticm:property name="tool_id">webpa</lticm:property>
    <lticm:property name="privacy_level">public</lticm:property>
    <lticm:property name="domain">{$domain}</lticm:property>
    <lticm:property name="oauth_compliant">true</lticm:property>
  </blti:extensions>
  <blti:vendor>
    <lticp:code>spvsp</lticp:code>
    <lticp:name>SPV Software Products</lticp:name>
    <lticp:description>Provider of open source educational tools.</lticp:description>
    <lticp:url>http://www.spvsoftwareproducts.com/</lticp:url>
    <lticp:contact>
      <lticp:email>stephen@spvsoftwareproducts.com</lticp:email>
    </lticp:contact>
  </blti:vendor>
</cartridge_basiclti_link>
EOD;

    header("Content-Type: application/xml; ");

    echo $xml;
} else {

    $json = <<< EOD
{
  "title": "WebPA",
  "description": "Access to WebPA assessments using LTI",
  "privacy_level": "public",
  "oidc_initiation_url": "{$url}index.php",
  "target_link_uri": "{$url}index.php",
  "scopes": [
    "https://purl.imsglobal.org/spec/lti-ags/scope/score",
    "https://purl.imsglobal.org/spec/lti-nrps/scope/contextmembership.readonly"
  ],
  "extensions": [
    {
      "domain": "{$domain}",
      "tool_id": "webpa",
      "platform": "canvas.instructure.com",
      "privacy_level": "public",
      "settings": {
        "text": "WebPA",
        "icon_url": "{$url}icon32.gif",
        "placements": [
        ]
      }
    }
  ],
  "public_jwk_url": "{$url}jwks.php",
  "custom_fields": {
    "canvas_user_login_id": "\$User.username"
  }
}
EOD;

    header("Content-Type: application/json; ");

    echo $json;
}
?>