window.addEventListener('load', function () {
(function($) {
  var defaults;
  $.event.fix = (function(originalFix) {
    return function(event) {
      event = originalFix.apply(this, arguments);
      if (event.type.indexOf('paste') === 0) {
        event.clipboardData = event.originalEvent.clipboardData;
      }
      return event;
    };
  })($.event.fix);
  defaults = {
    callback: $.noop,
    matchType: /image.*/
  };
  return $.fn.pasteImageReader = function(options) {
    if (typeof options === "function") {
      options = {
        callback: options
      };
    }
    options = $.extend({}, defaults, options);
    return this.each(function() {
      var $this, element;
      element = this;
      $this = $(this);
      return $this.bind('paste', function(event) {
        var clipboardData, found;
        found = false;
        clipboardData = event.clipboardData;
        return Array.prototype.forEach.call(clipboardData.types, function(type, i) {
          var file, reader;
          if (found) {
            return;
          }
          if (type.match(options.matchType) || clipboardData.items[i].type.match(options.matchType)) {
            file = clipboardData.items[i].getAsFile();
            reader = new FileReader();
            reader.onload = function(evt) {

              document.getElementById("picture").style.backgroundImage = "url('"+evt.target.result+"')";

              document.getElementById("base64_file_form").value = evt.target.result;
              document.getElementById("base64_file_form").setAttribute('name', 'base64_file');

							return options.callback.call(element, {
                dataURL: evt.target.result,
                event: evt,
                file: file,
                name: file.name
              });
            };
            reader.readAsDataURL(file);
            return found = true;
          }
        });
      });
    });
  };
})(jQuery);

$("html").pasteImageReader(function(results) {
  document.forms["image_upload"].submit();
  var dataURL, filename;
  filename = results.filename, dataURL = results.dataURL;
  $data.text(dataURL);
  $size.val(results.file.size);
  $type.val(results.file.type);
  $test.attr('href', dataURL);
  var img = document.createElement('img');
  img.src= dataURL;
  var w = img.width;
  var h = img.height;
  $width.val(w)
  $height.val(h);
  return $(".active").css({
    backgroundImage: "url(" + dataURL + ")"
  }).data({'width':w, 'height':h});
  });
});


//Drag and Drop

/*START*/

window.addEventListener('load',function() {
  $('#dropzone')
      .bind('dragover', function(e) {
          $(this).addClass('dragover');
           return false;
      })
      .bind('dragleave', function(e) {
          $(this).removeClass('dragover');
          e.stopPropagation();
          e.preventDefault();
          return false;
      })
      .bind('drop', function(e) {
          $(this).removeClass('dragover');
          e.stopPropagation();
          e.preventDefault();
          readImages(e);
          return false;
      });
  $(document.body)
      .bind('dragover', function(e) {
           return false;
      })
      .bind('drop', function(e) {
          e.stopPropagation();
          e.preventDefault();
          return false;
      });
});

function readImage(file, event) {
  document.getElementById("dropy").value = event.target.result;
  document.getElementById("picture").style.backgroundImage = "url('"+event.target.result+"')";
  document.getElementById("dropzone").setAttribute('name', 'base64_file');
}

function readImages(event) {
  var files = event.originalEvent.dataTransfer.files;
  $.each( files, function(index, file){
    var fileReader = new FileReader();
        fileReader.onload = (function(file) {
              return function( event ) {
                  return readImage(file, event);
              }
        })(file);
    fileReader.readAsDataURL(file);
    return found = true;
  });
}
