<?php

namespace Message\ImageResize\Bootstrap;

use Message\Cog\Bootstrap\EventsInterface;

/**
 * Bootstrap to register event listeners for the Image Resize cogule.
 *
 * @author James Moss <james@message.co.uk>
 */
class Events implements EventsInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function registerEvents($eventDispatcher)
	{
		$eventDispatcher->addSubscriber(new \Message\ImageResize\EventListener);
	}
}