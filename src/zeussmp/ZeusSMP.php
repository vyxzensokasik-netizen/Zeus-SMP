<?php

declare(strict_types=1);

namespace zeussmp;

use pocketmine\plugin\PluginBase;
use zeussmp\clan\ClanManager;
use zeussmp\economy\EconomyManager;
use zeussmp\menu\MenuManager;
use zeussmp\rank\RankManager;
use zeussmp\shop\ShopManager;
use zeussmp\warp\WarpManager;

class ZeusSMP extends PluginBase{

	private ClanManager $clanManager;
	private RankManager $rankManager;
	private WarpManager $warpManager;
	private ShopManager $shopManager;
	private EconomyManager $economyManager;
	private MenuManager $menuManager;

	protected function onEnable() : void{
		@mkdir($this->getDataFolder());

		$this->clanManager = new ClanManager($this);
		$this->rankManager = new RankManager($this);
		$this->warpManager = new WarpManager($this);
		$this->shopManager = new ShopManager($this);
		$this->economyManager = new EconomyManager($this);
		$this->menuManager = new MenuManager($this);

		$commandHandler = new CommandHandler($this);
		foreach (["menu", "clan", "warp", "setwarp", "delwarp", "rank", "balance", "pay", "setcoins"] as $cmd) {
			$command = $this->getServer()->getCommandMap()->getCommand($cmd);
			if($command !== null){
				$command->setExecutor($commandHandler);
			}
		}

		$this->getLogger()->info("Zeus SMP plugin berhasil diaktifkan!");
	}

	public function getClanManager() : ClanManager{
		return $this->clanManager;
	}

	public function getRankManager() : RankManager{
		return $this->rankManager;
	}

	public function getWarpManager() : WarpManager{
		return $this->warpManager;
	}

	public function getShopManager() : ShopManager{
		return $this->shopManager;
	}

	public function getEconomyManager() : EconomyManager{
		return $this->economyManager;
	}

	public function getMenuManager() : MenuManager{
		return $this->menuManager;
	}
}
