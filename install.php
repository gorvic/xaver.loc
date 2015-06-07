<?php
header("Content-Type: text/html; charset=utf-8");

require_once './includes/functions.php';

function _e($string) {
	echo $string;
}

function _x($text, $context) {
	return $text;
}

if (isset($_POST['submit'])) {

	$dbname = trim( $_POST[ 'dbname' ]  );
	$uname = trim(  $_POST[ 'uname' ]  );
	$pwd = trim(  $_POST[ 'pwd' ]  );
	$dbhost = trim( $_POST[ 'dbhost' ] );
	//$prefix = trim( $_POST[ 'prefix' ] ) );
	
	$connection = mysqli_connect($dbhost, $uname, $pwd);
  // Test if connection succeeded
  if(mysqli_connect_errno()) {
    die("Database connection failed: " . 
         mysqli_connect_error() . 
         " (" . mysqli_connect_errno() . ")"
    );
  }
  
  if (!mysqli_set_charset($connection, "utf8")) {
    die('Ошибка при загрузке набора символов utf8:'.mysqli_error($connection));
  }
  
  //проверим, есть ли база
  $query = "CREATE DATABASE IF NOT EXISTS {$dbname} CHARACTER SET utf8 COLLATE utf8_general_ci";
  var_dump($query);
  $result = mysqli_query($connection, $query);
  if (!$result) {
	  die('Ошибка создания БД:'.mysqli_error($connection));
  }
  
  $query = "SHOW DATABASES LIKE '{$dbname}'";
  $db_set = mysqli_query($connection, $query);
if (!$row = mysqli_fetch_assoc( $db_set)) {
	die ("Не удалось создать БД".mysqli_error($connection));
}
  
  $result = mysqli_select_db($connection, $dbname);
  if (!$result) {
	  die('Ошибка подключения к БД:'.mysqli_error($connection));
  }

  $filename = $_SERVER["DOCUMENT_ROOT"]."/mysql/mysqldump.sql";
if (!file_exists($filename)) {
	echo 'Mysql dump '.$filename.' doesn\'t exist';
	exit;
}

$handle = fopen($filename, "r");
if ($handle) {
	
	$templine = '';
    while (($line = fgets($handle)) !== false) {
     
		if (substr($line, 0, 2) == '--' || $line == '')
			continue;

		$templine .= $line;
		if (substr(trim($line), -1, 1) == ';') {
			// Perform the query
			mysqli_query($connection, $templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysqli_error($connection) . '<br /><br />');
			// Reset temp variable to empty
			$templine = '';
		}
	}

    fclose($handle);
} else {
    die ("Не смогли открыть файл!");
} 
redirect_to("index.php");

}

?>

<form method="post" action="install.php">
	<p><?php _e( "Below you should enter your database connection details. If you&#8217;re not sure about these, contact your host." ); ?></p>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="dbname"><?php _e( 'Database Name' ); ?></label></th>
			<td><input name="dbname" id="dbname" type="text" size="25" value="advertises" /></td>
			<td><?php //_e( 'The name of the database you want to run WP in.' ); ?></td>
		</tr>
		<tr>
			<th scope="row"><label for="uname"><?php _e( 'User Name' ); ?></label></th>
			<td><input name="uname" id="uname" type="text" size="25" value="<?php echo htmlspecialchars( _x( 'root', 'example username' ), ENT_QUOTES ); ?>" /></td>
			<td><?php //_e( 'Your MySQL username' ); ?></td>
		</tr>
		<tr>
			<th scope="row"><label for="pwd"><?php _e( 'Password' ); ?></label></th>
			<td><input name="pwd" id="pwd" type="text" size="25" value="<?php echo htmlspecialchars( _x( '123', 'example password' ), ENT_QUOTES ); ?>" autocomplete="off" /></td>
			<td><?php //_e( '&hellip;and your MySQL password.' ); ?></td>
		</tr>
		<tr>
			<th scope="row"><label for="dbhost"><?php _e( 'Database Host' ); ?></label></th>
			<td><input name="dbhost" id="dbhost" type="text" size="25" value="localhost" /></td>
			<td> <?php //_e( 'You should be able to get this info from your web host, if <code>localhost</code> does not work.' ); ?></td>
		</tr>
<!--		<tr>
			<th scope="row"><label for="prefix"><?php // _e( 'Table Prefix' ); ?></label></th>
			<td><input name="prefix" id="prefix" type="text" value="" size="25" /></td>
			<td><?php //_e( 'If you want to run multiple WordPress installations in a single database, change this.' ); ?></td>
		</tr>-->
	</table>
	<?php if ( isset( $_GET['noapi'] ) ) { ?><input name="noapi" type="hidden" value="1" /><?php } ?>
	<input type="hidden" name="language" value="ru_RU />
	<p class="step"><input name="submit" type="submit" value="Submit" class="button button-large" /></p>
</form>

