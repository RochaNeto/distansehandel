<?php
$controls = new LiteCacheControls();

if ($controls->is_action('save')) {
    $controls->options = stripslashes_deep($_POST['options']);

    $controls->options['parsed_urls'] = array();
    $urls = explode("\n", str_replace(array("\n", "\r", " "), "\n", $controls->options['urls']));
    foreach ($urls as &$uri)
    {
        $uri = trim($uri);
        if ($uri == '') continue;
        if ($uri[0] != '/') $uri = '/' . $uri;
        $controls->options['parsed_urls'][] = $uri;
    }

    $controls->options['folder'] = trim($controls->options['folder']);
    if (!empty($controls->options['folder'])) $controls->options['folder'] = untrailingslashit($controls->options['folder']);

    if (empty($controls->options['max_age']) || !is_numeric($controls->options['max_age'])) $controls->options['max_age'] = 0;

    /*
    $controls->options['parsed_cookies'] = array();
    $cookies = explode("\n", str_replace(array("\n", "\r", " "), "\n", $controls->options['cookies']));
    foreach ($cookies as &$cookie)
    {
        $cookie = trim($cookie);
        if ($cookie == '') continue;
        $controls->options['parsed_cookies'][] = $uri;
    }
    */

    if (empty($controls->options['agents'])) $controls->options['agents'] = LiteCacheAdmin::AGENTS;
    update_option('lite-cache', $controls->options);
    
    $controls->options['reject_agents'] = rtrim(trim($controls->options['reject_agents']));

    $advanced_cache = file_get_contents(dirname(__FILE__) . '/advanced-cache.php');
    $advanced_cache = str_replace('_AGENTS_', $controls->options['agents'], $advanced_cache);
    $advanced_cache = str_replace('_REJECTAGENTS_', $controls->options['reject_agents'], $advanced_cache);
    $advanced_cache = str_replace('_REJECTAGENTSENABLED_', empty($controls->options['reject_agents'])?'false':'true', $advanced_cache);
    $advanced_cache = str_replace('_MOBILE_', $controls->options['mobile'], $advanced_cache);
    $advanced_cache = str_replace('_NOGZIP_', $controls->options['nogzip'], $advanced_cache);
    $advanced_cache = str_replace('_FOLDER_', $lite_cache->get_folder(), $advanced_cache);
    $advanced_cache = str_replace('_MAX_AGE_', $controls->options['max_age'], $advanced_cache);
    $r = file_put_contents(WP_CONTENT_DIR . '/advanced-cache.php', $advanced_cache);
    if ($r == false) {
        $controls->errors = 'Unable to write the <code>wp-content/advanced-cache.php</code> file. Check the file or folder permissions.';
    }
}

if ($controls->is_action('clean')) {
    $folder = $lite_cache->get_folder();
    $lite_cache->remove_dir($folder . '');
}

if ($controls->is_action('clean-home')) {
    $home = get_option('home');
    $home = substr($home, strpos($home, '://')+1);
    $folder = $lite_cache->get_folder() . '/' . $home;
    @unlink($folder . '/index.html');
    @unlink($folder . '/index.html.gz');
    @unlink($folder . '/index-mobile.html');
    @unlink($folder . '/index-mobile.html.gz');
    @unlink($folder . '/index-mobile-user.html');
    @unlink($folder . '/index-mobile-user.html.gz');
    @unlink($folder . '/index-user.html');
    @unlink($folder . '/index-user.html.gz');
    @unlink($folder . '/robots.txt');
    $lite_cache->remove_dir($folder . '/feed/');
    $lite_cache->remove_dir($folder . '/page/');
    $base = get_option('category_base');
    if (empty($base)) $base = 'category';
    $lite_cache->remove_dir($folder . '/' . $base . '/');

    $base = get_option('tag_base');
    if (empty($base)) $base = 'tag';
    $lite_cache->remove_dir($folder . '/' . $base . '/');

    $lite_cache->remove_dir($folder . '/type/');

    $lite_cache->remove_dir($folder . '/' . date('Y') . '/');
}


if ($controls->is_action('size')) {
    $folder = $lite_cache->get_folder();
    $controls->messages = 'Cache size: ' . size_format((lc_size($folder . '/')));
}

