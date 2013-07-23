//Instanz der Klasse Ajax erzeugen und mit der Datenuebertragung starten
function loadForms(domain_url)
{


var domain = domain_url;

document.getElementById('list_div').style.display = 'block';

document.getElementById("list_div").innerHTML= '<div style="width:600px; maring-top:40px"><center><br /><br /><img src="'+domain+'/wp-content/plugins/cleverreach-newsletter-marketing/images/loading.gif" /><br /><br />Einen Moment bitte, Daten werden geladen!</center></div>';


  var list_id=document.cr_form.list_id.value;
  var api_key=document.cr_form.api_key.value;
 
  with (new Ajax()){
  
    url=""+domain+"/wp-content/plugins/cleverreach-newsletter-marketing/getForms.php";
    method="GET";
    params="list_id="+list_id+"&api_key="+api_key;
    onSuccess=successHandler;
    onError=errorHandler;
    doRequest();
  }
  
  
  
//Den Text in die Seite einfuegen
function successHandler(txt,xml){
  document.getElementById("list_div").innerHTML=txt;
  
  
}

//Fehler
function errorHandler(msg){
   document.getElementById("list_div").innerHTML=msg;

}

}



function Ajax() {
  //Eigenschaften deklarieren und initialisieren
  this.url="";
  this.params="";
  this.method="GET";
  this.onSuccess=null;
  this.onError=function (msg) {
    alert(msg)
  }
}

Ajax.prototype.doRequest=function() {
  //Ueberpruefen der Angaben
  if (!this.url) {
    this.onError("Es wurde kein URL angegeben. Der Request wird abgebrochen.");
    return false;
  }

  if (!this.method) {
    this.method="GET";
  } else {
    this.method=this.method.toUpperCase();
  }

  //Zugriff auf Klasse für readyStateHandler ermoeglichen  
  var _this = this;
  
  //XMLHttpRequest-Objekt erstellen
  var xmlHttpRequest=getXMLHttpRequest();
  if (!xmlHttpRequest) {
    this.onError("Es konnte kein XMLHttpRequest-Objekt erstellt werden.");
    return false;
  }
  
  //Fallunterscheidung nach Uebertragungsmethode
  switch (this.method) {
    case "GET": xmlHttpRequest.open(this.method, this.url+"?"+this.params, true);
                xmlHttpRequest.onreadystatechange = readyStateHandler;
                xmlHttpRequest.send(null);
                break;
    case "POST": xmlHttpRequest.open(this.method, this.url, true);
                 xmlHttpRequest.onreadystatechange = readyStateHandler;
                 xmlHttpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                 xmlHttpRequest.send(this.params);
                 break;
  }  

  //Private Methode zur Verarbeitung der erhaltenen Daten
  function readyStateHandler() {
    if (xmlHttpRequest.readyState < 4) {
      return false;
    }
    if (xmlHttpRequest.status == 200 || xmlHttpRequest.status==304) {
      if (_this.onSuccess) {
        _this.onSuccess(xmlHttpRequest.responseText, xmlHttpRequest.responseXML);
      }
    } else {
      if (_this.onError) {
        _this.onError("["+xmlHttpRequest.status+" "+xmlHttpRequest.statusText+"] Es trat ein Fehler bei der Datenbertragung auf.");
      }
    }
  }
}

//Gibt browserunabhaengig ein XMLHttpRequest-Objekt zurueck
function getXMLHttpRequest() 
{
  if (window.XMLHttpRequest) {
    //XMLHttpRequest fuer Firefox, Opera, Safari, ...
    return new XMLHttpRequest();
  } else 
  if (window.ActiveXObject) {
    try {   
      //XMLHTTP (neu) fuer Internet Explorer 
      return new ActiveXObject("Msxml2.XMLHTTP");
    } catch(e) {
      try {        
        //XMLHTTP (alt) fuer Internet Explorer
        return new ActiveXObject("Microsoft.XMLHTTP");  
      } catch (e) {
        return null;
      }
    }
  }
  return false;
}