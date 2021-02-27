<?php
declare(strict_types=1);


use PHPUnit\Framework\TestCase;

class FetchTest extends TestCase
{
    // TODO: setup with SQLite in future, currently uses 'live' database!
    public function testFetchOutputsTheTableOfUsers(): void
    {
        ob_start();
        require_once __DIR__ . '/../public/fetch.php';
        $output = ob_get_contents();
        ob_end_clean();

        if ($output === false) {
            throw new Error('Unable to test output of fetch.php');
        }

        self::assertStringContainsString('<td width="40%">Freda</td>', $output);
        self::assertStringContainsString('<td width="40%">Bloggs</td>', $output);
        self::assertStringContainsString('<td width="40%">David</td>', $output);
        self::assertStringContainsString('<td width="40%">Williams</td>', $output);
        self::assertStringContainsString('<td width="40%">John</td>', $output);
        self::assertStringContainsString('<td width="40%">Smith</td>', $output);
        self::assertStringContainsString('id="3">Delete</button>', $output);
        self::assertStringContainsString('id="3">Edit</button>', $output);
        self::assertStringContainsString('class="btn btn-primary btn-xs edit"', $output);
        self::assertStringContainsString('class="btn btn-danger btn-xs delete"', $output);
    }

    private function expected(): string
    {
        return <<<'HTML'

<table class="table table-striped table-bordered">
	<tr>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Edit</th>
		<th>Delete</th>
	</tr>

		<tr>
			<td width="40%">Fred</td>
			<td width="40%">Bloggs</td>
			<td width="10%">
				<button
				type="button"
				name="edit"
				class="btn btn-primary btn-xs edit"
				id="1">Edit</button>
			</td>
			<td width="10%">
				<button
				type="button"
				name="delete"
				class="btn btn-danger btn-xs delete"
				id="1">Delete</button>
			</td>
		</tr>

		<tr>
			<td width="40%">David</td>
			<td width="40%">Williams</td>
			<td width="10%">
				<button
				type="button"
				name="edit"
				class="btn btn-primary btn-xs edit"
				id="2">Edit</button>
			</td>
			<td width="10%">
				<button
				type="button"
				name="delete"
				class="btn btn-danger btn-xs delete"
				id="2">Delete</button>
			</td>
		</tr>

		<tr>
			<td width="40%">John</td>
			<td width="40%">Smith</td>
			<td width="10%">
				<button
				type="button"
				name="edit"
				class="btn btn-primary btn-xs edit"
				id="3">Edit</button>
			</td>
			<td width="10%">
				<button
				type="button"
				name="delete"
				class="btn btn-danger btn-xs delete"
				id="3">Delete</button>
			</td>
		</tr>
		</table>
HTML;
    }
}

