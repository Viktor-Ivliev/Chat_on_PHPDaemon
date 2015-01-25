function create() {
    window.ws = new WebSocket('ws://'+document.domain+':8090/chat');
    ws.onopen = function () {
        document.getElementById('message_log').innerHTML = '<tr><td colspan="4"><b>WebSocket opened</b></td></tr>'+document.getElementById('message_log').innerHTML;
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
                        document.getElementById('message_log').innerHTML = '<tr><td colspan="4"><div class="user_conected">' +
                        'Пользователь:<b>' +
                        json.name +
                        ':</b>вошел в чат...' +
                        '</div></td></tr>' + document.getElementById('message_log').innerHTML;
                    }

                } else {
                    if (json.this_name === undefined) {
                        document.getElementById('message_log').innerHTML = '<tr><td colspan="4"><div class="user_close_conected">' +
                        'Пользователь:<b>' +
                        json.name + '' +
                        ' :</b>покинул чат=(' +
                        '</div></td></tr>' + document.getElementById('message_log').innerHTML;
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
                document.getElementById('message_log').innerHTML = '<tr>' +
                    '<td class="avatar">' +
                        'аватар'+
                    '</td>'+
                    '<td class="user_name">' +
                            json.name +' :'+
                    '</td>'+
                    '<td class="mess"> ' +
                        json.message +
                    '</td>' +
                    '<td class="time_mes">' +
                        date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds() +
                    '</td>' +
                '</tr>' + document.getElementById('message_log').innerHTML;
            }
        }
    }
    ws.onclose = function () {document.getElementById('message_log').innerHTML = 'WebSocket closed <br/>'+document.getElementById('message_log').innerHTML;}

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
