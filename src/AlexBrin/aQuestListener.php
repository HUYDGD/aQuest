<?php

	namespace AlexBrin;

	use AlexBrin\Main;
	use pocketmine\command\ConsoleCommandSender;
	use pocketmine\item\Item;
	use pocketmine\item\enchantment\Enchantment;
	use pocketmine\item\Armor;
	use pocketmine\inventory\PlayerInventory;

	use pocketmine\event\Listener;
	use pocketmine\event\player\PlayerJoinEvent;
	use pocketmine\event\player\PlayerDeathEvent;
	use pocketmine\event\player\PlayerDropItemEvent;
	use pocketmine\event\player\PlayerItemConsumeEvent;
	use pocketmine\event\entity\EntityDamageEvent;
	use pocketmine\event\entity\EntityDamageByEntityEvent;
	use pocketmine\event\block\BlockPlaceEvent;
	use pocketmine\event\block\BlockBreakEvent;

	use pocketmine\Player;
	use pocketmine\Server;

	class aQuestListener implements Listener{
		
		private $plugin;

		public function __construct(Main $plugin) {
			$this->plugin = $plugin;
		}

		public function onPlayerJoin(PlayerJoinEvent $event) {
			$name = strtolower($event->getPlayer()->getName());
			if(!isset($this->plugin->users[$name])) {
				$this->plugin->users[$name] = [
					'during' => false,
					'complete' => 0
				];
				$this->plugin->save();
				$this->plugin->getLogger()->info("§eNgười chơi $name đã được thêm vào data của QuestPMMP!");
			}
		}

		public function onBlockPlace(BlockPlaceEvent $event) {
			$player = $event->getPlayer();
			$name = strtolower($player->getName());
			if($this->plugin->users[$name]['during'] !== false)
				$this->checkQuest($player, $this->plugin->users[$name]['during'], 'blockplace', $event->getBlock()->getId());
		}

		public function onBlockBreak(BlockBreakEvent $event) {
			$player = $event->getPlayer();
			$name = strtolower($player->getName());
			if($this->plugin->users[$name]['during'] !== false)
				$this->checkQuest($player, $this->plugin->users[$name]['during'], 'blockbreak', $event->getBlock()->getId());
		}

		public function onPlayerDeath(PlayerDeathEvent $event) {
			$player = $event->getEntity();
			$name = strtolower($player->getName());
			if($this->plugin->users[$name]['during'] !== false)
				$this->checkQuest($player, $this->plugin->users[$name]['during'], 'playerkill');
			if($player->getLastDamageCause() instanceof EntityDamageByEntityEvent) {
				$killer = $player->getLastDamageCause()->getDamager();
				if($killer instanceof $killer) {
					$name = strtolower($killer->getName());
					if($this->plugin->users[$name]['during'] !== false)
						$this->checkQuest($killer, $this->plugin->users[$name]['during'], 'playerdeath');
				}
			}
		}

		public function onPlayerDropItem(PlayerDropItemEvent $event) {
			$player = $event->getPlayer();
			$name = strtolower($player->getName());
			if($this->plugin->users[$name]['during'] !== false)
				$this->checkQuest($player, $this->plugin->users[$name]['during'], 'itemdrop');
		}

		public function onPlayerItemConsume(PlayerItemConsumeEvent $event) {
			$player = $event->getPlayer();
			$name = strtolower($player->getName());
			if($this->plugin->users[$name]['during'] !== false)
				$this->checkQuest($player, $this->plugin->users[$name]['during'], 'itemconsume');
		}

		public function checkQuest($player, $quest, $event, $bid = false) {
			$name = strtolower($player->getName());
			if($quest !== false) {
				if(strtolower($this->plugin->config['quests'][$quest['id']]['task']) == $event) {
					if($bid !== false) {
						if($this->plugin->config['quests'][$quest['id']]['id'] == $bid)
							$this->plugin->users[$name]['during']['count']++;
					}
					else {
						$this->plugin->users[$name]['during']['count']++;
					}
					if($this->plugin->config['quests'][$quest['id']]['num'] == $this->plugin->users[$name]['during']['count']) {
						$this->plugin->users[$name]['during'] = false;
						$this->plugin->users[$name]['complete']++;
						if(isset($this->plugin->config['quests'][$quest['id']]['award']))
							$this->giveAward($player, $this->plugin->config['quests'][$quest['id']]['award'], $quest['id']);
						else {
							$player->sendMessage(str_replace(['{quest}', '{count}'], [$this->plugin->config['quests'][$quest['id']]['name'], 1], $text));
						}
						$this->plugin->users[$name]['during'] = false;
					}
					$this->plugin->save();
				}
			}
		}

		public function giveAward($player, $award, $id) {
			if($award['id'] == 'money') {
				if(!isset($award['count'])) {
					$this->getLogger()->warning("§lBạn chưa quy định số tiền! (count)");
					return true;
				}
				$this->plugin->eco->giveMoney($player->getName(), $award['count']);
				$player->sendMessage(str_replace(['{quest}', '{award}', '{count}'], [$this->plugin->config['quests'][$id]['name'], isset($award['name']) ? $award['name'] : 'Монетки', $award['count']], $this->plugin->config['questEnd']));
				$player->addTitle("§aBạn đã hoàn thành quest!","§e> Đã mở nhiệm vụ mới! <");
				return true;
			}
			if($award['id'] == 'other') {
				Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), str_replace('{player}', $player->getName(), $award['command']));
				$player->sendMessage(str_replace(['{quest}', '{award}'], [$this->plugin->config['quests'][$id], $award['name'] ?? 'Привелегия'], $text));
				return true;
			}
			if(!isset($award['damage']))
				$award['damage'] = 0;
			if(!isset($award['count']))
				$award['count'] = 1;
			$item = Item::get($award['id'], $award['damage'], $award['count']);
			$itemName = $award['name'] ?? $item->getName();
			if(isset($award['name']))
				$item->setCustomName($award['name']);
			if($award['enchant'] !== false) {
				$award['enchant'] = explode(' ', $award['enchant']);
				if(!isset($award['enchant'][1]))
					$award['enchant'][1] = 1;
				$ench = Enchantment::getEnchantment($award['enchant'][0])->setLevel($award['enchant'][1]);
				$item->addEnchantment($ench);
			}
			$player->getInventory()->addItem($item);
			$player->sendMessage(['{quest}', '{award}', '{count}'], [$this->plugin->config['quests'][$id], $itemName, $award['count']], $this->plugin->config['questEnd']);
		}

	}

?>