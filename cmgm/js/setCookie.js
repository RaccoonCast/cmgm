/**
 * 
 * @param {*} cookie_name Cookie name
 * @param {*} cookie_value cookie value
 * @param {*} cookie_length_in_days Cookie length, in days
 */
function setCookie(cookie_name, cookie_value, cookie_length_in_days, deleteCookie) {
  var d = new Date();
  d.setTime(d.getTime() + (cookie_length_in_days * 24 * 60 * 60 * 1000));
  var expires = "expires=" + d.toUTCString();

  // Set .cmgm.us for full subdomain coverage
  // Force path=/ to avoid cookie duplication
  let cookieSet = cookie_name + "=" + cookie_value + ";" + "domain=.cmgm.us;path=/;" + expires;

  // Set Max-Age to 0, fully deleting the cookie
  if (deleteCookie) {
    cookieSet += ';Max-Age=0';
  }

  document.cookie = cookieSet;
}
