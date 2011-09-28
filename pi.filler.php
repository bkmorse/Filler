<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * Filler Plugin
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Plugin
 * @author		Brad Morse
 * @link		http://bkmorse.com
 */

$plugin_info = array(
	'pi_name'		=> 'Filler',
	'pi_version'	=> '1.0',
	'pi_author'		=> 'Brad Morse',
	'pi_author_url'	=> 'http://bkmorse.com',
	'pi_description'=> 'When you have a set number of spots on your webpage and your channel doesn\'t have enough entries, this just adds filler',
	'pi_usage'		=> Filler::usage()
);


class Filler {

	public $return_data;
    
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();

	  $content = $this->EE->TMPL->tagdata;     // all the content you want to add to the extra entries
    $total = $this->EE->TMPL->fetch_param('total');
    $channel = $this->EE->TMPL->fetch_param('channel');
    $status = $this->EE->TMPL->fetch_param('status');

		$return_data = '';
		
		if($channel != '' && $total != '' && $status != '') {
		  $this->EE->db->cache_on();
		  $count = $this->EE->db
  		->select('*')
  		->from('exp_channel_titles')
  		->join('exp_channels', 'exp_channel_titles.channel_id = exp_channels.channel_id', 'left')
  		->where('exp_channels.channel_name', $channel)
  		->where('exp_channel_titles.status', $status)
  		->count_all_results();
	  
  	  if($count < $total) {
  	    $i = 1;
  	    $total = $total - $count;
  	    while($i <= $total) {
  				$return_data .= $content;
  	      $i++;
        }
  	  }
  	}
	  $this->return_data = $return_data;
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>

  {exp:filler channel="test_channel" status="open" total="10"}
   text and/or html goes here
  {/exp:filler}
  
  channel, status (only one) and total are all required.
  
  If your channel only has 6 entries and you need 10 spots filled, you would set total to 10, and it would add the extra 4 entries
  
<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.filler.php */
/* Location: /system/expressionengine/third_party/filler/pi.filler.php */