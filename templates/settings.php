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
				<td class="small"><label for="lost_password"> <input
						type="checkbox" id="lost_password"
						name="lost_password" value="1" <?php if (isset($_['lost_password']) && $_['lost_password'] ==1 ): ?> checked="checked"<?php endif; ?>
						 />
				</label></td>
				<td class="activity_select_group" data-select-group="file_created">
				si coché la fonction de changement d'adresse <strong>email</strong> sera accessible aux utilisateurs	
				</td>
			</tr>
			<tr>
				<td class="small"><label for="password_form"> <input
						type="checkbox" id="password_form"
						name="password_form" value="1" 
						<?php if (isset($_['password_form']) && $_['password_form'] ==1 ): ?> checked="checked"<?php endif; ?>
				</label></td>
				<td class="activity_select_group" data-select-group="file_changed">
					si coché la fonction de changement de <strong>mot de passe</strong> sera accessible aux utilisateurs
				</td>
			</tr>
			<tr>
				<td class="small"><label for="displayname_form"> <input
						type="checkbox" id="displayname_form" name="displayname_form"
						<?php if (isset($_['displayname_form']) && $_['displayname_form'] ==1 ): ?> checked="checked"<?php endif; ?>
						value="1"  />
				</label></td>
				<td class="activity_select_group" data-select-group="file_deleted">
					si coché la fonction de changement du <strong>nom affiché</strong> sera accessible aux utilisateurs
				</td>
			</tr>
			<tr>
				<td class="small"><label for="connectFromLogin"> <input
						type="checkbox" id="connectFromLogin"
						<?php if (isset($_['connectFromLogin']) && $_['connectFromLogin'] ==1 ): ?> checked="checked"<?php endif; ?>
						name="connectFromLogin" value="1"  />
				</label></td>
				<td class="activity_select_group" data-select-group="file_restored">
					si coché la fonction de <strong>login</strong> à partir du <strong>formulaire</strong> sera accessible aux utilisateurs
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