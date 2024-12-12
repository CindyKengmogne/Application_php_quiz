<?php




class DbConnexion
{
    private static ?PDO $connection = null;
    private static string $connection_string = "mysql:host=localhost;dbname=gestionquiz";
    private static string $username = "root";
    private static string $password = "";


    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO(self::$connection_string, self::$username, self::$password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch (PDOException $e){
                die("Erreur de Connexion : " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}