function createNode(){
  var root = {
    "id" : "0",
    "text" : "Opties",
    "value" : "86",
    "showcheck" : false,
    complete : true,
    "isexpand" : true,
    "checkstate" : 0,
    "hasChildren" : true
  };
  var myCategory= new Array("Algemeen","Tekst","Embleem","Diverse","Electrisch I", "Electrisch II", "Electrisch III");
  var myEmpty = new Array("","","","","", "", "","");

  var myOptions= new Array(8) ;
  for (i = 0; i < myCategory.length; ++ i) {
	myOptions [i] = new Array(7);
  }
  myOptions[0][0]='Voorband';
  myOptions[0][1]='Zijkant';
  myOptions[0][2]='Volant plus';
  myOptions[0][3]='Afdichtingstrip tussen muur en kast';
  myOptions[0][4]='Plafondsteun';

  //alert(myOptions[0].length);
  //alert(myOptions[0].length);
  var arr = [];
  for(var i= 1;i<7; i++){
    var subarr = [];
    for(var j=1;j<8;j++){
      var value = "node-" + i + "-" + j;
      var text = myOptions[i-1][j-1];
      //text = text.replace(/^\s+|\s+$/g,'');
     
      if (text) {
        //alert(text);
        subarr.push( {
         "id" : value,
         "text" : text,
         "value" : value,
         "showcheck" : true,
         complete : true,
         "isexpand" : false,
         "checkstate" : 0,
         "hasChildren" : false
          });
     }
     
    }
    arr.push( {
      "id" : "node-" + i,
      "text" : myCategory[i-1],
      "value" : "node-" + i,
      "showcheck" : false,
      complete : true,
      "isexpand" : false,
      "checkstate" : 0,
      "hasChildren" : true,
      "ChildNodes" : subarr
    });
  }
  root["ChildNodes"] = arr;
  return root; 
}



treedata = [createNode()];
