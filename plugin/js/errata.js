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
	var _selectedRange;
	var _timeout;
	
	function init(){

		//We get the plugin root directory path from the <script> tag included in the html document
		_rootDirPath = $(_nsid+"script").attr("src");
		_rootDirPath = _rootDirPath.split("/");
		_rootDirPath = _rootDirPath.slice(0,_rootDirPath.length - 3);
		_rootDirPath = _rootDirPath.join("/");
		_rootDirPath = "/" + _rootDirPath;

		$("head").append('<link rel="stylesheet" type="text/css" href="'+_rootDirPath+'plugin/elements.css" />');	
		$("html").append("<div id='"+_ns+"boxWrapper' style='position:absolute; display:none'></div>");

		var textNodes = _getTextNodes(document);
		textNodes = $(textNodes);
		textNodes.parent().mouseup(_getSelectedText);

		$(_nsid+"boxWrapper").load(_rootDirPath+"plugin/elements.php "+_nsid+"box");
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
		var sel = _getSelection();
					
		var text = sel + "";			
		
		if (text.length > 0){
			clearTimeout(_timeout);

			_selectedRange = sel.getRangeAt(0);									

			_showBox(event);				
			var errata = $(_nsid+'errata');			
			errata.html(text);
		}
		else{
			hideBox();
		}		
	}

	function _getSelection(){
		
	    if (window.getSelection)
	    {
			sel = window.getSelection();
	             }
	    else if (document.getSelection)
	    {
			sel = document.getSelection();
	            }
	    else if (document.selection)
	    {
			sel = document.selection.createRange().text;
	            }

		return sel;
	}

	function sendErrata(){
		
		$(_nsid+"title").hide();
		$(_nsid+"form").hide();
		_setStatus("sendingErrata");

		var url = _rootDirPath+"plugin/newErrata.php";

		var errata = $(_nsid+"errata").text();
		
		var path = _getElementPath(_selectedRange.startContainer)+"["+_selectedRange.startOffset+"]->"+
					_getElementPath(_selectedRange.endContainer)+"["+_selectedRange.endOffset+"]";

		//We wrap the errata with a div in order to make its identification easier 
		var errataWrapper = document.createElement("div");
		errataWrapper.id = _ns+"errataWrapper";
		_selectedRange.surroundContents(errataWrapper);
		
		/**
		TODO Should we clone the whole HTML or just the BODY? 
		The HTML may include code we don't need such as the errataBox, 
		but showing the errata to the webmaster in its original context 
		(style, scripts, etc.) could be a cool feature.
		**/
		
		var html = $("<div />").append($("body").clone()).html();	
		
		errataWrapper = $("#"+errataWrapper.id);
		errataWrapper.contents().unwrap();

		var correction = $(_nsid+"correction").val();

		var ip = $(_nsid+"ipAddress").val();

		var data = "errata="+encodeURIComponent(errata)
					+"&correction="+encodeURIComponent(correction)
					+"&url="+encodeURIComponent(document.URL)
					+"&path="+encodeURIComponent(path)
					+"&ip="+encodeURIComponent(ip)
					+"&html="+encodeURIComponent(html);
		
		console.log("Sent message: "+data);	

		$.ajax({
			url: url,
			type: "POST",
			data: data
		}).done(function(msg){
			console.log("Returned message: '" + msg +"'");
			_setStatus("errataSent");
			_timeout = setTimeout(hideBox,3000);
		});
	}

	function _setStatus(status){		 
		$(_nsid+"status").children().hide('fast', function(){
			if (status != undefined){
				var idStatus = _nsid+"status-"+status;				
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
	}
	
	function _showBox(event){

			var wrapper = $(_nsid+"boxWrapper");
			
			if (wrapper.css("display")=="none"){
				$(_nsid+"title").show();
				wrapper
					.css("left",event.pageX)
					.css("top",event.pageY)
					.show();		
			}
	}
	
	function hideBox(){		
		$(_nsid+"form").hide();
		$(_nsid+"boxWrapper").hide();
		$(_nsid+"status span").hide();
	}
	
	function showForm(){
		$(_nsid+'form').show();
	}
	
	function showDetails(){
		$(_nsid+'details').show();
	}	
	
	var ns = com.estudiocaravana.Errata;

	//Public methods declaration
	ns.showForm = showForm;
	ns.showDetails = showDetails;
	ns.sendErrata = sendErrata;
	ns.init = init;
	
})();

var errata;

$(function(){
	
	errata = com.estudiocaravana.Errata;
	errata.init();
	
});


