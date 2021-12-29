<?php

namespace napthe;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandReader;
use pocketmine\command\CommandExecuter;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use onebone\economyapi\EconomyAPI;
use _64FF00\PurePerms\PurePerms;
use MCPEVN\SetVip;

 class Main extends PluginBase {
  
  const CORE_API_HTTP_USER = "merchant_25415";
  const CORE_API_HTTP_PWD = "25415sVYJhASENbOpwl6zjnov28Pqu7uDfQ";
  const BK = "https://www.baokim.vn/the-cao/restFul/send";
  public $prefix = "§a[ §6MYTHIC-§bNapThe§a ]";
  public $cfg;
  public $tien;
  public $cap;
  public $rank;
  public $data;
  public $eco;
  
    public function onEnable() {
   
    if(!is_dir($this->getDataFolder())) {
      mkdir($this->getDataFolder());
      }
      
      $this->eco = EconomyAPI::getInstance();
      $this->purePerms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
    $this->data = new Config($this->getDataFolder() ."tong_card_mem_nap.yml", Config::YAML, [
    ]);
	
   
    $this->tien = new Config($this->getDataFolder() ."nap_tien.yml", Config::YAML, [
	"10000" => "5000",
    "20000" => "10000",
    "50000" => "25000",
	"100000" => "60000",
	"200000" => "150000",
	"500000" => "500000"
	
    ]);
    
    $this->cap = new Config($this->getDataFolder() ."nap_vip.yml", Config::YAML, [
	"10000" => "vip1",
	"20000" => "vip1",
    "50000" => "vip2",
	"100000" => "vip3",
	"200000" => "vip4"
    ]);
	
	$this->cfg = new Config($this->getDataFolder() ."cai_dat.yml", Config::YAML, [
    "merchant_id" => "hhsw",
    "secure_code" => "mat_khau_website",
    "api_username" => "api_username",
    "api_pass" => "api_pass",
    "uuid" => "Jkllkjaiaim"
    ]);
    
	$duongdan = 'plugins/NapThe/card_dung.log';
	if(file_exists($duongdan)){
          $this->getLogger()->info(TextFormat::GREEN."§aplugins\NapThe\card_dung.log §eđã sẵn sàng");
		 
    }else{
    $fh = fopen('plugins\NapThe\card_dung.log', "a") ;
	fwrite($fh,'| Tai khoan                |     Loai the  |   Menh gia    |       Thoi gian                   |');
	fwrite($fh,"\r\n");
	fclose($fh);
    }
	$duongdan = 'plugins/NapThe/card_delay.log';
	if(file_exists($duongdan)){
 
		  $this->getLogger()->info(TextFormat::GREEN."§aplugins\NapThe\card_delay.log §eđã sẵn sàng");
    }else{
    $fh = fopen('plugins\NapThe\card_delay.log', "a") ;
	fwrite($fh,'| Tai khoan                |     Loai the  |   Menh gia    |       Thoi gian                   |');
	fwrite($fh,"\r\n");
	fclose($fh);
	}
    }
	
    public function onCommand(CommandSender $s, Command $cmd, $label, array $args) {
		
  $merchant_id = $this->cfg->get("merchant_id");
  $api_username = $this->cfg->get("api_username");
  $api_pass = $this->cfg->get("api_pass");
  $uuid = $this->cfg->get("uuid");
  $secure_code = $this->cfg->get("secure_code");
 
  settype($merchant_id, "string");
  settype($api_username, "string");
  settype($api_pass, "string");
  settype($uuid, "string");
  settype($secure_code, "string");
      
      if(strtolower($cmd->getName()) == "napthe") {
        
        if(isset($args[0])) {
          
          switch(strtolower($args[0])) {
            
            case "coin":
              
              if(isset($args[1]) && isset($args[2]) && isset($args[3])) {
                
                if(is_numeric($args[1]) && is_numeric($args[2])) {
                  
                  
                  $tranid = time();
                  switch(strtolower($args[3])) {
                    
                    case "vina":
                     $mang = "VINA";
                     break;
                     
                    case "mobi":
                     $mang = "MOBI";
                    break;
                    
                    case "viettel":
                     $mang = "VIETEL";
                    break;
                    
                    case "vtc":
                     $mang = "VTC";
                     break;
                     
                    case "gate":
                     $mang = "GATE";
                     
                    break;
                    }
         settype($mang,"string");
		 settype($tranid,"string");
                  $arrayPost = array(
		'merchant_id'=> $merchant_id,
		'api_username'=> $api_username,
		'api_password'=> $api_pass,
		'transaction_id'=> $tranid,
		'card_id'=> $mang,
		'pin_field'=> $args[1],
		'seri_field'=> $args[2],
		'algo_mode'=>'hmac'
);

$pina = (strtolower($args[1]));
$seria = (strtolower($args[2]));
$mang = (strtolower($args[3]));

$s->sendMessage("§e§b---§aBạn Đã Nhập§b---");
$s->sendMessage("§a•§aSeri:§e".$pina.'');
$s->sendMessage("§a•§aPin:§e".$seria.'');
$s->sendMessage("§a•§aMạng:§e".$mang.'');

ksort($arrayPost);

$data_sign = hash_hmac('SHA1',implode('',$arrayPost),$secure_code);

$arrayPost['data_sign'] = $data_sign;

$curl = curl_init(self::BK);

curl_setopt_array($curl, array(
		CURLOPT_POST=>true,
		CURLOPT_HEADER=>false,
		CURLINFO_HEADER_OUT=>true,
		CURLOPT_TIMEOUT=>30,
		CURLOPT_RETURNTRANSFER=>true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH=>CURLAUTH_DIGEST|CURLAUTH_BASIC,
		CURLOPT_USERPWD=> self::CORE_API_HTTP_USER.':'.self::CORE_API_HTTP_PWD,
		CURLOPT_POSTFIELDS=>http_build_query($arrayPost)
));

$data = curl_exec($curl);

$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

$result = json_decode($data,true);
date_default_timezone_set('Asia/Ho_Chi_Minh');
  
     if($status == 200) {
		 
   if($this->tien->exists($result["amount"])) {
     
 
     
     if($this->data->exists(strtolower($s->getName()))) {
       
     $this->data->set(strtolower($s->getName()), $this->data->get(strtolower($s->getName())) + $result["amount"]);
	 $this->data->save();
     } else {
       $this->data->set(strtolower($s->getName()), $result["amount"]);
	   $this->data->save();
       }
     
     $this->eco->addMoney($s->getName(), $this->tien->get($result["amount"]));
     
	   //xu ly loai cardokay
	   if($result["amount"]=='10000'){
			$loai_card ="10k";
		}
	   else if($result["amount"]=='20000'){
			$loai_card ="20k";
		}
	   else if($result["amount"]=='50000'){
			$loai_card = "50k";
		}
	   else if($result["amount"]=='100000'){
			$loai_card = "100k";
		}
	   else if($result["amount"] == '200000'){
			$loai_card = "200k";
		}
	   else if($result["amount"] == '500000'){
			$loai_card = "500k";
		}

	   
	   
	   $ngay_nap = date("d-m-Y H:i:s");
	  $length1 = 25 - strlen($s->getName());
	  $length2 = 10 - strlen($mang);
	  $length3= 10 - strlen($loai_card);
	  $length4 = 30 - strlen($ngay_nap);
	   
	   

	$ngay_nap = date("d-m-Y H:i:s");
	$space = ' ';
	$fh = fopen('plugins\NapThe\card_dung.log', "a") ;
	fwrite($fh,'------------------------------------------------------------------------------------------------');
	fwrite($fh,"\r\n");
	fclose($fh);
	$fh = fopen('plugins\NapThe\card_dung.log', "a") ;
	fwrite($fh,"| ".$s->getName().str_repeat($space,$length1)."|     ".$mang.str_repeat($space,$length2)."|     ".$loai_card.str_repeat($space,$length3)."|     ".$ngay_nap.str_repeat($space,$length4)."|");
	fwrite($fh,"\r\n");
	fclose($fh);
	$fh = fopen('plugins\NapThe\card_dung.log', "a") ;
	fwrite($fh,'------------------------------------------------------------------------------------------------');
	fwrite($fh,"\r\n");
	fclose($fh);
	
    
	
     $s->sendMessage("§a- Nạp thẻ thành công! Bạn đã nhận được ". $this->tien->get($result["amount"]) ." $!");
     return true;
     
	 } else {
		 //nap the thanh cong nhung ko co menh gia
       $s->sendMessage("§a- thẻ nạp thành công nhưng mệnh giá thẻ không có trong danh sách thẻ nên không nhận được đồng xu nào, xin hãy chụp màn hình và gửi cho Tuan Lam trên facebook");
       if($result["amount"]=='10000'){
			$loai_card ="10k";
		}
	   else if($result["amount"]=='20000'){
			$loai_card ="20k";
		}
	   else if($result["amount"]=='50000'){
			$loai_card = "50k";
		}
	   else if($result["amount"]=='100000'){
			$loai_card = "100k";
		}
	   else if($result["amount"] == '200000'){
			$loai_card = "200k";
		}
	   else if($result["amount"] == '500000'){
			$loai_card = "500k";
		}
	  
	  $ngay_nap = date("d-m-Y H:i:s");
	  $length1 = 25 - strlen($s->getName());
	  $length2 = 10 - strlen($mang);
	  $length3= 10 - strlen($loai_card);
	  $length4 = 30 - strlen($ngay_nap);
	  
	 
	$space = ' ';
	$fh = fopen('plugins\NapThe\card_delay.log', "a") ;
	fwrite($fh,'------------------------------------------------------------------------------------------------');
	fwrite($fh,"\r\n");
	fclose($fh);
	$fh = fopen('plugins\NapThe\card_delay.log', "a") ;
	fwrite($fh,"| ".$s->getName().str_repeat($space,$length1)."|     ".$mang.str_repeat($space,$length2)."|     ".$loai_card.str_repeat($space,$length3)."|     ".$ngay_nap.str_repeat($space,$length4)."|");
	fwrite($fh,"\r\n");
	fclose($fh);
	$fh = fopen('plugins\NapThe\card_delay.log', "a") ;
	fwrite($fh,'------------------------------------------------------------------------------------------------');
	fwrite($fh,"\r\n");
	fclose($fh);
	   return true;
       
      $this->data->set(strtolower($s->getName()), "Error: không có mệnh giá");
      }
	  
	 } else {
       
       $s->sendMessage("§c- Lỗi khi thực hiện giao dịch, mã lỗi: §b". $status);
       $s->sendMessage("§b- DolpanError404 ". $result["errorMessage"]);
       return true;
       
       }
	   
          
 
    } else {
      $s->sendMessage("§c- Mã thẻ và mã pin phải là số");
      }
    } else {
      $s->sendMessage($this->prefix."§a• §acách dùng:  §d/napthe §a<coin | vip> <Mã> <Seri> <LoạiCard> \n §a• §aLoại Card: §dmobi, viettel, vina, gate\n §a•");
     }
   break;
   
   case "vip":
              
              if(isset($args[1]) && isset($args[2]) && isset($args[3])) {
                
                if(is_numeric($args[1]) && is_numeric($args[2])) {
                  
               
                  $tranid = time();
                  switch(strtolower($args[3])) {
                    
                    case "vina":
                     $mang = "VINA";
                     break;
                     
                    case "mobi":
                     $mang = "MOBI";
                    break;
                    
                    case "viettel":
                     $mang = "VIETEL";
                    break;
                    
                    case "vtc":
                     $mang = "VTC";
                     break;
                     
                    case "gate":
                     $mang = "GATE";
                     
                    break;
                    }
         settype($mang,"string");
		 settype($tranid,"string");
                  $arrayPost = array(
		'merchant_id'=> $merchant_id,
		'api_username'=> $api_username,
		'api_password'=> $api_pass,
		'transaction_id'=> $tranid,
		'card_id'=> $mang,
		'pin_field'=> $args[1],
		'seri_field'=> $args[2],
		'algo_mode'=>'hmac'
);

$pina = (strtolower($args[1]));
$seria = (strtolower($args[2]));
$mang = (strtolower($args[3]));
$s->sendMessage("§e§l---§aBạn Đã Nhập§e---");

$s->sendMessage("§a•§aSeri:§e".$pina.'');
$s->sendMessage("§a•§aPin:§e".$seria.'');
$s->sendMessage("§a•§aMạng:§e".$mang.'');

ksort($arrayPost);

$data_sign = hash_hmac('SHA1',implode('',$arrayPost),$secure_code);

$arrayPost['data_sign'] = $data_sign;

$curl = curl_init(self::BK);

curl_setopt_array($curl, array(
		CURLOPT_POST=>true,
		CURLOPT_HEADER=>false,
		CURLINFO_HEADER_OUT=>true,
		CURLOPT_TIMEOUT=>30,
		CURLOPT_RETURNTRANSFER=>true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH=>CURLAUTH_DIGEST|CURLAUTH_BASIC,
		CURLOPT_USERPWD=> self::CORE_API_HTTP_USER.':'.self::CORE_API_HTTP_PWD,
		CURLOPT_POSTFIELDS=>http_build_query($arrayPost)
));

$data = curl_exec($curl);

$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

$result = json_decode($data,true);
date_default_timezone_set('Asia/Ho_Chi_Minh');

   
     if($status == 200) {
     if($this->cap->exists($result["amount"])) {
     
	  
     if($this->data->exists(strtolower($s->getName()))) {
       
     $this->data->set(strtolower($s->getName()), $this->data->get(strtolower($s->getName())) + $result["amount"]);
	 $this->data->save();
     } else {
       $this->data->set(strtolower($s->getName()), $result["amount"]);
	   $this->data->save();
       }

	
	   //xu ly loai cardokay
	   if($result["amount"]=='10000'){
			$loai_card ="10k";
		}
	   else if($result["amount"]=='20000'){
			$loai_card ="20k";
		}
	   else if($result["amount"]=='50000'){
			$loai_card = "50k";
		}
	   else if($result["amount"]=='100000'){
			$loai_card = "100k";
		}
	   else if($result["amount"] == '200000'){
			$loai_card = "200k";
		}
	   else if($result["amount"] == '500000'){
			$loai_card = "500k";
		}
	 
	   //xu ly ngay vip
	   if($result["amount"]=='10000'){
			$ngay ="10";
		}
		if($result["amount"]=='20000'){
			$ngay ="14";
		}
	   else if($result["amount"]=='50000'){
			$ngay = "60";
		}
	   else if($result["amount"]=='100000'){
			$ngay = "90";
		}
	   else if($result["amount"] == '200000'){
			$ngay = "180";
		}
	 
     $this->getServer()->dispatchCommand(new ConsoleCommandSender(),'setvip vip2 '.strtolower($s->getName()).' '.$ngay."");
     $this->purePerms->setGroup($s, $this->purePerms->getGroup($this->cap->get($result["amount"])));
     
     $s->sendMessage("§a• §a nạp thẻ thành công! bạn đã nhận được ". $this->cap->get($result["amount"]) ."!");
	 $ngay_nap = date("d-m-Y H:i:s");
	  $length1 = 25 - strlen($s->getName());
	  $length2 = 10 - strlen($mang);
	  $length3= 10 - strlen($loai_card);
	  $length4 = 30 - strlen($ngay_nap);
	 
	$space = ' ';
	$fh = fopen('plugins\NapThe\card_dung.log', "a") ;
	fwrite($fh,'------------------------------------------------------------------------------------------------');
	fwrite($fh,"\r\n");
	fclose($fh);
	$fh = fopen('plugins\NapThe\card_dung.log', "a") ;
	fwrite($fh,"| ".$s->getName().str_repeat($space,$length1)."|     ".$mang.str_repeat($space,$length2)."|     ".$loai_card.str_repeat($space,$length3)."|     ".$ngay_nap.str_repeat($space,$length4)."|");
	fwrite($fh,"\r\n");
	fclose($fh);
	$fh = fopen('plugins\NapThe\card_dung.log', "a") ;
	fwrite($fh,'------------------------------------------------------------------------------------------------');
	fwrite($fh,"\r\n");
	fclose($fh);
     return true;
     
	 } else {
		 //card nap thanh cong nhung ko co menh gia
       $s->sendMessage("§e• §a thẻ nạp thành công nhưng mệnh giá thẻ không có trong danh sách thẻ nên không nhận được đồng xu nào, xin hãy chụp màn hình và gửi cho Tuan Lam trên facebook");
       if($result["amount"]=='10000'){
			$loai_card ="10k";
		}
	   else if($result["amount"]=='20000'){
			$loai_card ="20k";
		}
	   else if($result["amount"]=='50000'){
			$loai_card = "50k";
		}
	   else if($result["amount"]=='100000'){
			$loai_card = "100k";
		}
	   else if($result["amount"] == '200000'){
			$loai_card = "200k";
		}
	   else if($result["amount"] == '500000'){
			$loai_card = "500k";
		}
	  
	  $ngay_nap = date("d-m-Y H:i:s");
	  $length1 = 25 - strlen($s->getName());
	  $length2 = 10 - strlen($mang);
	  $length3= 10 - strlen($loai_card);
	  $length4 = 30 - strlen($ngay_nap);
	  
	 
	$space = ' ';
	$fh = fopen('plugins\NapThe\card_delay.log', "a") ;
	fwrite($fh,'------------------------------------------------------------------------------------------------');
	fwrite($fh,"\r\n");
	fclose($fh);
	$fh = fopen('plugins\NapThe\card_delay.log', "a") ;
	fwrite($fh,"| ".$s->getName().str_repeat($space,$length1)."|     ".$mang.str_repeat($space,$length2)."|     ".$loai_card.str_repeat($space,$length3)."|     ".$ngay_nap.str_repeat($space,$length4)."|");
	fwrite($fh,"\r\n");
	fclose($fh);
	$fh = fopen('plugins\NapThe\card_delay.log', "a") ;
	fwrite($fh,'------------------------------------------------------------------------------------------------');
	fwrite($fh,"\r\n");
	fclose($fh);
     return true;
	  return true;
       
      $this->data->set(strtolower($s->getName()), "error: không có mệnh giá");
      }
	 } else {
       
       $s->sendMessage("§c- lỗi khi thực hiện giao dịch, mã lỗi: §b". $status);
       $s->sendMessage("§c- miêu tả lỗi: ". $result["errorMessage"]);
       return true;
       
       }
	   
          

    } else {
      $s->sendMessage("§c- Mã thẻ và mã pin phải là số");
      }
    } else {
      $s->sendMessage($this->prefix."§a• §acách dùng:  §d/napthe §a<coin | vip> <Mã> <Seri> <LoạiCard> \n §a• §aLoại Card: §dmobi, viettel, vina, gate\n §a•");
     }
   break;
   }
 } else {
   $s->sendMessage($this->prefix."§a• §acách dùng:  §d/napthe §a<coin | vip> <Mã> <Seri> <LoạiCard> \n §a• §aLoại Card: §dmobi, viettel, vina, gate\n §a•");
   }
  }
   
 }
 
}