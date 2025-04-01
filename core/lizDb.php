<?php
class lizDb
{
    private $conn;
    private $stmt;

    public function __construct($servername, $username, $password, $database)
    {
        $this->conn = new mysqli($servername, $username, $password, $database);
    }

    public function query($sql, $params = [])
    {
        $this->stmt = $this->conn->prepare($sql);
        if ($this->stmt === false) {
            die("Query preparation failed: " . $this->conn->error);
        }

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $this->stmt->bind_param($types, ...$params);
        }

        $this->stmt->execute();
        return $this->stmt;
    }

    public function getRow($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getResults($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function count($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? array_values($row)[0] : 0;
    }
    public function insert($sql, $params = [])
    {
        $this->query($sql, $params);
        return $this->conn->insert_id;
    }

    public function update($sql, $params = [])
    {
        $this->query($sql, $params);
        return $this->stmt->affected_rows;
    }
}



// Uso:
// $db = new Database('localhost', 'nombre_bd', 'usuario', 'contraseña');
// $row = $db->getRow("SELECT * FROM users WHERE id = ?", [1]);
// $results = $db->getResults("SELECT * FROM users");
// $count = $db->count("SELECT * FROM users WHERE active = ?", [1]);


// Obtener una fila
/*$row = $db->getRow("SELECT * FROM usuarios WHERE id = 1");
print_r($row);

// Obtener múltiples filas
$results = $db->getResults("SELECT * FROM usuarios");
print_r($results);

// Ejecutar una consulta
$db->query("INSERT INTO usuarios (nombre, email) VALUES ('Juan', 'juan@example.com')");
// Contar filas
$count = $db->count("SELECT * FROM usuarios");
echo "Total de usuarios: " . $count;
*/