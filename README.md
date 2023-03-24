<h1 align="center" id="title">pdoQueryHunter</h1>

<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ul>
    <li>
      <a href="#fetch-data">Fetch Data</a>
    </li>
    <li>
      <a href="#query-result">Query Result</a>
    </li>
    <li>
      <a href="#insert-data">Insert Data</a>
    </li>
    <li>
      <a href="#update-data">Update Data</a>
    </li>
    <li>
      <a href="#delete-data">Delete Data</a>
    </li>
  </ul>
</details>

<p id="description">This is a secure and robust PHP query builder that uses PDO to interact with the database. The query builder is designed to prevent SQL injection attacks and ensure that all user input is properly sanitized and validated. An easy-to-use interface that can be customized to suit your specific needs. Whether you're building a small application or a large-scale enterprise system this query builder provides the flexibility and scalability you need to manage your database queries efficiently. With its reliable performance and user-friendly interface this PHP query builder is the perfect tool for anyone who wants to build powerful and efficient applications using PDO. 

Note: This query builder is inspired from codeigniter so the functions may be familiar to you if you have used codeigniter query builder before but under the hood they are different</p>

  
<h2>Features</h2>

Here're some of the project's best features:

*   Secure and robust PHP query builder
*   Uses PDO for safe and convenient database interaction
*   Prevents SQL injection attacks using prepared statements and parameterized queries
*   Provides error handling and debugging features to help identify and fix issues quickly
*   Easy to use and customize for your specific needs

<h2>Upcoming</h2>

Upcoming feature and function still about to come:

*   Support for multiple database drivers.
*   Where_in(), where_not_in(), having(), not_like() function.
*   Helper functions like distinct(),max(),sum(),avg() etc functions.
*   Insert_branch(), Update_branch for multiple insert and update.
*   DB Transaction
*   Empty,Truncate Table
*   Cache system

<h2>Installation:</h2>

<p>1. Clone The Repository</p>

```
git clone https://github.com/S4F4Y4T/pdoQueryHunter
```

<p>2. Open config.php and update database information</p>

<p>3. Extend Mmodel class</p>

<h2>Usage</h2>

<h3 id="fetch-data">Fetch Data</h3>

<h5>$this->db->execute()</h5>

<p>Call this function to retrive all the records from table</p>

```
$data = $this->db->execute('table');

//output: select * from table
```

<p>The first parameter accept the table name and second parameter enable you to set limit. Here is an example:</p>

```
$data = $this->db->execute('table', 20); 
$data = $this->db->execute('table', 10, 20);

//output: SELECT * FROM table LIMIT 20
//output: SELECT * FROM table LIMIT 10,20
```

<p>Go to <a href="#query-result">Query Result</a> to see how to use this query to show result</p>

<h5>$this->db->buildFetchQuery()</h5>

<p>Generate the select query like $this->db->execute() but does not run the query but return the query as string:</p>

```
$data = $this->db->buildFetchQuery('table', 20); 

//output string: select * from table LIMIT 20
```

<h5>$this->db->select()</h5>

<p>Call this function which enable you to use the select column of query. Here is an example:</p>

```
$this->db->select('id,name'); 
$data = $this->db->execute('table'); 

//output: select id,name from table
```
<p>By default all (*) column is selecting</p>

<h5>$this->db->table()</h5>

<p>Let you select the table you want to fetch records from. Here is an example:</p>

```
$this->db->select('id,name'); 
$this->db->table('table2');
$data = $this->db->execute(); 

//output: select id,name from table2
```
<p>You can select the table on $this->db->table() or $this->db->execute(), you can use method whichever you prefer</p>

<h5>$this->db->join()</h5>

<p>Let you join table. Here is an example:</p>

```
$this->db->select('id,name'); 
$this->db->table('table');
$this->db->join('table2', 'table2.id = table.table2_id');
$data = $this->db->execute('table'); 

//output: SELECT id, name FROM table, table INNER JOIN table2 ON table2.id = table.table2_id
```
<p>You can call this function multiple time if you need to join multiple table</p>

<p>If you need specific type of join you can define it on third parameter, Options are: inner, outer, left, right, cross</p>

```
$this->db->select('id,name'); 
$this->db->table('table');
$this->db->join('table2', 'table2.id = table.table2_id', 'left');
$data = $this->db->execute('table'); 

output: SELECT id, name FROM table, table LEFT JOIN table2 ON table2.id = table.table2_id
```

<h3>Specific Data</h3>

<h5>$this->db->where()</h5>

<p>This function enables you to set WHERE clauses using one of these methods::</p>

<h5>1. Key and value method</h5>

```
$this->db->where('id', 1); 

//output: WHERE id = :id
```

<p>If you use multiple function calls they will be chained together with AND between them:</p>

```
$this->db->where('id', 1); 
$this->db->where('name', 'safayat'); 

//output: WHERE id = :id AND name = :name
```

<h5>2. Modified operator with Key and value method</h5>

```
$this->db->where('id >', 1); 
$this->db->where('id !=', 3); 

//output: WHERE id > :id AND id != :id
```

<p>Operator options are: =, !=, <>, <, >, <=, >= </p>
  
<p>You can also pass associative array to the function</p>
    
