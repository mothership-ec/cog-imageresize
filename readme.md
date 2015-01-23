# Image Resize

## Resize Class and Controller

The Resizer can be accessed using the service `image.resize`:

	$this->get('image.resize')->resize($url);

The Resize-class has two important methods:

* **generateUrl($url, $width, $height)** - Generates a url consisting of the file-name, width, height and a hash which will be the later location of the file. The $url is the publically accessible path to the image we want to resize. Urls generated by this method could look something like this: `resize_directory/test_AUTOx400-de46b9.jpg`
* **resize($url)** - Does the actual resizing, returns the resized file and saves it in the location defined by $url. The $url passed in is a the url generated by generateUrl().

This means, when we have an image called `test.jpg`, which is saved in `cog:://public/files`, this is how we could resize it:

	$resizer = $this->get('image.resize');
	$url = $resizer->generateUrl('/files/test.jpg', null, 400); // you can leave out either height or width!
	$resizedImg = $resizer->resize($url);

We go the way of generating the url because that allows us to controll the behaviour with a controller:
When we want to render a resized image in a view, we can pass in the generated `$url` as `src`-attribute. What will happen is, the first time the image is requested, the router will call the image resize controller, which will resize the image, save it to the new destination and return the image.
After that the image already exists and can requested as any other image.

This is handy especially when used together with the twig extension.

## Twig Extension

There are two methods which allow us to access the `generateUrl()`-method the `ImageResize\Resize`-class provides:

* **getResizedUrl(ResizableInterface $file, $width, $height)** - Returns the result of `Resizer::generateUrl` using the `getUrl()`-method the ResizableInterface defines:

		<img src="{{ getResizedUrl(file, 500, 200) }}" width="500" height="200" alt="file.altText">

* **getResizedImage(ResizableInterface $file, $width, $height, $attributes = array())** - Returns the rendered `<img>`-tag including `src`, `width`, `height`, `alt`(using ResizableInterface's `getAltText()`-method) and additional attributes using the `$attributes`-array:

		{# file->getUrl() = '/test.jpg' #}
		{# file->getAltText() = 'test' #}

		{{ getResizedImage(file, 500, 200, {'class': 'file'}) }}

	Which will render

		<img src="/test_500x200-88953b.jpg" width="500" height="200" alt="test" class="file">

	If the $attributes-array provides an `alt`-attribute, instead of using file's `getAltText()` to determine the alt-text, `$attributes['alt']` will be shown:

		{{ getResizedImage(file, 500, 200, {'alt': 'abcabc'}) }}

	This will return this:

		<img src="/test_500x200-88953b.jpg" width="500" height="200" alt="abcabc">

## Default Image

If the `$url` passed to the `resize`-method is not the path to an existing file in the system, a default-image is resized instead.

This default image can be configured in `config/app.yml`:

	image-resize:
		default-image-path: cogules/MyApp:SetUp/images/default.png

The `generateUrl` method looks up the file and if it doesn't exist it returns the url to the default image. That way only one image for each size is generated and the correct resized image can be generated as soon as a file is uploaded to the server.

## License

Mothership E-Commerce
Copyright (C) 2015 Jamie Freeman

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.
