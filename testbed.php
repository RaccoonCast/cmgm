<?php
$text = '1911 Gardena Avenue';
?>
<script>
// Copy short address to clipboard
var copy = "<?php echo $text ?>"
copyToClipboard(copy);

 function copyToClipboard(text) {
    var dummy = document.createElement("textarea");
    document.body.appendChild(dummy);
    dummy.value = text;
    dummy.select();
    document.execCommand("copy");
    document.body.removeChild(dummy);
}
</script>
