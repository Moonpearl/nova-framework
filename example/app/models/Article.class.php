<?php

class Article extends Nova\Model
{
  const TABLE_NAME = 'articles';
  const REQUIRED = ['title', 'content'];
  const TYPE = [
    'title' => 'string',
    'content' => 'string',
    'author' => 'int',
    'date' => '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/'
  ];

  protected $title;
  protected $content;
  protected $author;
  protected $date;

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
