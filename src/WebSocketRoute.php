<?php
namespace Chat;
use PHPDaemon\Core\Daemon;
use PHPDaemon\WebSocket\Route;
class WebSocketRoute extends Route
{
	public $client;
    public $appInstance;
    public $id; // Здесь храним ID сессии
    public $name;//имя пользователя
    public $prefix_name_index;//прэфикс к имени
    public $avatar;



    public function __construct($client,$appInstance) {
        $this->client=$client;
        $this->appInstance=$appInstance;
    }

	public function onFrame($data, $type) {
        if($data!="mes") {
            $data_j = json_decode($data,true);
            if (isset($data_j["name"])) {
                $data_j["name"] = $this->uniqueName($data_j["name"]);
                $data_j["list_names"] = $this->listName();
                $this->avatar = $data_j["avatar"];
                foreach ($this->appInstance->sessions as $id => $session) {
                    if ($this->id != $id) {
                        $session->client->sendFrame(json_encode($data_j), 'STRING');
                    }
                }
                $data_j["this_name"] = true;
                $this->client->sendFrame(json_encode($data_j), 'STRING');
            } else {
                $data_j["name"] = $this->name . $this->prefix_name_index;
                $data_j["avatar"] =$this->avatar;
                foreach ($this->appInstance->sessions as $id => $session) {
                    $session->client->sendFrame(json_encode($data_j), 'STRING');
                }
            }
        }else{
            $this->client->sendFrame($data, 'STRING');
        }
	}
    /// проверка на уникальность имени, в противном случае присваевает прэфикс
    //$name - имя пользователя
    public function uniqueName($name)
    {
        $this->name = $name;
        $count = -1;
        $max_prefix=0;
        $brackets=array('(',')');
        foreach ($this->appInstance->sessions as $id => $session) {
            if ($name == $session->name) {
                $count++;
                if($max_prefix<str_replace($brackets, "", $session->prefix_name_index))
                {
                    $max_prefix= str_replace($brackets, "", $session->prefix_name_index);
                    $count = $max_prefix;
                }
            }
        }
        if ($count == 0) {
            $this->prefix_name_index = "";
        } else {
            $this->prefix_name_index = " (" . $count . ")";
        }
        return $this->name.$this->prefix_name_index;
    }


    /// получает масив существующих пользователей
    /// $close_use / false = не включая данного пользователя
    public function listName($close_use=true){
        $list_names = [];
        foreach ($this->appInstance->sessions as $id => $session) {
            if($close_use === true)//можно и без === true но так вылелуется
            {
                $list_names[]=$session->name.$session->prefix_name_index;
            }elseif($this->id!=$id){
                $list_names[]=$session->name.$session->prefix_name_index;
            }
        }
        return  $list_names;
    }

	// Этот метод срабатывает при закрытии соединения клиентом
    public function onFinish() {
        // Удаляем сессию из массива
        $data["list_names"]=$this->listName(false);
        foreach ($this->appInstance->sessions as $id => $session) {
            $data["name"] = $this->name.$this->prefix_name_index;
            $data["close_name"]=1;
            $session->client->sendFrame(json_encode($data), 'STRING');
        }
        unset($this->appInstance->sessions[$this->id]);
    }
}