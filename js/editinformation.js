var error_count;

//prevent non-numeric inputs in mobile number
$('#edit-contactno-field').keypress(function (e) {
    if (e.keyCode < 48 || e.keyCode > 57){
        e.preventDefault();
    }
});

//prevent keypress on email field
$('#edit-email-field').on('keypress', function(e){
    e.preventDefault();
});

//prevent keypress on bdate field
$('#edit-bdate-field').on('keypress', function(e){
    e.preventDefault();
});

//when logout button is clicked
$('#logout-btn').on('click', function(){
	$.ajax({
		url: 'includes/logout',
		success: function(data){
			if(data == 'LOGOUT'){
				window.location.href = 'index';
			}
		},
        error: function(xhr){
            alert('Oops, an error occurred.');
            console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
        }
	});
});


//remover error on input
$('#edit-fname-field').on('input', function(){
    if($('#edit-fname-field').hasClass('is-invalid')){
        $('#edit-fname-field').removeClass('is-invalid');
        $('#save-info-btn').removeClass('is-invalid');
        error_count--;
    }
});
/*$('#edit-email-field').on('input', function(){
    if($('#edit-email-field').hasClass('is-invalid')){
        $('#edit-email-field').removeClass('is-invalid');
        $('#save-info-btn').removeClass('is-invalid');
        error_count--;
    }
});*/
$('#edit-mname-field').on('input', function(){
    if($('#edit-mname-field').hasClass('is-invalid')){
        $('#edit-mname-field').removeClass('is-invalid');
        $('#save-info-btn').removeClass('is-invalid');
        error_count--;
    }
});
$('#edit-lname-field').on('input', function(){
    if($('#edit-lname-field').hasClass('is-invalid')){
        $('#edit-lname-field').removeClass('is-invalid');
        $('#save-info-btn').removeClass('is-invalid');
        error_count--;
    }
});
$('#edit-bplace-field').on('input', function(){
    if($('#edit-bplace-field').hasClass('is-invalid')){
        $('#edit-bplace-field').removeClass('is-invalid');
        $('#save-info-btn').removeClass('is-invalid');
        error_count--;
    }
});
$('#edit-address-field').on('input', function(){
    if($('#edit-address-field').hasClass('is-invalid')){
        $('#edit-address-field').removeClass('is-invalid');
        $('#save-info-btn').removeClass('is-invalid');
        error_count--;
    }
});
$('#edit-contactno-field').on('input', function(){
    if($('#edit-contactno-field').hasClass('is-invalid')){
        $('#edit-contactno-field').removeClass('is-invalid');
        $('#save-info-btn').removeClass('is-invalid');
        error_count--;
    }
});
$('#edit-bdate-field').on('change', function(){
    if($('#edit-bdate-field').hasClass('is-invalid')){
        $('#edit-bdate-field').removeClass('is-invalid');
        $('#save-info-btn').removeClass('is-invalid');
        error_count--;
    }
});

