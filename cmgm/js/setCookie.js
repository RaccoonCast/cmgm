/**
 * 
 * @param {*} cookie_name Cookie name
 * @param {*} cookie_value cookie value
 * @param {*} cookie_length_in_days Cookie length, in days
 */
function setCookie(cookie_name, cookie_value, cookie_length_in_days) {
   var d = new Date();
   d.setTime(d.getTime() + (cookie_length_in_days*24*60*60*1000));
   var expires = "expires="+ d.toUTCString();
   document.cookie = cookie_name + "=" + cookie_value + ";" + expires + ";path=/";
 }