function lc_size($dir) {
    $files = glob($dir . '*', GLOB_MARK);
    $size = 0;
    foreach ($files as &$file) {
        if (substr($file, -1) == '/')
            $size += lc_size($file);
        else
            $size += @filesize($file);
    }
    return $size;
}

if ($controls->options == null) $controls->options = get_option('lite-cache');

// For installation that does not create the directory on activation
wp_mkdir_p($lite_cache->get_folder());

?>

<div class="wrap">

<div id="satollo-header">
    <a href="http://www.satollo.net/plugins/lite-cache" target="_blank">Get Help</a>
    <a href="http://www.satollo.net/forums" target="_blank">Forum</a>

    <form style="display: inline; margin: 0;" action="http://www.satollo.net/wp-content/plugins/newsletter/do/subscribe.php" method="post" target="_blank">
        Subscribe to satollo.net <input type="email" name="ne" required placeholder="Your email">
        <input type="hidden" name="nr" value="lite-cache">
        <input type="submit" value="Go">
    </form>

    <a href="https://www.facebook.com/satollo.net" target="_blank"><img style="vertical-align: bottom" src="<?php echo NEWSLETTER_URL; ?>/images/facebook.png"></a>

    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5PHGDGNHAYLJ8" target="_blank"><img style="vertical-align: bottom" src="http://www.satollo.net/images/donate.png"></a>
    <a href="http://www.satollo.net/donations" target="_blank">Even <b>1$</b> helps: read more</a>

    <div style="display: inline; position: relative; top: 5px"><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.satollo.net%2Fplugins%2Flite-cache&amp;send=false&amp;layout=button_count&amp;width=130&amp;show_faces=false&amp;action=recommend&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=102960746539273" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:130px; height:21px;" allowTransparency="true"></iframe></div>
