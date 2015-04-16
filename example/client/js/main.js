$(document).on("ready", function(){
	$(".btn-default").bind("click", function(event){	
		loadChallenge();
	});
	
	$(".btn-primary").bind("click", function(event){	
		checkChallenge();
	});
	
	$(".modal-header label input").bind("click", function(event){
		loadChallenge();
	});
	
	loadChallenge();
});

var CAPTCHA_WEBSERVICE_GET = "../server/get.php";
var CAPTCHA_WEBSERVICE_CHECK = "../server/check.php";

function loadChallenge()
{
	$(".alert-success").addClass("hide");
	$(".alert-warning").addClass("hide");	
	$(".alert-danger").addClass("hide");
	
	$(".challenge input").val("");
	
	$.ajax({
		url : CAPTCHA_WEBSERVICE_GET,
		type : "GET",
		dataType : "json",
		data : {
			type : $(".modal-header label input:checked").attr("value")
		},
		success : function(data) {
			//hide index
			$(".challenge > span").text(data.index);
			
			//process and prettify challenge text
			$(".challenge > div").text("$$" + data.challenge.replace(new RegExp(" ", "g"), "\\:") + "$$");
			
			//conversion from Tex format to HTML
			MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
			
			$(".challenge").removeClass("hide");
			$(".modal-footer").removeClass("hide");
			$(".modal-body > center").addClass("hide");
		},
		error : function(data) {
			console.log("error");
			console.log(data);
		}
	});
}

function checkChallenge()
{
	var answer = $(".challenge input").val();
	var index = $(".challenge > span").text();
	
	$(".modal-body > center").removeClass("hide");
	
	$.ajax({
		url : CAPTCHA_WEBSERVICE_CHECK,
		type : "POST",
		dataType : "json",
		data : {
			type : $(".modal-header label input:checked").attr("value"),
			answer : answer,
			index : index
		},
		success : function(data) {
			switch(data.status)
			{
				case "success":
					$(".alert-success").removeClass("hide");
					$(".alert-warning").addClass("hide");	
					$(".alert-danger").addClass("hide");
					break;
				case "fail":
					$(".alert-warning").removeClass("hide");
					$(".alert-success").addClass("hide");
					$(".alert-danger").addClass("hide");
					break;
				case "error":
					$(".alert-danger").removeClass("hide");
					$(".alert-success").addClass("hide");
					$(".alert-warning").addClass("hide");
					break;
			}
				
			$(".modal-body > center").addClass("hide");
		}
	});
}