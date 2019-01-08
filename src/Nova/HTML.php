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
}
