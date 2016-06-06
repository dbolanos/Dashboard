<?php

/**
 * Created by PhpStorm.
 * User: jarce
 * Date: 6/3/2016
 * Time: 11:29 AM
 */
class WebService
{

    private $prmIdPlayer;
    private $prmCurrencyCode;

    function __construct($prmIdPlayer, $prmCurrencyCode)
    {
        $this->prmCurrencyCode = $prmCurrencyCode;
        $this->prmIdPlayer = $prmIdPlayer;
    }

    private function getWebService()
    {
        return new SoapClient("http://172.33.0.41:8090/proxyplayer.asmx?WSDL");
    }

    function getElement($xml, $item)
    {
        foreach ($xml->attributes() as $key => $value) {
            if ($key == $item) {
                return $value;
            }
        }
    }

    function getPlayerBalance()
    {
        $params = array('prmIdPlayer' => $this->prmIdPlayer, 'prmCurrencyCode' => $this->prmCurrencyCode);
        $test = $this->getWebService()->GetPlayerBalance($params);
        $xml = new SimpleXMLElement($test->GetPlayerBalanceResult);

        return $xml;
    }


    function strReplaceIndex()
    {
        $template = file_get_contents("Theme/index.html");
        $result = str_replace("{{currentBalance}}", $this->getElement($this->getPlayerBalance(), 'CurrentBalance'), $template);
        $result = str_replace("{{availableBalance}}", $this->getElement($this->getPlayerBalance(), 'AvailBalance'), $result);
        $result = str_replace("{{riskBalance}}", $this->getElement($this->getPlayerBalance(), 'AmountAtRisk'), $result);
        $result = str_replace("{{ThisWeek}}", $this->getElement($this->getPlayerBalance(), 'ThisWeek'), $result);
        $result = str_replace("{{LastWeek}}", $this->getElement($this->getPlayerBalance(), 'LastWeek'), $result);

        return $result;
    }
}