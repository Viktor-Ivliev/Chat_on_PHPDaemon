<?php

namespace Chat;

use PHPDaemon\Core\AppInstance;
use PHPDaemon\Servers\WebSocket;

class Application extends AppInstance
{
	public $requestClass = '\Chat\HttpRequest';
	public $enableRPC=true; // Без этой строчки не будут работать широковещательные вызовы
    public $sessions=array();
    public $data;

	public function onReady()
	{

		$appInstance = $this;

		//Метод timerTask() будет вызываться каждые 5 секунд
        $this->timerTask($appInstance);

		$wsServer = WebSocket\Pool::getInstance(); /* @var WebSocket\Pool $wsServer */
		$wsServer->addRoute('chat', function ($client) {
			$session=new WebSocketRoute($client, $appInstance); // Создаем сессию
            $session->id=uniqid(); // Назначаем ей уникальный ID
            $this->sessions[$session->id]=$session; //Сохраняем в массив
            return $session;
			//return new WebSocketRoute($client, $this);
		});
	}

	function timerTask($appInstance) {
        // Отправляем каждому клиенту свое сообщение
        $this->data = "";
    	foreach($this->sessions as $id=>$session) {
    		if(isset($session->data))
    		{
    			$this->data = $this->data."".$session->data.'<br/>';
    			$session->data = null;
    		}
    	}
    	//if($this->data!='')
    	{
	        foreach($this->sessions as $id=>$session) {
	            $session->client->sendFrame('This is private message '.$this->data.' '.$id, 'STRING');
	        }
	    }

        
        // После отправляем всем клиентам сообщение от каждого воркера (широковещательный вызов)
        //$appInstance->broadcastCall('sendBcMessage', array(\PHPDaemon\Core\Daemon::$process->getPid()));
        
        // Перезапускаем наш метод спустя 5 секунд
        \PHPDaemon\Core\Timer::add(function($event) use ($appInstance) {
            $this->timerTask($appInstance);
            $event->finish();
        }, 5e6); // Время задается в микросекундах
    }
    
    // Функция для широковещательного вызова (при вызове срабатывает во всех воркерах)
    public function sendBcMessage($pid) {
    	$this->data = "";
    	foreach($this->sessions as $id=>$session) {
    		if(isset($session->data))
    		{
    			$this->data = $this->data."".$session->data.'<br/>';
    			$session->data = null;
    		}
    	}

        foreach($this->sessions as $id=>$session) {
			$session->client->sendFrame($this->data.'This message from worker #'.$pid.'<br/>', 'STRING');
        }
        $data = "";
    }


}
