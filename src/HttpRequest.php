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
					#message_id, #message_button_id, #lab_message_id, #lab_name{
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
						border: 1px solid;
						white-space: pre-wrap;
						word-wrap: break-word;
					}
					#name_use_id{
						width: 300px;
						height: 30;
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
						position: fixed;
						margin-right: auto;
						margin-left: 18%;
						padding-left: 15px;
						padding-right: 15px;
						background: ivory;
						min-height: 95%;
						top: 3%;
						min-width: 33%;
					}
					form{
						padding-top: 10px;
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
					.mess, .user_conected, .user_close_conected, .time_mes, .list_names{
						display: inline;
						font-size: 14px;
					}

					.mess{
						color: darkslateblue;
					}
					.user_conected{
						color: darkmagenta;
					}
					.user_close_conected{
						color: saddlebrown;
					}
					.time_mes{
						color: darkgray;
					}
					.list_names{
						position: absolute;
						top: 0%;
						left: 105%;
						background-color: rgb(12, 12, 12) !important;
						overflow: auto;
						min-height: 100%;
						color: aliceblue;
						min-width: 65%
					}
					.title_names{
						font-size: 18px;
						color: burlywood;
						list-style-type: none;
					}
					#lab_name{
						color: brown;
						margin-left: 20px
					}
					body{
						font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
						font-size: 14px;
						line-height: 1.42857143;
						color: #333;
						background: rgb(203,206,181); /* Old browsers */
						background: -moz-linear-gradient(left, rgba(203,206,181,1) 0%, rgba(78,89,88,1) 49%, rgba(203,206,181,1) 99%); /* FF3.6+ */
						background: -webkit-gradient(linear, left top, right top, color-stop(0%,rgba(203,206,181,1)), color-stop(49%,rgba(78,89,88,1)), color-stop(99%,rgba(203,206,181,1))); /* Chrome,Safari4+ */
						background: -webkit-linear-gradient(left, rgba(203,206,181,1) 0%,rgba(78,89,88,1) 49%,rgba(203,206,181,1) 99%); /* Chrome10+,Safari5.1+ */
						background: -o-linear-gradient(left, rgba(203,206,181,1) 0%,rgba(78,89,88,1) 49%,rgba(203,206,181,1) 99%); /* Opera 11.10+ */
						background: -ms-linear-gradient(left, rgba(203,206,181,1) 0%,rgba(78,89,88,1) 49%,rgba(203,206,181,1) 99%); /* IE10+ */
						background: linear-gradient(to right, rgba(203,206,181,1) 0%,rgba(78,89,88,1) 49%,rgba(203,206,181,1) 99%); /* W3C */
						filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#cbceb5', endColorstr='#cbceb5',GradientType=1 ); /* IE6-9 */

					}
				</style>
				<title>Чатик</title>
				<script type="text/javascript">



					function create() {
						window.ws = new WebSocket('ws://'+document.domain+':8090/chat');
						ws.onopen = function () {
							document.getElementById('log').innerHTML = '<b>WebSocket opened</b> <br/>'+document.getElementById('log').innerHTML;
							var message = JSON.stringify({
								"name":document.forms["publish"].elements["name"].value
							});
							setInterval('ws.send("mes")', 2*58*1000);
							ws.send(message);
						}


						ws.onmessage = function (e) {
							if (e.data = "mes") {
								var json = JSON.parse(e.data);
								if (json.message === undefined) {
									if (json.close_name === undefined) {
										if (json.this_name === undefined) {
											document.getElementById('log').innerHTML = '<div class="user_conected">' +
											'Пользователь:<b>' +
											json.name +
											':</b>вошел в чат...' +
											'</div><br/>' + document.getElementById('log').innerHTML;
										}

									} else {
										if (json.this_name === undefined) {
											document.getElementById('log').innerHTML = '<div class="user_close_conected">' +
											'Пользователь:<b>' +
											json.name + '' +
											' :</b>покинул чат=(' +
											'</div><br/>' + document.getElementById('log').innerHTML;
										}
									}
									document.getElementById('list_names').innerHTML = '<li class="title_names">Всего : ' + json.list_names.length + '</li>';
									for (var i = 0; i < json.list_names.length; i++) {
										document.getElementById('list_names').innerHTML += '<li>' + json.list_names[i] + '</li>';
									}
									if (document.getElementById('lab_name').innerHTML == "Ваш ник: ") {
										document.getElementById('lab_name').innerHTML += json.name;
									}


								} else {
									var date = new Date();
									document.getElementById('log').innerHTML = '<div class="time_mes">' +
									date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds() +
									' </div><b>' +
									json.name +
									' :</b>' +
									'<div class="mess"> ' +
									json.message +
									'</div><br/>' + document.getElementById('log').innerHTML;
								}
							}
						}
						ws.onclose = function () {document.getElementById('log').innerHTML = 'WebSocket closed <br/>'+document.getElementById('log').innerHTML;}

						window.onbeforeunload = function (ws) {
							ws.onclose();
						};

					};

					function getMessage() {
						var message = JSON.stringify({
							"message":document.forms["publish"].elements["message"].value
						});
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
						var obj = document.getElementById('lab_name');
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
					  <input class="btn" type="button" onclick='
					  if(document.forms["publish"].elements["message"].value!="")
						{
							getMessage();
						}
						' value="Отправить" id="message_button_id"/>
					  <label id="lab_name_use_id">Введите ник:</label>
					  <input type="text" name="name" id="name_use_id"/>
					  <input class="btn" type="button" onclick='
					  	  if(document.forms["publish"].elements["name"].value=="")
					  	  {
						  	document.forms["publish"].elements["name"].value="user";
						  }
						  create();
						  showHide();
					  ' value="Сохранить" id="name_use_button_id"/>
						<label id="lab_name">Ваш ник: </label>
					</form>
					<div id="log"></div>
					<div class="list_names">
						<ul id ="list_names"></ul>
					</div>

				</div>
			</body>
		</html>
		<?php
	}

}