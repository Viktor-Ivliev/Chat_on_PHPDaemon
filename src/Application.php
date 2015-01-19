<?php

namespace Chat;

use PHPDaemon\Core\AppInstance;
use PHPDaemon\Servers\WebSocket;

class Application extends AppInstance
{
	public $requestClass = '\Chat\HttpRequest';
    public $sessions=array();
    public $data;

	public function onReady()
	{

		$appInstance = $this;
		$ape = true;

		//Метод timerTask() будет вызываться каждые 1.2 секунды
		$this->timerTask($appInstance);

		$wsServer = WebSocket\Pool::getInstance(); /* @var WebSocket\Pool $wsServer */

		$wsServer->addRoute('chat', function ($client) {
			$session=new WebSocketRoute($client, $appInstance); // Создаем сессию
            $session->id=uniqid(); // Назначаем ей уникальный ID
            $this->sessions[$session->id]=$session; //Сохраняем в массив
            return $session;
		});
	}

	function timerTask($appInstance) {
        // Отправляем каждому клиенту свое сообщение
        $this->data = "";
    	foreach($this->sessions as $id=>$session) {
    		if($session->data != "")
    		{
    			$this->data = $this->data.'('.$id.')<div class="mess">'.$session->data.'</div><br/>';
    			$session->data = "";
    		}
    	}
    	if($this->data!='')
    	 {
	         foreach($this->sessions as $id=>$session) {
	         	$session->client->sendFrame($this->data, 'STRING');
	            //$session->client->sendFrame($this->data.' для'.$id, 'STRING');
	         }
	     }

        \PHPDaemon\Core\Timer::add(function($event) use ($appInstance) {
            $this->timerTask($appInstance);
            $event->finish();
        }, 1e6); // Время задается в микросекундах
    }
}
