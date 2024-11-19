<?php

/**
 * This function should be used for canon.cmgm.us links only. 
 * It requires a subset of special characters to be URL-encoded, but not others.
 */
function url_partial_encode($str) {
  $str = str_replace("#", "%23", $str);
  $str = str_replace("&", "%26", $str);
  $str = str_replace("+", "%2B", $str);
  
  return $str;
}

function convert_url($url): string {

   // Check if it's blank or null
   // api/pciplus/getTowers.php sometimes sends null URLs, which we don't want
   if (strlen($url) == 0 || $url == null) {
    return "";
   }


   // Check if it's an ID
   if (str_starts_with($url, '#')) {
    $url = str_replace('#', '', $url);
    return 'https://cmgm.us/database/Edit.php?id=' . $url;
  }

  // Check if it's a canon photo (non-directory) link
  // Use old format for now - will update it for ?r= afterward
  else if (str_starts_with($url, '@') && !str_ends_with($url, '/')) {
    $url = str_replace('@', '', $url);
    $url = 'https://canon.cmgm.us/' . $url;

    // Encode to support canon.cmgm.us
    return url_partial_encode($url);
  }

  // Check if it's a canon directory
  else if (str_starts_with($url, '@') && str_ends_with($url, '/')) {
    $url = str_replace('@', '', $url);
    // Convert to windows-friendly format (backslashes)
    // $url = str_replace('/', '\\', $url);
    $url = 'https://canon.cmgm.us/?r=' . $url;

    // Encode to support canon.cmgm.us
    return url_partial_encode($url);
  }

  // Check if it's an attachment
  else if (preg_match('/^(?:image|misc)/', $url)) {
    return 'https://files.cmgm.us/' . $url;
  }

  // Check if file exists on files.cmgm.us/
  else if (!empty($url) && (strpos($url, '/') === false && strpos($url, '\\') === false)) {
    $headers = @get_headers('https://files.cmgm.us/' . $url);
    if ($headers && strpos($headers[0], '200')) {
      return 'https://files.cmgm.us/' . $url;
    }
  }  

  // Check if the URL doesnt have a protocol
    else if (!str_starts_with($url, 'https://') && !str_starts_with($url, 'http://')) {
    return 'https://' . $url;
  }

  return $url;
}