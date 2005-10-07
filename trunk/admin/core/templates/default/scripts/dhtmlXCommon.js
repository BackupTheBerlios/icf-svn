 
function dtmlXMLLoaderObject(funcObject,dhtmlObject){
 this.xmlDoc="";
 this.onloadAction=funcObject||null;
 this.mainObject=dhtmlObject||null;
 return this;
};
 
 dtmlXMLLoaderObject.prototype.waitLoadFunction=function(dhtmlObject){
 this.check=function(){
 if(!dhtmlObject.xmlDoc.readyState)dhtmlObject.onloadAction(dhtmlObject.mainObject);
 else{
 if(dhtmlObject.xmlDoc.readyState != 4)return false;
 else dhtmlObject.onloadAction(dhtmlObject.mainObject);}
};
 return this.check;
};
 
 
 dtmlXMLLoaderObject.prototype.getXMLTopNode=function(tagName){
 if(this.xmlDoc.responseXML){var temp=this.xmlDoc.responseXML.getElementsByTagName(tagName);return temp[0];}
 else return this.xmlDoc.documentElement;
};
 
 
 dtmlXMLLoaderObject.prototype.loadXMLString=function(xmlString){

 var parser = new DOMParser();
 this.xmlDoc = parser.parseFromString(xmlString);
 try 
{
}
 catch(e){
 this.xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
 this.xmlDoc.loadXML(xmlString);
}
 this.onloadAction(this.mainObject);
}
 dtmlXMLLoaderObject.prototype.loadXML=function(filePath){
 try 
{
 this.xmlDoc = new XMLHttpRequest();
 this.xmlDoc.open("GET",filePath,true);
 this.xmlDoc.onreadystatechange=new this.waitLoadFunction(this);
 this.xmlDoc.send(null);
}
 catch(e){

 if(document.implementation && document.implementation.createDocument)
{
 this.xmlDoc = document.implementation.createDocument("","",null);
 this.xmlDoc.onload = new this.waitLoadFunction(this);
}
 else
{
 this.xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
 this.xmlDoc.async="true";
 this.xmlDoc.onreadystatechange=new this.waitLoadFunction(this);
}
 this.xmlDoc.load(filePath);
}
};
 
 
function callerFunction(funcObject,dhtmlObject){
 this.handler=function(e){
 if(!e)e=event;
 funcObject(e,dhtmlObject);
 return true;
};
 return this.handler;
};

 
function getAbsoluteLeft(htmlObject){
 var xPos = htmlObject.offsetLeft;
 var temp = htmlObject.offsetParent;
 while(temp != null){
 xPos+= temp.offsetLeft;
 temp = temp.offsetParent;
}
 return xPos;
}
 
function getAbsoluteTop(htmlObject){
 var yPos = htmlObject.offsetTop;
 var temp = htmlObject.offsetParent;
 while(temp != null){
 yPos+= temp.offsetTop;
 temp = temp.offsetParent;
}
 return yPos;
}
 
 
 
function convertStringToBoolean(inputString){if(typeof(inputString)=="string")inputString=inputString.toLowerCase();
 switch(inputString){
 case "1":
 case "true":
 case "yes":
 case "y":
 case 1: 
 case true: 
 return true;
 break;
 default: return false;
}
}

 
function getUrlSymbol(str){
 if(str.indexOf("?")!=-1)
 return "&"
 else
 return "?"
}
 
 
function dhtmlDragAndDropObject(){
 this.lastLanding=0;
 this.dragNode=0;
 this.dragStartNode=0;
 this.dragStartObject=0;
 this.tempDOMU=null;
 this.tempDOMM=null;
 this.waitDrag=0;
 if(window.dhtmlDragAndDrop)return window.dhtmlDragAndDrop;
 window.dhtmlDragAndDrop=this;
 return this;
};
 
 dhtmlDragAndDropObject.prototype.removeDraggableItem=function(htmlNode){
 htmlNode.onmousedown=null;
 htmlNode.dragStarter=null;
 htmlNode.dragLanding=null;
}
 dhtmlDragAndDropObject.prototype.addDraggableItem=function(htmlNode,dhtmlObject){
 htmlNode.onmousedown=this.preCreateDragCopy;
 htmlNode.dragStarter=dhtmlObject;
 this.addDragLanding(htmlNode,dhtmlObject);
}
 dhtmlDragAndDropObject.prototype.addDragLanding=function(htmlNode,dhtmlObject){
 htmlNode.dragLanding=dhtmlObject;
}
 dhtmlDragAndDropObject.prototype.preCreateDragCopy=function(e)
{
 if(window.dhtmlDragAndDrop.waitDrag){
 window.dhtmlDragAndDrop.waitDrag=0;
 document.body.onmouseup=window.dhtmlDragAndDrop.tempDOMU;
 document.body.onmousemove=window.dhtmlDragAndDrop.tempDOMM;
 return;
}
 window.dhtmlDragAndDrop.waitDrag=1;
 window.dhtmlDragAndDrop.tempDOMU=document.body.onmouseup;
 window.dhtmlDragAndDrop.tempDOMM=document.body.onmousemove;
 window.dhtmlDragAndDrop.dragStartNode=this;
 window.dhtmlDragAndDrop.dragStartObject=this.dragStarter;
 document.body.onmouseup=window.dhtmlDragAndDrop.preCreateDragCopy;
 document.body.onmousemove=window.dhtmlDragAndDrop.callDrag;
};
 dhtmlDragAndDropObject.prototype.callDrag=function(e){
 if(!e)e=window.event;
 dragger=window.dhtmlDragAndDrop;
 if(!dragger.dragNode){
 dragger.dragNode=dragger.dragStartObject.a36(dragger.dragStartNode);
 document.body.appendChild(dragger.dragNode);
 document.body.onmouseup=dragger.stopDrag;
 dragger.waitDrag=0;
}

 dragger.dragNode.style.left=e.clientX+15;dragger.dragNode.style.top=e.clientY+3;

 if(!e.srcElement)var z=e.target;else z=e.srcElement;
 dragger.checkLanding(z);
}
 dhtmlDragAndDropObject.prototype.checkLanding=function(htmlObject){
 if(htmlObject.dragLanding){if(this.lastLanding)this.lastLanding.dragLanding.a37Out(this.lastLanding);
 this.lastLanding=htmlObject;this.lastLanding=this.lastLanding.dragLanding.a37In(this.lastLanding,this.dragStartNode);}
 else{
 if(htmlObject.tagName!="BODY")this.checkLanding(htmlObject.parentNode);
 else{if(this.lastLanding)this.lastLanding.dragLanding.a37Out(this.lastLanding);this.lastLanding=0;}
}
}
 dhtmlDragAndDropObject.prototype.stopDrag=function(e){
 dragger=window.dhtmlDragAndDrop;
 if(dragger.lastLanding)dragger.lastLanding.dragLanding.a37(dragger.dragStartNode,dragger.dragStartObject,dragger.lastLanding);
 dragger.lastLanding=0;
 dragger.dragNode.parentNode.removeChild(dragger.dragNode);
 dragger.dragNode=0;
 dragger.dragStartNode=0;
 dragger.dragStartObject=0;
 document.body.onmouseup=dragger.tempDOMU;
 document.body.onmousemove=dragger.tempDOMM;
 dragger.tempDOMU=null;
 dragger.tempDOMM=null;
 dragger.waitDrag=0;
};
