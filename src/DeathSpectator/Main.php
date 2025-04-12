<?php
namespace DeathSpectator;

use DeathSpectator\RespawnCountdownTask;
use pocketmine\player\GameMode;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\player\Player;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    
    public function onDamage(EntityDamageEvent $event){
        $entity = $event->getEntity();
        if($entity instanceof Player){
            $finalDamage = $event->getFinalDamage();
            if ($entity->getHealth() - $finalDamage <= 0) {
                    $entity->respawn();
                    $entity->setHealth($entity->getMaxHealth());
                    $entity->setGamemode(GameMode::SPECTATOR());
                    $respawnTime = 5;
                    $gamemode = $this->getConfig()->get("respawngamemode");
                    $this->getScheduler()->scheduleRepeatingTask(new RespawnCountdownTask($entity, $respawnTime, $gamemode), 20);
                    $event->cancel();
            }
        }
    }
}