```
$cond = [
    'id' => 1,
    'id !=' => 3
  ];
$this->db->where($cond); 

//output: WHERE id = :id AND id != :id
```
  
<h5>$this->db->or_where()</h5>

<p>This function is same as the above one except multiple functions are join by OR. Here is an example:</p>

```
$this->db->where('id', 1);
$this->db->or_where('id', 2);

//output: WHERE id = :id OR id = :id
```

<h3>Ordering Result</h3>
  
<h5>$this->db->order()</h5>
  
<p>This function let you order your query result. Here is an example:</p>
  
```
$this->db->order('id', 'desc');

//output: ORDER BY id desc
```
  
<p>You can also call this function multiple time to join them. Options for ordering are: asc, desc, random</p>
  
<h3>Limiting Result</h3>
  
<h5>$this->db->limit()</h5>
  
<p>This function let you limit number or result return by the query query. Here is an example:</p>
  
```
$this->db->limit(5);

//output: LIMIT 5
```
  
<p>Second parameter lets you set a result offset.
  
```
$this->db->limit(5, 10);

//output: LIMIT 5,10
```
  
<h3>Similiar Data</h3>
  
<h5>$this->db->like()</h5>
  
<p>This function let you search through your data. Here is an example:</p>
  
```
$this->db->like('name', 'safayat');

//output: WHERE `name` LIKE '%safayat%'
```

<p>You can use the third parameter to define where the wild card will be place. Options are: first,last,none,both<p>
  
```
$this->db->like('name', 'safayat', 'first');
$this->db->like('name', 'safayat', 'last');
$this->db->like('name', 'safayat', 'none');
$this->db->like('name', 'safayat', 'both');

//output: WHERE `name` LIKE '%safayat'
//output: WHERE `name` LIKE 'safayat%'
//output: WHERE `name` LIKE 'safayat'
//output: WHERE `name` LIKE '%safayat%'
```

<p>You can also pass associative array. Here is an example</p>
  
<h5>$this->db->or_like()</h5>
  
<p>Same as above function but join the function with OR</p>
  
```
$data = [
    'name' => 'safayat'
  ];
$this->db->like($data);

//output: WHERE `name` LIKE '%safayat%'
```
  
<h3>Query Grouping</h3>
  
<p>Query grouping allows you to create groups of WHERE clauses by enclosing them in brackets. This will allow you to create queries with complex WHERE clauses. Here is an example:</p>
  
```
$this->db->where('category', 'fruit');
$this->db->group_start();
$this->db->where('price <', 10);
$this->db->or_where('quantity >', 20);
$this->db->group_end();
$this->db->where('value <', 10);
  
$this->db->execute('table');

//output: WHERE category = :category AND (price < :price OR quantity > :quantity) AND value < :value
```
  
<h3>Query Group By</h3>

<h5>$this->db->group_by();</h5>
  
<p>This function let you use group by in your query. Here is an example:</p>
  
```
$this->db->group_by('id,name');

//output: GROUP BY id,name
```

<h3 id="query-result">Query Result</h3>

<p>The query is assigned to a variable $data which can be used to output results.</p>

<h5>fetch()</h5>

<p>This method return result as an array of object. normally you will use this to foreach loop. Here is an example</p>

```
foreach($data->fetch() as $result)
{
  echo $result->value1.'<br>';
  echo $result->value2'<br>';
  echo $result->value3'<br>';
}
```

<h5>fetch_array()</h5>

<p>This method return result as an array. Here is an example</p>

```
foreach($data->fetch_array() as $result)
{
  echo $result->value1.'<br>';
  echo $result->value2'<br>';
  echo $result->value3'<br>';
}
```

<h5>count()</h5>

<p>This method return the number of rows return by the query. Here is an example</p>

```
$data = $this->db->execute('table'); 
echo "number of rows:". $data->count();
```

<h3 id="insert-data">Insert Data</h3>

<h5>$this->db->insert()</h5>

<p>Generate the insert query string and run based on the data you provided. Here is an example:</p>

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
  
<h5>$this->db->insert_id()</h6>

<p>This function return you the last inserted id. Here is an example</p>

```
$this->db->set('key1', 'value1');
$this->db->set('key2', 'value2');
$this->db->set('key3', 'value3');

$this->db->insert('table', $data);

echo $this->db->insert_id();
  
//output: 1
```

<h3 id="update-data">Update Data</h3>

<h5>$this->db->update()</h5>

<p>Generate the update query string and run based on the data you provided. Here is an example:</p>

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

<h3 id="delete-data">Delete Data</h3>

<h5>$this->db->delete()</h5>

<p>Generate the delete query and run based on the data you provided. Here is an example:</p>

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

<h5>$this->db->buildDeleteQuery()</h6>

<p>Generate the delete query like $this->db->delete() but does not run the query but return the query as string:</p>

```
$this->db->where('id', 1);
$this->db->buildDeleteQuery('table');

//output: DELETE FROM table WHERE id = :id
```

<h3>Method Chaining</h4>

<p>Method chaining allows you to simplify your syntax by connecting multiple functions. Here is an example</p>

```
$query = $this->db->select('title')
                ->where('id', 1)
                ->limit(1, 10)
                ->execute('mytable');
```

<h2>License:</h2>

Distributed under the MIT License. See `LICENSE.txt` for more information.
