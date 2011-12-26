// TODO Unobstructive JavaScript

var com;

/** 
 * TODO Try to cache the errataBox elements
 */

if (!com) com = {};

if (!com.estudiocaravana) com.estudiocaravana = {};

com.estudiocaravana.Errata = {};

(function( ){

	var _rootDirPath;
	var _ns = "com-estudiocaravana-errata-";
	var _nsid = "#"+_ns;
	
	function init(){

		//We get the plugin root directory path from the <script> tag included in the html document
		_rootDirPath = $(_nsid+"script").attr("src");
		_rootDirPath = _rootDirPath.split("/");
		_rootDirPath = _rootDirPath.slice(0,_rootDirPath.length - 3);
		_rootDirPath = _rootDirPath.join("/");
		_rootDirPath = "/" + _rootDirPath;

		$("head").append('<link rel="stylesheet" type="text/css" href="'+_rootDirPath+'plugin/elements.css" />');	
		$("html").append("<div id='"+_ns+"errataBoxWrapper' style='position:absolute; display:none'></div>");
		var textNodes = _getTextNodes(document);
		textNodes = $(textNodes);
		textNodes.parent().mouseup(_getSelectedText);		

		$(_nsid+"errataBoxWrapper").load(_rootDirPath+"plugin/elements.php "+_nsid+"errataBox");
	}
	
	function _getTextNodes(node) {
		
		var whitespace = /^\s*$/;		
	    var textNodesStack = new Array();
	    
	    function _getTextNodesAux(node) {
	        if (node.nodeType == 3) {
	            if (!whitespace.test(node.nodeValue)) {
	                textNodesStack.push(node);
	            }
	        } else {
	        	if (node.childNodes != undefined){        		
	        		for(i in node.childNodes){
	        			_getTextNodesAux(node.childNodes[i]);
	        		}        		
	           	}
	        }
	    }
	
	    _getTextNodesAux(node);
	    return textNodesStack;
	}

	function _getSelectedText(event)
	{
			// if ($(_nsid+"errataBoxWrapper").css("display")!="none"){
				// hideErrataBox();
			// }
			// else{
				var text;
			     if (window.getSelection)
			    {
			        text = window.getSelection();
			             }
			    else if (document.getSelection)
			    {
			        text = document.getSelection();
			            }
			    else if (document.selection)
			    {
			        text = document.selection.createRange().text;
			            }
			
				console.log(text);

				text += "";			
				
				if (text.length > 0){
									
					showErrataBox(event, this);				
					var errata = $(_nsid+'errata');			
					errata.html(text);
										
				}
				// else{
				// 	hideErrataBox();
				// }
			// }	
	}

	function sendErrata(){
		
		$(_nsid+"errataTitle").hide();
		$(_nsid+"errataForm").hide();
		_setStatus("sendingErrata");
		
		console.log("Función enviarErrata");

		var url = _rootDirPath+"plugin/newErrata.php";

		var errata = $(_nsid+"errata").text();
		
		var path = $(_nsid+"errataPath").val();
		
		var html = encodeURIComponent($("<div />").append($("html").clone()).html());	
		
		var correction = $(_nsid+"errataCorrection").val();

		var ip = $(_nsid+"ipAddress").val();

		var data = "errata="+errata
					+"&correction="+correction
					+"&url="+document.URL
					+"&path="+path
					+"&ip="+ip
					+"&html="+html;
		
		console.log("Mensaje: "+data);	

		$.ajax({
			url: url,
			type: "POST",
			data: data
		}).done(function(msg){
			console.log('Errata "' + msg +'" mandada');
			_setStatus("errataSent");			
			//TODO Close the errataBox after some time
		});
	}

	function _setStatus(status){		 
		$(_nsid+"status").children().hide('fast', function(){
			if (status != undefined){
				var idStatus = _nsid+"status-"+status;			
				console.log("Status="+idStatus+" active");
				$(idStatus).show();			
			}
		});
	}

	function _getElementPath(element)
	{
		return "//" + $(element).parents().andSelf().map(function() {
	        var $this = $(this);
	        var tagName = this.nodeName;
	        if (tagName != undefined && $this.siblings(tagName).length > 0) {
	            tagName += "[" + $this.prevAll(tagName).length + "]";
	        }
	        return tagName;
	    }).get().join("/").toUpperCase();
	    
	    //TODO Add the word offset to the path
	}
	
	function showErrataBox(event, errata){

			var wrapper = $(_nsid+"errataBoxWrapper");
					
			wrapper.find(_nsid+"errataPath").val(_getElementPath(errata));
			
			if (wrapper.css("display")=="none"){
				wrapper
					.css("left",event.pageX)
					.css("top",event.pageY)
					.show();		
			}
	}
	
	function hideErrataBox(){		
		$(_nsid+"errataBox div").hide();
		$(_nsid+"errataBoxWrapper").hide();
	}
	
	function showErrataForm(){
		$(_nsid+'errataForm').show();
	}
	
	function showErrataDetails(){
		$(_nsid+'errataDetails').show();
	}	
	
	var ns = com.estudiocaravana.Errata;

	//Public methods declaration
	ns.showErrataBox = showErrataBox;
	ns.showErrataForm = showErrataForm;
	ns.showErrataDetails = showErrataDetails;
	ns.sendErrata = sendErrata;
	ns.init = init;
	
})();

var errata;

$(function(){
	
	errata = com.estudiocaravana.Errata;
	errata.init();
	
});


