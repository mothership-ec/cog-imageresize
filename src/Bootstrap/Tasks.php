<?php

namespace Message\ImageResize\Bootstrap;

use Message\ImageResize\Task;
use Message\Cog\Bootstrap\TasksInterface;

class Tasks implements TasksInterface
{
	public function registerTasks($tasks)
	{
		$tasks->add(
			new Task\ClearCache('imageresize:clear:cache'),
			'Deletes all resized image files'
		);
	}
}