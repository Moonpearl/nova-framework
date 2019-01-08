<?php

namespace Nova;

class Model
{
  const TABLE_NAME = null;

  protected $id;

  // Fetch all from dqtqbqse
  static public function fetchAll($columns = null) {
    $className = get_called_class();
    $app = Application::getInstance();

    return $app->queryAsObject(
      $className::buildQuery([
        'select' => $columns
      ]),
      $className
    );
  }

  static public function fetchById($id, $columns = null) {
    $className = get_called_class();
    $app = Application::getInstance();

    return $app->queryAsObject(
      $className::buildQuery([
        'select' => $columns,
        'where' => ['id', $id],
        'limit' => 1
      ]),
      $className
    )[0];
  }

  // Build SQL query from options
  static private function buildQuery($options = []) {
    $result = [];
    $className = get_called_class();

    // Process SELECT statement
    if (isset($options['select'])) {
      if (is_array($options['select'])) {
        array_push($result, 'SELECT');

        $select = [];
        foreach ($options['select'] as $name) {
          // if (in_array($name, $className::MULTILINGUAL_COLUMNS)) {
          //   array_push($select, '`' . $name . '_' . Language::$current->code . '` AS `' . $name . '`');
          // } else {
            array_push($select, '`' . $name . '`');
          // }
        }

        array_push($result, join(',' . PHP_EOL, $select));

      } else {
        array_push($result, 'SELECT ' . $options['select']);
      }
      $select = '';
    } else {
      array_push($result, 'SELECT *');
    }

    // Process FROM statement
    if (isset($options['from'])) {
      $table = $options['from'];
    } else {
      $table = $className::TABLE_NAME;
    }
    array_push($result, 'FROM `' . $table . '`');

    // Process WHERE statement
    if (isset($options['where'])) {
      if (!is_array($options['where']) || sizeof($options['where']) != 2) {
        throw new Exception('Error in Model::buildQuery - WHERE statement should be an array with exactly 2 parameters (' . print_r($options['where']) . ' given instead)');
      }
      array_push($result, 'WHERE `' . $options['where'][0] . '` = ' . $options['where'][1]);
    }

    // Process ORDER statement
    if (isset($options['order_by'])) {
      $order_by = $options['order_by'];
    } else {
      $order_by = 'id';
    }
    if (isset($options['order_asc'])) {
      if ($options['order_asc']) {
        $order = 'ASC';
      } else {
        $order = 'DESC';
      }
    } else {
      $order = 'ASC';
    }
    array_push($result, 'ORDER BY `' . $order_by . '` ' . $order);

    // Process LIMIT statement
    if (isset($options['limit'])) {
      array_push($result, 'LIMIT ' . $options['limit']);
    }

    $result = join(PHP_EOL, $result);

    return $result;
  }

  public getId() {
    return $this->id;
  }
}
