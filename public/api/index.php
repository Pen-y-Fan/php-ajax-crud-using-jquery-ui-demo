<?php

declare(strict_types=1);

//fetch.php

include_once(__DIR__ . '/../../vendor/autoload.php');

use App\controller\PeopleController;
use App\Tests\CreateSQLiteTable;

if (getenv('APP_ENV') && getenv('APP_ENV') === 'TESTING') {
    $createSQLiteTable = new CreateSQLiteTable();
    $database = $createSQLiteTable->createSQLiteTableWithData();
    $people = new PeopleController($database);
} else {
    $people = new PeopleController();
}

$result = $people->index();

$total_row = count($result);

$output = '
<table class="table table-striped table-bordered">
	<tr>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Edit</th>
		<th>Delete</th>
	</tr>
';
if ($total_row > 0 && $result !== false) {
    foreach ($result as $row) {
        $output .= '
		<tr>
			<td width="40%">' . $row['first_name'] . '</td>
			<td width="40%">' . $row['last_name'] . '</td>
			<td width="10%">
				<button
				type="button"
				name="edit"
				class="btn btn-primary btn-xs edit"
				id="' . $row['id'] . '">Edit</button>
			</td>
			<td width="10%">
				<button
				type="button"
				name="delete"
				class="btn btn-danger btn-xs delete"
				id="' . $row['id'] . '">Delete</button>
			</td>
		</tr>
		';
    }
} else {
    $output .= '
	<tr>
		<td colspan="4" align="center">Data not found</td>
	</tr>
	';
}
$output .= '</table>';
echo $output;
