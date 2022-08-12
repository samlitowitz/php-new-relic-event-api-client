<?php

namespace PhpNewRelic\EventAPI;

use PhpNewRelic\CustomEventCollection;
use PhpNewRelic\EventAPI\Http\Response;

interface Client {
	public function send(CustomEventCollection $customEvents): Response;
}
