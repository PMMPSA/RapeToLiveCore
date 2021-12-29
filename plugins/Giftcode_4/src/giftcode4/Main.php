<?php

namespace giftcode4;

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
	 
	 if($cmd->getName() == "timcode4") {
		if($s->isOp()) {
			
		
		 $code = $this->generateCode();
		 $s->sendMessage("§aCode: ". $code);
		}
	 }
	 if($cmd->getName() == "nhancode4") {
	 
	    if(isset($args[0])) {
		
		
		if($this->codeExists($this->giftcode, $args[0])) {
		
		
	     if(!($this->codeExists($this->used, $args[0]))) {
		 
           $chance = mt_rand(1, 100);
		   
		   $this->addCode($this->used, $args[0]);
		   
		   $this->getServer()->getLogger()->info("§aDEBUG code:". $args[0]);
		   $this->getServer()->broadcastMessage("§a§l[§dGiftcode§a] ". $s->getName() ." §eđã sử dụng Giftcode số 4");
		   
		   switch($chance) {
		   case 50:
		     
			 $keys = array_rand(Item::$list, 4);
			 for($i = 0; $i <= 3; ++$i) {
			    $item = Item::get($keys[$i], 0, 1);
			   $s->addItem($item);
			   $s->sendMessage("§l§a[§dSPC§a]§e Bạn nhận được". $item->getName() ." §etừ giftcode");
			  
	    }
		break;
		  case 40:
		    $s->sendMessage("§l§a[§dSPC§a]§eBạn nhận được 25.000$, 120 diamond, 1 túi khoảng sản 64 các loại và 1 set giáp kim cương!!");
			$s->sendMessage("§l§e========§bSPC§e========");
			$this->eco->addMoney($s->getName(), 25000);
			$s->getInventory()->addItem(Item::get(41, 0, 64));
			$s->getInventory()->addItem(Item::get(264, 0, 120));
			$s->getInventory()->addItem(Item::get(42, 0, 64));
			$s->getInventory()->addItem(Item::get(22, 0, 64));
			$s->getInventory()->addItem(Item::get(57, 0, 64));
			$s->getInventory()->addItem(Item::get(152, 0, 64));
			$s->getInventory()->addItem(Item::get(133, 0, 64));
			$s->getInventory()->addItem(Item::get(310, 0, 1));
			$s->getInventory()->addItem(Item::get(311, 0, 1));
			$s->getInventory()->addItem(Item::get(311, 0, 1));
			$s->getInventory()->addItem(Item::get(312, 0, 1));
			$s->getInventory()->addItem(Item::get(313, 0, 1));
			break;
	       default:
		    $s->sendMessage("§l§a[§dSPC§a]§eBạn nhận được 25.000$, 120 diamond, 1 túi khoảng sản 64 các loại và 1 set giáp kim cương!!");
			$s->sendMessage("§l§e========§bSPC§e========");
			$this->eco->addMoney($s->getName(), 25000);
			$s->getInventory()->addItem(Item::get(41, 0, 64));
			$s->getInventory()->addItem(Item::get(264, 0, 120));
			$s->getInventory()->addItem(Item::get(42, 0, 64));
			$s->getInventory()->addItem(Item::get(22, 0, 64));
			$s->getInventory()->addItem(Item::get(57, 0, 64));
			$s->getInventory()->addItem(Item::get(152, 0, 64));
			$s->getInventory()->addItem(Item::get(133, 0, 64));
			$s->getInventory()->addItem(Item::get(310, 0, 1));
			$s->getInventory()->addItem(Item::get(311, 0, 1));
			$s->getInventory()->addItem(Item::get(311, 0, 1));
			$s->getInventory()->addItem(Item::get(312, 0, 1));
			$s->getInventory()->addItem(Item::get(313, 0, 1));
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