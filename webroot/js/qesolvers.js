var noteTimeOut;
var library = {
	"num_only":"All input values must be numeric.",
	"a_is_zero":"Value A can not be zero."
};

function cleanGenJS(s){
	return s.replace("<s","&lt;s").replace("\"","\\\"");
}

function doNote(n,f){
	if(f!==""){
		document.getElementById(f).classList.add("error_field");
	}
	document.getElementById("note").innerHTML = cleanGenJS(n);
	document.getElementById("note").style.display = "block";
	clearTimeout(noteTimeOut);
	noteTimeOut = setTimeout(function(){
		document.getElementById("note").style.display = "none";
	}, 3000);
}

function clearErrors(){
	var classEle = document.getElementsByClassName("error_field");
	for (var i = 0; i < classEle.length; i++) {
		classEle[parseInt(i)].classList.remove("error_field");
	}
}

function doAjax(url, form, callback){
	var data = new FormData(document.getElementById(form));
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.onload = function () {
        callback(JSON.parse(this.responseText));
	};
	xhr.send(data);
}

function QESolverCallback(response){
	var qres = document.getElementById("qesolver_response");
	qres.style.display = "block";
	document.getElementById("token").value = response.token;
	if( response.efield !== "" ){ document.getElementById(response.efield).classList.add("error_field"); }
	qres.innerHTML=cleanGenJS(""
	+"<div>"
		+"Answer: <br>" + response.answer + "<br>"
		+"<span class=\"answer_recurring\">Answered (" + response.recurring + ") time" + ((response.recurring!==1)?"s":"") + "</span>"
	+"</div>");
}

window.addEventListener("load", function(event){
	document.getElementById("qesolver_button_submit").onclick = function(){
		event.preventDefault();
		clearErrors();
		if(document.getElementById("input-a").value===0 || document.getElementById("input-a").value===""){
			doNote(library.a_is_zero, "");
			document.getElementById("input-a").classList.add("error_field");
			return false;
		}
		var f = "";
		if( isNaN( document.getElementById("input-a").value ) || document.getElementById("input-a").value === "" ) {
			doNote(library.num_only, "input-a"); return false;
		}
		if( isNaN( document.getElementById("input-b").value ) || document.getElementById("input-b").value === "" ) {
			doNote(library.num_only, "input-b"); return false;
		}
		if( isNaN( document.getElementById("input-c").value ) || document.getElementById("input-c").value === "" ) {
			doNote(library.num_only, "input-c"); return false;
		}
		doAjax("QESolvers/solve_equation","qesolver_form",QESolverCallback);
    };
});

