<?php
/*
Plugin Name:  Facebook Fan and like box widget
Plugin URI: http://newplugins.us/facebook-fan-and-like-wp-widget/
Description: Display facebook fan box as widgetss in your WordPress blog.
Author: vaske2408
Version: 1.0
Author URI: http://newplugins.us/

Copyright 2011

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// wordpress plugin action hook
add_action('plugins_loaded', 'face_box_1');

// face_box_2ialization function
global $fanbox_fan_box;
function face_box_1() {
   $fanbox_fan_box = new wp_fansy();
}

/*
 * This is the namespace for the 'recent_comments_gravatar' plugin / widgetss.
 */
class wp_fansy {
   
   protected $_name = "Facebook Fan and like ";
   protected $_folder;
   protected $_path;
   protected $_width = 300;
   protected $_height = 320;
   protected $_link = 'http://wordpress.org/';
   protected $_facebook_fan_box_api = 'http://www.facebook.com/connect/connect.php';
   
   /*
    * Constructor
    */
   function __constructs() {
      $path = __FILE__;
      if (!$path) { $path = $_SERVER['PHP_SELF']; }
         $current_dir = dirname($path);
      $current_dir = str_replace('\\', '/', $current_dir);
      $current_dir = explode('/', $current_dir);
      $current_dir = end($current_dir);
      if (empty($current_dir) || !$current_dir)
         $current_dir = 'recent-comments-gravatar';
      $this->_folder = $current_dir;
      $this->_path = '/wp-content/plugins/' . $this->_folder . '/';

      $this->face_box_2();
   }
   
   /*
    * face_box_2ialization function, called by plugin_loaded action. .
    */
   function face_box_2() {
      add_filter("plugin_action_links_$plugin", array(&$this, 'link'));
      load_plugin_textdomain($this->_folder, false, $this->_folder);      
      
      if (!function_exists('register_sidebar_widgetss') || !function_exists('register_widgetss_controlss'))
         return;
      register_sidebar_widgetss($this->_name, array(&$this, "widgetss"));
      register_widgetss_controlss($this->_name, array(&$this, "controlss"), $this->_width, $this->_height);
   }

   /*
    * Options validation.
    */
   function face_box_3(&$options) {
      if (!is_array($options)) {
         $options = array(
            'width' => '320',
            'height' => '300',
            'profile_id' => '',      
            'connections' => '10',
            'stream' => '',
            'header' => '',
            'locale' => '',
            'link_to_us' => 'checked');
      }
      
      // validations and defaults
      if (intval($options['width']) == 0) $options['width'] = '320';
      if (intval($options['height']) == 0) $options['height'] = '300';
      if (intval($options['connections']) == 0) $options['connections'] = '10';
   }
   
   /*
    * Called by register_sidebar_widgetss() function.
    * Rendering of the widgetss happens here.
    */
   function widgetss($args) {
      extract($args);

      $options = get_option($this->_folder);
      $this->face_box_3($options);
      
      // There is no before title, after title, before widgetss, and after widgetss
      // This is plain facebook fanbox, no embellishment
      echo '<iframe scrolling="no" frameborder="0" width="' . $options['width']. '" height="' . $options['height'] . '" src="' . $this->_facebook_fan_box_api . '?id=' . $options['profile_id'] . '&amp;connections=' . $options['connections'] . '&amp;stream=' . ($options['stream'] == 'checked' ? 'true' : 'false') . '&amp;header=' . ($options['header'] == 'checked' ? 'true' : 'false') . '&amp;locale=' . $options['locale'] . '"></iframe>';

   }

