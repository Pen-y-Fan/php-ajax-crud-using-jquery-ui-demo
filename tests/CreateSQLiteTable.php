<?php
declare(strict_types=1);


class CreateSQLiteTable
{

    public function createSQLiteTableWithData(): PDO
    {
        putenv('APP_ENV=TESTING');
        include(__DIR__ . '/../database_connection.php');

        $dropTable_SQL = /** @lang SQLite */
            "DROP TABLE IF EXISTS tbl_sample;";

        /** @var PDO $connect */
        $connect->exec($dropTable_SQL);

        $createTable_SQL = /** @lang SQLite */
            <<<SQL
            CREATE TABLE tbl_sample
            (
                    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
                    "first_name" TEXT,
                    "last_name" TEXT
                    "reg_date" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
SQL;

        $connect->exec($createTable_SQL);

        $table_SQLinsert = /** @lang SQLite */
            <<<SQL
            INSERT INTO tbl_sample (`first_name`, `last_name`)
            VALUES (:firstName, :lastName );
SQL;

        $firstName = '';
        $lastName = '';

        $stmt = $connect->prepare($table_SQLinsert);
        $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR, 255);
        $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR, 255);

        $names = [
            [
                'firstName' => 'Fred',
                'lastName' => 'Bloggs'
            ],
            [
                'firstName' => 'David',
                'lastName' => 'Williams'
            ],
            [
                'firstName' => 'John',
                'lastName' => 'Smith'
            ],
        ];

        foreach ($names as $name) {
            try {
                $firstName = $name['firstName'];
                $lastName = $name['lastName'];
                $stmt->execute();
            } catch (PDOException $e) {
                echo "Error adding name" . PHP_EOL . $e->getMessage();
                print_r($name);
                exit;
            }
        }
        return $connect;
    }
}
