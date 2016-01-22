<?php
style ('server_session_manager', 'preferencesadmin');
?>

<form id="preference_user_action" class="section" method="post" action="#">
        <h2>Préférences actions utilisateurs</h2>
        <br/>
        <table class="grid adminsettings">
                <thead>
                        <tr>
                        </tr>
                </thead>
                <tbody>
                        <tr>
                                <td class="small"><label for="logoutbutton"> <input type="checkbox"
                                                id="logoutbutton" name="logoutbutton" value="1" <?php if (isset($_['logoutbutton'])&& $_['logoutbutton'] ==1 ): ?> checked="checked"<?php endif; ?>
                                                 />
                                </label></td>
                                <td class="activity_select_group" data-select-group="shared">
                                si coché le bouton de <strong>logout</strong> sera accessible aux utilisateurs
                                </td>
                        </tr>
                        <tr>
                        <td class="small"> <input type="submit" value="Validez" /></td>
                        <td></td>
                        </tr>
                </tbody>
        </table>

        </form>
        <br/>

