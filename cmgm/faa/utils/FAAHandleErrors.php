<?php

function handleFAAErrors($data, $json): bool
{
  // Check for error from FAA
  if (isset($data["code"])) {
    http_response_code(500);
    echo $json;
    return true;
  }

  // Check for malformed response from FAA
  if (!is_array($data)) {
    http_response_code(500);
    echo "Server returned the following:";
    echo "<br/><br/>";
    echo $json;
    return true;
  }

  return false;
}
