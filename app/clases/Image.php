<?php 
// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;

// create an image manager instance with favored driver
$manager = new ImageManager();

// to finally create image instances
$img = $manager->make('./images/3.jpg')->resize(300, 200)->save('./images/bar.jpg');