   /*
    * Plugin controlss funtion, used by admin screen.
    */
   function controlss() {
      $options = get_option($this->_folder);
      $this->face_box_3($options);
      if ($_POST[$this->_folder . '-submit']) {
         $options['width'] = htmlspecialchars(stripslashes($_POST[$this->_folder . '-width']));
         $options['height'] = htmlspecialchars($_POST[$this->_folder . '-height']);
         $options['profile_id'] = htmlspecialchars(stripslashes($_POST[$this->_folder . '-profile_id']));
         $options['connections'] = htmlspecialchars(stripslashes($_POST[$this->_folder . '-connections']));
         $options['stream'] = htmlspecialchars(stripslashes($_POST[$this->_folder . '-stream']));
         $options['header'] = htmlspecialchars($_POST[$this->_folder . '-header']);
         $options['locale'] = htmlspecialchars(stripslashes($_POST[$this->_folder . '-locale']));
         $options['link_to_us'] = htmlspecialchars($_POST[$this->_folder . '-link_to_us']);
         update_option($this->_folder, $options);
      }

?>
      <p>
         <label for="<?php echo($this->_folder) ?>-width"><?php _e('Width: ', $this->_folder); ?></label>
         <input type="text" id="<?php echo($this->_folder) ?>-width" name="<?php echo($this->_folder) ?>-width" value="<?php echo $options['width']; ?>" size="2"></input> (<a href="<?php echo $this->_link?>#width" target="_blank">?</a>)
      </p>
      <p>
         <label for="<?php echo($this->_folder) ?>-title"><?php _e('Height: ', $this->_folder); ?></label>
         <input type="text" id="<?php echo($this->_folder) ?>-height" name="<?php echo($this->_folder) ?>-height" value="<?php echo $options['height']; ?>" size="2"></input> (<a href="<?php echo $this->_link?>#height" target="_blank">?</a>)
      </p>
      <p>
         <label for="<?php echo($this->_folder) ?>-title"><?php _e('Profile Id: ', $this->_folder); ?></label>
         <input type="text" id="<?php echo($this->_folder) ?>-profile_id" name="<?php echo($this->_folder) ?>-profile_id" value="<?php echo $options['profile_id']; ?>" size="20"></input> (<a href="<?php echo $this->_link?>#profile-id" target="_blank">?</a>)
      </p>
      <p>
         <label for="<?php echo($this->_folder) ?>-connections"><?php _e('Connections: ', $this->_folder); ?></label>
         <input type="text" id="<?php echo($this->_folder) ?>-connections" name="<?php echo($this->_folder) ?>-connections" value="<?php echo $options['connections']; ?>" size="2"></input> (<a href="<?php echo $this->_link?>#connections" target="_blank">?</a>)
      </p>
      <p>
          <input type="checkbox" id="<?php echo($this->_folder) ?>-stream" name="<?php echo($this->_folder) ?>-stream" value="checked" <?php echo $options['stream'];?> /> <?php _e('Stream', $this->_folder) ?> (<a href="<?php echo $this->_link?>#stream" target="_blank">?</a>)       
      </p>
      <p>
          <input type="checkbox" id="<?php echo($this->_folder) ?>-stream" name="<?php echo($this->_folder) ?>-header" value="checked" <?php echo $options['header'];?> /> <?php _e('Header', $this->_folder) ?> (<a href="<?php echo $this->_link?>#wg-header" target="_blank">?</a>)
      </p>
      <p>
         <div style="display: none;"> <input type="checkbox" id="<?php echo($this->_folder) ?>-link_to_us" name="<?php echo($this->_folder) ?>-link_to_us" value="checked" <?php echo $options['link_to_us'];?> /> <?php _e('Link to us (optional)', $this->_folder) ?>  </div>
      </p>
      <p><?php printf(__('More details about these options, visit <a href="%s" target="_blank">Plugin Home</a>', $this->_folder), $this->_link) ?></p>
      <input type="hidden" id="<?php echo($this->_folder) ?>-submit" name="<?php echo($this->_folder) ?>-submit" value="1" />
<?php
   }

   /*
    * Add extra link to widgetss list.
    */
   function link($links) {
      $options_link = '<a href="' . $this->_link . '">' . __('Donate', $this->_folder) . '</a>';
      array_unshift($links, $options_link);
      return $links;
   }

}
