<?php defined('WB_PATH') or header("Location: ../index.php", true, 301); ?>
<html>
    <head>
		<title>Hallo {{LOGIN_DISPLAY_NAME}}</title>
	</head>
    <body>
		<table>

			<tr>
				<td align="center" colspan="2"><img src="{{MEDIA_URL}}/topics-pictures/thumbs/1.jpg"><hr /></td>
			</tr>
			<tr>
				<td align="center" colspan="2">
					<p>Hallo {{LOGIN_DISPLAY_NAME}},</p>
					<br />
					<p>bitte klicken Sie auf folgenden Link, um die Aktivierung auf <b>{{LOGIN_WEBSITE_TITLE}}</b> abzuschließen:</p>
					<p><b>{{CONFIRMATION_LINK}}</b></p>					
					<p>(Sollte der Link nicht anklickbar sein, so kopieren Sie ihn bitte in die Adresszeile Ihres Browsers und rufen die Adresse manuell auf.)</p>
					<br />
					<p>Der Aktivierungslink ist gültig bis {{CONFIRMATION_TIMEOUT}}</p>
					<br />
					<p><b>Falls Sie sich <i>nicht</i> auf "{{LOGIN_WEBSITE_TITLE}}" registriert haben, löschen Sie bitte diese E-Mail.</b></p>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="2">
					------------------------------------------------------<br />
					<small>Diese E-Mail wurde automatisch erstellt.</small><br />
					------------------------------------------------------
				</td>

			</tr>
		</table>
	</body>
</html>