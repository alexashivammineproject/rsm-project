function myFunction(country){

    var selectedText = country.options[country.selectedIndex].innerHTML;
    var country_phone = selectedText.split(" ");
    console.log(selectedText);
    var tag =country_phone[0];
    var check = selectedText.replace(tag, "");
    console.log(check);
    document.getElementById("country_phone").value = check;
}

jQuery(document).ready(function($) {
  
   
    $(".submitClass").click(function(){
        var urlRoute = $('#submitdataform').attr('data-action');
    
        var name = $('#name').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var message = $('#message').val();
        var productName = $('#product-name').attr('product-name');
        var Countrycode = $('#countryCode').val();
        console.log(Countrycode);

      console.log(message);
            if(name == '')
            {
                $("#nameError").css("display", "none");
                $("#emailError").css("display", "none");
				$("#phoneError").css("display", "none");
                $("#massageError").css("display", "none");
                $('<span id="nameError">Name is requied</span>').insertAfter('#name');
            }else if(email == '')
            {
                $("#nameError").css("display", "none");
                $("#emailError").css("display", "none");
                 $("#phoneError").css("display", "none");
                $("#massageError").css("display", "none");
                $('<span id="emailError">Email is requied</span>').insertAfter('#email');
            }else if(phone == '')
            {
                $("#nameError").css("display", "none");
                $("#emailError").css("display", "none");
                $("#phoneError").css("display", "none");
                $("#massageError").css("display", "none");
                $('<span id="phoneError">Phone number is requied</span>').insertAfter('#phone');
            }else if(message == '')
            {
                $("#nameError").css("display", "none");
                $("#emailError").css("display", "none");
                $("#phoneError").css("display", "none");
                 $("#massageError").css("display", "none");
                $('#massageError').css("display", "block");
            }
            else{
				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					type: "post",
					url: urlRoute,
					data:  {
						name:name,
						email:email,
						phone:phone,
						product:productName,
						message:message,
						countryCode:Countrycode

					},
					beforeSend: function() {
						// Show a loader or spinner
						$('#loader').show();
					  },
					success: function (data) {
						  console.log(data);
						 
					},
					error: function (data) {
						console.log('fail');
					},
					complete: function() {
						// Hide the loader or spinner
						$('#loader').hide();
				    	window.location.href="/thank-you"
				  }
				});
            }
          
    });

    


        $(function() {
            // Open Popup
            $('[popup-open]').on('click', function() {
                var popup_name = $(this).attr('popup-open');
				console.log(popup_name);
        $('[popup-name="' + popup_name + '"]').show(800);
            });
        
            // Close Popup
            $('[popup-close]').on('click', function() {
        var popup_name = $(this).attr('popup-close');
        $('[popup-name="' + popup_name + '"]').hide(800);
            });
        
            // Close Popup When Click Outside
            $('.popup').on('click', function() {
        var popup_name = $(this).find('[popup-close]').attr('popup-close');
        $('[popup-name="' + popup_name + '"]').hide(800);
            }).children().click(function() {
        return false;
            });
        
        });
});
