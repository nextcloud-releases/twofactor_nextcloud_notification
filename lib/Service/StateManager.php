<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2019, Roeland Jago Douma <roeland@famdouma.nl>
 *
 * @author Roeland Jago Douma <roeland@famdouma.nl>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\TwoFactorNextcloudNotification\Service;

use OCA\TwoFactorNextcloudNotification\AppInfo\Application;
use OCA\TwoFactorNextcloudNotification\Event\StateChanged;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\IConfig;
use OCP\IUser;

class StateManager {
	public function __construct(
		private IEventDispatcher $dispatcher,
		private IConfig $config,
	) {
	}

	public function setState(IUser $user, bool $state): void {
		$this->config->setUserValue($user->getUID(), Application::APP_ID, 'enabled', $state ? '1' : '0');
		$this->dispatcher->dispatchTyped(new StateChanged($user, $state));
	}

	public function getState(IUser $user): bool {
		return $this->config->getUserValue($user->getUID(), Application::APP_ID, 'enabled', '0') === '1';
	}
}
