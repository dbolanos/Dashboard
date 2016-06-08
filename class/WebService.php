<?php

/**
 * Created by PhpStorm.
 * User: jarce
 * Date: 6/3/2016
 * Time: 11:29 AM
 */
class WebService
{
    private static $valueThisWeek;
    private static $valueLastWeek;

    private static function getWebService()
    {
        return new SoapClient("http://172.33.0.41:8090/proxyplayer.asmx?WSDL");
    }

    static function getElement($xml, $item)
    {
        foreach ($xml->attributes() as $key => $value) {
            if ($key == $item) {
                return $value;
            }
        }
    }

    static function getPlayerBalance($prmIdPlayer, $prmCurrencyCode)
    {
        $params = array('prmIdPlayer' => $prmIdPlayer, 'prmCurrencyCode' => $prmCurrencyCode);
        $test = self::getWebService()->GetPlayerBalance($params);
        $xml = new SimpleXMLElement($test->GetPlayerBalanceResult);

        return $xml;
    }


    static function strReplaceIndex($prmIdPlayer, $prmCurrencyCode)
    {
        $template = file_get_contents("Theme/index.html");
        $currentBalance = self::getElement(self::getPlayerBalance($prmIdPlayer, $prmCurrencyCode), 'CurrentBalance');
        $result = str_replace("{{currentBalance}}", $currentBalance, $template);
        $result = str_replace("{{availableBalance}}", self::getElement(self::getPlayerBalance($prmIdPlayer, $prmCurrencyCode), 'AvailBalance'), $result);
        $result = str_replace("{{riskBalance}}", self::getElement(self::getPlayerBalance($prmIdPlayer, $prmCurrencyCode), 'AmountAtRisk'), $result);
        $result = str_replace("{{ThisWeekPercent}}", self::getPercent(self::getElement(self::getPlayerBalance($prmIdPlayer, $prmCurrencyCode), 'thisWeek')), $result);
        $result = str_replace("{{LastWeekPercent}}", self::getPercent(self::getElement(self::getPlayerBalance($prmIdPlayer, $prmCurrencyCode), 'LastWeek')), $result);
        $result = str_replace("{{ThisWeek}}", self::getElement(self::getPlayerBalance($prmIdPlayer, $prmCurrencyCode), 'ThisWeek'), $result);
        $result = str_replace("{{LastWeek}}", self::getElement(self::getPlayerBalance($prmIdPlayer, $prmCurrencyCode), 'LastWeek'), $result);
        $result = str_replace("{{BonusPoints}}", self::getElement(self::getPlayerBalance($prmIdPlayer, $prmCurrencyCode), 'BonusPoints'), $result);

        return $result;
    }


    private static function getPercent($week)
    {
        $result = 0;
        if (!$week == 0) {
            $result = abs($week/10);

        }
        return $result;
    }
}