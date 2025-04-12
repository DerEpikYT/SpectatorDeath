<?php
namespace DeathSpectator;

use DeathSpectator\RespawnCountdownTask;
use pocketmine\player\GameMode;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPlayerDeath(PlayerDeathEvent $event): void {
        $player = $event->getPlayer();
        $playerName = $player->getName();

        $messageTemplate = $this->getConfig()->get("deathmessage");
        $customMessage = str_replace("$player", $playerName, $messageTemplate);

        $event->setDeathMessage($customMessage);
        $respawnTime = 5;
        $player->setGamemode(GameMode::SPECTATOR());

        // Countdown starten
        $gamemode = $this->getConfig()->get("respawngamemode");
        $this->getScheduler()->scheduleRepeatingTask(new RespawnCountdownTask($player, $respawnTime, $gamemode), 20);
    }
}
