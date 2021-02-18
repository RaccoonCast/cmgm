function getParamOrCookie(inParam, inDefaulVal, onlyUseParams)
{
  var blacklist = ["startDate", "endDate", "BMW"];
  var params = getQueryParams();
  /*if(hasKey(params,inParam)){console.log("param value of " + inParam + " is " + params[inParam]);}
  if($.cookie(inParam) != undefined) {console.log(" cookie value of " + inParam + " is " + $.cookie(inParam));}

  if(blacklist.includes(inParam))	{		console.log("param is blacklisted " + inParam); }*/

  if(blacklist.includes(inParam))
  {
    if(hasKey(params,inParam))
        return params[inParam];
      else
        return inDefaulVal;
  }
  var result = undefined;
  if(inDefaulVal != undefined)
    result = inDefaulVal;

  if(hasKey(params,inParam))
    result = params[inParam];
  else if($.cookie(inParam) != undefined)
    result = $.cookie(inParam);

  if(result == "true")
    result = true;
  if(result == "false")
    result = false;

  //console.log("final value of " + inParam + " is " + result);
  return result;
}
