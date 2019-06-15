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

class SelfSelector extends Selector {

    public function __construct()
      {
        parent::__construct("Self", "s", false);
      }

    public function applySelector(CommandSender $sender, array $parameters = []): array
      {
        return [$sender->getName()];
      }
}
?>
