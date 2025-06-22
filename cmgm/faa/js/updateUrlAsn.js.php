<script>
  const correctAsn = "<?= $asn ?>";

  // Get current URL
  const url = new URL(window.location);
  const urlAsn = url.searchParams.get('asn');

  // If ASN doesn't match what it's supposed to, update in background
  if (correctAsn != urlAsn) {
    url.searchParams.set('asn', correctAsn);
    window.history.replaceState(null, "", url);
  }
</script>