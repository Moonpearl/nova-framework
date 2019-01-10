<?php

namespace Nova;

abstract class Model
{
  const TABLE_NAME = null;
  const REQUIRED = [];
  const TYPE = [ 'id' => 'int' ];

  protected $id;

  public function __construct($data = null) {
    if (!is_null($data)) {
      $className = get_called_class();
      $required = array_merge(self::REQUIRED, $className::REQUIRED);
      $type = array_merge(self::TYPE, $className::TYPE);

      foreach(get_object_vars($this) as $varName => $value) {
        if (isset($data[$varName])) {
          $value = $data[$varName];
          if (isset($type[$varName])) {
            $varType = $type[$varName];
            if (preg_match('/^\/.+\/$/', $varType)) {
              if (!preg_match($varType, $value)) {
                throw new \RuntimeException("Variable '$varName' doesn't match pattern define in model '$className' ($value given)");
              }
            } else {
              switch($varType) {
                case 'string':
                  $value = strval($value);
                  break;

                case 'int':
                  $value = intval($value);
                  break;

                case 'bool':
                  $value = boolval($value);
                  break;

                case 'float':
                  $value = floatval($value);
                  break;
              }
            }
            $this->$varName = $value;
          }
        } else {
          if (in_array($varName, $required)) {
            throw new \RuntimeException("Variable '$varName' required in model '$className'");
          }
        }
      }
    }
  }

  static public function amount() {
    $className = get_called_class();
    $tableName = $className::TABLE_NAME;

    $stmt = Database::query(
      "SELECT
        COUNT(*)
      FROM `$tableName`"
    );

    return $stmt->fetch()['COUNT(*)'];
  }

  // Fetch all from database
  static public function findAll($columns = null) {
    $className = get_called_class();

    return Database::queryAsObject(
      $className::buildQuery([
        'select' => $columns
      ]),
      $className
    );
  }

  // Fetch all from dqtqbqse
  static public function findMostRecent($columns = null) {
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

  static public function find($id, $columns = null) {
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

  public function save() {
    if (is_null($this->id)) {
      return $this->insert();
    } else {
      return $this->update();
    }
  }

  public function getVarsWithoutId() {
    $vars = get_object_vars($this);
    unset($vars['id']);
    return $vars;
  }

  public function insert() {
    $className = get_called_class();
    $tableName = $className::TABLE_NAME;

    $vars = $this->getVarsWithoutId();
    $varNames = array_keys($vars);

    list($insert, $values) = $className::packArray($varNames);

    $stmt = Database::prepare(
      "INSERT INTO `$tableName` $insert
      VALUES $values"
    );
    $stmt->execute($vars);

    $this->id = Database::lastInsertId();

    return $this->id;
  }

  public function update() {
    $className = get_called_class();
    $tableName = $className::TABLE_NAME;

    $vars = $this->getVarsWithoutId();
    unset($vars['id']);

    $set = [];
    foreach (array_keys($vars) as $varName) {
      array_push($set, "$varName = :$varName");
    }
    $set = join(', ', $set);

    $stmt = Database::prepare(
      "UPDATE `$tableName`
      SET $set
      WHERE `id` = $this->id"
    );
    $stmt->execute($vars);

    return $this->id;
  }

  static public function delete($id) {
    $className = get_called_class();
    $tableName = $className::TABLE_NAME;

    Database::exec(
      "DELETE FROM `$tableName`
      WHERE `id` = $id"
    );
  }

  static public function packArray($varNames) {
    $insert = [];
    $values = [];
    foreach ($varNames as $varName) {
      array_push($insert, '`' . $varName . '`');
      array_push($values, ':' . $varName);
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
