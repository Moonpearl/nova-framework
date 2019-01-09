<?php

class Article extends Nova\Model
{
  const TABLE_NAME = 'articles';

  private $title;
  private $content;
  private $author;
  private $date;

  public function getTitle() {
    return $this->title;
  }

  public function getContent() {
    return $this->content;
  }

  public function getAuthor() {
    return $this->author;
  }

  public function getDate() {
    return $this->date;
  }
}
