<?php

	namespace AlexBrin;

	use pocketmine\plugin\PluginBase;
	use pocketmine\utils\Config;
	use pocketmine\command\Command;
	use pocketmine\command\CommandSender;

	use pocketmine\event\Listener;

	use pocketmine\Player;
	use pocketmine\Server;

	class Main extends PluginBase {
		public $config, $users;

		public function onEnable() {
			$folder = $this->getDataFolder();
			if(!is_dir($folder))
				@mkdir($folder);
			$this->saveDefaultConfig();
			$this->config = (new Config($folder.'config.yml', Config::YAML))->getAll();
			$this->users = (new Config($folder.'users.yml', Config::YAML))->getAll();
			$this->getServer()->getPluginManager()->registerEvents(new aQuestListener($this), $this);
			$this->eco = new aQuestEconomyManager($this);
		}

		public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
			if($sender instanceof Player) {
				if(strtolower($command->getName()) == 'quest') {
					$name = strtolower($sender->getName());
					$num = $this->users[$name]['complete'];
					$quest = $this->config['quests'][$num];
					if($this->users[$name]['during'] !== false) {
						$sender->sendMessage(str_replace(['\n', '{quest}', '{type}', '{target}', '{count}'], ["\n", $quest['name'], $this->getTypeQuest($quest['task']), $quest['target'], $quest['num']], $this->config['questHelp']));
						return false;
					}
					if($num >= count($this->config['quests'])) {
						$sender->sendMessage($config['questEnded']);
						return false;
					}
					$this->users[$name]['during'] = [
						'id' => $num,
						'count' => 0
					];
					$this->save();
					$sender->sendMessage(str_replace('{quest}', $quest['name'], $this->config['getQuest']));
					$sender->sendMessage(str_replace(['\n', '{type}', '{target}', '{count}'], ["\n", $this->getTypeQuest($quest['task']), $quest['target'], $quest['num']], $this->config['getQuestInfo']));
				}
			} else $sender->sendMessage('§cKhông thể sử dụng lệnh!');
			return false;
		}

		/**
		 * @param string  $type
		 * @return string $type
		 */
		public function getTypeQuest($type) {
			switch(strtolower($type)) {

				case 'blockbreak':
						$type = 'Đập';
					break;

				case 'blockplace':
						$type = 'Đặt';
					break;

				case 'playerkill':
						$type = 'Giết';
					break;

				case 'playerdeath':
						$type = 'Chết';
					break;

				case 'itemconsume':
						$type = 'Ăn/uống';
					break;

				case 'itemdrop':
						$type = 'Thả';
					break;

				default: 
						$type = '???';

			}
			return $type;
		}

		public function save() {
			$cfg = new Config($this->getDataFolder().'users.yml', Config::YAML);
			$cfg->setAll($this->users);
			$cfg->save();
		}

	}

?>