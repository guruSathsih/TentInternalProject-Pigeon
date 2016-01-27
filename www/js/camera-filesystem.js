	 var pictureSource;   // picture source
    var destinationType; // sets the format of returned value  
	
	
    // Called when a photo is successfully retrieved
    //
    function uploadPhoto(imageURI) { 
      var options = new FileUploadOptions();
	  options.fileKey = "file";
	  options.fileName = imageURI.substr(imageURI.lastIndexOf('/')+1);
	  options.mimeType = "image/jpeg";
	  
	  pictureSource = imageURI.substr(imageURI.lastIndexOf('/')+1); 
	  
	  var params = new Object();
      params.userId = localStorage.getItem("userId"); 
 	  params.toUser = toUser;
	  if(type == 0){
		type = "single";
		}else if(type == 1){
			type = "group";
		}
	  params.type = type; 
      options.params = params;
      options.chunkedMode = false;// If it is not set the PHP server won't able to read this image'
	  var ft = new FileTransfer();
	  ft.upload(imageURI,getBaseURL()+"?rquest=uploadImage",win,fail,options); 
    }

    function win(r){ 
		pictureSource=navigator.camera.PictureSourceType;
		$("#chat1").html(r.response);  
						$("div.chat-history").scrollTop(999999); 
						 $("#message-to-send").val("");
						  //$("#message-to-send").focus();
	}
	function fail(error){ 
		pictureSource=navigator.camera.PictureSourceType;
	} 
    
    // A button will call this function
    //
    function getPhoto(source) { 
      // Retrieve image file location from specified source
      navigator.camera.getPicture(uploadPhoto, onFail, { quality: 50, 
        destinationType: destinationType.FILE_URI,
        sourceType: source });
    }

    // Called if something bad happens.
    // 
    function onFail(message) {
      //alert('Failed because: ' + message);
    }
