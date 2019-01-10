<?php

namespace Nova;

class Model
{
  const TABLE_NAME = null;

  protected $id;

  static public function amount() {
    $class_name = get_called_class();

    $stmt = Database::query('
      SELECT
        COUNT(*)
      FROM `' . $class_name::TABLE_NAME . '`
    ');

    return $stmt->fetch()['COUNT(*)'];
  }

  // Fetch all from dqtqbqse
  static public function fetchAll($columns = null) {
    $className = get_called_class();

    return Database::queryAsObject(
      $className::buildQuery([
        'select' => $columns
      ]),
      $className
    );
  }

  // Fetch all from dqtqbqse
  static public function fetchMostRecent($columns = null) {
    $className = get_called_class();

    return Database::queryAsObject(
      $className::buildQuery([
        'select' => $columns,
        'order_by' => 'date',
        'order_asc' => false
      ]),
      $className
    );
  }

  static public function fetchById($id, $columns = null) {
    $className = get_called_class();

    return Database::queryAsObject(
      $className::buildQuery([
        'select' => $columns,
        'where' => ['id', $id],
        'limit' => 1
      ]),
      $className
    )[0];
  }

  static public function add($params) {
    $className = get_called_class();

    list($insert, $values) = $className::packArray($params);
    $query = [
      'INSERT INTO `' . $className::TABLE_NAME . '` ' . $insert,
      'VALUES ' . $values
    ];
    $query = join(PHP_EOL, $query);
    Database::exec($query);
    return $app->lastInsertId();
  }

  static public function update($id, $params) {
    $className = get_called_class();

    $set = [];
    foreach ($params as $columnName => $value) {
      array_push($set, '`' . $columnName . '` = \'' . $value . '\'');
    }
    $set = join(', ', $set);

    list($insert, $values) = $className::packArray($params);
    $query = [
      'UPDATE `' . $className::TABLE_NAME . '`',
      'SET ' . $set,
      'WHERE `id` = ' . $id
    ];
    $query = join(PHP_EOL, $query);
    Database::exec($query);
  }

  static public function delete($id) {
    $className = get_called_class();

    $query = [
      'DELETE FROM `' . $className::TABLE_NAME . '`',
      'WHERE `id` = ' . $id
    ];
    $query = join(PHP_EOL, $query);
    Database::exec($query);
  }

  static public function packArray($params) {
    $insert = [];
    $values = [];
    foreach ($params as $columnName => $value) {
      array_push($insert, '`' . $columnName . '`');
      array_push($values, '\'' . $value . '\'');
    }
    $result = [];
    foreach ([$insert, $values] as $data) {
      array_push($result, '(' . join(', ', $data) . ')');
    }
    return $result;
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

  public function getId() {
    return $this->id;
  }
}
