<?php

namespace giftcode;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use onebone\economyapi\EconomyAPI;
use pocketmine\utils\Config;

class Main extends PluginBase {
        
     public $used;
	 public $eco;
	 public $giftcode;
	 public $instance;

	 public function onEnable() {
	    if(!is_dir($this->getDataFolder())) {
		mkdir($this->getDataFolder());
		}

		$this->eco = EconomyAPI::getInstance();
		
		$this->used = new \SQLite3($this->getDataFolder() ."used-code.db");
		$this->used->exec("CREATE TABLE IF NOT EXISTS code (code);");
		
		$this->giftcode = new \SQLite3($this->getDataFolder() ."code.dn");
		$this->giftcode->exec("CREATE TABLE IF NOT EXISTS code (code);");
	 }
	 
	 public static function getInstance() {
	  return $this;
	  }
	  
	 public function generateCode() {
	     $characters = '012345abcdeABCDE';
    $charactersLength = strlen($characters);
	$length = 10;
    $randomString = 'MYTHIC';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
	
		$this->addCode($this->giftcode, $randomString);
		
		$this->getServer()->getLogger()->info("§aDEBUG ". $randomString);
    return $randomString;
	 }
	 public function codeExists($file, $code) {
		 
		 
		 $query = $file->query("SELECT * FROM code WHERE code='$code';");
		 $ar = $query->fetchArray(SQLITE3_ASSOC);
		 
		 if(!empty($ar)) {
			 return true;
		 } else {
			 return false;
		 }
	 }
	 
	 public function addCode($file, $code) {
		 
		 $stmt = $file->prepare("INSERT OR REPLACE INTO code (code) VALUES (:code);");
		 $stmt->bindValue(":code", $code);
		 $stmt->execute();
		 
	 }
	 public function onCommand(CommandSender $s, Command $cmd, $label, array $args) {
	 
	 if($cmd->getName() == "gencode") {
		if($s->isOp()) {
			
		
		 $code = $this->generateCode();
		 $s->sendMessage("§aCode: ". $code);
		}
	 }
	 if($cmd->getName() == "redeem") {
	 
	    if(isset($args[0])) {
		
		
		if($this->codeExists($this->giftcode, $args[0])) {
		
		
	     if(!($this->codeExists($this->used, $args[0]))) {
		 
           $chance = mt_rand(1, 100);
		   
		   $this->addCode($this->used, $args[0]);
		   
		   $this->getServer()->getLogger()->info("§aDEBUG code:". $args[0]);
		   $this->getServer()->broadcastMessage("§e[§aGiftcode§a] ". $s->getName() ." đã sử dụng 1 giftcode!");
		   
		   switch($chance) {
		   case 50:
		     
			 $keys = array_rand(Item::$list, 4);
			 for($i = 0; $i <= 3; ++$i) {
			    $item = Item::get($keys[$i], 0, 1);
			   $s->addItem($item);
			   $s->sendMessage("§aBạn nhận được §c". $item->getName() ." §atừ giftcode");
			  
	    }
		break;
		  case 40:
		    $s->sendMessage("§a Bạn may mắn nhận được 10k!và 5 khối kim cương");
			$s->sendMessage("§a You are very lucky to get 10000 gil and 5 blocks of §bDIAMOND");
			$this->eco->addMoney($s->getName(), 10000);
			$s->getInventory()->addItem(Item::get(57, 0, 5));
			break;
	       default:
		    $s->sendMessage("§e may mắn không đến với bạn hôm nay rồi :(, nhận được 3k và 64 khối đất cỏ :))");
			$s->sendMessage("§e Lucky didn't get on well today. Recieved 3000 gil and 64 grass");
			$this->eco->addMoney($s->getName(), 3000);
			$s->getInventory()->addItem(Item::get(2, 0, 64));
			break;
	    }
	   } else {
	   $s->sendMessage("§c Giftcode đã được sử dụng");
	   return true;
	   }
      } else {
	  $s->sendMessage("§c Không tìm thấy giftcode!");
	  return true;
	  }
	 }
    }
   }
  }