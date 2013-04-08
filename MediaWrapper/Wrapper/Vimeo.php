<?php
/**
 * @file
 * Vimeo wrapper.
 */

namespace MediaWrapper\Wrapper;

class Vimeo extends Wrapper {

  // Pattern to detect if an URL be longs to us
  public static $detect = '#vimeo\.com#';

  function __construct($text) {
    self::$patterns = array(
      '#http://vimeo.com/([0-9]+)#',
    );

    $this->options += array(
      'title' => 0,
      'byline' => 0,
      'portrait' => 0,
    );

    parent::__construct($text);
  }

  function thumbnail() {
    $data = unserialize(file_get_contents('http://vimeo.com/api/v2/video/' . $this->info['id'] . '.php'));
    return $data[0]['thumbnail_large'];
  }

  function player(array $options = array()) {
    $options = $this->player_options($options, FALSE);
    $query = array_intersect_key($options, array('title' => 0, 'byline' => 0, 'portrait' => 0));

    switch ($this->options['mode']) {
      default:
        return '<iframe class="vimeo-player" type="text/html" width="' . $options['width'] . '" height="' . $options['height'] . '" src="http://player.vimeo.com/video/' . $this->info['id'] . '?' . http_build_query($query, '', '&amp;') . '" frameborder="0"></iframe>';
    }
  }
}

