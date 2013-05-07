<?php

namespace Table;

class TableException extends \FuelException {}

class Table
{

	protected static $_instance = null;
	
	
	protected static $_instances = array();
	
	
	public static function __init()
	{
		\Config::load('theme', true);
	}
	
	
	public static function instance($instance = null)
	{
		if ($instance !== null)
		{
			if ( ! array_key_exists($instance, static::$_instances))
			{
				return false;
			}
			
			return static::$_instances[$instance];
		}
		
		if (static::$_instance === null)
		{
			static::$_instance = static::forge();
		}
		
		return static::$_instance;
	}
	
	
	/**
	 * Gets 
	 *
	 * @param	string	instance name
	 * @return  
	 */
	public static function forge($name = 'default')
	{
		if ($exists = static::instance($name))
		{
			\Error::notice('Table instance with this name already exists and can\'t be overwritten.');
			return $exists;
		}
		
		static::$_instances[$name] = new Table;
		
		if ($name == 'default')
		{
			static::$_instance = static::$_instances[$name];
		}
		
		return static::$_instances[$name];
	}
	
	
	private function __construct($config = array())
	{
	}
	
	
	protected $order_by = '';
	
	
	protected $direction = '';
	
	
	protected $config = array(
		'sortable'			=> true,
		'default_direction'	=> 'asc',
		'asc_class'			=> 'asc',
		'desc_class'		=> 'desc',
		'asc_symbol'		=> '&#9650;',
		'desc_symbol'		=> '&#9660;'
	);
	

	protected $template = array(
		'wrapper'		=> "<div class=\"table\">\n\t{table}\n</div>\n",
		'table'			=> "<table>\n\t{thead}\n{tbody}\n</table>",
		'thead'			=> "<thead>\n\t<tr>\n\t{ths}\n</tr>\n</thead>",
		'tbody'			=> "<tbody>\n\t{rows}\n</tbody>",
		'row'			=> "<tr>\n\t{tds}\n</tr>",
		'td'			=> "<td>{value}</td>",
	);
	
	
	public $header = array();
	
	
	public $rows = array();
	
	
	public function render()
	{
		$html = str_replace('{table}', $this->table(), $this->template['wrapper']);
		
		return $html;
	}
	
	
	/**
	 * Table
	 */
	public function table()
	{
		$html = str_replace(array('{thead}','{tbody}'), array($this->thead(), $this->tbody()), $this->template['table']);
		
		return $html;
	}
	
	public function thead()
	{
		$ths = '';
		$th_start = "<th>";
		$th_end	= "</th>";
	
		// go trough each table header and add it to the string.
		foreach ($this->header as $key => $head)
		{
			// set each value
			if ( ! empty($head['values']))
			{
				$values = '';
			
				foreach ($head['values'] as $key => $vals)
				{
					$values .= ' ' . $key . '="' . $vals . '"';
				}
				
				$th_start = "<th" . $values . ">";
			}
			
			// set sorting
			if ($this->config['sortable'] === true && isset($head['table']) && ! empty($head['table']))
			{
				if ( ! empty($this->direction) && $head['table'] == $this->order_by) {
					
					if ($this->direction == 'asc')
					{
						$dir = 'desc';
					}
					else if ($this->direction == 'desc')
					{
						$dir = 'asc';
					}
				}
				else
				{
					$dir = $this->config['default_direction'];
				}
				
				$ths .= $th_start . '<a href="?order=' . $head['table'] . '&direction=' . $dir . '">' . $head['title'] .$th_end;
			}
			else
			{
				$ths .= $th_start . $head['title'] .$th_end;
			}
		}
	
		$html = str_replace('{ths}', $ths, $this->template['thead']);
		
		return $html;
	}
	
	
	public function tbody()
	{
		$rows = '';	
	
		foreach ($this->rows as $row)
		{
			$rows .= $this->row($row);
		}
		
		$html = str_replace('{rows}', $rows, $this->template['tbody']);
		
		return $html;
	}
	
	
	public function row($row = array())
	{
		$tds = '';
	
		foreach ($row as $val)
		{
			$tds .= str_replace('{value}', $val, $this->template['td']);
		}	
	
		$html = str_replace('{tds}', $tds, $this->template['row']);
	
		return $html;
	}
	
	
	public function direction()
	{
		$dir = \Input::get('direction', $this->config['default_direction']);
		
		$this->direction = $dir;
		
		return $dir;
	}
	
	
	public function order($table = '')
	{
		if ( ! empty($table))
		{
			$order = \Input::get('order', $table);
		
			$this->order_by = $order;
		
			return $order;
		}
		else
		{
			throw new \RuntimeException("Default order must be set to be able to use the sorting options.");
		}
	}
}