<?php

namespace ktpl;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\entity\Entity;
use pocketmine\entity\Effect;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use onebone\economyapi\economyAPI;

class PX extends PluginBase implements Listener{

public $eco;

  public function onEnable(){
      $this->eco = EconomyAPI::getInstance();
      $this->getLogger()->info("PLUGIN QUÀ VIP ĐỘC QUYỀN FOR DOLPAN ON");
  }

  public function onCommand(CommandSender $sender, Command $command, $lable, array $args){
      if($command->getName() == "quavip1"){
          if(!isset($args[0])){
              $sender->sendMessage("§l§d》§f》§d》§f》§d》§f》§d》§f¤§bList VIP presetnts:§f¤§d《§f《§d《§f《§d《§f《§d《");
              $sender->sendMessage("§l§bcupthan: §fCúp Thần");
              $sender->sendMessage("§l§friuanhsang: §eRìu Ánh Sáng");
              $sender->sendMessage("§l§cxengdianguc: §fXẻng Địa Ngục");
              $sender->sendMessage("§l§d》§f》§d》§f》§d》§f》§d》§f¤§b§f¤§d《§f《§d《§f《§d《§f《§d《");
}
          switch($args[0]){
             case "cupthan":
               $p = $sender->getName();
$pi = $sender->getInventory();
$i = Item::get(278, 0 ,1);
$c = Enchantment::getEnchantment(15);
$c->setLevel(10);
$i->addEnchantment($c);
$i->setCustomName("§l§bCÚP THẦN");
$pi->addItem($i);
$sender->sendMessage("§aBẠN ĐÃ NHẬN ĐƯỢC CÚP THẦN NHỜ ĐẶC QUYỀN VIP");
break;
               case "riuanhsang":
               $p = $sender->getName();
$pi = $sender->getInventory();
$i = Item::get(279, 0 ,1);
$c = Enchantment::getEnchantment(15);
$c->setLevel(10);
$i->addEnchantment($c);
$i->setCustomName("§l§eRÌU ÁNH SÁNG");
$pi->addItem($i);
$sender->sendMessage("§aBẠN ĐÃ NHẬN ĐƯỢC RÌU ÁNH SÁNG NHỜ ĐẶC QUYỀN VIP");
break;
               case "xengdianguc":
$p = $sender->getName();
$pi = $sender->getInventory();
$i = Item::get(277, 0 ,1);
$c = Enchantment::getEnchantment(15);
$c->setLevel(10);
$i->addEnchantment($c);
$i->setCustomName("§l§cXẺNG ĐỊA NGỤC");
$pi->addItem($i);
$sender->sendMessage("§bBẠN ĐÃ NHẬN ĐƯỢC XẺNG ĐỊA NGỤC NHỜ ĐẶC QUYỀN VIP");
break;
}
}
}
}


                