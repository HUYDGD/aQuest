# aQuest
aQuest is an open source plugin for infinite task creation for <a href="https://github.com/pmmp/PocketMine-MP">PocketMine-MP</a>

## FAQ
**Q**: Why did I complete the quest but didn't receive the reward ? <br>
**A**: You must use the command /quest before doing the quest

## Installation & Setup
1. Choose a version and install the plugin from <a href="https://github.com/SoiOniichan/aQuest/releases">here</a>
2. Configure your tasks in config.yml
+ Mission types are available: blockBreak, blockPlace, playerKill, playerDeath, itemConsume, itemDrop
  + You can write blockBreak and blockPlace at the same time
    + Put the task type in the `task`
    + The name of the target will be placed in the `target` section
    + Customize id and quantity in the `id` and `num` sections
    + Reward configuration after task completion is in the `award` section (Need economic plugin, you can also add enchant)
3. (Required) Install additional plugins supporting aQuest:
+ EconomyAPI
  + PocketMoney
    + MassiveEconomy
4. You're done! Start your server and enjoy :3

## Commands
| Command  | Description | Permissions | Aliases |
| ------------- | ------------- | ------------- | ------------- |
| /quest  | Show current task  | Not available  | Not available  |

## Permissions
| Permissions  | Description | Default |
| ------------- | ------------- |------------- |
| Not available  | Not available  | true  |

## Issue Reporting
+ If you experience a crash in aQuest, click <a href="https://github.com/SoiOniichan/aQuest/issues/new?assignees=&labels=&template=issue--crash.md&title=">here</a>
+ If you would like to suggest a feature to be added to aQuest, click <a href="https://github.com/SoiOniichan/aQuest/issues/new?assignees=&labels=&template=feature_request.md&title=">here</a>

## License
`MIT License`

`Copyright (c) 2020 SoiOniichan`

`Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:`

`The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.`

`THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.`