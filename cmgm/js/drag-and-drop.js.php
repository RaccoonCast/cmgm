<!-- <style>

#dropzone {
  /* Use darker colors if dark mode is enabled */
  --bg-color: <?php echo ($theme == 'black') ? 'black' : 'white' ; ?>;
  --primary-color: <?php echo ($theme == 'black') ? 'white' : 'black'; ?>;
  --border-color: <?php echo ($theme == 'black') ? 'red' : 'black'; ?>;

  background-color: var(--bg-color);
  max-width: 600px;
  color: var(--primary-color);
  border: 1px solid var(--border-color); /* add red border */
  border-radius: 18px;
  box-shadow: 0 0 15px var(--border-color); /* add box-shadow*/
  }

  #dropzone:hover {
    box-shadow: 0 0 22px var(--border-color); /* add box-shadow*/
}

  .dz-preview {
    display: none !important;
  }
</style> -->

<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<script>
// Turn off auto discover (we'll do it manually)
Dropzone.autoDiscover = false;

if (typeof drop == 'undefined') {
  let drop;
}

const prod_url = 'https://upload.cmgm.us';
const test_url = 'http://local.cmgm.us/database/Upload.php';

drop = new Dropzone('#dropzone', {
  url: prod_url, // Prod: upload.cmgm.us
  method: 'POST',
  paramName: 'fileToUpload',
  uploadMultiple: false,
  maxFiles: 1,
  maxFileSize: 256000000,
  dictMaxFilesExceeded: "Only one file may be submitted at a time!",
  responseType: 'text',
  clickable: '#dropzone',
  dictDefaultMessage: 'Drop files here or click to upload'
});

// Process the queue immediately when a file is added
drop.on('addedfile', () => {
  drop.processQueue();
})

drop.on('success', (_file, response) => {
  // Replace the current page content with the HTML response
  // (Not standard practice, but avoids messing with existing upload button / server code)
  document.open();
  document.write(response);
  document.close();
})
</script>