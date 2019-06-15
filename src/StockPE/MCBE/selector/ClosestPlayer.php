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

//mine libs!
use StockPE\MCBE\Dump;

class ClosestPlayer extends Selector {

    public function __construct()
      {
        parent::__construct("Closest player", "p", false);
      }

    public function applySelector(CommandSender $sender, array $parameters = []): array
      {
        $online = Server::getInstance()->getOnlinePlayers();
        if(!($sender instanceof Player))
         {
           if(count($online) > 0)
            {
              return [$online[array_keys($online)[0]]->getName()];
            } else
              {
                return [$sender->getName()];
              }
         }
        if(count($online) > 1)
         {
           foreach($online as $p)
             {
               if($p->getLevel()->getName() == $sender->getLevel()->getName() && (!isset($selectedP) || $p->distanceSquared($sender) < $selectorP->distanceSquared($sender)))
                {
                  $selectedP = $p;
                }
             }
           return [$selectorP->getName()];
         } else
           {
             return [$sender->getName()];
           }
      }
}
?>
