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
namespace StockPE\MCBE;

//pmmp libs!
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\server\ServerCommandEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\utils\TextFormat;
use pocketmine\command\{Command, CommandSender, defaults\VanillaCommand};
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\entity\Entity;
use pocketmine\lang\TranslationContainer;
use pocketmine\utils\Config;

//mine libs!
use StockPE\MCBE\selector\Selector;
use StockPE\MCBE\selector\ClosestPlayer;
use StockPE\MCBE\selector\AllPlayers;
use StockPE\MCBE\selector\RandomPlayer;
use StockPE\MCBE\selector\SelfSelector;
use StockPE\MCBE\selector\Entities;
use StockPE\MCBE\Dump;

class Vanilla extends PluginBase implements Listener {

    protected static $selectors = [];
    public static $cfg;

    public function onEnable(): void
      {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        self::registerSelector(new ClosestPlayer());
        self::registerSelector(new AllPlayers());
        self::registerSelector(new RandomPlayer());
        self::registerSelector(new SelfSelector());
        self::registerSelector(new Entities());

        //config setup!
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->saveResource("editme.yml");
        self::$cfg = new Config($this->getDataFolder() . "editme.yml", Config::YAML);
      }

    public function onCommandPreProcess(PlayerCommandPreProcessEvent $event): void
      {
        $m = substr($event->getMessage(), 1);
        if(substr($event->getMessage(), 0, 1) == "/" && $this->execSelectors($m, $event->getPlayer())) $event->setCancelled();
      }

    public function onServerCommand(ServerCommandEvent $event): void
      {
        $m = $event->getCommand();
        if($this->execSelectors($m, $event->getSender())) $event->setCancelled();
      }

    public function execSelectors(string $m, CommandSender $sender): bool
      {
        preg_match_all($this->buildRegExr(), $m, $matches);
        $commandsToExecute = [$m];
        foreach($matches[0] as $index => $match)
          {
            if(isset(self::$selectors[$matches[1][$index]]))
             {
               $params = self::$selectors[$matches[1][$index]]->acceptModifiers() ? $this->checkArgParams($matches, $index): [];
               $newCommandsToExecute = [];
               foreach($commandsToExecute as $index => $cmd)
                 {
                   foreach(self::$selectors[$matches[1][$index]]->applySelector($sender, $params) as $selectorStr)
                     {
                      if(strpos($selectorStr, " ") !== -1) $selectorStr = explode(" ", $selectorStr)[count(explode(" ", $selectorStr)) - 1];
                       $newCommandsToExecute[] = substr_replace($cmd, " " . $selectorStr . " ", strpos($cmd, $match), strlen($match));

                     }
                   if(count($newCommandsToExecute) == 0)
                    {
                      $sender->sendMessage("§cYour selector $match (" . self::$selectors[$matches[1][$index]]->getName() . ") did not mactch any player/entity.");
                      return true;
                    }

                }
             $commandsToExecute = $newCommandsToExecute;
           }
          }
        if(!isset($matches[0][0])) return false;
        foreach($commandsToExecute as $cmd)
          {
            $this->getServer()->dispatchCommand($sender, $cmd);
          }
        return true;
      }

    public function checkArgParams(array $match, int $index): array
      {
        $params = [];
        if(strlen($match[2][$index]) !== 0)
         {
           if(strpos($match[3][$index], ",") !== -1)
            {
              foreach(explode(",", $match[3][$index]) as $param)
                {
                  $parts = explode("=", $param);
                  $params[$parts[0]] = $parts[1];
                }
            } else
              {
                $parts = explode("=", $match[3][$index]);
                $params[$parts[0]] = $parts[1];
              }
         }
        return $params;
      }

    public function buildRegExr(): string
      {
        $regexr = "/ @(";
        $regexr .= preg_replace("/(\\$|\\(|\\)|\\^|\\[|\\])/", "\\\\$1", implode("|", array_keys(self::$selectors)));
        $regexr .= ")(\\[(((\w+)?=(.)+(,)?){1,})\\])?";
        $regexr .= "( |$)/";
        return $regexr;
      }

    public static function registerSelector(Selector $sel): void
      {
        self::$selectors[$sel->getSelectorChar()] = $sel;
      }

    public static function unregisterSelector(string $selChar): void
      {
        unset(self::$selectors[$selChar]);
      }

    public static function getSelector(string $selChar): Selector
      {
        return self::$selectors[$selChar];
      }

    public function onPlayerCreation(PlayerCreationEvent $event)
      {
    	  $event->setPlayerClass(Dump::class);
    	}

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool
      {
        switch ($command->getName())
          {
            case "rm@e":
            if($sender instanceof Player)
             {
               foreach($this->getServer()->getLevels() as $level)
                 {
                   foreach($level->getEntities() as $entity)
                     {
                       if(!$entity instanceof Player)
                        {
                          $entity->flagForDespawn();
                          $sender->sendMessage("StockPE: killed all entities!");
                        }
                     }
                 }
             }
            break;
          }
        return true;
      }
}
?>