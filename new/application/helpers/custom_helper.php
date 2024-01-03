<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function url_id_encode($id)
{
	$CI =& get_instance();
    $CI->load->library('encrypt'); // load library 
    $ret= $CI->encrypt->encode($id);
	$ret = strtr(
                    $ret,
                    array(
                        '+' => '.',
                        '=' => '-',
                        '/' => '~'
                    )
                );
	return $ret;
}
function url_id_decode($id)
{
	$CI =& get_instance();
    $CI->load->library('encrypt'); // load library
	$id = strtr(
                $id,
                array(
                    '.' => '+',
                    '-' => '=',
                    '~' => '/'
                )
            );
    return $CI->encrypt->decode($id);
}
function sanitize_input_post($post_input) {
	if(!empty($post_input)) {
		return html_escape($post_input);
	} else { return ''; }
}