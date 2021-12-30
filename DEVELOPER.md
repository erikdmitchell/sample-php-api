## DB

### Delete

```
include('class/mysql_crud.php');
$db = new Database();
$db->connect();
$db->delete('CRUDClass','id=5');  // Table name, WHERE conditions
$res = $db->getResult();  
print_r($res);
```

### SQL (query)

```
include('class/mysql_crud.php');
$db = new Database();
$db->connect();
$db->sql('SELECT id,name FROM CRUDClass');
$res = $db->getResult();
foreach($res as $output){
	echo $output["name"]."<br />";
}
```

### Insert

```
include('class/mysql_crud.php');
$db = new Database();
$db->connect();
$data = $db->escapeString("name5@email.com"); // Escape any input before insert
$db->insert('CRUDClass',array('name'=>'Name 5','email'=>$data));  // Table name, column names and respective values
$res = $db->getResult();  
print_r($res);
```

### Select

```
include('class/mysql_crud.php');
$db = new Database();
$db->connect();
$db->select('CRUDClass','id,name',NULL,'name="Name 1"','id DESC'); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
$res = $db->getResult();
print_r($res);
```

### Update

```
include('class/mysql_crud.php');
$db = new Database();
$db->connect();
$db->update('CRUDClass',array('name'=>"Name 4",'email'=>"name4@email.com"),'id="1" AND name="Name 1"'); // Table name, column names and values, WHERE conditions
$res = $db->getResult();
print_r($res);
```