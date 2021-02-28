<?php
declare(strict_types=1);


use PHPUnit\Framework\TestCase;

class SQLiteTest extends TestCase
{
    public function testPDOSqlExtensionIsLoaded(): void
    {
        self::assertTrue(extension_loaded("pdo_sqlite"));
    }


    public function testSQLiteCanBeCreated(): void
    {
        $connect = new PDO('sqlite::memory:');
        self::assertIsObject($connect);
    }

    public function testSQLDataCanBeStored(): void
    {
        $connect = new PDO('sqlite::memory:');
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $createTable_SQL = <<<SQL
CREATE TABLE tbl_sample
(
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "first_name" TEXT,
    "last_name" TEXT
);
SQL;

        $connect->exec($createTable_SQL);

        // Array with some test data to insert to database
        $names = [
            [
                'firstName' => 'Fred ',
                'lastName' => 'Bloggs 😀'
            ],
            [
                'firstName' => 'David',
                'lastName' => 'Williams'
            ],
            [
                'firstName' => 'John',
                'lastName' => 'Smith 😍'
            ],
        ];


        // Prepare INSERT statement to SQLite3 memory db
        $insert = "INSERT INTO tbl_sample (first_name, last_name)
                VALUES (:firstname, :lastname)";
        $stmt = $connect->prepare($insert);

        // Loop thru all data from messages table
        // and insert it to db
        foreach ($names as $key => $name) {
            $stmt->bindValue(':firstname', $name['firstName']);
            $stmt->bindValue(':lastname', $name['lastName']);

            // Execute statement
            $stmt->execute();
        }

        // Select all data from memory db messages table
        $sth = $connect->query(/** @lang SQLite */ 'SELECT * FROM tbl_sample');

        self::assertIsObject($sth);
        $result = $sth->fetchAll();

        self::assertIsArray($result);
        $connect->exec("DROP TABLE `tbl_sample`");

        $postDropResult = $sth->fetchAll();


        // Close memory db connection
        $connect = null;

        self::assertNull($connect);
        $count = count($names);

        for ($i = 0; $i < $count; $i++) {
            self::assertSame($i + 1, (int)$result[$i]['id']);
            self::assertSame($names[$i]['firstName'], $result[$i]['first_name']);
            self::assertSame($names[$i]['lastName'], $result[$i]['last_name']);
        }
        self::assertIsArray($postDropResult);
        self::assertCount(0, $postDropResult);
    }
}
