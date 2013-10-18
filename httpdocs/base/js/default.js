
// array.indexOf is new in javascript 1.6, but IE doesn't support it at this time, so we need to have a workaround in IE
if(!Array.indexOf){
    Array.prototype.indexOf = function(obj){
        for(var i=0; i<this.length; i++){
            if(this[i]==obj){
                return i;
            }
        }
    };
}

	function checkOne(obj)
	{
	    var grid = obj.parentNode.parentNode.parentNode;
	    var inputs = grid.getElementsByTagName("input"); 
	    for(var i=0;i<inputs.length;i++)
	    {
	        if (inputs[i].type =="checkbox")
	        {
	            if(obj.checked && inputs[i] != obj && inputs[i].checked)
	            {
	                inputs[i].checked = false;
	            }
	        }
	    }
	}






function confirmText(txt) {
	return (confirm(txt));
}//[end function]



function copy_field(from,to){
	if (!document.getElementById){
		return;
	}

	document.getElementById(from).value=document.getElementById(from);
}//[end function]

function form_submit(frmName){
		document.forms[frmName].submit();
}//[end function]


/*
begin popup
************* */
  var myWindow = null;

  function closeMyWindow() {
    if (myWindow && !myWindow.closed) {
      myWindow.close();
      myWindow = null;
    }
  }

  //onunload = closeMyWindow;

  function openMyWindow(url, width, height) {
    if (!window.open) return true;
    closeMyWindow();
    var x = (screen.width -width) >>1,
        y = (screen.height-height)>>1;
    myWindow = window.open(url,'popWin',
      'width='    + width +
      ',height='  + height +
      ',left='    + x +
      ',top='     + y +
      ',screenX=' + x +
      ',screenY=' + y +
	 /* ',menubar=1' +  */
      ',scrollbars=yes');
    if (!myWindow) return true;
    myWindow.focus();
    return false;
  } //end function
 
 
 /*
 Toggles all checkboxes of form field
  */
   var toggle_choice = 0;
   function toggle_checkbox(theForm,theCheckbox){
	var formName = document.forms[theForm].elements[theCheckbox];
//	alert("tst: "+formName.checked);
	if (typeof formName.length == 'undefined') {
    	//only 1 checkbox;make it an array 
        	formName = new Array(formName);
    }
	
	if (toggle_choice==0){
		choice = 'select';
		toggle_choice=1;
	}else{
		choice = 'deselect';
		toggle_choice=0;
	}
	
	
	if (choice=="select"){
	    for (var i = 0; i < formName.length; i++) {
    	  formName[i].checked=true;
     	}
	}else if (choice=="deselect"){
		for (var i = 0; i < formName.length; i++) {
    	  formName[i].checked=false;
     	}
	}
} // [end function]

	function en_dis_elem(srcObj,target,status){
		if (!document.getElementById(target)){
			return;
		}

		var targetObj = document.getElementById(target);
		//alert('test: '+src.options[src.selectedIndex].value);
		if (srcObj.options[srcObj.selectedIndex].value=='2'){
			targetObj.selectedIndex = 0;
			targetObj.disabled = status;
			return;
		}
		targetObj.disabled = false;
			
	} //end function
	
	function displayVisibility(srcObj,targetElemId,type){ 
		if (!document.getElementById(targetElemId)){
			return;
		}
		var targetObj = document.getElementById(targetElemId);
		//alert('2. foreigner: '+srcObj.checked+' | targetElem: '+targetElemId);
		if (srcObj.checked==true)
		{ //show
			targetObj.style.display='inline';
				
		}else
		{//hide
			targetObj.style.display='none';	
			//targetObj.style.display='none';
		}				
	} //end function	
	

// ----------------------------------
// Uploads
// ----------------------------------
/* This function is called when user selects file in file dialog */
function jsUpload(elem,frmTarget,frmAction,frmSubmit)
{
    // this is just an example of checking file extensions
    // if you do not need extension checking, remove 
    // everything down to line
    // elem.form.submit();
/*
    var re_text = /\.txt|\.xml|\.zip/i;
    var filename = elem.value;

    // checking file type 
    if (filename.search(re_text) == -1)
    {
        alert("File does not have text(txt, xml, zip) extension");
        //elem.form.reset();
        return false;
    }
*/	

   // elem.form.submit();
    //document.getElementById('upload_status').value = "uploading file...";
   // elem.disabled = true;
  
   //document.forms['frm_upload'].submit();
   elem.form.target = frmTarget; //'upload_iframe';
   elem.form.action = frmAction; //'uploads.php';
   if (frmSubmit==1){
   		document.getElementById('fileName').value       = '';
   		document.getElementById('fileNameOrig').value   = '';
   		document.getElementById('imgProcess').src       = "images/upload_process.gif"; 
		document.getElementById('fileStatus').innerHTML = '';  
		document.getElementById('btn_submit').disabled  = true; 		
		elem.form.submit();
   }   
   return true;
}

function formUpload(frmObj,frmTarget,frmAction,frmSubmit){
	if (!frmObj){
		alert('Error: no form, please contact your administrator');
		return false;
	}
	frmObj.target = frmTarget;
	frmObj.action = frmAction;
	if (frmSubmit==1){
		frmObj.submit();
	}	
	//return true;
	//alert('form target: '+frmObj.target);	
	//alert('form action: '+frmObj.action);	
}

function changeImage(id,fullPath){
	//alert('id= '+id+' fullpath: '+fullPath);
	if (!document.getElementById(id)){
		return false;
	} 
	document.getElementById(id).src =  fullPath;
} //end function

var genericAutocomplete = { 
    off : function(srcInputID) { 
    var tag = document.getElementById(srcInputID);
     if (tag) { 
      tag.value = ''; 
      } 
      } //end of turnOff 
}; //end of genericAutocomplete  
  	