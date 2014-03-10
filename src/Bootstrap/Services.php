<?php

namespace Message\ImageResize\Bootstrap;

use Message\ImageResize\Templating;

use Message\ImageResize\Resize\TwigExtension;

use Message\Cog\Bootstrap\ServicesInterface;

class Services implements ServicesInterface
{
	const ROUTE_NAME = 'imageresize.cache';

	public function registerServices($container)
	{
		$container['imagine'] = function($c) {
			if (extension_loaded('imagick')) {
				return new \Imagine\Imagick\Imagine();
			}

			if (extension_loaded('gmagick')) {
				return new \Imagine\Gmagick\Imagine();
			}

			if (extension_loaded('gd')) {
				return new \Imagine\Gd\Imagine();
			}

			throw new \RuntimeException('No image processing libraries available for Imagine.');
		};

		$container['image.resize'] = function($c) {
			$resize = new \Message\ImageResize\Resize(
				$c['imagine'],
				$c['routing.generator'],
				Services::ROUTE_NAME,
				$c['cfg']->imageResize->salt,
				$c['cfg']->imageResize->defaultImagePath
			);

			$resize->setDefaultQuality(90);

			return $resize;
		};

		$container->extend('templating.twig.environment', function($twig, $c) {
			$twig->addExtension(
				new Templating\TwigExtension($c['image.resize'])
			);

			return $twig;
		});
	}
}