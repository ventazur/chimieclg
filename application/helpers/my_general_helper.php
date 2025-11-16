<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * Une collection de functions concues initialement pour d'autres projets
 *
 * ---------------------------------------------------------------------------- */

/* ----------------------------------------------------------------------------
 *
 * force ssl()
 *
 * ---------------------------------------------------------------------------- */
function force_ssl() 
{
    if ($_SERVER['SERVER_PORT'] != 443)
    {
        $url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        redirect($url);
    }
}

/* ----------------------------------------------------------------------------
 *
 * e()
 *
 * ----------------------------------------------------------------------------
 *
 * Converts accented chars to html counterpart.
 * 
 * ---------------------------------------------------------------------------- */
function e($str = '')
{
    $CI =& get_instance();
    return htmlentities($str, ENT_QUOTES, $CI->config->item('charset'), FALSE);
}

/* ----------------------------------------------------------------------------
 *
 * array_keys_swap()
 *
 * ----------------------------------------------------------------------------
 *
 * This function works only for multidimensional arrays.
 *
 * This function replaces the keys of an array for any of the key's value of its subarray.
 * When strict = TRUE, it will return FALSE if the value was already assigned as a key.
 * The key must exists in every subarray.
 *
 * Example:
 *
 * Original Array
 *
 * [0] => ( [cat_id] = 55        
 *          [cat_name] = 'Cars'
 *        ),
 * [1] => ( [cat_id] = 23
 *          [cat_name] = 'Trucks'
 *
 * Modified Array 
*
 * [55] => ( [cat_id] = 55        
 *          [cat_name] = 'Cars'
 *         ),
 * [23] => ( [cat_id] = 23
 *          [cat_name] = 'Trucks'
 *
 * ---------------------------------------------------------------------------- */
function array_keys_swap($arr, $key, $strict = FALSE)
{
    if ( ! is_array($arr)) 
        return FALSE;

    $arr_out = array();

    foreach($arr as $a)
    {
        if ( ! array_key_exists($key, $a))
            return FALSE;

        if ($strict)
        {
            if (array_key_exists($a[$key], $arr_out))
                return FALSE;
        }

        $arr_out[$a[$key]] = $a;
    }
    
    return $arr_out;
}

/* -------------------------------------------------------------------------------------------- 
 *
 * date_humanize()
 *
 * --------------------------------------------------------------------------------------------
 *
 * This function manipules date.
 * It takes an epoch style date and transforms it in a human readable form (YYYY-MM-DD) with
 * leading zeros for months and days.
 *
 * If $precision = TRUE, it adds the time.
 *
 * -------------------------------------------------------------------------------------------- */
function date_humanize($epoch = 0, $precision = FALSE) 
{
    if ($epoch === 0)
        $epoch = date('U');

    if ($precision === TRUE)
        return date('Y-m-d H:i:s', $epoch);    

    return date('Y-m-d', $epoch);
}

/* -------------------------------------------------------------------------------------------- 
 *
 * date_epochize()
 *
 * -------------------------------------------------------------------------------------------- */
function date_epochize($human_date, $period = 'start')
{
    if (empty($human_date))
        return FALSE;

    $period = strtolower($period);

    list($year, $month, $day) = explode('-', $human_date);      

    $year   = (int) $year;
    $month  = (int) $month;
    $day    = (int) $day;

    if ($period === 'start')
        return mktime(0, 0, 0, $month, $day, $year);

    if ($period === 'end')
        return mktime(0, 0, -1, $month, $day + 1, $year);
}
