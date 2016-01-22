/**
 *
 */
$(document)
                .ready(
                                function() {
                                                var dataFromServer ;
                                        $.ajax({
                                                url : OC.filePath('server_session_manager', 'ajax',
                                                                'configget.php'),
                                                // global : false,
                                                type : 'POST',
                                                data : {
                                                        requesttoken : $('input[name=requesttoken]').val()
                                                },
                                                async : false, // blocks window close
                                                success : function(result,status) {
                                                        if(result != undefined && result.configuration != undefined ){
                                                        $.each(result.configuration, function(configkey,
                                                                        configvalue) {
                                                                dataFromServer[configkey] = configvalue;
                                                                });
                                                        }
                                                },
                                                error : function() {

                                                        console.log("Error triggered by server_session_manager/js/utils");
                                                //      console.log("Triggered ajaxError handler." + thrownError);
                                                }
                                        });
                                        if (dataFromServer !=undefined && dataFromServer['logoutbutton']!=undefined){
                                        if ($('#logout').length && !dataFromServer['logoutbutton']) {
                                                $('#logout').remove();
                                                }
                                        }
                                });

