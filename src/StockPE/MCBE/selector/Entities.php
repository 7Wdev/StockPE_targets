<?php
declare(strict_types=1);
/*

  _____ _             _    _____  ______
 / ____| |           | |  |  __ \|  ____|
| (___ | |_ ___   ___| | _| |__) | |__
 \___ \| __/ _ \ / __| |/ /  ___/|  __|
 ____) | || (_) | (__|   <| |    | |____
|_____/ \__\___/ \___|_|\_\_|    |______|


                                         */
namespace StockPE\MCBE\selector;

//pmmp libs!
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\level\Position;

//mine libs!
use StockPE\MCBE\Vanilla;

class Entities extends Selector {

     public function __construct()
       {
         parent::__construct("Entities", "e", true);
       }

     public function applySelector(CommandSender $sender, array $parameters = []): array
       {
          $defaultParams = Selector::DEFAULT_PARAMS;
          if($sender instanceof Position)
           {
             $defaultParams["x"] = $sender->x;
             $defaultParams["y"] = $sender->y;
             $defaultParams["z"] = $sender->z;
           }
          $params = $parameters + $defaultParams;
          $return = [];
          foreach(Server::getInstance()->getLevels() as $lvl)
            {
              foreach($lvl->getEntities() as $e)
                {
                  if($params["c"] !== 0 && count($return) == $params["c"]) continue;
                  if($e->getLevel()->getName() !== $params["lvl"] && $params["lvl"] !== "") continue;
                  if(!$this->checkDefaultParams($e, $params)) continue;
                  $return[] = "".$e->getId();
                }
            }
          return array_merge($return, Vanilla::getSelector("a")->applySelector($sender, $parameters));
       }
}
?>
