<script type="text/javascript"><?php
    $global_enabled = apply_filters( 'loftloader_pro_enabled_session', false );
    $page_enabled = apply_filters( 'loftloader_pro_page_show_once', false );
    $page_id = $page_enabled ? get_queried_object_id() : -1;
    $page_uid = hash( 'md5', $page_id );
    $scope = 'none';
    if ( $page_enabled ) {
        $scope = 'page';
    } else if ( $global_enabled ) {
        $scope = ( 'once' == llp_get_loader_setting( 'loftloader_pro_show_range', true ) ) ? 'site' : 'front';
    } ?>
    ( function() {
        var loftloaderCache = {
            'timestamp': <?php echo esc_js( time() ); ?>,
            'isOncePerSession': "<?php echo ( $global_enabled || $page_enabled ? 'on' : 'off' ); ?>",
            'scope': "<?php echo esc_js( $scope ); ?>",
            'isFront': "<?php echo ( is_front_page() ? 'on' : 'off' ); ?>",
            'uid': "<?php echo $page_uid; ?>",
            'pageID': <?php echo $page_id; ?>
        },
        htmlClass = document.documentElement.classList, isFront = ( 'on' === loftloaderCache.isFront ),
        sessionID = false, LoftLoaderProCacheSessionStorage = {
            getItem: function( name ) {
                try {
                    return sessionStorage.getItem( name );
                } catch( msg ) {
                    return true;
                }
            },
            setItem: function( name, value ) {
                try {
                    sessionStorage.setItem( name, value );
                } catch( msg ) {}
            }
        };
        if ( 'on' === loftloaderCache.isOncePerSession ) {
            switch ( loftloaderCache.scope ) {
                case 'site':
                    sessionID = 'loftloaderSiteOncePerSession';
                    break;
                case 'front':
                    if ( isFront ) {
                        sessionID = 'loftloaderFrontOncePerSession';
                    }
                    break;
                case 'page':
                    sessionID = loftloaderCache.uid;
                    break;
            }
            if ( sessionID ) {
                if ( LoftLoaderProCacheSessionStorage.getItem( sessionID ) ) {
                    var styles = [ 'loftloader-page-smooth-transition-bg', 'loftloader-pro-disable-scrolling', 'loftloader-pro-always-show-scrollbar' ];
                    styles.forEach( function ( s ) {
                        if ( document.getElementById( s ) ) {
                            document.getElementById( s ).remove();
                        }
                    } );
                    htmlClass.add( 'loftloader-pro-hide' );
                } else {
                    LoftLoaderProCacheSessionStorage.setItem( sessionID, 'done' );
                    htmlClass.remove( 'loftloader-pro-hide' );
                }
            }
        }
    } ) ();
</script>
