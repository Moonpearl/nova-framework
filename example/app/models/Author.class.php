<?php

class Author extends Nova\Model
{
  const TABLE_NAME = 'authors';
  const REQUIRED = ['name'];
  const TYPE = [
    'name' => 'string',
    'image' => 'string'
  ];

  protected $name;
  protected $image;

  public function getName() {
    return $this->name;
  }

  public function getImage() {
    return $this->image;
  }
}
