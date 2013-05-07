table
---

Package for the fuelphp framework that streamlines the creation of tables with or without sorting.

---

This package is still in early development.

To start using this package you need to add it to your list of always loaded files i the fuel php config.

Some example code to get you started:

    $table = \Table::instance();
		
    $query = Model_User::query()->order_by($table->order('username'), $table->direction())->get();
	
	$table->header = array(
		array(
			'title'	=> 'Username',
			'table'	=> 'title',
		),
		array(
			'title'	=> 'Joined',
			'table'	=> 'created_at',
		),
	);
	
	foreach ($query as $r) {
		$data[] = array($r['title'], \Date::time_ago($r['created_at']));
	}
	
	$table->rows = $data;
	
	$this->theme->set_partial('content', 'users/users')->set('table', $table, false);


Then in your theme partial just do a $table->render() to get a nice table :)

The value you input into $table->order() is the default table in the database that you wish the data to be orderd by if no order has been recieved trough post.

All config values are the defult ones at the moment.
