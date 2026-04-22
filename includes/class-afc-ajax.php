<?php
/**
 * AJAX Handler — Appliance Fit Check
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AFC_Ajax {

    public static function init() {
        add_action( 'wp_ajax_afc_check_fit',        array( __CLASS__, 'handle' ) );
        add_action( 'wp_ajax_nopriv_afc_check_fit', array( __CLASS__, 'handle' ) );
    }

    public static function handle() {
        check_ajax_referer( 'afc_nonce', 'nonce' );

        $fields = array( 'prod_height', 'prod_width', 'prod_depth', 'space_height', 'space_width', 'space_depth' );
        $data   = array();

        foreach ( $fields as $field ) {
            $val = isset( $_POST[ $field ] ) ? floatval( $_POST[ $field ] ) : null;
            if ( $val === null || $val < 0 || $val > 200 ) {
                wp_send_json_error( array( 'message' => 'Invalid input: ' . $field ) );
            }
            $data[ $field ] = $val;
        }

        $appliance_type = isset( $_POST['appliance_type'] ) ? sanitize_text_field( $_POST['appliance_type'] ) : '';

        $dims = array(
            'height' => round( $data['space_height'] - $data['prod_height'], 2 ),
            'width'  => round( $data['space_width']  - $data['prod_width'],  2 ),
            'depth'  => round( $data['space_depth']  - $data['prod_depth'],  2 ),
        );

        $worst_rank = -1;

        foreach ( $dims as $dim => $clearance ) {
            $r = self::classify( $clearance );
            if ( $r['rank'] > $worst_rank ) {
                $worst_rank = $r['rank'];
            }
        }

        $overall = self::classify_by_rank( $worst_rank );

        wp_send_json_success( array(
            'overall'        => $overall,
            'appliance_type' => $appliance_type,
            'inputs'         => array(
                'prod_height'  => $data['prod_height'],
                'prod_width'   => $data['prod_width'],
                'prod_depth'   => $data['prod_depth'],
                'space_height' => $data['space_height'],
                'space_width'  => $data['space_width'],
                'space_depth'  => $data['space_depth'],
            ),
            'date' => date( 'F j, Y' ),
        ) );
    }

    private static function classify( $clearance ) {
        if ( $clearance >= 0.25 )                          return self::result_data( 'PERFECT' );
        if ( $clearance >= 0.00 && $clearance <= 0.24 )    return self::result_data( 'TIGHT'   );
        if ( $clearance >= -0.25 && $clearance <= -0.01 )  return self::result_data( 'MODIFY'  );
        return self::result_data( 'NOFIT' );
    }

    private static function classify_by_rank( $rank ) {
        $map = array( 0 => 'PERFECT', 1 => 'TIGHT', 2 => 'MODIFY', 3 => 'NOFIT' );
        return self::result_data( isset( $map[ $rank ] ) ? $map[ $rank ] : 'NOFIT' );
    }

    private static function result_data( $key ) {
        $map = array(
            'PERFECT' => array( 'key' => 'PERFECT', 'rank' => 0, 'label' => 'Fits Perfectly'    ),
            'TIGHT'   => array( 'key' => 'TIGHT',   'rank' => 1, 'label' => 'Tight Fit'         ),
            'MODIFY'  => array( 'key' => 'MODIFY',  'rank' => 2, 'label' => 'Adjustment Needed' ),
            'NOFIT'   => array( 'key' => 'NOFIT',   'rank' => 3, 'label' => 'Does Not Fit'      ),
        );
        return isset( $map[ $key ] ) ? $map[ $key ] : $map['NOFIT'];
    }
}
