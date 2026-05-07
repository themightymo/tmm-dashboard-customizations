<?php

function tmm_get_github_plugin_data() {
    $cached_data = get_site_transient( TMM_MAINTENANCE_MODE_GITHUB_CACHE_KEY );
    if ( is_array( $cached_data ) ) {
        return $cached_data;
    }

    $remote_plugin_file_url = sprintf(
        'https://raw.githubusercontent.com/%s/%s/tmm-dashboard-customizations.php',
        TMM_MAINTENANCE_MODE_GITHUB_REPO,
        TMM_MAINTENANCE_MODE_GITHUB_BRANCH
    );

    $response = wp_remote_get(
        $remote_plugin_file_url,
        array(
            'timeout'    => 15,
            'user-agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . home_url(),
        )
    );

    if ( is_wp_error( $response ) ) {
        return false;
    }

    if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
        return false;
    }

    $remote_file_contents = wp_remote_retrieve_body( $response );
    if ( ! preg_match( '/^[ \t\/*#@]*Version:\s*(.+)$/mi', $remote_file_contents, $matches ) ) {
        return false;
    }

    $github_data = array(
        'new_version' => trim( $matches[1] ),
        'url'         => sprintf( 'https://github.com/%s', TMM_MAINTENANCE_MODE_GITHUB_REPO ),
        'package'     => sprintf(
            'https://github.com/%s/archive/refs/heads/%s.zip',
            TMM_MAINTENANCE_MODE_GITHUB_REPO,
            TMM_MAINTENANCE_MODE_GITHUB_BRANCH
        ),
    );

    set_site_transient( TMM_MAINTENANCE_MODE_GITHUB_CACHE_KEY, $github_data, 6 * HOUR_IN_SECONDS );

    return $github_data;
}

function tmm_push_github_update_to_wordpress( $transient ) {
    if ( empty( $transient->checked ) || ! is_array( $transient->checked ) ) {
        return $transient;
    }

    if ( ! isset( $transient->checked[ TMM_MAINTENANCE_MODE_PLUGIN_FILE ] ) ) {
        return $transient;
    }

    $github_data = tmm_get_github_plugin_data();
    if ( ! $github_data || empty( $github_data['new_version'] ) ) {
        return $transient;
    }

    $installed_version = $transient->checked[ TMM_MAINTENANCE_MODE_PLUGIN_FILE ];

    if ( version_compare( $github_data['new_version'], $installed_version, '>' ) ) {
        $update = new stdClass();
        $update->slug = dirname( TMM_MAINTENANCE_MODE_PLUGIN_FILE );
        $update->plugin = TMM_MAINTENANCE_MODE_PLUGIN_FILE;
        $update->new_version = $github_data['new_version'];
        $update->url = $github_data['url'];
        $update->package = $github_data['package'];

        $transient->response[ TMM_MAINTENANCE_MODE_PLUGIN_FILE ] = $update;
    }

    return $transient;
}
add_filter( 'pre_set_site_transient_update_plugins', 'tmm_push_github_update_to_wordpress' );

function tmm_rename_github_zip_folder( $source, $remote_source, $upgrader, $hook_extra ) {
    if ( ! isset( $hook_extra['plugin'] ) || $hook_extra['plugin'] !== TMM_MAINTENANCE_MODE_PLUGIN_FILE ) {
        return $source;
    }

    $plugin_folder = dirname( TMM_MAINTENANCE_MODE_PLUGIN_FILE );
    $correct_path  = trailingslashit( $remote_source ) . $plugin_folder . '/';

    if ( $source === $correct_path ) {
        return $source;
    }

    global $wp_filesystem;
    if ( $wp_filesystem->move( $source, $correct_path ) ) {
        return $correct_path;
    }

    return $source;
}
add_filter( 'upgrader_source_selection', 'tmm_rename_github_zip_folder', 10, 4 );

function tmm_clear_github_update_cache() {
    delete_site_transient( TMM_MAINTENANCE_MODE_GITHUB_CACHE_KEY );
}
add_action( 'upgrader_process_complete', 'tmm_clear_github_update_cache', 10, 0 );

function tmm_handle_clear_cache_action() {
    if (
        isset( $_POST['tmm_clear_github_cache'] ) &&
        check_admin_referer( 'tmm_clear_github_cache_nonce' )
    ) {
        tmm_clear_github_update_cache();
        delete_site_transient( 'update_plugins' );
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-success is-dismissible"><p>GitHub update cache cleared.</p></div>';
        } );
    }
}
add_action( 'admin_init', 'tmm_handle_clear_cache_action' );
