<?php
namespace Chat;
use PHPDaemon\HTTPRequest\Generic;
class HttpRequest extends Generic
{
	public function run()
	{


		if($_SERVER['REQUEST_URI'] == '/chat.css')
		{
			$this->header('Content-Type: text/css');
			echo file_get_contents(__DIR__.'/../css/chat.css');
			return;
		} elseif ($_SERVER['REQUEST_URI'] == '/chat.js') {
			$this->header('Content-Type:text/javascript');
			echo file_get_contents(__DIR__ . '/../javascript/chat.js');
			return;
		}


		$this->header('Content-Type: text/html');
		?>
		<!DOCTYPE>
		<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<link href="chat.css" rel="stylesheet">

				<title>Чатик</title>
				<script src="chat.js"></script>
			</head>
			<body>

				<!-- форма для отправки сообщений -->
				<div class="container">
					<form name="publish" method="post" >
					  <label id="lab_message_id">Введите текст сообщения:</label>
					  <textarea type="text" name="message" id="message_id"></textarea>
					  <input class="btn" type="button" onclick='
					  if(document.forms["publish"].elements["message"].value!="")
						{
							getMessage();
						}
						' value="Отправить" id="message_button_id"/>
						<label id="lab_name_use_id">Введите ник:</label>
						<input type="text" name="name" id="name_use_id"/>
						<label id="lab_avatar_use_id">загрузите аватарку не больше 40 кбт:</label><br/>
						<input type="file" name="avatar" id="avatar_use_id"/>
						<input class="btn" type="button" onclick='
					  	  if(document.forms["publish"].elements["name"].value=="")
					  	  {
						  	document.forms["publish"].elements["name"].value="user";
						  }
						  create();
						  showHide();
					  ' value="Сохранить" id="name_use_button_id"/>
					</form>
					<div id="log">
						<table id="message_log">

						</table>
					</div>

					<div class="list_names">
						<div id="avatar_user_view"></div>
						<div id="names_use"></div>
						<ul id ="list_names"></ul>

					</div>

				</div>
			</body>
		</html>

		<?php
	}

}