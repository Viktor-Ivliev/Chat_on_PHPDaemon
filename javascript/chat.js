onload = function () {
    // вешаем обработчик события, срабатывающий при изменении avatar
    document.getElementById('avatar_use_id').addEventListener('change', onFilesSelect, true);
}

var avatar = "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBwwSEhIQEBAQEhQVEhQYFRYVDRUVFRcWFxQZIhUd" +
    "Hx8aHDQlHR0nGxYVLTEtJSkrPS8vGiQzODMsNzQtLysBCgoKDQ0OFRAQFSsgHRwsLCssLCssKyssLDcrLCwrKywrLCwrKyw3LCwrKysrKysr" +
    "LCsrNysrKysrKywrKysrK//AABEIAFAAUAMBIgACEQEDEQH/xAAbAAACAwEBAQAAAAAAAAAAAAAAAgQFBgMBB//EADIQAAICAQICBwcDBQAA" +
    "AAAAAAERAAIDBAUSIRUxQVFhktEGInGBkaHBMoLhE0JSYnL/xAAWAQEBAQAAAAAAAAAAAAAAAAAAAQL/xAAXEQEBAQEAAAAAAAAAAAAAAAAA" +
    "EQFB/9oADAMBAAIRAxEAPwD7g4OK4OAzg4rg4DODlfrN30+M8NrEntFQyPjOFPaHSkr3x4mnL7SzUq3cHOePJWwFqkEHqILBjORTODiuDgK4" +
    "OI4OVDuRdyz2pivaodly5dpndz1wMhtm3Wy3VuKoAZKLP17ZO3bZseOhvQnkmCW5e589aVNrFADnMvr9wzaiwpWp4XyqOZPiZq7rMzE72Vz2" +
    "9/H2crDwPN/iaByr2bb/AOlUm36rJ+AHUJYuTVw7g4jg5FK4OK4OAzg4rg4EPd9PfJj4KkD3gSygg/4nHZdBbFxm3CSUkXy5v8Q37Ba+MVrU" +
    "2PGCgH2GcvZ7S3xjJxUNGaphNA+svE6uXBxYORTODiuDgI5E3HcK4gGDYnqAK6usyS5m98yPKR/iAJrMTddM2+Zy+EVr8mfv6SDm1Wa/6slj" +
    "+7l9BynGEsZqdp921FQuIWHZxBkfP1nubeNTYLiFf+Qj9T+JAhEK649RkqXW9x8Lnn6yZh3nUV6zW/xqj9pXQgrTbdutcp4DU1smObB75YOZ" +
    "DR5eDJS3dYTXGTcXNI5Sbht2a+S1qgI/7eEuXBwM90TqO4eaHROo7h5poXBxSM90TqO4eaHROo7h5poXBxSM90TqO4eaHROo7h5poXBxSM9X" +
    "adQxyHWP7po3FcHA/9k=";

function onFilesSelect(e){
    // получаем объект FileList
    var file = e.target.files[0],
    // FileReader
    fr;
    // Если в файле содержится изображение
    if (/image.*/.test(file.type)) {
        fr = new FileReader();
        // считываем его в строку base64
        fr.readAsDataURL(file);
        // как только файл загружен
        fr.onload = (function (file) {
            return function (e) {
                avatar = e.target.result;
            }
        })(file);
    }
}

    function create() {
    window.ws = new WebSocket('ws://'+document.domain+':8090/chat');
    ws.onopen = function () {
        document.getElementById('message_log').innerHTML = '<tr><td colspan="4"><b>WebSocket opened</b></td></tr>'+document.getElementById('message_log').innerHTML;
        document.getElementById('avatar_user_view').innerHTML  = '<img src="' + avatar  + '" width="100" height="100" />';
        var message = JSON.stringify({
            "name":document.forms["publish"].elements["name"].value,
            "avatar":avatar

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
                if (document.getElementById('names_use').innerHTML == "") {
                    document.getElementById('names_use').innerHTML = "Ваш ник: " + json.name;
                }


            } else {
                var date = new Date();
                document.getElementById('message_log').innerHTML = '<tr>' +
                    '<td class="avatar">' +
                        '<img src="'+json.avatar+'" width="50" height="50" />'+
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
    var obj = document.getElementById('lab_avatar_use_id');
    obj.style.display = "none";
    var obj = document.getElementById('avatar_use_id');
    obj.style.display = "none";
    var obj = document.getElementById('message_id');
    obj.style.display = "block";
    var obj = document.getElementById('message_button_id');
    obj.style.display = "inline";
    var obj = document.getElementById('lab_message_id');
    obj.style.display = "inline";

};
