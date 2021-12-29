<?php

namespace giftcode3;

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
	 
	 if($cmd->getName() == "timcode3") {
		if($s->isOp()) {
			
		
		 $code = $this->generateCode();
		 $s->sendMessage("§aCode: ". $code);
		}
	 }
	 if($cmd->getName() == "nhancode3") {
	 
	    if(isset($args[0])) {
		
		
		if($this->codeExists($this->giftcode, $args[0])) {
		
		
	     if(!($this->codeExists($this->used, $args[0]))) {
		 
           $chance = mt_rand(1, 100);
		   
		   $this->addCode($this->used, $args[0]);
		   
		   $this->getServer()->getLogger()->info("§aDEBUG code:". $args[0]);
		   $this->getServer()->broadcastMessage("§a§l[§dGiftcode§a] ". $s->getName() ." §eđã sử dụng Giftcode số 3");
		   
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
		    $s->sendMessage("§l§a[§dSPC§a]§eBạn nhận được 15.000$, 64 thịt bò, 1 set vật dụng kim cương, 5 nether star, túi block 20 khoảng sản lapis và emerald ");
			$s->sendMessage("§l§e========§bSPC§e========");
			$this->eco->addMoney($s->getName(), 15000);
			$s->getInventory()->addItem(Item::get(276, 0, 1));
			$s->getInventory()->addItem(Item::get(277, 0, 1));
			$s->getInventory()->addItem(Item::get(364, 0, 64));
          $s->getInventory()->addItem(Item::get(278, 0, 1));
			$s->getInventory()->addItem(Item::get(279, 0, 1));
			$s->getInventory()->addItem(Item::get(293, 0, 1));
			$s->getInventory()->addItem(Item::get(133, 0, 20));
          $s->getInventory()->addItem(Item::get(22, 0, 20));
          $s->getInventory()->addItem(Item::get(399, 0, 5));
			break;
	       default:
		    $s->sendMessage("§l§a[§dSPC§a]§eBạn nhận được 15.000$, 64 thịt bò, 1 set vật dụng kim cương, 5 nether star, túi block 20 khoảng sản lapis và emerald ");
			$s->sendMessage("§l§e========§bSPC§e========");
			$this->eco->addMoney($s->getName(), 15000);
			$s->getInventory()->addItem(Item::get(276, 0, 1));
			$s->getInventory()->addItem(Item::get(277, 0, 1));
			$s->getInventory()->addItem(Item::get(364, 0, 64));
          $s->getInventory()->addItem(Item::get(278, 0, 1));
			$s->getInventory()->addItem(Item::get(279, 0, 1));
			$s->getInventory()->addItem(Item::get(293, 0, 1));
			$s->getInventory()->addItem(Item::get(133, 0, 20));
          $s->getInventory()->addItem(Item::get(22, 0, 20));
          $s->getInventory()->addItem(Item::get(399, 0, 5));
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