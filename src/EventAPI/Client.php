<?php

namespace PhpNewRelic\EventAPI;

use PhpNewRelic\CustomEventCollection;

interface Client {
	public function send(CustomEventCollection $customEvents): void;
}
