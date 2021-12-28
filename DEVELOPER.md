DB


Fetch a record from a database:
$account = $db->query('SELECT * FROM accounts WHERE username = ? AND password = ?', 'test', 'test')->fetchArray();
echo $account['name'];
Or you could do:

$account = $db->query('SELECT * FROM accounts WHERE username = ? AND password = ?', array('test', 'test'))->fetchArray();
echo $account['name'];

Fetch multiple records from a database:
$accounts = $db->query('SELECT * FROM accounts')->fetchAll();


You can specify a callback if you do not want the results being stored in an array (useful for large amounts of data):

$db->query('SELECT * FROM accounts')->fetchAll(function($account) {
    echo $account['name'];
});


Get the number of rows:
$accounts = $db->query('SELECT * FROM accounts');
echo $accounts->numRows();

Get the affected number of rows:
$insert = $db->query('INSERT INTO accounts (username,password,email,name) VALUES (?,?,?,?)', 'test', 'test', 'test@gmail.com', 'Test');
echo $insert->affectedRows();

Get the total number of queries:
echo $db->query_count;

Get the last insert ID:
echo $db->lastInsertID();

Close the database:
$db->close();