<?php

namespace DeathSpectator;

use pocketmine\player\GameMode;
use pocketmine\scheduler\Task;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\world\sound\NoteInstrument;
use pocketmine\world\sound\NoteSound;
use pocketmine\world\sound\XpCollectSound;

class RespawnCountdownTask extends Task {
    private Player $player;
    private int $secondsLeft;

    private string $gamemode;

    public function __construct(Player $player, int $seconds, string $gamemode) {
        $this->player = $player;
        $this->secondsLeft = $seconds;
        $this->gamemode = strtolower($gamemode);
    }

    public function onRun(): void {
        if (!$this->player->isOnline()) {
            $this->getHandler()?->cancel();
            return;
        }

        if ($this->secondsLeft > 0) {
            $this->player->getWorld()->addSound($this->player->getPosition(), new XpCollectSound());
            $this->player->sendTitle("§eYou will respawn in §c{$this->secondsLeft}...");
            $this->secondsLeft--;
        } else {
            $this->player->sendMessage("Respawning...");
            switch ($this->gamemode) {
                case "CREATIVE":
                    $this->player->setGamemode(GameMode::CREATIVE());
                    break;
                case "ADVENTURE":
                    $this->player->setGamemode(GameMode::ADVENTURE());
                    break;
                case "SURVIVAL":
                    $this->player->setGamemode(GameMode::SURVIVAL());
                    break;
                default:
                    $this->player->setGamemode(GameMode::SURVIVAL());
                    break;
            }
            $this->player->teleport($this->player->getWorld()->getSafeSpawn());
            $this->getHandler()?->cancel();
        }
    }
}