//if save information btn is clicked
$('#save-info-btn').on('click', function(){
    function getBase64Image(img) {
      var canvas = document.createElement("canvas");
      canvas.width = img.width;
      canvas.height = img.height;
      var ctx = canvas.getContext("2d");
      ctx.drawImage(img, 0, 0);
      var dataURL = canvas.toDataURL("image/png");
      return dataURL;
    }

    //const REGEX_EMAIL = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    const REGEX_NAME = /^[A-Za-zñÑ ]+$/;
    const REGEX_MOBILE = /^[0-9]+$/;
    const REGEX_ADDRESS = /^[A-Za-z0-9ñÑ.,# ]+$/;
    const REGEX_BPLACE = /^[A-Za-zñÑ., ]+$/;
    //let user_email = $('#edit-email-field').val().trim();
    let fname = $('#edit-fname-field').val().trim();
    let mname = $('#edit-mname-field').val().trim();
    let lname = $('#edit-lname-field').val().trim();
    let suffix = $('#edit-suffix-select :selected').val();
    let gender = $('#edit-gender-select :selected').val();
    let civilstat = $('#edit-civilstat-select :selected').val();
    let bplace = $('#edit-bplace-field').val().trim();
    let house_address = $('#edit-address-field').val().trim();
    let mobile = $('#edit-contactno-field').val().trim();
    // let bdate = $('#edit-bdate-field').val();
    //let year_of_stay = $('#year-of-stay-select').val();
    let user_photo = $('#profile-upload-edit').get(0).files.length;
    var base64data_photo = "";
    const ERROR_EMPTY = 'Field cannot be empty';
    error_count = 0;

    /*user email validation
    if(user_email == ""){
        $('#edit-email-error').html(ERROR_EMPTY);
        $('#edit-email-field').addClass('is-invalid');
        $('#save-info-btn').addClass('is-invalid');
        error_count++;
    }
    else if (user_email != ""){
        //check if email exist
        $.ajax({
            url: 'includes/edit_user_info_function',
            method: 'POST',
            data: { checkEmailEdit: user_email },
            async: false,
            success: function(data){
                if(data == 'true'){
                    $('#edit-email-error').html('Email address is already taken');
                    $('#edit-email-field').addClass('is-invalid');
                    $('#save-info-btn').addClass('is-invalid');
                    error_count++;
                }
            }
        });

        if(!REGEX_EMAIL.test(user_email)){
            $('#edit-email-error').html('Please enter a valid email');
            $('#edit-email-field').addClass('is-invalid');
            $('#save-info-btn').addClass('is-invalid');
            error_count++;
        }
        else if(user_email.length > 256){
            $('#edit-email-error').html('Maximum of 256 characters only');
            $('#edit-email-field').addClass('is-invalid');
            $('#save-info-btn').addClass('is-invalid');
            error_count++;
        }
    }*/

    //name validation for user
    //first name
    if(fname == ""){
        $('#edit-fname-error').html(ERROR_EMPTY);
        $('#edit-fname-field').addClass('is-invalid');
        $('#save-info-btn').addClass('is-invalid');
        error_count++;
    }
    else if(fname != ""){
        if(!REGEX_NAME.test(fname)){
            $('#edit-fname-error').html('Please enter a valid name');
            $('#edit-fname-field').addClass('is-invalid');
            $('#save-info-btn').addClass('is-invalid');
            error_count++;
        }
        else if(fname.length > 60){
            $('#edit-fname-error').html('Maximum of 60 characters only');
            $('#edit-fname-field').addClass('is-invalid');
            $('#save-info-btn').addClass('is-invalid');
            error_count++;
        }
    }

    //middle name
    if(mname != ""){
        if(!REGEX_NAME.test(mname)){
            $('#edit-mname-error').html('Please enter a valid name');
            $('#edit-mname-field').addClass('is-invalid');
            $('#save-info-btn').addClass('is-invalid');
            error_count++;
        }
        else if(mname.length > 60){
            $('#edit-mname-error').html('Maximum of 60 characters only');
            $('#edit-mname-field').addClass('is-invalid');
            $('#save-info-btn').addClass('is-invalid');
            error_count++;
        }
    }

    //last name
    if(lname == ""){
        $('#edit-lname-error').html(ERROR_EMPTY);
        $('#edit-lname-field').addClass('is-invalid');
        $('#save-info-btn').addClass('is-invalid');
        error_count++;
    }
    else if(lname != ""){
        if(!REGEX_NAME.test(lname)){
            $('#edit-lname-error').html('Please enter a valid name');
            $('#edit-lname-field').addClass('is-invalid');
            $('#save-info-btn').addClass('is-invalid');
            error_count++;
        }
        else if(lname.length < 2){
            $('#edit-lname-error').html('Last name is too short');
            $('#edit-lname-field').addClass('is-invalid');
            $('#save-info-btn').addClass('is-invalid');
            error_count++;
        }
        else if(lname.length > 60){
            $('#edit-lname-error').html('Maximum of 60 characters only');
            $('#edit-lname-field').addClass('is-invalid');
            $('#save-info-btn').addClass('is-invalid');
            error_count++;
        }
    }

    //birthdate validation
    // if(bdate == ""){
    //     $('#edit-bdate-error').html(ERROR_EMPTY);
    //     $('#edit-bdate-field').addClass('is-invalid');
    //     $('#save-info-btn').addClass('is-invalid');
    //     error_count++;
    // }

    //birthplace validation
    if(bplace == ""){
        $('#edit-bplace-error').html(ERROR_EMPTY);
        $('#edit-bplace-field').addClass('is-invalid');
        $('#save-info-btn').addClass('is-invalid');
        error_count++;
    }
    else if(bplace != ""){
        if(!REGEX_BPLACE.test(bplace)){
            $('#edit-bplace-error').html('Please enter a valid birthplace');
            $('#edit-bplace-field').addClass('is-invalid');
            $('#save-info-btn').addClass('is-invalid');
            error_count++;
        }
        else if(bplace.length < 5){
            $('#edit-bplace-error').html('Birthplace is too short');
            $('#edit-bplace-field').addClass('is-invalid');
            $('#save-info-btn').addClass('is-invalid');
            error_count++;
        }
        else if(bplace.length > 60){
            $('#edit-bplace-error').html('Maximum of 60 characters only');
            $('#edit-bplace-field').addClass('is-invalid');
            $('#save-info-btn').addClass('is-invalid');
            error_count++;
        }
    }

    //house address validation
    if(house_address == ""){
        $('#edit-address-error').html(ERROR_EMPTY);
        $('#edit-address-field').addClass('is-invalid');
        $('#save-info-btn').addClass('is-invalid');
        error_count++;
    }
    else if(house_address != ""){
        if(!REGEX_ADDRESS.test(house_address)){
            $('#edit-address-error').html('Please enter a valid address');
            $('#edit-address-field').addClass('is-invalid');
            $('#save-info-btn').addClass('is-invalid');
            error_count++;
        }
        else if(house_address.length < 8){
            $('#edit-address-error').html('Address is too short');
            $('#edit-address-field').addClass('is-invalid');
            $('#save-info-btn').addClass('is-invalid');
            error_count++;
        }
        else if(house_address.length > 256){
            $('#create-address-error').html('Maximum of 256 characters only');
            $('#create-address-field').addClass('is-invalid');
            $('#proceed-create-btn').addClass('is-invalid');
            error_count++;
        }
    }

    //mobile number validation
    if(mobile == ""){
        $('#edit-contactno-error').html(ERROR_EMPTY);
        $('#edit-contactno-field').addClass('is-invalid');
        $('#save-info-btn').addClass('is-invalid');
        error_count++;
    }
    else if(mobile != ""){
        if(!REGEX_MOBILE.test(mobile) || mobile.length != 11 || mobile.charAt(0)+mobile.charAt(1) != '09'){
            $('#edit-contactno-error').html('Please enter a valid mobile number');
            $('#edit-contactno-field').addClass('is-invalid');
            $('#save-info-btn').addClass('is-invalid');
            error_count++;
        }
    }

    if(user_photo === 0){
        base64data_photo = getBase64Image(document.getElementById('image-edit-holder'));
    }
    else{
        base64data_photo = $('#image-edit-holder').attr('src');
    }

    //if error count is 0, send ajax request
    if(error_count == 0){
        $('#modal-editinfo-confirm').modal('show');
        //if yes btn in confirm edit modal is clicked
        $('#confirmsave-info-btn').on('click', function(){
            $.ajax({
                url: 'includes/edit_user_info_function',
                method: 'POST',
                data: { pic_data: base64data_photo, fname: fname.toUpperCase(), mname: mname.toUpperCase(), lname: lname.toUpperCase(), suffix: suffix.toUpperCase(), bplace: bplace.toUpperCase(), gender: gender.toUpperCase(), civilstat: civilstat.toUpperCase(), /*year_of_stay: year_of_stay,*/ address: house_address.toUpperCase(), mobileno: mobile },
                beforeSend: function(){
                    $('#loader-edit').show();
                    $('#no-btn').attr('disabled', true);
                    $('#confirmsave-info-btn').attr('disabled', true);
                },
                success: function(data){
                    $('#loader-edit').hide();
                    $('#no-btn').attr('disabled', false);
                    $('#save-info-btn').attr('disabled', true);
                    $('#confirmsave-info-btn').attr('disabled', false);
                    $('#modal-editinfo-confirm').modal('hide');
                    if(data == "SUCCESS_UPDATE"){
                        $('#modal-success-editinfo').modal('show');
                        setTimeout(function(){
                            $('#modal-success-editinfo').modal('hide');
                        }, 1500);
                        $('#modal-success-editinfo').on('hidden.bs.modal', function(){
                            window.location.href = 'dashboard';
                        });
                    }
                    else{
                        alert('Oops, an error occurred.');
                        console.error(data);
                        $('#loader-edit').hide();
                        $('#no-btn').attr('disabled', false);
                        $('#save-info-btn').attr('disabled', false);
                        $('#confirmsave-info-btn').attr('disabled', false);
                        $('#modal-editinfo-confirm').modal('hide');
                    }
                },
                error: function(xhr){
                    alert('Oops, an error occurred.');
                    console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                }
            });
        });
    }
});

//if profile photo is uploaded, crop the image
$(document).ready(function(){
    var $modal = $('#modal-crop-img');
    var crop_image = document.getElementById('image-crop-container');
    var cropper;
    $('#profile-upload-edit').on('input',function(event){
        var files = event.target.files;
        var done = function(url){
            crop_image.src = url;
            $modal.modal('show');
        };
        if(files && files.length > 0)
        {
            var reader = new FileReader();
            reader.onload = function(event)
            {
                done(reader.result);
            };
            reader.readAsDataURL(files[0]);
        }
    });
    $modal.on('shown.bs.modal', function() {
        cropper = new Cropper(crop_image, {
            aspectRatio: 1,
            viewMode: 3
        });
    }).on('hidden.bs.modal', function(){
        cropper.destroy();
        cropper = null;
    });


    //if cancel crop is clicked, clear file input
    $('#cancel-crop').on('click', function(){
    	$('#profile-upload-edit').val(null);
    });

    //if crop button is clicked, crop the image and display
    $('#crop-image-display').on('click', function(){
    	var canvas = cropper.getCroppedCanvas({
			width:150,
			height:150
		});

		canvas.toBlob(function(blob){
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function(){
				var base64data = reader.result;
				$modal.modal('hide');
				$('#image-edit-holder').attr('src', base64data);
			};
		});
    });
});