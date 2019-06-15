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

class RandomPlayer extends Selector {

    public function __construct()
      {
        parent::__construct("Random player", "r", true);
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
        $possible = [];
        foreach(Server::getInstance()->getOnlinePlayers() as $p)
          {
            if($p->getLevel()->getName() !== $params["lvl"] && $params["lvl"] !== "") continue;
            if(!$this->checkDefaultParams($p, $params)) continue;
            $possible[] = $p;
          }
        if(count($possible) == 0) return [];
        return [$possible[array_rand($possible)]->getName()];
      }
}
?>
