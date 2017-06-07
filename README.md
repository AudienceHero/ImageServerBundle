# AudienceHero/ImageServerBundle

A very simple and composable image server for Symfony applications.

## Installation

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require audience-hero/image-server-bundle "~1"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new AudienceHero\Bundle\ImageServerBundle\AudienceHeroImageServerBundle(),
        );

        // ...
    }

    // ...
}
```

### Step 3: Configuration

Add this to your `app/config/config.yml` file:

```yml
# ...
audience_hero_image_server:
    # (Required) Put the hostname under which the image server will answer
    host: img.example.com  
    # (Optional) The doctrine cache provider key (See DoctrineCacheBundle documentation)
    # cache: image_server  
# ...
```

### Step 4: Routing

Add this to your `app/config/routing.yml` file:

```yml
_audience_hero_image_server:
    resource: "@AudienceHeroImageServerBundle/Resources/config/routing.yml"
    prefix:   /
# ...
```

## Usage

The image server responds to an endpoint and serves images with the possibility to transform them (ie: scale/crop).

### Generating URLs

#### In Twig templates

```twig
{{ img('http://www.example.com/image.jpg', {'size': 400x400, 'crop': 'square-center'}) }}
```

#### Anywhere else

```php
$this->generateUrl('audience_hero_img_show', [
    'url' => 'http://www.example.com/image.jpg',
    'size' => '400x400',
    'crop' => 'square-center'
]);
```

### Transformers

#### CropTransformer

Possibles values for the `crop` parameter:

* `none` Do not crop image
* `square` Crop the image to a square
* `square-center` Crop the image to a square, but centered

#### ResizeTransformer

Possibles values for the `size` parameter:

* `640x480` Resize the image to 640x480
* `640x0` Resize the image to a width of 640 pixels. Height is automatically adjusted to preserve the original aspect ratio.
* `0x480` Resize the image to a height of 480 pixels. Width is automatically adjusted to preserve the original aspect ratio.

## License

See the [LICENSE](LICENSE) file.