<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function p($var)
{
    echo '<pre>';

    echo gettype($var) . '<br />';
    
    if (is_bool($var)) {
        if ($var === TRUE)
            print_r('TRUE');
        elseif ($var === FALSE)
            print_r('FALSE');
        else
            print_r('unknown bool value');
    }
    else
        print_r($var);  // var_dump($array);

	echo '</pre>';
}

function print_r_html($array = array(''))
{
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

function echo_wrap($string = '')
{
	echo '<pre>---[';
	echo $string;
	echo ']---</pre>';
	
}

function get_type($var)
{
    if (is_object($var))
		return get_class($var);

    if (is_null($var))
		return 'null';

    if (is_string($var))
		return 'string';

    if (is_array($var))
		return 'array';

    if (is_int($var))
		return 'integer';

    if (is_bool($var))
		return 'boolean';

    if (is_float($var))
		return 'float';

    if (is_resource($var))
        return 'resource';
    
    return 'unknown';
}
