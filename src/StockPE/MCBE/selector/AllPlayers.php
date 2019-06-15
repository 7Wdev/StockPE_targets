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

class AllPlayers extends Selector {

    public function __construct()
      {
        parent::__construct("All players", "a", true);
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
        foreach(Server::getInstance()->getOnlinePlayers() as $p)
          {
            if($params["c"] !== 0 && count($return) == $params["c"]) continue;
            if($p->getLevel()->getName() !== $params["lvl"] && $params["lvl"] !== "") continue;
            if(!$this->checkDefaultParams($p, $params)) continue;
            $return[] = $p->getName();
          }
        return $return;
      }
}
?>
