function getBaseURL() {
    var url = location.href;  // entire url including querystring - also: window.location.href; 
	if(url.indexOf("http://localhost") != -1){
		return "http://localhost:8090/pigeon/rest/api.php";
	}else{
		return "http://creatustent.com/pigeon/rest/api.php";
	}
}
 
function getUploadURL() {
    var url = location.href;  // entire url including querystring - also: window.location.href; 
	if(url.indexOf("http://localhost") != -1){
		return "http://localhost:8090/pigeon/uploads";
	}else{
		return "http://97.74.182.1/creatusftp/uploads";
	}
}

function getSiteURL() {
    var url = location.href;  // entire url including querystring - also: window.location.href; 
	if(url.indexOf("http://localhost") != -1){
		return "http://localhost:8090/pigeon";
	}else{
		return "http://creatustent.com/pigeon";
	}
}

function hideFormMessages()
{
    $('.error').fadeOut('fast');
}

function showMessage(message)
{
	$('.error').html(message);
	$('.error').fadeIn('fast');
	clearTimeout(top.timerId);
    top.timerId = setTimeout(hideFormMessages, 5000);
}

function doSignUp()
{	
	if(($.trim($('#name').val()) == '' ||  $.trim($('#name').val()) == '')){ 
		showMessage('Please Enter Your Name');
		$('#name').select();
		return false;
	}else if( ($.trim($('#mob-num').val()) == '' ||  $.trim($('#mob-num').val()) == '') )
	{ 
		showMessage('Please Enter Mobile Num');
		$('#mob-num').select();
		return false;	
	}
	else if( ($.trim($('#email').val()) != '' &&  $.trim($('#email').val()) == '')&&(!IsEmail($.trim($('#email').val()))) )
	{
		showMessage('Email Address Invalid');
		$('#email').select();
		return false;
	}
	else if( ($.trim($('#pword').val()) == '' ||  $.trim($('#pword').val()) == 'Password') )
	{ 
		showMessage('Please Enter Password');
		$('#pword').select();
		return false;	
	}
	else if( ($.trim($('#re-pword').val()) == '' ||  $.trim($('#re-pword').val()) == 'Password') )
	{
		//$("#divMessage").html('Email ID is required');
		showMessage('Please Re-Type password');
		$('#re-pword').select();
		return false;
	}
	else if( ($.trim($('#pword').val()).length <5 ) )
	{ 
		showMessage('Password needs to be atleast 5 characters long');
		$('#pword').select();
		return false;
	}
	else if( $.trim($('#pword').val()) != $.trim($('#re-pword').val()) )
	{
		//$("#divMessage").html('Email ID is required');
		showMessage('Passwords Do not match');
		$('#re-pword').select();
		return false;
	}
	else 
	{ 
		startPageLoad();
		$.ajax({
			type:'POST',
			//url:"/spillmobile/process/api.php?rquest=login",
			url:getBaseURL()+"?rquest=signuppigeon",
			data: $("#signupform").serialize(),
			//dataType: 'json',
			success:function(responseText){ 
					endPageLoad(); 
					if(responseText == 0)
					{
						 showMessage("Sign up failed. Please check your details..");
					}
					else
					{
						localStorage.setItem('userId',responseText['username']);
						window.location.href= 'home.html'; 
					}
					/*$.each(responseText, function(key, value){
						$("#result").html('Logged User: ' + value);   					
					});*/		
			},			
			failed:function(responseText)
			{
				endPageLoad();
				//alert(responseText);
				$.each(responseText, function(i,item)
				{
					alert(item);
				});
				//$("#divMessage").html(responseText);   					
			}
		});/*
		return false;*/
	}
}

function startPageLoad()
{
	$("#pagedimmer").show();
	$('#pagedimmer').animate({'opacity': .8}, 0);
	$.mobile.loading( 'show', {
		text: 'foo',
		textVisible: false,
		theme: 'e',
		//html: "Loading..."
	});
}

function endPageLoad()
{
	$("#pagedimmer").hide();
	$.mobile.loading( 'hide', {
		text: 'foo',
		textVisible: true,
		theme: 'a',
		html: "Loading..."
	});
}

