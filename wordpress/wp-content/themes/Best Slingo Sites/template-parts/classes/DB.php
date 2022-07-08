<?php

class DB implements Injectable {

	public function GetAdDisclosure(): string {
		global $wpdb;
		$ad = $wpdb->get_results( "select body from {$wpdb->prefix}addisclosure" );

		return $ad[0]->body ?? "";
	}

	public function GetEntries( $post_type ): array {
		global $wpdb;

		return $wpdb->get_results( "select ID,post_title from {$wpdb->prefix}posts where post_type='$post_type' and post_status='publish'" );
	}

	public function GetFooterText(): string {
		global $wpdb;

		return $wpdb->get_results( "select * from {$wpdb->prefix}footertext" )[0]->body;
	}


}