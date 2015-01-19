<?php
namespace Chat;
use PHPDaemon\HTTPRequest\Generic;
class HttpRequest extends Generic
{
	public function run()
	{
		$this->header('Content-Type: text/html');?>
		<!DOCTYPE>
		<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<style type="text/css">
					#message_id, #message_button_id, #lab_message_id{
						display: none;
					}
					#message_id{
						margin: 10px 0px;
						width: 400px;
						height: 34px;
						padding: 6px 12px;
						font-size: 14px;
						line-height: 1.42857143;
						color: #555;
						background-color: #fff;
						background-image: none;
						border: 1px solid #ccc;
						border-radius: 4px;
						-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
						box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
						-webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
						transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
						overflow: auto;
						-webkit-appearance: textarea;
						background-color: white;
						border: 1px solid;
						resize: auto;
						cursor: auto;
						padding: 2px;
						white-space: pre-wrap;
						word-wrap: break-word;
					}
					#name_use_id{
						width: 300px;
						display: block;
						margin: 10px 0px;
						color: #555;
						background-color: #fff;
						background-image: none;
						border: 1px solid #ccc;
					}
					#lab_message_id, #lab_name_use_id{
						color: blue;
						font-size: 125%;
					}

					#message_id:focus, #name_use_id:focus{
						-webkit-box-shadow: -1px 2px 24px 3px rgba(0, 0, 253, 1);
						-moz-box-shadow:    -1px 2px 24px 3px rgba(0, 0, 253, 1);
						box-shadow:         -1px 2px 24px 3px rgba(0, 0, 253, 1);
					}

					b{
						color: rgb(17, 139, 46);
					}

					b:first-letter {
						font-family: "Times New Roman", Times, serif; /* Гарнитура шрифта первой буквы */
						font-size: 200%;
					}
					#log{
						width: 400px;
						height: 500px;
						border: 1px solid #999999;
						overflow:auto;
					}
					.container{
						margin-right: auto;
						margin-left: auto;
						padding-left: 15px;
						padding-right: 15px;
					}
					.btn{
						overflow: visible;
						text-transform: none;
						margin-top: 5px;
						margin-bottom: 5px;
						color: #fff;
						background-color: #428bca;
						border-color: #357ebd;
						display: inline-block;
						margin-bottom: 0;
						font-weight: 400;
						text-align: center;
						vertical-align: middle;
						cursor: pointer;
						background-image: none;
						border: 1px solid transparent;
						white-space: nowrap;
						padding: 6px 12px;
						font-size: 14px;
						line-height: 1.42857143;
						border-radius: 4px;
						-webkit-user-select: none;
						-moz-user-select: none;
						-ms-user-select: none;
						font-family: inherit;
						-webkit-appearance: button;
					}
					.btn:hover{
						background-color: #184063;
						border-color: #2431CA;
					}

					.mess{
						display: inline;
						color: darkslateblue;
					}
					body{
						font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
						font-size: 14px;
						line-height: 1.42857143;
						color: #333;
						background-color: #fff;
					}
				</style>
				<title>Чатик</title>
				<script type="text/javascript">
					(function (w) {
						// Example
						window.ws = new WebSocket('ws://'+document.domain+':8090/chat');
						ws.onopen = function () {document.getElementById('log').innerHTML = 'WebSocket opened <br/>'+document.getElementById('log').innerHTML;}
						ws.onmessage = function (e) {
							var json = JSON.parse(e.data);
							document.getElementById('log').innerHTML = '<b>'+json.name+' :</b><div class="mess"> '+json.message+'</div><br/>'+document.getElementById('log').innerHTML;}

						ws.onclose = function () {document.getElementById('log').innerHTML = 'WebSocket closed <br/>'+document.getElementById('log').innerHTML;}
						window.onbeforeunload = function (ws) {
							ws.onclose();
						};
					})(window);

					function getName() {
							var message = JSON.stringify({"name":document.forms["publish"].elements["name"].value,"message":document.forms["publish"].elements["message"].value});
							document.forms["publish"].elements["message"].value = "";
						    window.ws.send(message);
					};

					function showHide() {
							var obj = document.getElementById('name_use_id');
							obj.style.display = "none";
							var obj = document.getElementById('name_use_button_id');
							obj.style.display = "none";
							var obj = document.getElementById('lab_name_use_id');
							obj.style.display = "none";
							var obj = document.getElementById('message_id');
							obj.style.display = "block";
							var obj = document.getElementById('message_button_id');
							obj.style.display = "inline";
							var obj = document.getElementById('lab_message_id');
							obj.style.display = "inline";
					};

				</script>
			</head>
			<body>
				<!-- форма для отправки сообщений -->
				<div class="container">
					<form name="publish" method="post" >
					  <label id="lab_message_id">Введите текст сообщения:</label>
					  <textarea type="text" name="message" id="message_id"></textarea>
					  <input class="btn" type="button" onclick="getName();" value="Отправить" id="message_button_id"/>
					  <label id="lab_name_use_id">Введите ник:</label>
					  <input type="text" name="name" id="name_use_id"/>
					  <input class="btn" type="button" onclick="showHide();" value="Сохранить" id="name_use_button_id"/>
					</form>

					<div id="log"></div>

				</div>
			</body>
		</html>
		<?php
	}

}