</div>

  <h2>Lite Cache</h2>
 

  <?php if (!defined('WP_CACHE') || !WP_CACHE) { ?>
    <div class="error">
      <p>
        You must add to the file wp-config.php (at its beginning after the &lt;?php) the line of code:
        <code>define('WP_CACHE', true);</code></strong>. You can avoid to add that code only if you are
        using the .htaccess hack (but it limits the cache flexibility).
      </p>
    </div>
  <?php } ?>

  <?php if (filemtime(WP_CONTENT_DIR . '/advanced-cache.php') < filemtime(dirname(__FILE__) . '/advanced-cache.php')) { ?>
    <div class="error">
      <p>
        You must save the options since some files must be updated.
      </p>
    </div>
  <?php } ?>

  <?php if (!is_dir($lite_cache->get_folder())) { ?>
    <div class="error">
      <p>
        Lite Cache was not able to create or find the <code><?php $lite_cache->get_folder(); ?></code> folder, please
        create it manually with list, write and read permissions (usually 777).
      </p>
    </div>
  <?php } ?>

  <?php if (get_option('permalink_structure') == '') { ?>
    <div class="error"><p>You must choose a different permalink structure under Permalink panel otherwise Lite Cache cannot work properly.</p></div>
  <?php } ?>


  <?php $controls->show_messages(); ?>
  <?php $controls->show_errors(); ?>


  <form method="post" action="">
    <?php $controls->init(); ?>


    <p>All your questions should be answered on <a href="http://www.satollo.net/plugins/lite-cache" target="_tab">Lite Cache official page</a> (open in a new tab).</p>

    <p>
      <?php $controls->button('clean', 'Clean the whole cache'); ?>
      <?php $controls->button('clean-home', 'Clean home, feed, archives'); ?>
      <?php $controls->button('size', 'Compute the cache size'); ?>
    </p>

    <div id="tabs">
      <ul>
        <li><a href="#tabs-1">Configuration</a></li>
        <li><a href="#tabs-2">Mobile</a></li>
        <li><a href="#tabs-3">.htaccess</a></li>
        <li><a href="#tabs-4">Advanced</a></li>
      </ul>

      <div id="tabs-1">

        <table class="form-table">
          <!--
          <tr>
              <th>Cache feed too?</th>
              <td>
          <?php $controls->checkbox('feed'); ?>
              </td>
          </tr>
          -->
          <tr>
              <th>Cached pages will be valid for</th>
              <td>
                <?php $controls->text('max_age'); ?> hours
                <div class="hints">0 means forever</div>
              </td>
          </tr>

          <tr>
            <th>Cache even for logged in users?</th>
            <td>
              <?php $controls->checkbox('user'); ?> NOT RECOMMENDED!
              <div class="hints">See <a href="http://www.satollo.net/plugins/lite-cache">this page</a> to disable the cache if you are the administrator</div>
            </td>
          </tr>
          <tr>
            <th>URLs to exclude from cache</th>
            <td>
              <?php $controls->textarea('urls'); ?>
              <div class="hints">One per line</div>
            </td>
          </tr>
          <!--
          <tr>
              <th>Cookies that "bypass" the cache</th>
              <td>
                <?php $controls->textarea('coockies'); ?>
                <div class="hints">One per line</div>
              </td>
          </tr>
          -->
        </table>

      </div>
      <div id="tabs-2">
        <table class="form-table">
          <!--
          <tr>
              <th>Cache feed too?</th>
              <td>
                <?php $controls->checkbox('feed'); ?>
              </td>
          </tr>
          -->
          <tr>
            <th>Mode</th>
            <td>
              <?php $controls->select('mobile', array(0 => '[disabled] Do not detect mobile devices', 1 => '[enabled] Detect mobile devices and use a separate cache', 2 => '[enabled] Detect mobile devices and bypass the cache')); ?>
              <div class="hints">
                It make sense to disable the cache for mobile devices when their traffic is very low.
              </div>
            </td>
          </tr>
          <tr>
            <th>Mobile theme</th>
            <td>
              <?php
              $themes = get_themes();
              $list = array('' => 'Use the active blog theme');
              foreach ($themes as $key => $data)
                $list[$key] = $key;
              ?>
              <?php $controls->select('theme', $list); ?>
              <div class="hints">
                Even if the active blog theme is used for mobile devices, if some plugins create different content/layout for mobile
                devices so you MUST set the caching option to "use a separate cache".
              </div>
            </td>
          </tr>
          <tr>
            <th>Mobile agents</th>
            <td>
              <?php $controls->text('agents', 80); ?>
              <div class="hints">Must be lower case and separated by the pipe (|) symbol. No single quotes or spaces! A default value can be
                  <code><?php echo LiteCacheAdmin::AGENTS; ?></code></div>
            </td>
          </tr>
          
        </table>
      </div>

      <div id="tabs-3">

        <p>
          This mode works only if you are using the .htaccess file in your blog. Modify it adding the piece of code below just
          before the part delimited by "# BEGIN WordPress". This working mode has some limits and cannot be used with
          logged in user cache option enabled.
        </p>
        <p><strong>Warning</strong>! When you change the option above to cache for logged in users too, this code change too!</p>

        <?php
        $home_root = parse_url(get_option('home'), PHP_URL_PATH);
        $home_root = trailingslashit($home_root);
        ?>
        <pre style="border: 1px solid #999; background-color: #fff; padding: 15px; font-family: monospace; font-size: 11px">
&lt;FilesMatch "\.html\.gz$"&gt;
ForceType text/html
&lt;/FilesMatch&gt;
AddEncoding gzip .gz
AddType text/html .gz
AddDefaultCharset UTF-8

RewriteEngine On
RewriteBase <?php echo $home_root; ?>

<?php if (false && $controls->options['user'] == 1) { ?>
RewriteCond %{HTTP:Cookie} !^.*cache_disable.*$
RewriteCond %{HTTP:Accept-Encoding} gzip
RewriteCond %{HTTP:Cookie} ^.*wordpress_logged_in.*$
RewriteCond %{HTTP:Cookie} !^.*wp-postpass_.*$
RewriteCond %{REQUEST_METHOD} !=POST
RewriteCond %{QUERY_STRING} !.+
<!--RewriteCond <?php echo ABSPATH; ?>wp-content/cache/lite-cache%{REQUEST_URI}/index-user.html.gz -f-->
RewriteCond <?php echo $lite_cache->get_folder(); ?>%{REQUEST_URI}/index-user.html.gz -f
RewriteRule ^(.*) wp-content/cache/lite-cache/$1/index-user.html.gz [L]

RewriteCond %{HTTP:Cookie} !^.*cache_disable.*$
RewriteCond %{HTTP:Cookie} ^.*wordpress_logged_in.*$
RewriteCond %{HTTP:Cookie} !^.*wp-postpass_.*$
RewriteCond %{REQUEST_METHOD} !=POST
RewriteCond %{QUERY_STRING} !.+
<!--RewriteCond <?php echo ABSPATH; ?>wp-content/cache/lite-cache%{REQUEST_URI}/index-user.html -f-->
RewriteCond <?php echo $lite_cache->get_folder(); ?>%{REQUEST_URI}/index-user.html -f
RewriteRule ^(.*) wp-content/cache/lite-cache/$1/index-user.html [L]
<?php } ?>

