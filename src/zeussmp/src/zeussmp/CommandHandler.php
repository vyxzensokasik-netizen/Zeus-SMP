<?php

declare(strict_types=1);

namespace zeussmp;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class CommandHandler implements CommandExecutor{

	public function __construct(private ZeusSMP $plugin){}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		switch ($command->getName()) {
			case "menu":
				if(!$sender instanceof Player){
					$sender->sendMessage(TextFormat::RED . "Command ini hanya bisa dipakai in-game.");
					return true;
				}
				$this->plugin->getMenuManager()->openMainMenu($sender);
				return true;

			case "clan":
				return $this->handleClanCommand($sender, $args);

			case "warp":
				if(!$sender instanceof Player){
					return true;
				}
				if(empty($args)){
					$this->plugin->getMenuManager()->openWarpMenu($sender);
					return true;
				}
				$this->teleportToWarp($sender, $args[0]);
				return true;

			case "setwarp":
				if(!$sender instanceof Player){
					$sender->sendMessage(TextFormat::RED . "Command ini hanya bisa dipakai in-game.");
					return true;
				}
				if(empty($args)){
					$sender->sendMessage(TextFormat::RED . "Gunakan: /setwarp <nama>");
					return true;
				}
				$this->plugin->getWarpManager()->setWarp($args[0], $sender->getPosition());
				$sender->sendMessage(TextFormat::GREEN . "[Zeus SMP] Warp '{$args[0]}' berhasil dibuat.");
				return true;

			case "delwarp":
				if(empty($args)){
					$sender->sendMessage(TextFormat::RED . "Gunakan: /delwarp <nama>");
					return true;
				}
				if(!$this->plugin->getWarpManager()->warpExists($args[0])){
					$sender->sendMessage(TextFormat::RED . "Warp tidak ditemukan.");
					return true;
				}
				$this->plugin->getWarpManager()->deleteWarp($args[0]);
				$sender->sendMessage(TextFormat::GREEN . "[Zeus SMP] Warp '{$args[0]}' berhasil dihapus.");
				return true;

			case "rank":
				return $this->handleRankCommand($sender, $args);

			case "balance":
				if(!$sender instanceof Player){
					return true;
				}
				$balance = $this->plugin->getEconomyManager()->getBalance($sender->getName());
				$sender->sendMessage(TextFormat::AQUA . "[Zeus SMP] " . TextFormat::WHITE . "Saldo kamu: " . TextFormat::YELLOW . $balance . " koin");
				return true;

			case "pay":
				if(!$sender instanceof Player){
					return true;
				}
				if(count($args) < 2 || !is_numeric($args[1]) || (int) $args[1] <= 0){
					$sender->sendMessage(TextFormat::RED . "Gunakan: /pay <pemain> <jumlah>");
					return true;
				}
				$target = $this->plugin->getServer()->getPlayerByPrefix($args[0]);
				if($tar
