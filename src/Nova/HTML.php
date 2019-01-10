<?php

namespace Nova;

class HTML
{
  static public function generateTag($tagName, $contents, $data = []) {
    $result = [];
    array_push($result, '<' . $tagName);
    foreach ($data as $dataName => $value) {
      array_push($result, self::generateData($dataName, $value));
    }
    array_push($result, '>');
    return join(' ', $result) . $contents . '</' . $tagName . '>';
  }

  static public function generateAutoClosingTag($tagName, $data = []) {
    $result = [];
    array_push($result, '<' . $tagName);
    foreach ($data as $dataName => $value) {
      array_push($result, self::generateData($dataName, $value));
    }
    array_push($result, '/>');
    return join(' ', $result);
  }

  static private function generateData($dataName, $value) {
    return $dataName . '="' . $value . '"';
  }

  static public function generateCSSLink($filename, $data = []) {
    return self::generateAutoClosingTag('link', array_merge(['rel' => 'stylesheet', 'href' => $filename], $data));
  }

  static public function generateStylesheet($name, $data = []) {
    if (!Path::is_full_url($name)) $name = Path::css_url($name);
    return self::generateCSSLink($name, $data);
  }

  static public function generateTitle($content) {
    return self::generateTag('title', $content);
  }
}
