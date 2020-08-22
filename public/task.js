$(document).ready(function() {

    var Preview = function() {
        var validateEmail = function(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        };

        this.formValidation = function() {
            var email = $("#task-email").val(),
                text = $("#task-text").val()

            if(!validateEmail(email)) {
                return false;
            }

            if(text.length < 3) {
                return false;
            }

            return true;
        };

        this.run = function() {
            $("form#task-form textarea, form#task-form input, form#task-form select").each(function(num, e) {
                if(e.type == 'file' && e.value) {
                    var file = e.files[0], image = new Image();
                    if(e.accept.match(file.type).length) {
                        var reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onload = function (e) {
                            image.src = e.target.result;
                            $("#preview-image").empty().append(image);
                        };
                    }
                } else {
                    $('#preview-' + e.id).val(e.value);
                }
            });
        };
    };

    var prv = new Preview();

    $("#preview-task").on('click', function() {
        if(prv.formValidation()) {
            prv.run();
        } else {
            $("#form-save").click();
            return false;
        }
    });

});