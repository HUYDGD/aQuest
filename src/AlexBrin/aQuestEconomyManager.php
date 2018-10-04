<?php

	namespace AlexBrin;

	use AlexBrin\Main;
	use pocketmine\event\Listener;
	use AlexBrin\aQuestListener;

	class aQuestEconomyManager implements Listener{

	    private $plugin;
		
		public function __construct(Main $plugin) {
			$this->plugin = $plugin;
			$pManager = $plugin->getServer()->getPluginManager();
			$this->eco = $pManager->getPlugin("EconomyAPI") ?? $pManager->getPlugin("PocketMoney") ?? $pManager->getPlugin("MassiveEconomy") ?? null;
			unset($pManager);
			if($this->eco === null)
				$plugin->getLogger()->warning('Không tìm thấy PocketMoney, MassiveEconomy và EconomyAPI!');
			else
				$plugin->getLogger()->info('§aĐã tìm thấy: §d'.$this->eco->getName());
		}

		public function giveMoney($player, $amount) {
			if($this->eco === null)
				return "§cThất bại khi give tiền cho người chơi!";
			$this->eco->setMoney($player, $this->getMoney($player) + $amount);
		}

		public function getMoney($player) {
			switch($this->eco->getName()) {

				case 'EconomyAPI':
						$balance = $this->eco->myMoney($player);
					break;

				case 'PocketMoney':
						$balance = $this->eco->getMoney($player);
					break;

				case 'MassiveEconomy':
						$balance = $this->eco->getMoney($player);
					break;

				default:
					$balance = 0;

			}
			return $balance;
		}

		public function getEconomyPlugin($name = false) {
			if($name)
				return $this->eco->getName();
			return $this->eco;
		}

	}

?>