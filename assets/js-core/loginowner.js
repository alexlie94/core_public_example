function base_url() {
    var pathparts = window.location.pathname.split('/');
    if (location.host == 'localhost:8090' || location.host == 'localhost' || location.host == "172.17.1.25") {
        var folder = pathparts[2].trim('/');
        if(folder == 'backend'){
            return window.location.origin + '/' + pathparts[1].trim('/') + '/' + pathparts[2].trim('/') + '/';
        }
        return window.location.origin + '/' + pathparts[1].trim('/') + '/'; // http://localhost/myproject/controller or folder
    } else {
        return window.location.origin + '/'+ pathparts[1].trim('/') + '/'; // http://stackoverflow.com/
    }
}

function disabledButton(selector) {
	selector.prop("disabled", true);
}

function loadingButton(selector) {
	disabledButton(selector);
	selector.html(
		"<span class=\"indicator-label\">Please wait...<span class=\"spinner-border spinner-border-sm align-middle ms-2\"></span></span>"
	);
}

function loadingButtonOff(selector, text) {
	enabledButton(selector);
	selector.html("<span class=\"indicator-label\">"+text+"</span>");
}

function enabledButton(selector) {
	selector.prop("disabled", false);
}

$(document).on("keyup", ":input", function () {
	$(this).removeClass("fv-plugins-bootstrap5-row-invalid");
	$(this).next(".invalid-feedback").remove();
});

function update_csrf(token) {
	$(":input.token_csrf").val(token);
}

function setCookie(cname) {
	let name = cname + "=";
	let decodedCookie = decodeURIComponent(document.cookie);
	let ca = decodedCookie.split(";");
	for (let i = 0; i < ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) == " ") {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}

function getCookie() {
	return setCookie("csrf_cookie_name");
}

function textWarning(message)
{
	let warning = "<div class=\"alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10\">";
	warning += "<span class=\"svg-icon svg-icon-2hx svg-icon-danger me-4 mb-5 mb-sm-0\"><svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"><path opacity=\"0.3\" d=\"M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z\" fill=\"currentColor\"></path><path d=\"M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z\" fill=\"currentColor\"></path></svg></span>";
	warning += "<div class=\"d-flex flex-column pe-0 pe-sm-10\"><h5 class=\"mb-1\">message</h5><span>"+message+"</span></div>";
	warning += "<button type=\"button\" class=\"position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto\" data-bs-dismiss=\"alert\"><i class=\"bi bi-x fs-1 text-danger\"></i></button>";
	warning += "</div>";

	return warning;
}

$(document).on("submit", "form#login", function (e) {
	e.preventDefault();
	var btn = $("#btnSubmit");
	var textButton = btn.text();
	var url = base_url() + "login/check";
	var msgAlert = $("#alert-messages");

	var data = $(this).serializeArray(); // convert form to array
	data.push({ name: "_token", value: getCookie() });

	$.ajax({
		url: url,
		method: "POST",
		dataType: "JSON",
		data: $.param(data),
		beforeSend: function () {
			loadingButton(btn);
		},
		success: function (response) {
			msgAlert.html("");
			if (!response.success) {
				if (!response.validate) {
					$.each(response.messages, function (key, value) {
						var element = $("#" + key);
						element
							.removeClass("fv-plugins-bootstrap5-row-invalid")
							.addClass(value.length > 0 ? "fv-plugins-bootstrap5-row-invalid" : "")
							.next(".invalid-feedback")
							.remove();

						element.after(value);
					});
				} else {
					msgAlert.html(textWarning(response.messages));
				}
				loadingButtonOff(btn, textButton);
			} else {
				if (response.menu_first != "") {
					window.location.href = base_url() + response.menu_first;
				} else {
					loadingButtonOff(btn, textButton);
				}
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.log(errorThrown);
			loadingButtonOff(btn, textButton);
		},
	});
});