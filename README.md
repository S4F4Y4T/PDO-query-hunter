<h1 align="center" id="title">pdoQueryHunter</h1>

<p id="description">This is a secure and robust PHP query builder that uses PDO to interact with the database. The query builder is designed to prevent SQL injection attacks and ensure that all user input is properly sanitized and validated. An easy-to-use interface that can be customized to suit your specific needs. Whether you're building a small application or a large-scale enterprise system this query builder provides the flexibility and scalability you need to manage your database queries efficiently. With its reliable performance and user-friendly interface this PHP query builder is the perfect tool for anyone who wants to build powerful and efficient applications using PDO. 

Note: This query builder is inspired from codeigniter so the functions may be familiar to you if you have used codeigniter query builder before but under the hood they are different</p>

  
<h2>Features</h2>

Here're some of the project's best features:

*   Secure and robust PHP query builder
*   Uses PDO for safe and convenient database interaction
*   Prevents SQL injection attacks using prepared statements and parameterized queries
*   Provides error handling and debugging features to help identify and fix issues quickly
*   Easy to use and customize for your specific needs
*   Support for multiple database drivers

<h2>Installation:</h2>

<p>1. Clone The Repository</p>

```
git clone https://github.com/S4F4Y4T/PDO-query-hunter.git
```

<p>2. Open config.php and update database information</p>

<h2>Usage</h2>

<h3>Fetch Data</h4>

<h5>$this->db->execute()</h6>

<p>Call this function to retrive all the records from a table</p>

```
$this->db->execute('table') 

//output: select * from table
```

<h3>Insert Data</h4>

<h5>$this->db->insert()</h6>

<p>Generate the insert query string and run based on the data you provided. Here is a example:</p>

```
$data = [
  'key1' => 'value1',
  'key2' => 'value2',
  'key3' => 'value3'
];

$this->db->insert('table', $data);

//query: INSERT INTO table(key1,key2,key3) VALUES(:key1, :key2, :key3)
```

<p>The first parameter will contain the table name as string and second parameter will contain associative array</p>

<h5>$this->db->buildInsertQuery()</h6>

<p>Generate the insert query like $this->db->insert() but does not run the query but return the query as string:</p>

```
$data = [
  'key1' => 'value1',
  'key2' => 'value2',
  'key3' => 'value3'
];

$this->db->buildInsertQuery('table', $data);

//output string: INSERT INTO table(key1,key2,key3) VALUES(:key1, :key2, :key3)
```

<h5>$this->db->set()</h6>

<p>This function enables you to set values for inserts or updates instead of passing the data directly to the function. If you call the function multiple times they will assemble properly for insert or update as well.</p>

```
$this->db->set('key1', 'value1');
$this->db->set('key2', 'value2');
$this->db->set('key3', 'value3');

$this->db->insert('table', $data);
```

<p>You can also pass associative array</p>

```
$data = [
  'key1' => 'value1',
  'key2' => 'value2',
  'key3' => 'value3'
];

$this->db->set($data);
$this->db->insert('table', $data);
```

<h3>Update Data</h4>

<h5>$this->db->update()</h6>

<p>Generate the update query string and run based on the data you provided. Here is a example:</p>

```
$data = [
  'key1' => 'value1',
  'key2' => 'value2',
  'key3' => 'value3'
];
$this->db->update('table', $data, ['id' => 1]);

//query: UPDATE table SET key1=:key1,key2=:key2,key3=:key3 WHERE id = :id
```

<p>The first parameter will contain the table name as string, second parameter will contain associative array of data and the third parameter will contain the where condition as associative array</p>

<p>You can also use $this->db->where() function which will allow you to set the WHERE condition. Here is a example:</p>

```
$data = [
  'key1' => 'value1',
  'key2' => 'value2',
  'key3' => 'value3'
];
$this->db->where('id', 1);
$this->db->update('table', $data);

//output: UPDATE table SET key1=:key1,key2=:key2,key3=:key3 WHERE id = :id
```

<h5>$this->db->buildUpdateQuery()</h6>

<p>Generate the update query like $this->db->update() but does not run the query but return the query as string:</p>

```
$data = [
  'key1' => 'value1',
  'key2' => 'value2',
  'key3' => 'value3'
];
$this->db->where('id', 1);
$this->db->buildUpdateQuery('table', $data);

//output: UPDATE table SET key1=:key1,key2=:key2,key3=:key3 WHERE id = :id
```

<h3>Delete Data</h4>

<h5>$this->db->delete()</h6>

<p>Generate the delete query and run based on the data you provided. Here is a example:</p>

```
$this->db->delete('table', ['id' => 1]);

Query: DELETE FROM table WHERE id = :id
```

<p>The first parameter will contain the table name as string, second parameter will contain the where condition as associative array. You can also use the where() or or_where() functions instead of passing the data to the second parameter of the function.</p>

```
$this->db->where('id', 1);
$this->db->where('id <', 5);
$this->db->delete('table');
```

<p>Generate the delete query like $this->db->delete() but does not run the query but return the query as string:</p>

```
$this->db->where('id', 1);
$this->db->buildDeleteQuery('table');

//output: DELETE FROM table WHERE id = :id
```

<h3>Method Chaining</h4>

<p>Method chaining allows you to simplify your syntax by connecting multiple functions. Here is example</p>

```
$query = $this->db->select('title')
                ->where('id', 1)
                ->limit(1, 10)
                ->execute('mytable');
```

<h2>License:</h2>

Distributed under the MIT License. See `LICENSE.txt` for more information.
