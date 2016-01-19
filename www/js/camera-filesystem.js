	 var pictureSource;   // picture source
    var destinationType; // sets the format of returned value  
	
	var toUser;
	var type;
    // Called when a photo is successfully retrieved
    //
    function uploadPhoto(imageURI) { 
      var options = new FileUploadOptions();
	  options.fileKey = "file";
	  options.fileName = imageURI.substr(imageURI.lastIndexOf('/')+1);
	  options.mimeType = "image/jpeg";
	  
	  pictureSource = imageURI.substr(imageURI.lastIndexOf('/')+1);
	  alert("Name:"+pictureSource);
	  alert(imageURI);
	  
	  var params = new Object();
      params.userId = localStorage.getItem("userId"); 
 	  params.toUser = toUser;
	  params.type = type;
      options.params = params;
      options.chunkedMode = false;// If it is not set the PHP server won't able to read this image'
	  var ft = new FileTransfer();
	  ft.upload(imageURI,getBaseURL()+"?rquest=uploadImage",win,fail,options);
	  
	  alert('completed uploading');
    }

    function win(r){
		alert('success'); 
		alert("Response = " + r.response);
		alert("Sent = " + r.bytesSent);
		pictureSource=navigator.camera.PictureSourceType;
		$("#chat1").html(r.response);  
						$("div.chat-history").scrollTop(999999); 
						 $("#message-to-send").val("");
						  $("#message-to-send").focus();
	}
	function fail(error){
		alert('Failed');
		pictureSource=navigator.camera.PictureSourceType;
	} 
    
    // A button will call this function
    //
    function getPhoto(source) {alert('getPhoto');
      // Retrieve image file location from specified source
      navigator.camera.getPicture(uploadPhoto, onFail, { quality: 50, 
        destinationType: destinationType.FILE_URI,
        sourceType: source });
    }

    // Called if something bad happens.
    // 
    function onFail(message) {
      alert('Failed because: ' + message);
    }
