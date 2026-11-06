<?php 
$scope = isset($scope) ? $scope : '';
$size = isset($invisible) ? 'data-size="invisible"' : '';
$invissible = isset($invisible) && $invisible;
$ajaxSubmit = isset($form) ? $form->submit_with_ajax : 0;
?>


<div id="{{ $scope }}" aria-label="Captcha being used on this page and may cause problems for some users" class="g-recaptcha" data-sitekey="{{ $site_key }}" {{ $size }}></div>

@if ($invissible)

<script type="text/javascript">
	
	document.addEventListener('DOMContentLoaded', function(){ 
		var gr_script = document.createElement('script');
		gr_script.src = 'https://www.google.com/recaptcha/api.js?&onload=renderGoogleInvisibleRecaptcha&hl={{ $lang }}&render=explicit';
		gr_script.setAttribute('defer', 'defer');
		gr_script.setAttribute('async', 'async');
		if (document.querySelectorAll('[src="' + gr_script.src + '"]').length > 0)
		{
			return;
		}
		else{
			document.head.appendChild(gr_script);
		}
	}, false);

	if (typeof window.renderGoogleInvisibleRecaptcha != 'function'){
		window.renderGoogleInvisibleRecaptcha = function() {
			for (var i = 0; i < document.forms.length; ++i) {
				var form = document.forms[i];
				var holder = form.querySelector('.g-recaptcha');
				if (null === holder){
					continue;
				}

				@if(!$ajaxSubmit)
					(function(frm){

						var holderId = grecaptcha.render(holder,{
							'sitekey': '{{ $site_key }}',
							'size': 'invisible',
							'badge' : 'bottomright', 
							'isolated' : true,
							'callback' : function (recaptchaToken) {
								HTMLFormElement.prototype.submit.call(frm);
							}
						});

						frm.onsubmit = function (evt){
							evt.preventDefault();
							//grecaptcha.reset(holderId);
							grecaptcha.execute(holderId);
						};

					})(form);
				@else
					(function(frm){
						var holderId = grecaptcha.render(holder,{
							'sitekey': '{{ $site_key }}',
							'size': 'invisible',
							'badge' : 'bottomright', 
							'isolated' : true,
							'callback' : function (recaptchaToken) {
								console.log('hovered on form captcha executed');
							}
						});

						frm.onmouseover = function (evt){
							evt.preventDefault();
							grecaptcha.execute(holderId);
						};

					})(form);

				@endif
			}
		};
	}



	
</script>

@else

<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function(){ 
		var gr_script = document.createElement('script');
		gr_script.src = 'https://www.google.com/recaptcha/api.js?&onload=renderGoogleRecaptcha&hl={{ $lang }}&render=explicit';
		gr_script.setAttribute('defer', 'defer');
		gr_script.setAttribute('async', 'async');

		$( document ).ajaxComplete(function( event, request, settings ) {
			window.renderGoogleRecaptcha();
		});

		if (document.querySelectorAll('[src="' + gr_script.src + '"]').length > 0)
		{
			return;
		}
		else{
			document.head.appendChild(gr_script);
		}
	}, false);
	window.renderGoogleRecaptcha = function() {
		var recaptchas = document.querySelectorAll('div[class=g-recaptcha]:not(.rendered)');
		for( i = 0; i < recaptchas.length; i++) {
			
			grecaptcha.render( recaptchas[i].id, {
				'sitekey' : '{{ $site_key }}',
			});
			$(recaptchas[i]).addClass('rendered');
		}	
	}
</script>

@endif

