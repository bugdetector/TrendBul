var data;
var pagesize = 40;
var activepage;
var tooglefunc;
var visible;
function onHover(element){
  tooglefunc = setTimeout(function(){
    element.getElementsByTagName('img')[1].src = element.getElementsByTagName('img')[0].src;
    element.getElementsByTagName('span')[0].classList.toggle("show");
    visible = true;
  },500);
}
function onOut(element){
  clearTimeout(tooglefunc);
  if(visible == true){
    element.getElementsByTagName('span')[0].classList.toggle("show");
    visible = false;
  }
}
function onLoad(){
  document.getElementById('link-id').style.display = 'none';
  document.getElementById('file-id').style.display = 'none';
  listCategory(document.getElementById('sex_id').value);
  $( document ).ajaxComplete(function() {
      setTimeout(check, 500);
   });
}
function check() {
    if($("#LoadingModal").is(':visible')){
	    $("#LoadingModal").modal("hide");
    }
}
function search(idstr) {
  var formdata = new FormData(document.getElementById(idstr));
  window.scrollTo(0,0);
  document.getElementById('showcase').innerHTML = "";
  $("#LoadingModal").modal("show");
  $.ajax({
	    type: 'POST',
	    url:"ajax.php",
	    data: formdata,
	    dataType:"json",
	    processData:false,
	    contentType:false,
	    success: function(response){
        data = response.result;
        var showcase = document.getElementById('showcase');
	      var result_str = "";
        var length = response.result.length;
        var pagecount = Math.floor(length/pagesize)-1;
        for (var i = 0; i < pagesize && i<length; i++) {
          result_str += data[i];
        }
        var paging = document.getElementById("paging");
        var paging_str = "<input class='btn-danger' id='page-1' type='button' value='1' onclick='pageChange(this)'/>";
        activepage = "page-1";
        for (var i = 1; i < pagecount && i<10; i++) {
          var pagenumber = i+1;
          paging_str+="<input class='btn-primary' id='page-"+pagenumber+"' type='button' value='"+pagenumber
          +"' onclick='pageChange(this)'/>";
        }
        paging.innerHTML = paging_str;

        showcase.innerHTML = result_str;
	      document.getElementById('searched').innerHTML = response.searched;
	    }
	  });
  
}
function pageChange(pagebutton){
    var pageid = pagebutton.value;
    var showcase = document.getElementById('showcase');
    var result_str = "";
    var start = pagesize*pageid;
    var length = data.length;
    var pagecount = Math.round(length/pagesize)-1;
    for (var i = start; i < start+pagesize && i<length; i++) {
      result_str += data[i];
    }
    showcase.innerHTML = result_str;

    document.getElementById(activepage).setAttribute("class","btn-primary");
    pagebutton.setAttribute("class","btn-danger");
    activepage = pagebutton.id;
}
function methodChanged(){
  var searchmethod = document.getElementById('search-id').value;
  if(searchmethod=='linkupload'){
    document.getElementById('link-id').style.display = 'block';
    document.getElementById('file-id').style.display = 'none';
    document.getElementById('submitbutton').value = 'Ara';
  }else if(searchmethod=='fileupload'){
    document.getElementById('link-id').style.display = 'none';
    document.getElementById('file-id').style.display = 'block';
    document.getElementById('submitbutton').value = 'Gönder';
  }else if(searchmethod=="list"){
    document.getElementById('link-id').style.display = 'none';
    document.getElementById('file-id').style.display = 'none';
    document.getElementById('submitbutton').value = 'Listele';
  }
};
function listCategory(str) {
  $.ajax({
    type: 'POST',
    url:"get_categories_ajax.php",
    data: {sex:str},
    success: function(response){
        var rs = document.getElementsByName("category");
        rs[0].innerHTML = response;
        if(rs[1]){
          rs[1].innerHTML = response;
      }
    }
  });
 }
 function modalClicked(button){
    saveSituation();
    var modalmethod = document.getElementById('methodcontainer');
    var modalsex = document.getElementById('sexcontainer');
    var modalcategory = document.getElementById('categorycontainer');

    document.getElementById('methodmodal').innerHTML = modalmethod.innerHTML;
    document.getElementById('sexmodal').innerHTML = modalsex.innerHTML;
    document.getElementById('categorymodal').innerHTML = modalcategory.innerHTML;
    document.getElementById('modalfile').value = document.getElementById("id-"+button.id).src;
 }
 function saveSituation() {
   var selectoptions = document.getElementById('methodcontainer').getElementsByTagName('input');
   var len = selectoptions.length;
   for (var i=0; i<len; i++) {
     selectoptions[i].checked == true ? selectoptions[i].setAttribute("checked",true):selectoptions[i].removeAttribute("checked");
   }
   selectoptions = document.getElementById('sex_id').getElementsByTagName("option");
   len = selectoptions.length;
   for(var i=0;i<len;i++){
     selectoptions[i].selected == true ? selectoptions[i].setAttribute("selected",true):selectoptions[i].removeAttribute("selected");
   }
   selectoptions = document.getElementById('category_id').getElementsByTagName("option");
   len = selectoptions.length;
   for(var i=0;i<len;i++){
     selectoptions[i].selected == true ? selectoptions[i].setAttribute("selected",true):selectoptions[i].removeAttribute("selected");
   }
 }