# Mobile gzip
RewriteCond %{HTTP:Cookie} !^.*cache_disable.*$
RewriteCond %{HTTP:Accept-Encoding} gzip
RewriteCond %{HTTP:Cookie} !^.*(wordpress_logged_in_|wp-postpass_).*$
RewriteCond %{HTTP_USER_AGENT} ^.*(<?php echo $options['mobile']; ?>).*$ [NC]
RewriteCond %{REQUEST_METHOD} !=POST
RewriteCond %{QUERY_STRING} !.+
<!--RewriteCond <?php echo ABSPATH; ?>wp-content/cache/lite-cache%{REQUEST_URI}/index-mobile.html.gz -f-->
RewriteCond <?php echo $lite_cache->get_folder(); ?>%{REQUEST_URI}/index-mobile.html.gz -f
RewriteRule ^(.*) wp-content/cache/lite-cache/$1/index-mobile.html.gz [L]

# Mobile
RewriteCond %{HTTP:Cookie} !^.*cache_disable.*$
RewriteCond %{HTTP:Cookie} !^.*(wordpress_logged_in_|wp-postpass_).*$
RewriteCond %{HTTP_USER_AGENT} ^.*(<?php echo $options['mobile']; ?>).*$ [NC]
RewriteCond %{REQUEST_METHOD} !=POST
RewriteCond %{QUERY_STRING} !.+
<!--RewriteCond <?php echo ABSPATH; ?>wp-content/cache/lite-cache%{REQUEST_URI}/index-mobile.html -f-->
RewriteCond <?php echo $lite_cache->get_folder(); ?>%{REQUEST_URI}/index-mobile.html -f
RewriteRule ^(.*) wp-content/cache/lite-cache/$1/index-mobile.html [L]

# Standard gzip
RewriteCond %{HTTP:Cookie} !^.*cache_disable.*$
RewriteCond %{HTTP:Accept-Encoding} gzip
RewriteCond %{HTTP:Cookie} !^.*(wordpress_logged_in_|wp-postpass_).*$
RewriteCond %{REQUEST_METHOD} !=POST
RewriteCond %{QUERY_STRING} !.+
<!--RewriteCond <?php echo ABSPATH; ?>wp-content/cache/lite-cache%{REQUEST_URI}/index.html.gz -f-->
RewriteCond <?php echo $lite_cache->get_folder(); ?>%{REQUEST_URI}/index.html.gz -f-->
RewriteRule ^(.*) wp-content/cache/lite-cache/$1/index.html.gz [L]

# Standard
RewriteCond %{HTTP:Cookie} !^.*cache_disable.*$
RewriteCond %{HTTP:Cookie} !^.*(wordpress_logged_in_|wp-postpass_).*$
RewriteCond %{REQUEST_METHOD} !=POST
RewriteCond %{QUERY_STRING} !.+
<!--RewriteCond <?php echo ABSPATH; ?>wp-content/cache/lite-cache%{REQUEST_URI}/index.html -f-->
RewriteCond <?php echo $lite_cache->get_folder(); ?>%{REQUEST_URI}/index.html -f
RewriteRule ^(.*) wp-content/cache/lite-cache/$1/index.html [L]
        </pre>
      </div>

      <div id="tabs-4">
        <table class="form-table">
           <tr>
              <th>Disable compression</th>
              <td>
                <?php $controls->select('nogzip', array(0 => 'No', 1 => 'Yes')); ?>

                <p>
                  Disable the compression if you got page served which results in a lot of 
                  odd characters. <strong>If change this setting you MUST clear the cache</strong>.
                </p>
              </td>
          </tr>
          <tr>
              <th>Cache only posts newer than</th>
              <td>
                <?php $controls->text('newer_than_days'); ?> days
                <p>
                  Older posts won't be cached and stored resulting in a lower disk space usage. Useful when older posts
                  have low traffic.
                </p>
              </td>
          </tr>
          <tr>
              <th>When invalidation occurs invalidate even</th>
              <td>
                <?php $controls->text('last_posts'); ?> latest post
                <p>
                  The number of latest posts to invalidate when the home is invalidated.
                </p>
              </td>
          </tr>
            <tr>
            <th>Agents to rejects</th>
            <td>
              <?php $controls->text('reject_agents', 80); ?>
              <div class="hints">Must be lower case and separated by the pipe (|) symbol. No single quotes or spaces!
                  
               </div>
            </td>
          </tr>          
          <tr>
              <th>Cache folder</th>
              <td>
                <?php $controls->text('folder', 70); ?> path on disk
                <p>
                  Leave blank for default value. The .htaccess rules may not work with custom cache folder. You can even evaluate to leave
                  this blank and create a symbolic link <code>wp-content/cache/lite-cache -&gt; [your folder]</code>. Your blog is located on
                  <code><?php echo ABSPATH; ?></code>. A wrong configuration can destroy your blog.
              </td>
          </tr>
        </table>
      </div>

    </div>
    <p>
      <?php $controls->button('save', 'Save'); ?>
    </p>

  </form>
