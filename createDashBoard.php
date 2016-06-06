<?php
/**
 * Created by PhpStorm.
 * User: jarce
 * Date: 6/3/2016
 * Time: 2:57 PM
 */
require_once("class/WebService.php");
$oWebService=new WebService('92396','usd');
echo $oWebService->strReplaceIndex();