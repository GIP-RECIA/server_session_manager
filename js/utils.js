/**
 * 
 */

$(document)
		.ready(
				function() {
					var dataFromServer = new Array();
					var isUserLoggedIn = false;
					var isInIncognitoMode = false;
					var getUrlParameter = function getUrlParameter(sParam) {
						var sPageURL = decodeURIComponent(window.location.search
								.substring(1)), sURLVariables = sPageURL
								.split('&'), sParameterName, i;

						for (i = 0; i < sURLVariables.length; i++) {
							sParameterName = sURLVariables[i].split('=');

							if (sParameterName[0] === sParam) {
								return sParameterName[1] === undefined ? true
										: sParameterName[1];
							}
						}
					};
					theService = getUrlParameter('service');
					if (theService === undefined) {
						isInIncognitoMode = false
					} else {
						isInIncognitoMode = true;
						return;
					}
					/**
					 * Squelette pour passer les preferences admin en ajax... a voir si c'est necessaire 
					 * */
//					$('#preference_user_action').submit(function(event){
//						$.ajax({
//							url : OC.filePath('server_session_manager', '',
//									'settings.php'),
//							// global : false,
//							type : 'POST',
//							data : {
//								requesttoken : $('input[name=requesttoken]').val()
//							},
//							dataType : 'json',
//							async : false, // blocks window close
//							success : function(result) {
////								isUserLoggedIn = result.logged;
//							},
//							error : function(event) {
//								console.log(event);
//							}
//						});
//						return false;
//					});
					$.ajax({
						url : OC.filePath('server_session_manager', 'ajax',
								'isloggedin.php'),
						// global : false,
						type : 'POST',
						data : {
							requesttoken : $('input[name=requesttoken]').val()
						},
						dataType : 'json',
						async : false, // blocks window close
						success : function(result) {
							isUserLoggedIn = result.logged;
						},
						error : function(event) {
							console.log(event);
						}
					});

					if (!isUserLoggedIn && !isInIncognitoMode) {
						window.location.replace("/?app=user_cas");
					}
					$.ajax({
						url : OC.filePath('server_session_manager', 'ajax',
								'configget.php'),
						// global : false,
						type : 'POST',
						data : {
							requesttoken : $('input[name=requesttoken]').val()
						},
						dataType : 'json',
						async : false, // blocks window close
						success : function(result) {
							$.each(result.configuration, function(configkey,
									configvalue) {
								dataFromServer[configkey] = configvalue;
							});
						}
					});
					if ($('#logout').length && !dataFromServer['logoutbutton']) {
						$('#logout').remove();
					}
					if (('#passwordform').length
							&& !dataFromServer['password_form']) {
						$('#passwordform').remove();
					}
					if (('#displaynameform').length
							&& !dataFromServer['displayname_form']) {
						$('#displaynameform').remove();
					}
					if (('#lostpassword').length
							&& !dataFromServer['lost_password']) {
						$('#lostpassword').remove();
					}
					if (!dataFromServer['connectFromLogin'] && !isUserLoggedIn) {
						$('form').remove();
						var submitInput = $('<form method="post" action="/?app=user_cas" name="cas_login" >'
								+ '<fieldset><input type="submit" id="submit" class="login primary" value="Connexion" />'
								+ '</fieldset>' + '</form>');
						submitInput.insertAfter("header");
					}
				});

$(document).ajaxError(function(event, jqxhr, settings, thrownError) {
	console.log("Triggered ajaxError handler." + thrownError);
});