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
	
	function init(){

		//We get the plugin root directory path from the <script> tag included in the html document
		_rootDirPath = $("#com-estudiocaravana-errata-script").attr("src");
		_rootDirPath = _rootDirPath.split("/");
		_rootDirPath = _rootDirPath.slice(0,_rootDirPath.length - 3);
		_rootDirPath = _rootDirPath.join("/");
		_rootDirPath = "/" + _rootDirPath;

		$("head").append('<link rel="stylesheet" type="text/css" href="'+_rootDirPath+'plugin/elements.css" />');	
		$("html").append("<div id='com-estudiocaravana-errata-errataBoxWrapper' style='position:absolute; display:none'></div>");
		var textNodes = _getTextNodes(document);
		textNodes = $(textNodes);
		textNodes.parent().mouseup(_getSelectedText);		

		$("#com-estudiocaravana-errata-errataBoxWrapper").load(_rootDirPath+"plugin/elements.php #com-estudiocaravana-errata-errataBox");
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
			// if ($("#com-estudiocaravana-errata-errataBoxWrapper").css("display")!="none"){
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
					var errata = $('#com-estudiocaravana-errata-errata');			
					errata.html(text);
										
				}
				else{
					hideErrataBox();
				}
			// }	
	}

	function sendErrata(){
		
		$("#com-estudiocaravana-errata-errataTitle").hide();
		$("#com-estudiocaravana-errata-errataForm").hide();
		$("#com-estudiocaravana-errata-sendingErrata").show(0, 
			function(){
				console.log("Función enviarErrata");

				var url = _rootDirPath+"plugin/newErrata.php";

				var errata = $("#com-estudiocaravana-errata-errata").text();
				
				var path = $("#com-estudiocaravana-errata-errataPath").val();
				
				var html = $("<div />").append($("html").clone()).html();	
				
				var correction = $("#com-estudiocaravana-errata-errataCorrection").val();
				
				//TODO Get the user's IP address 

				var data = "errata="+errata
							+"&correction="+correction
							+"&url="+document.URL
							+"&path="+path
							+"&html="+html;
				
				console.log("Mensaje: "+data);	

				$.ajax({
					url: url,
					type: "POST",
					data: data
				}).done(function(msg){
					console.log('Errata "' + msg +'" mandada');
					//TODO Messages should be injected in its proper place
					$("#com-estudiocaravana-errata-sendingErrata").hide();
					$("#com-estudiocaravana-errata-errataSent").show();
				});
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

			var wrapper = $("#com-estudiocaravana-errata-errataBoxWrapper");
					
			wrapper.find("#com-estudiocaravana-errata-errataPath").val(_getElementPath(errata));
			
			if (wrapper.css("display")=="none"){
				wrapper
					.css("left",event.pageX)
					.css("top",event.pageY)
					.show();		
			}
	}
	
	function hideErrataBox(){		
		$("#com-estudiocaravana-errata-errataBox div").hide();
		$("#com-estudiocaravana-errata-errataBoxWrapper").hide();
	}
	
	function showErrataForm(){
		$('#com-estudiocaravana-errata-errataForm').show();
	}
	
	function showErrataDetails(){
		$('#com-estudiocaravana-errata-errataDetails').show();
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


