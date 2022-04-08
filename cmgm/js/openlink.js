function openlink(url)
{
    instance = window.open("about:blank");
    instance.document.write("<meta http-equiv=\"refresh\" content=\"0;url="+url+"\">");
    instance.document.close();
    return false;
}