</div>

<?php
class LiteCacheControls {

    var $options = null;
    var $errors = null;
    var $messages = null;

    function is_action($action=null) {
        if ($action == null) return !empty($_REQUEST['act']);
        if (empty($_REQUEST['act'])) return false;
        if ($_REQUEST['act'] != $action) return false;
        if (check_admin_referer('save')) return true;
        die('Invalid call');
    }

     function text($name, $size=20) {
         if (!isset($this->options[$name])) $this->options[$name] = '';
        $value = $this->options[$name];
        if (is_array($value)) $value = implode(',', $value);
        echo '<input name="options[' . $name . ']" type="text" size="' . $size . '" value="';
        echo htmlspecialchars($value);
        echo '"/>';
    }

     function checkbox($name) {
         if (!isset($this->options[$name])) $this->options[$name] = '';
        $value = $this->options[$name];
        echo '<input class="panel_checkbox" name="options[' . $name . ']" type="checkbox" value="1"';
        if (!empty($value)) echo ' checked';
        echo '/>';
    }

     function textarea($name) {
         if (!isset($this->options[$name])) $this->options[$name] = '';
        echo '<textarea name="options[' . $name . ']">';
        echo htmlspecialchars($this->options[$name]);
        echo '</textarea>';
    }

    function select($name, $options) {
        if (!isset($this->options[$name])) $this->options[$name] = '';
        $value = $this->options[$name];

        echo '<select name="options[' . $name . ']">';
        foreach ($options as $key => $label) {
            echo '<option value="' . $key . '"';
            if ($value == $key)
                echo ' selected';
            echo '>' . htmlspecialchars($label) . '&nbsp;&nbsp;</option>';
        }
        echo '</select>';
    }

    function button($action, $label, $message=null) {
        if ($message == null) {
            echo '<input class="button-secondary" type="submit" value="' . $label . '" onclick="this.form.act.value=\'' . $action . '\'"/>';
        } else {
            echo '<input class="button-secondary" type="submit" value="' . $label . '" onclick="this.form.act.value=\'' . $action . '\';return confirm(\'' .
            htmlspecialchars($message) . '\')"/>';
        }
    }

    function init() {
        echo '<script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery("textarea").focus(function() {
                    jQuery("textarea").css("height", "100px");
                    jQuery(this).css("height", "400px");
                });
            });
            </script>
            ';
        echo '<input name="act" type="hidden" value=""/>';
        wp_nonce_field('save');
    }

    function hidden($name) {
        if (!isset($this->options[$name])) $this->options[$name] = '';
        $value = $this->options[$name];
        echo '<input name="options[' . $name . ']" type="hidden" value="';
        echo htmlspecialchars($value);
        echo '"/>';
    }

    function show_errors() {
        if (empty($this->errors)) return;
        echo '<div class="error"><p>';
        echo $this->errors;
        echo '</p></div>';
    }

    function show_messages() {
        if (empty($this->messages)) return;
        echo '<div class="updated"><p>';
        echo $this->messages;
        echo '</p></div>';
    }

}
?>