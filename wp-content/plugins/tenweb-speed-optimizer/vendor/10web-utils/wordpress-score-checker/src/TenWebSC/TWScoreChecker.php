<?php

namespace TenWebSC;

class TWScoreChecker
{
    /**
     * Save the page speed in the post meta.
     *
     * @param $post_id
     * @param $old
     * @param $no_optimized
     *
     * @return void
     */
    public static function twsc_check_score( $post_id, $old = FALSE, $no_optimized = FALSE ) {
        $key = $old ? 'previous_score' : 'current_score';
        // Getting front_page placeholder instead of page ID for Home page.
        $url = ($post_id == 'front_page') ? get_home_url() : get_permalink( $post_id );
        $page_score_old_meta = get_post_meta($post_id, 'two_page_speed', TRUE);
        if ( empty($page_score_old_meta) ) {
            $page_score_old_meta = array();
            $page_score_old_meta[$key] = array();
        }
        if (!$url) {
            return;
        }

        // To check the not optimized page score. This will need on the plugin update to have old scores for existing users.
        if ( $no_optimized ) {
            $url = add_query_arg(array('two_nooptimize' => 1), $url);
        }
        $desktop_score = self::twsc_google_check_score( $url, 'desktop' );
        if ( isset($desktop_score['error']) ) {
            $page_score_old_meta[$key]['status'] = 'notstarted';
            \TenWebWpTransients\OptimizerTransients::delete('two_optimize_inprogress_' . $post_id );
            self::twsc_update_score_info($post_id,$page_score_old_meta);
            return;
        }
        $score = $desktop_score;

        $mobile_score = self::twsc_google_check_score( $url, 'mobile' );
        if ( isset($mobile_score['error']) ) {
            $page_score_old_meta[$key]['status'] = 'notstarted';
            \TenWebWpTransients\OptimizerTransients::delete('two_optimize_inprogress_' . $post_id );
            self::twsc_update_score_info($post_id,$page_score_old_meta);
            return;
        }
        $score = array_merge($score, $mobile_score);
        $score['date'] = date('d.m.Y h:i:s a', strtotime(current_time( 'mysql' )));
        $score['status'] = 'completed';
        if ( $post_id == 'front_page' || $post_id == get_option('page_on_front') ) {
            $page_score = get_option('two-front-page-speed');
        }
        else {
            $page_score = get_post_meta($post_id, 'two_page_speed', TRUE);
        }
        if (empty($page_score)) {
            $page_score = array();
        }
        $page_score[$key] = $score;
        self::twsc_update_score_info($post_id,$page_score);
    }

    /**
     * Update the page speed in the post meta or option.
     *
     * @param $post_id
     * @param $page_score
     *
     * @return void
     */
    public static function twsc_update_score_info($post_id,$page_score)
    {
        if ($post_id == 'front_page' || $post_id == get_option('page_on_front')) {
            update_option('two-front-page-speed', $page_score);
        } else {
            update_post_meta($post_id, 'two_page_speed', $page_score);
        }
    }

    /**
     * Get the page speed from Google by URL.
     *
     * @param $page_url
     * @param $strategy
     *
     * @return array
     */
    public static function twsc_google_check_score( $page_url, $strategy ) {
        $google_api_keys = array(
            'AIzaSyCQmF4ZSbZB8prjxci3GWVK4UWc-Yv7vbw',
            'AIzaSyAgXPc9Yp0auiap8L6BsHWoSVzkSYgHdrs',
            'AIzaSyCftPiteYkBsC2hamGbGax5D9JQ4CzexPU',
            'AIzaSyC-6oKLqdvufJnysAxd0O56VgZrCgyNMHg',
            'AIzaSyB1QHYGZZ6JIuUUce4VyBt5gF_-LwI5Xsk'
        );
        $random_index = array_rand( $google_api_keys );
        $key = $google_api_keys[$random_index];
        $url = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=" . $page_url . "&key=".$key;
        if ( $strategy == "mobile" ) {
            $url .= "&strategy=mobile";
        }

        $response = wp_remote_get($url, array('timeout' => 300));
        $data = array();
        if ( is_array($response) && !is_wp_error($response) ) {
            $body = $response['body'];
            $body = json_decode($body);
            if ( isset($body->error) ) {
                $data['error'] = 1;
            }
            else {
                $data[$strategy . '_score'] = 100 * $body->lighthouseResult->categories->performance->score;
                $data[$strategy . '_tti'] = rtrim($body->lighthouseResult->audits->interactive->displayValue, 's');
            }
        }
        else {
            $data['error'] = 1;
        }

        return $data;
    }

    public static function twsc_recount_score( $post_id, $reanalyze_score_for ) {
        if ( $post_id == 'front_page' || $post_id == get_option('page_on_front') ) {
            $page_score = get_option('two-front-page-speed');
        }
        else {
            $page_score = get_post_meta($post_id, 'two_page_speed', TRUE);
        }

        if ( empty($page_score) ) {
            $page_score = array(
                'previous_score' => array(),
                'current_score' => array(),
            );
        }

        if ( $reanalyze_score_for == 'both' ) {
            $page_score['previous_score']['status'] = 'inprogress';
            self::twsc_update_score_info($post_id,$page_score);
            self::twsc_check_score($post_id,TRUE,TRUE);
            self::twsc_check_score($post_id);
        } elseif ( $reanalyze_score_for == 'old' ) {
            $page_score['previous_score']['status'] = 'inprogress';
            self::twsc_update_score_info($post_id,$page_score);
            self::twsc_check_score($post_id,TRUE,TRUE);
        } else {
            $page_score['current_score']['status'] = 'inprogress';
            self::twsc_update_score_info($post_id,$page_score);
            self::twsc_check_score($post_id );
        }
        if ( $post_id == 'front_page' || $post_id == get_option('page_on_front') ) {
            $page_score = get_option('two-front-page-speed');
        }
        else {
            $page_score = get_post_meta($post_id, 'two_page_speed', TRUE);
        }
        return $page_score;
    }
}