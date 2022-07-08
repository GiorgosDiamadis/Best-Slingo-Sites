<?php
/*
 * Plugin Name: Ad Disclosure
 * Description: Edit ad disclosure text
 * Version: 1.0
 * Author: Diamadis Giorgos
 */

class AdDisclosure {

	public function __construct() {
		add_action( "admin_menu", array( $this, "adminPage" ) );
		$this->createTable();
	}

	function createTable() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = $wpdb->prefix . 'addisclosure';
		$sql             = "CREATE TABLE `$table_name` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `body` text DEFAULT NULL,
              PRIMARY KEY(id)
              ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
              ";
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}

	function adminPage() {
		add_menu_page( "Ad Disclosure", "Ad Disclosure", "manage_options", __FILE__, array( $this, "HTML" ) );
	}

	function HTML() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'addisclosure';
		if ( isset( $_POST['newsubmit'] ) ) {
			$name = $_POST['newbody'];
			$wpdb->query( "INSERT INTO $table_name(body) VALUES('$name')" );
		}
		if ( isset( $_POST['del'] ) ) {
			$wpdb->query( "DELETE FROM $table_name WHERE id=1" );
		}
		if ( isset( $_POST["update"] ) ) {
			$newname = $_POST["newbody"];

			$wpdb->query( "update $table_name set body='$newname' where id=1" );
		}

		$exists = $wpdb->get_results( "select body from $table_name where id=1" );

		?>
        <div class="wrap">
            <h2>Ad Disclosure</h2>
			<?php
			if ( count( $exists ) == 0 ) {
				?>
                <form action="" method="post">
                    <input type="hidden" value="AUTO_GENERATED" disabled>
                    <div>
                        <textarea type="text" cols="100" rows="10" id="newbody" name="newbody"></textarea>

                    </div>
                    <div>
                        <button id="newsubmit" name="newsubmit" type="submit">Insert</button>

                    </div>
                </form>
				<?php
			} else {
				?>
                <form action="" method="post">
                    <div>
                            <textarea type="text" cols="100" rows="10" id="newbody"
                                      name="newbody"><?php echo $exists[0]->body ?></textarea>
                    </div>
                    <div>
                        <button id="update" name="update" type="submit">Update</button>

                    </div>
                </form>
				<?php
			}
			?>
        </div>
		<?php
	}
}


$adDiscolure = new AdDisclosure();
