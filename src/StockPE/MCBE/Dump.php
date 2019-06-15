<?php
declare(strict_types = 1);
/*

  _____ _             _    _____  ______
 / ____| |           | |  |  __ \|  ____|
| (___ | |_ ___   ___| | _| |__) | |__
 \___ \| __/ _ \ / __| |/ /  ___/|  __|
 ____) | || (_) | (__|   <| |    | |____
|_____/ \__\___/ \___|_|\_\_|    |______|


                                         */
namespace StockPE\MCBE;

//pmmp libs
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\Server as PMServer;
use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;

//mine libs!
use StockPE\MCBE\Vanilla;

class Dump extends Player {

  	public function getName(): string
		  {
		    $username = $this->username;
	    	if($this->hasSpaces($username))
				 {
           $Replacement = Vanilla::$cfg->get("dump");
           $username = str_replace(" ", $Replacement, $username);
			     $this->username = $username;
			     $this->displayName = $username;
			     $this->iusername = strtolower($username);
           return $username;
				 }

		return $username;
		  }

  	public function getDisplayName(): string
		  {
		    $displayName = $this->displayName;
        if($this->hasSpaces($displayName))
				 {
			     $displayName = str_replace(" ", "_", $displayName);
			     $this->username = $displayName;
			     $this->displayName = $displayName;
			     $this->iusername = strtolower($displayName);
			     return $displayName;
				 }
		    return $displayName;
		  }

  	public function getLowerCaseName(): string
		  {
		    $iusername = $this->iusername;
		    if($this->hasSpaces($iusername))
				 {
			     $iusername = str_replace(" ", "_", $iusername);
			     $this->username = $iusername;
			     $this->displayName = $iusername;
			     $this->iusername = strtolower($iusername);
			     return $iusername;
				 }
		    return $iusername;
		  }

  	private function hasSpaces(string $string): bool
		  {
		    return strpos($string, ' ') !== false;
	    }
}
?>