function IsEmail(email) 
{
  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

function doLogin(){
		if( ($.trim($('#mob-num').val()) == '' ||  $.trim($('#mob-num').val()) == '') )
		{ 
			showMessage('Please Enter Mobile Num');
			$('#mob-num').select();
			return false;	
		}else if( ($.trim($('#pword').val()) == '' ||  $.trim($('#pword').val()) == 'Password') )
		{ 
			showMessage('Please Enter Password');
			$('#pword').select();
			return false;	
		}
		else {
			startPageLoad();
			$.ajax({
				type:'POST', 
				url:getBaseURL()+"?rquest=login",
				data: $("#login").serialize(),
				//dataType: 'json',
				success:function(responseText){
						endPageLoad(); 
						if(responseText == 0)
						{ 
							showMessage("Invalid User Credentials");
						}
						else
						{ 
							localStorage.setItem('userId',responseText.mobile_no);
							window.location.href= 'home.html';
						} 		
				},			
			});
			return false;
		}
}

function loadAllPigeons(){
	content = null; 
	/*startPageLoad();*/
			$.ajax({
				type:'POST', 
				url:getBaseURL()+"?rquest=loadPigeons",  
				data:{'userId':localStorage.getItem('userId')},
				success:function(responseText){ 
						if(responseText == 0)
						{ 
							showMessage("No Pigeons are Available !!!");
						}
						else
						{ 
							$("#pigeons").html(responseText);
							$("#pigeons").trigger('create'); 
						} 		
						/*endPageLoad(); */
				},			
			});
}

function isUser(){
	userId = localStorage.getItem('userId'); 
	if(userId.length > 0){
		return 1;
	}else{
		window.location.href = "login.html";
	}
}

function signOut(){
	localStorage.clear();
	window.location.href = 'login.html';
}

function loadHistory(toUserId){   
	startPageLoad(); 
	userId = localStorage.getItem('userId');  
	$.ajax({
		type:'POST',
		url	:getBaseURL()+"?rquest=loadHistory",
		data:{
				'fromUser':localStorage.getItem('userId'),
				'toUser':toUserId
			},
		success:function(response){  
				 	$("#chat1").html(response);
					if ($('#atc').length > 0) {
					      $("#chat-message-box").css("display","none");
					}else{ 
						 $("#chat-message-box").css("display","block"); 
						$("div.chat-history").scrollTop(999999); 
					}
				
				 /*$("#foc").focus(); */ 
			}
	});
	endPageLoad();
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function sendContactRequest(from,to){
	var fromUser = ""+from;
	var toUser = ""+to;
	startPageLoad(); 
	if((fromUser.length > 0) && (toUser.length > 0)){ 
		$.ajax({
			type:'POST',
			url	:getBaseURL()+"?rquest=sendContactRequest",
			data:{
					'fromUser':fromUser,
					'toUser':toUser
				},
			success:function(response){ 
					 $("#chat1").html(response);  
					  $("#chat-message-box").css("display","block");
				}
		});
	}
		endPageLoad();
}

function acceptContactRequest(from,to){
	var fromUser = ""+from;
	var toUser = ""+to;
	startPageLoad(); 
	if((fromUser.length > 0) && (toUser.length > 0)){ 
		$.ajax({
			type:'POST',
			url	:getBaseURL()+"?rquest=acceptContactRequest",
			data:{
					'fromUser':fromUser,
					'toUser':toUser
				},
			success:function(response){ 
					if(response == 1){
					 	loadHistory(fromUser);
					}
				}
		});
	}
		endPageLoad();
}

function sendTextMessage(from,to,type){
	var fromUser = ""+from;
	var toUser = ""+to;
	msgType = "";
	if(type == 0){
		msgType = "single";
	}else if(type == 1){
		msgType = "group";
	}
	var message = $("#message-to-send").val(); 
	startPageLoad(); 
	if((fromUser.length > 0) && (toUser.length > 0)){ 
		$.ajax({
			type:'POST',
			url	:getBaseURL()+"?rquest=sendTextMessage",
			data:{
					'fromUser':fromUser,
					'toUser':toUser,
					'message':message,
					'type':msgType
				},
			success:function(response){ 
					if(response != 0){
						$("#chat1").html(response);  
						$("div.chat-history").scrollTop(999999); 
						 $("#message-to-send").val("");
						  $("#message-to-send").focus();
					}
					 
				}
		});
	}
		endPageLoad();
}
 function handle(e){
        if(e.keyCode === 13){ 
			return true;
        } 
        return false;
    } 

function display(id,name){   
	$("#"+id).text(name); 
}

function displayMessageBox(toUserId){
	var fromUserId = localStorage.getItem('userId');
	type = 0;
	var box = "<div class='chat-message clearfix'><table class='col-sm-12'><tr><td style='width:100%;height:100%'><label id='ltext'></label><textarea name='message-to-send' id='message-to-send' placeholder ='Type your message' rows='3' style='width:100%;height:100%;border-bottom:none;border-left:none;border-right:none;border-radius:0 !important' onkeypress='if(handle(event)){sendTextMessage("+fromUserId+","+toUserId+","+type+");}'></textarea>  <td style='text-align:right'><img src='img/camera-orange.png' width='40px' height='40px' style='padding:2px;cursor:pointer'  onClick='toUser = "+toUserId+";getPhoto(pictureSource.PHOTOLIBRARY);'/><img src='img/try.png' style='cursor:pointer;' width='40px' height='40px' onclick='sendTextMessage("+fromUserId+","+toUserId+","+type+")'/><img width='40px' id='audioId' onclick='audioToggle()' height='40px' src='img/audio.png' style='cursor:pointer;'/></td></tr></table></div> ";
	$("#chat-message-box").html(box);  
}

function activeUser(){
	userId = localStorage.getItem('userId');
	$.ajax({
		type:"post",
		url:getBaseURL()+"?rquest=activateUser",
		data:{'userId':userId},
		success:function(response){ 
			} 
	});
}

function activeOrNot(chatId){ 
	$.ajax({
		type:"post",
		url:getBaseURL()+"?rquest=activeOrNot",
		data:{'userId':chatId},
		success:function(response){ 
				if(response == 1){
					$("#status").text("online");
				}else{
					$("#status").text("offline");
				}
			} 
	});
}

function loadCurrentChat(){
	userId = localStorage.getItem('userId');  
	$.ajax({
		type:"post",
		url:getBaseURL()+"?rquest=loadCurrentChat",
		data:{'userId':userId},
		success:function(response){ 
				$("#currentChat").html(response);
				$("#currentChat").trigger('create'); 
			} 
	});
} 

function goBack(){
	  window.history.back();
}

function validateGroup(){
	if($.trim($('#gname').val()) == '' ){ 
		showMessage('Please Enter Group Name');
		$('#gname').select();
		return false;
	}else{
		startPageLoad();
		gname = $('#gname').val();
		userId = localStorage.getItem('userId');
		profile_pic = "groupIcon.png";
		$.ajax({
			type:'POST', 
			url:getBaseURL()+"?rquest=createGroup",
			data:{
					'gname':gname,
					'userId':userId,
					'pic':profile_pic
					},
			success:function(responseText){ 
					endPageLoad(); 
					if(responseText == 0)
					{
						 showMessage("Failed to create Group...");
					}
					else
					{ 
						localStorage.setItem('groupId',responseText); 
						 window.location.href = 'addcontacts-group.html?groupName='+gname;
					} 		
			} 
		}); 
	}
}

function searchPigeons(){
	content = null; 
	groupId = localStorage.getItem('groupId'); 
	userId = localStorage.getItem('userId');
	/*startPageLoad();*/
			$.ajax({
				type:'POST', 
				url:getBaseURL()+"?rquest=searchPigeons",  
				data:{'userId':userId,'groupId':groupId},
				success:function(responseText){ 
						if(responseText == 0)
						{ 
							showMessage("No Pigeons are Available !!!");
						}
						else
						{ 
							$("#pigeons").html(responseText);
							$("#pigeons").trigger('create'); 
						} 		
						/*endPageLoad(); */
				},			
			});
}

function addContatsToGroup(){
	userId = localStorage.getItem('userId');
	gname = $("#nameGroup").val();
	groupId = localStorage.getItem('groupId'); 
	contacts = [];
	i=0;
	$("form#addparticipants :input:checkbox:checked ").each(function(){
	 contacts[i] = $(this).val(); // This is the jquery object of the input, do what you will
	 i++; 
	});  
	
	if(contacts.length == 0){ 
		showMessage("Select Atleast one contact to add !!!");
		return false;
	}
	startPageLoad();
			$.ajax({
				type:'POST', 
				url:getBaseURL()+"?rquest=addParticipants",  
				data:{'groupId':groupId,'contacts':contacts,'userId':userId},
				success:function(responseText){ 
						if(responseText == 0)
						{ 
							showMessage("Colud Not Add Contacts to the Group !!!");
						}
						else
						{ 
							 window.location.href = "group_chat.html?groupId="+groupId+"&groupName="+gname;
						} 		
						endPageLoad(); 
				},			
			});
}

function loadGroupHistory(groupId){   
	startPageLoad(); 
	userId = localStorage.getItem('userId');  
	$.ajax({
		type:'POST',
		url	:getBaseURL()+"?rquest=loadGroupChatHistory",
		data:{
				'userId':localStorage.getItem('userId'),
				'groupId':groupId
			},
		success:function(response){  
				 	$("#chat1").html(response);
					if ($('#atc').length > 0) {
					      $("#chat-message-box").css("display","none");
					}else{ 
						 $("#chat-message-box").css("display","block"); 
						$("div.chat-history").scrollTop(999999); 
					}
				
				 /*$("#foc").focus(); */ 
			}
	});
	endPageLoad();
}

function displayGroupMessageBox(groupId){
	var fromUserId = localStorage.getItem('userId');
	type = 1;
	var box = "<div class='chat-message clearfix'><table class='col-sm-12'><tr><td style='width:95%'><label id='ltext'></label><textarea name='message-to-send' id='message-to-send' placeholder ='Type your message' rows='3' onkeypress='if(handle(event)){sendTextMessage("+fromUserId+","+groupId+","+type+");}'></textarea>  <!-- <i class='fa fa-file-o'></i> &nbsp;&nbsp;&nbsp;<i class='fa fa-file-image-o'></i> --><td style='width:5%;text-align:right'><i class='fa fa-file-image-o'></i><img src='img/orange-send-button.png' style='cursor:pointer;' width='40px' height='40px' onclick='sendTextMessage("+fromUserId+","+groupId+","+type+")'/></td></tr></table></div> ";
	$("#chat-message-box").html(box);  
}

function getActiveParticipants(groupId){
	
}

function loadFriendsList(){
	userId = localStorage.getItem('userId');  
	$.ajax({
		type:"post",
		url:getBaseURL()+"?rquest=loadFriendsList",
		data:{'userId':userId},
		success:function(response){ 
				$("#currentChat").html(response);
				$("#currentChat").trigger('create'); 
			} 
	});
} 