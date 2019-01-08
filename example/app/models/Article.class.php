<?php

class Article extends Nova\Model
{
  const TABLE_NAME = 'article';

  private $title;
  private $content;
  private $author;
  private $date;

  public getTitle() {
    return $this->title;
  }

  public getContent() {
    return $this->content;
  }

  public getAuthor() {
    return $this->author;
  }

  public getDate() {
    return $this->date;
  }
}
