<?php
/**
 * @file
 * PHPUnit tests for MediaWrapper.
 */

class MediaWrapperTest extends PHPUnit_Framework_TestCase {
  function __construct() {
    include_once __DIR__ . '/../MediaWrapper/MediaWrapper.php';
  }

  function assertImageUrl($url) {
    $size = getimagesize($url);
    $this->assertTrue(is_array($size));
    $this->assertArrayHasKey('mime', $size);
    $this->assertStringStartsWith('image/', $size['mime']);
  }

  public function testYoutube() {
    $m = MediaWrapper::getInstance()->getWrapper('http://www.youtube.com/watch?v=9bZkp7q19f0');
    $this->assertEquals('http://img.youtube.com/vi/9bZkp7q19f0/0.jpg', $m->thumbnail());
    $this->assertImageUrl($m->thumbnail());
    $this->assertEquals('<iframe class="youtube-player" type="text/html" width="560" height="315" src="http://www.youtube.com/embed/9bZkp7q19f0?wmode=transparent" frameborder="0"></iframe>', $m->player());

    $m->player_options(array('width' => '200', 'height' => '100'));
    $this->assertEquals('<iframe class="youtube-player" type="text/html" width="200" height="100" src="http://www.youtube.com/embed/9bZkp7q19f0?wmode=transparent" frameborder="0"></iframe>', $m->player());

    // Now pass directly the options
    $this->assertEquals('<iframe class="youtube-player" type="text/html" width="400" height="200" src="http://www.youtube.com/embed/9bZkp7q19f0?wmode=transparent&autoplay=1" frameborder="0"></iframe>', $m->player(array('width' => '400', 'height' => '200', 'autoplay' => 1)));

    // Test if options are overriden by the last passed option.
    $this->assertEquals('<iframe class="youtube-player" type="text/html" width="200" height="100" src="http://www.youtube.com/embed/9bZkp7q19f0?wmode=transparent" frameborder="0"></iframe>', $m->player());

    // Test with extra data on the url.
    $m = MediaWrapper::getInstance()->getWrapper('http://www.youtube.com/watch?feature=player_embedded&v=IdioCTTwdw8');
    $this->assertEquals('http://img.youtube.com/vi/IdioCTTwdw8/0.jpg', $m->thumbnail());
  }

  public function testYoutubeSpecial() {
    $m = MediaWrapper::getInstance()->getWrapper('http://youtu.be/f620pz-Dyk0');
    $this->assertEquals('http://img.youtube.com/vi/f620pz-Dyk0/0.jpg', $m->thumbnail());
    $this->assertImageUrl($m->thumbnail());
  }

  public function testVimeo() {
    $m = MediaWrapper::getInstance()->getWrapper('http://vimeo.com/56488043');
    $this->assertImageUrl($m->thumbnail());
    $this->assertEquals('http://b.vimeocdn.com/ts/391/133/391133096_640.jpg', $m->thumbnail());
    $this->assertEquals('<iframe class="vimeo-player" type="text/html" width="560" height="315" src="http://player.vimeo.com/video/56488043?byline=0&portrait=0" frameborder="0"></iframe>', $m->player());
  }

  public function testCacheSystem() {
    require __DIR__ . '/RandomWrapper.php';
    MediaWrapper::register(array('RandomWrapper'));
    $m = MediaWrapper::getInstance()->getWrapper('http://test.com/1982');
    $value1 = $m->player();
    $value2 = $m->player();
    $this->assertEquals($value1, $value2, 'Test if data is fetched from cache.');
  }
}

