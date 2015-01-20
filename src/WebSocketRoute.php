<?php
namespace Chat;
use PHPDaemon\Core\Daemon;
use PHPDaemon\WebSocket\Route;
class WebSocketRoute extends Route
{
	public $client;
    public $appInstance;
    public $id; // Здесь храним ID сессии
    public $name;
    public $prefix_name_index;



    public function __construct($client,$appInstance) {
        $this->client=$client;
        $this->appInstance=$appInstance;
    }

	public function onFrame($data, $type) {
        $date= (array) json_decode($data);
        if(isset($date["name"])) {
            $date["name"] = $this->unique_name($date["name"]);
            foreach ($this->appInstance->sessions as $id => $session) {
                if($this->id!=$id)
                {
                    $session->client->sendFrame(json_encode($date), 'STRING');
                }
            }
        }else{
            $date["name"] = $this->name.$this->prefix_name_index;
            foreach ($this->appInstance->sessions as $id => $session) {
                $session->client->sendFrame(json_encode($date), 'STRING');
            }
        }
	}

    public function unique_name($date)
    {
        $this->name = $date;
        $count = -1;
        foreach ($this->appInstance->sessions as $id => $session) {
            if ($date == $session->name) {
                ++$count;
            }
        }
        if ($count == 0) {
            $this->prefix_name_index = "";
        } else {
            $this->prefix_name_index = " (" . $count . ")";
        }
        return $this->name.$this->prefix_name_index;
    }

	// Этот метод срабатывает при закрытии соединения клиентом
    public function onFinish() {
        // Удаляем сессию из массива
        foreach ($this->appInstance->sessions as $id => $session) {
            if($this->id!=$id)
            {
                $date["name"] = $this->name.$this->prefix_name_index;
                $date["close_name"]=1;
                $session->client->sendFrame(json_encode($date), 'STRING');
            }
        }
        unset($this->appInstance->sessions[$this->id]);
    }
}