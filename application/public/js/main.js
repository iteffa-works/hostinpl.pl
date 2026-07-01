window.onerror = function(e) {
	if(e == 'Uncaught ReferenceError: grecaptcha is not defined') toastr.error("Не удалось загрузить каптчу, пожалуйста, перезагрузите страницу!");
}

var containerScrollDown = true;

function redirect(url) {
	document.location.href=url;
}

function reload() {
	window.location.reload();
}

function checkScrollContainer(container_id){
	container = document.getElementById(container_id);
	if (container.scrollHeight - container.scrollTop > container.offsetHeight * 1.1){
		containerScrollDown = false;
	} else{
		containerScrollDown = true;
	}
}