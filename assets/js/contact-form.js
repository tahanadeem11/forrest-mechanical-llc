$(function () {
	var $form = $("#contactpage");

	$form.validate({
		rules: {
			fname: { required: true },
			email: { required: true, email: true },
			phone: { required: true, minlength: 7 },
			service: { required: true },
			property: { required: true },
			msg: { required: true }
		},
		errorElement: "span",
		errorPlacement: function (error, element) {
			error.appendTo(element.parent());
		},
		submitHandler: function (form) {
			var $btn = $("#submit");
			var $result = $("#form_result");
			$btn.prop("disabled", true);
			$.ajax({
				url: "contact-form.php",
				type: "POST",
				data: $(form).serialize(),
				dataType: "json"
			}).done(function (res) {
				var ok = res.status === "Success";
				$result.html('<span class="alert ' + (ok ? "alert-success" : "alert-danger") + ' d-block"></span>');
				$result.find("span").text(res.msg);
				if (ok) { form.reset(); }
			}).fail(function () {
				$result.html('<span class="alert alert-danger d-block">Sorry, something went wrong. Please call us at (970) 518-1261.</span>');
			}).always(function () {
				$result.show();
				$btn.prop("disabled", false);
			});
			return false;
		}
	});
});
