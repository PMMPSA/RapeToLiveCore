<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 *  _____            _               _____           
 * / ____|          (_)             |  __ \          
 *| |  __  ___ _ __  _ ___ _   _ ___| |__) | __ ___  
 *| | |_ |/ _ \ '_ \| / __| | | / __|  ___/ '__/ _ \ 
 *| |__| |  __/ | | | \__ \ |_| \__ \ |   | | | (_) |
 * \_____|\___|_| |_|_|___/\__, |___/_|   |_|  \___/ 
 *                         __/ |                    
 *                        |___/                     
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author RapeToLive
 * @link https://github.com/RapeToLive/RapeToLive
 *
 *
*/

namespace pocketmine;


use pocketmine\metadata\Metadatable;
use pocketmine\metadata\MetadataValue;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\Plugin;

class OfflinePlayer implements IPlayer, Metadatable {

	private $name;
	private $server;
	private $namedtag;

	/**
	 * @param Server $server
	 * @param string $name
	 */
	public function __construct(Server $server, $name){
		$this->server = $server;
		$this->name = $name;
		if(file_exists($this->server->getDataPath() . "players/" . strtolower($this->getName()) . ".dat")){
			$this->namedtag = $this->server->getOfflinePlayerData($this->name);
		}else{
			$this->namedtag = null;
		}
	}

	/**
	 * @return bool
	 */
	public function isOnline(){
		return $this->getPlayer() !== null;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return $this->name;
	}

	/**
	 * @return Server
	 */
	public function getServer(){
		return $this->server;
	}

	/**
	 * @return bool
	 */
	public function isOp(){
		return $this->server->isOp(strtolower($this->getName()));
	}

	/**
	 * @param bool $value
	 */
	public function setOp($value){
		if($value === $this->isOp()){
			return;
		}

		if($value === true){
			$this->server->addOp(strtolower($this->getName()));
		}else{
			$this->server->removeOp(strtolower($this->getName()));
		}
	}

	/**
	 * @return bool
	 */
	public function isBanned(){
		return $this->server->getNameBans()->isBanned(strtolower($this->getName()));
	}

	/**
	 * @param bool $value
	 */
	public function setBanned($value){
		if($value === true){
			$this->server->getNameBans()->addBan($this->getName(), null, null, null);
		}else{
			$this->server->getNameBans()->remove($this->getName());
		}
	}

	/**
	 * @return bool
	 */
	public function isWhitelisted(){
		return $this->server->isWhitelisted(strtolower($this->getName()));
	}

	/**
	 * @param bool $value
	 */
	public function setWhitelisted($value){
		if($value === true){
			$this->server->addWhitelist(strtolower($this->getName()));
		}else{
			$this->server->removeWhitelist(strtolower($this->getName()));
		}
	}

	/**
	 * @return Player
	 */
	public function getPlayer(){
		return $this->server->getPlayerExact($this->getName());
	}

	/**
	 * @return null
	 */
	public function getFirstPlayed(){
		return $this->namedtag instanceof CompoundTag ? $this->namedtag["firstPlayed"] : null;
	}

	/**
	 * @return null
	 */
	public function getLastPlayed(){
		return $this->namedtag instanceof CompoundTag ? $this->namedtag["lastPlayed"] : null;
	}

	/**
	 * @return bool
	 */
	public function hasPlayedBefore(){
		return $this->namedtag instanceof CompoundTag;
	}

	/**
	 * @param string        $metadataKey
	 * @param MetadataValue $metadataValue
	 */
	public function setMetadata($metadataKey, MetadataValue $metadataValue){
		$this->server->getPlayerMetadata()->setMetadata($this, $metadataKey, $metadataValue);
	}

	/**
	 * @param string $metadataKey
	 *
	 * @return MetadataValue[]
	 */
	public function getMetadata($metadataKey){
		return $this->server->getPlayerMetadata()->getMetadata($this, $metadataKey);
	}

	/**
	 * @param string $metadataKey
	 *
	 * @return bool
	 */
	public function hasMetadata($metadataKey){
		return $this->server->getPlayerMetadata()->hasMetadata($this, $metadataKey);
	}

	/**
	 * @param string $metadataKey
	 * @param Plugin $plugin
	 */
	public function removeMetadata($metadataKey, Plugin $plugin){
		$this->server->getPlayerMetadata()->removeMetadata($this, $metadataKey, $plugin);
	}


}
