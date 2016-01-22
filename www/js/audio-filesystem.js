 var src;
  var mediaRec;
  
   // Record audio
    //
    function recordAudio() { 
        

        // Record audio
        mediaRec.startRecord();

        // Stop recording after 10 sec
       /* var recTime = 0;
        var recInterval = setInterval(function() {
            recTime = recTime + 1;
            setAudioPosition(recTime + " sec");
            if (recTime >= 10) {
                clearInterval(recInterval);
                mediaRec.stopRecord();
            }
        }, 1000);*/
    }
 
    }

    // onSuccess Callback
    //
    function onSuccess() {
        console.log("recordAudio():Audio Success");
    }

    // onError Callback
    //
    function onError(error) {
        alert('code: '    + error.code    + '\n' +
              'message: ' + error.message + '\n');
    }

    // Set audio position
    //
    function setAudioPosition(position) {
        document.getElementById('audio_position').innerHTML = position;
    }
	
	//Stop Recording
	function stopRecording(){  
                /*clearInterval(recInterval);*/
                mediaRec.stopRecord(); 
				 var params = new Object();
		     	 params.userId = localStorage.getItem("userId"); 
		 		 params.toUser = toUser;
			 	 params.type = type;
	  
				var options = new FileUploadOptions(); 
			 	
			      options.params = params;
			      options.chunkedMode = false;// If it is not set the PHP server won't able to read this image'
				 var ft = new FileTransfer();
	  ft.upload(src,getBaseURL()+"?rquest=uploadAudio",win,fail,options);
	}
	
	function upload(){ 
		 var ft = new FileTransfer();
	  ft.upload(src,getBaseURL()+"?rquest=uploadAudio",win,fail,options);
	}
	
	
	function win(r){
		/*alert('success'); 
		alert("Response = " + r.response);
		alert("Sent = " + r.bytesSent); */
 
		
	}
	function fail(error){
	/*	alert('Failed'+error);*/
	}