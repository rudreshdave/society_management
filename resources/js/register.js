$(document).ready(function () {

    const form = $("#societyRegisterForm");

    form.validate({
        ignore: ":hidden",

        rules: {
            // STEP 1
            society_name: { required: true },
            registration_no: { required: true },
            address_line_1: { required: true },
            city: { required: true },
            state: { required: true },
            pincode: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 6
            },

            // STEP 2
            name: { required: true },
            email: { required: true, email: true },
            mobile: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            password: {
                required: true,
                minlength: 8
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            }
        },

        errorElement: "span",
        errorClass: "error",

        errorPlacement: function (error, element) {
            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },

        highlight: function (element) {
            $(element).addClass("is-invalid");
        },

        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        }
    });

    // STEP 1 → STEP 2
    $("#nextStep").on("click", function () {
        let valid = true;

        $("#step1 input").each(function () {
            if (!form.validate().element(this)) {
                valid = false;
            }
        });

        if (valid) {
            $("#step1").removeClass("active");
            $("#step2").addClass("active");

            $("#step1Indicator").removeClass("active");
            $("#step2Indicator").addClass("active");
        }
    });

    // BACK
    $("#prevStep").on("click", function () {
        $("#step2").removeClass("active");
        $("#step1").addClass("active");

        $("#step2Indicator").removeClass("active");
        $("#step1Indicator").addClass("active");
    });

    // STEP 2 SUBMIT VALIDATION
    form.on("submit", function (e) {
        let valid = true;

        $("#step2 input").each(function () {
            if (!form.validate().element(this)) {
                valid = false;
            }
        });

        if (!valid) e.preventDefault();
    });

});
