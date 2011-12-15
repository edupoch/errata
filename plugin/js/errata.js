//TODO Unobstructive JavaScript

var com;

/** 
 * TODO Ahorrar las búsquedas a elementos del errataBox cacheándolas en variables
 * Existe un problema, porque cuando intento hacerlo y modifico un nodo padre,
 * parece que pierdo la referencia al objeto.
 */

if (!com) com = {};

if (!com.estudiocaravana) com.estudiocaravana = {};

com.estudiocaravana.Errata = {};

(function( ){
	
	function init(){
		$("head").append('<link rel="stylesheet" type="text/css" href="plugin/elements.css" />');	
		$("html").append("<div id='com-estudiocaravana-errata-errataBoxWrapper' style='position:absolute; display:none'></div>");
		var textNodes = _getTextNodes(document);
		textNodes = $(textNodes);
		textNodes.parent().mouseup(_getSelectedText);
		
		//TODO Cargar los elementos indep. de la carpeta en la que estemos
		$("#com-estudiocaravana-errata-errataBoxWrapper").load("plugin/elements.php #com-estudiocaravana-errata-errataBox");
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
				var errata = $("#com-estudiocaravana-errata-errata").text();
				
				var path = $("#com-estudiocaravana-errata-errataPath").val();
				
				var html = $("<div />").append($("html").clone()).html();	
				
				var correction = $("#com-estudiocaravana-errata-errataCorrection").val();
				
				//TODO Cargar correctamente los datos
				
				var data = "errata="+errata
							+"&correction="+correction
							+"&url="+document.URL
							+"&path="+path
							+"&html="+html;
				
				console.log("Mensaje: "+data);	
				
				$.ajax({
					url: "/errata2/plugin/newErrata.php",
					type: "POST",
					data: data
				}).done(function(msg){
					console.log('Errata "' + msg +'" mandada');
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
	    
	    //TODO Añadir el offset de palabras al path
	}
	
	function showErrataBox(event, errata){
			var wrapper = $("#com-estudiocaravana-errata-errataBoxWrapper");
					
			wrapper.find("#com-estudiocaravana-errata-errataPath").val(_getElementPath(errata));
			
			if (wrapper.css("display")=="none"){
				console.log("Muestro el Wrapper");
				wrapper
					.css("left",event.pageX)
					.css("top",event.pageY)
					.show();		
			}
	}
	
	function hideErrataBox(){		
		$("#com-estudiocaravana-errata-errataBox div").hide();
		$("#com-estudiocaravana-errata-errataBoxWrapper").hide();
		console.log("Oculto el Wrapper");		
	}
	
	function showErrataForm(){
		$('#com-estudiocaravana-errata-errataForm').show();
	}
	
	function showErrataDetails(){
		$('#com-estudiocaravana-errata-errataDetails').show();
	}	
	
	var ns = com.estudiocaravana.Errata;
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


