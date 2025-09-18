<?php
// Configurações do banco de dados
$db_host = "10.100.0.22";
$db_user = "root";
$db_pass = "Theredpil2001";
$db_name = "sistema";

// Obtém o IP do cliente
$ip = $_SERVER["REMOTE_ADDR"];

// Conexão usando MySQLi (versão moderna)
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verifica se a conexão falhou
if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}

// Configura charset para evitar problemas com acentuação
$mysqli->set_charset("utf8");

// Criar funções de compatibilidade para mysql_* (para código legado)
if (!function_exists('mysql_connect')) {
    function mysql_connect($host, $user, $pass)
    {
        global $mysqli;
        return $mysqli;
    }
}

if (!function_exists('mysql_select_db')) {
    function mysql_select_db($db, $link = null)
    {
        global $mysqli;
        return $mysqli->select_db($db);
    }
}

if (!function_exists('mysql_query')) {
    function mysql_query($query, $link = null)
    {
        global $mysqli;
        return $mysqli->query($query);
    }
}

if (!function_exists('mysql_fetch_assoc')) {
    function mysql_fetch_assoc($result)
    {
        if ($result instanceof mysqli_result) {
            return $result->fetch_assoc();
        }
        return false;
    }
}

if (!function_exists('mysql_fetch_array')) {
    function mysql_fetch_array($result, $type = MYSQLI_BOTH)
    {
        if ($result instanceof mysqli_result) {
            return $result->fetch_array($type);
        }
        return false;
    }
}

if (!function_exists('mysql_num_rows')) {
    function mysql_num_rows($result)
    {
        if ($result instanceof mysqli_result) {
            return $result->num_rows;
        }
        return 0;
    }
}

if (!function_exists('mysql_insert_id')) {
    function mysql_insert_id($link = null)
    {
        global $mysqli;
        return $mysqli->insert_id;
    }
}

if (!function_exists('mysql_real_escape_string')) {
    function mysql_real_escape_string($string, $link = null)
    {
        global $mysqli;
        return $mysqli->real_escape_string($string);
    }
}

if (!function_exists('mysql_error')) {
    function mysql_error($link = null)
    {
        global $mysqli;
        return $mysqli->error;
    }
}

if (!function_exists('mysql_close')) {
    function mysql_close($link = null)
    {
        global $mysqli;
        return $mysqli->close();
    }
}

// Variável de compatibilidade $con
$con = $mysqli;

// Exemplo de fechamento da conexão (caso necessário em outro ponto do código)
// mysql_close($con);
// $mysqli->close();
