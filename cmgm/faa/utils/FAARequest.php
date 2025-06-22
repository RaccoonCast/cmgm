<?php

function makeRequest($asnYear, $asnRegion, $asnSequence, $asnCaseType)
{
  $curl = curl_init();

  $postData = json_encode([
    "asnRegion" => $asnRegion,
    "asnYear" => (int) $asnYear,
    "asnSequence" => $asnSequence,
    "asnCaseType" => $asnCaseType,
  ]);

  curl_setopt_array($curl, [
    CURLOPT_URL => "https://oeaaa.faa.gov/oeaaa/oe3a/external/portal-api/caseFiling/dynamicCaseDataByAsn.do",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $postData,
    CURLOPT_HTTPHEADER => [
      "Accept: application/json, text/plain, */*",
      "Cache-Control: no-cache",
      "Content-Type: application/json;charset=utf-8",
      "If-Modified-Since: 0",
      "Pragma: no-cache",
    ],
  ]);

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error:" . $err;
    return null;
  } else {
    return $response;
  }
}
