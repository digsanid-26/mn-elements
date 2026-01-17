<?php
namespace MN_Elements\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MN YouTube API Helper
 *
 * Helper class for YouTube Data API v3 integration
 *
 * @since 1.0.0
 */
class MN_YouTube_API {

	/**
	 * YouTube API base URL
	 */
	const API_BASE_URL = 'https://www.googleapis.com/youtube/v3/';

	/**
	 * Get videos from YouTube playlist
	 *
	 * @param string $playlist_id YouTube playlist ID
	 * @param string $api_key YouTube API key
	 * @param int $max_results Maximum number of videos to return (1-50)
	 * @param string $orderby Order by parameter (default, title, title_desc, published, published_desc)
	 * @return array|WP_Error Array of video data or WP_Error on failure
	 */
	public static function get_playlist_videos( $playlist_id, $api_key, $max_results = 10, $orderby = 'default' ) {
		if ( empty( $playlist_id ) || empty( $api_key ) ) {
			return new \WP_Error( 'missing_params', __( 'Playlist ID and API Key are required.', 'mn-elements' ) );
		}

		// Add a timestamp to force cache refresh when ordering changes
		// This ensures that when user changes ordering, they get fresh data immediately
		$cache_version = get_option( 'mn_youtube_playlist_cache_version_' . $playlist_id, 1 );
		
		// Check cache first - include orderby and version in cache key
		$cache_key = 'mn_youtube_playlist_' . md5( $playlist_id . $max_results . $orderby . $cache_version );
		$cached_data = get_transient( $cache_key );
		
		if ( false !== $cached_data ) {
			return $cached_data;
		}

		// For non-default ordering, we need to fetch ALL videos first, then sort, then limit
		// For default ordering, we can just fetch the requested amount
		$fetch_all = ( $orderby !== 'default' );
		$all_items = [];
		$page_token = '';
		$max_pages = $fetch_all ? 10 : 1; // Limit to 500 videos max (10 pages x 50)
		$page_count = 0;

		do {
			// Fetch from YouTube API
			$url = self::API_BASE_URL . 'playlistItems';
			$args = [
				'part' => 'snippet,contentDetails',
				'playlistId' => $playlist_id,
				'maxResults' => $fetch_all ? 50 : min( $max_results, 50 ),
				'key' => $api_key,
			];

			if ( ! empty( $page_token ) ) {
				$args['pageToken'] = $page_token;
			}

			$response = wp_remote_get( add_query_arg( $args, $url ) );

			if ( is_wp_error( $response ) ) {
				return $response;
			}

			$body = wp_remote_retrieve_body( $response );
			$data = json_decode( $body, true );

			if ( isset( $data['error'] ) ) {
				return new \WP_Error( 
					'api_error', 
					isset( $data['error']['message'] ) ? $data['error']['message'] : __( 'YouTube API error', 'mn-elements' )
				);
			}

			if ( isset( $data['items'] ) && ! empty( $data['items'] ) ) {
				$all_items = array_merge( $all_items, $data['items'] );
			}

			// Get next page token for pagination
			$page_token = isset( $data['nextPageToken'] ) ? $data['nextPageToken'] : '';
			$page_count++;

		} while ( $fetch_all && ! empty( $page_token ) && $page_count < $max_pages );

		if ( empty( $all_items ) ) {
			return new \WP_Error( 'no_videos', __( 'No videos found in playlist.', 'mn-elements' ) );
		}

		// Get video IDs for detailed info
		$video_ids = [];
		foreach ( $all_items as $item ) {
			if ( isset( $item['contentDetails']['videoId'] ) ) {
				$video_ids[] = $item['contentDetails']['videoId'];
			}
		}

		// Get video details (duration, etc.) - batch in groups of 50
		$video_details = [];
		$id_chunks = array_chunk( $video_ids, 50 );
		foreach ( $id_chunks as $chunk ) {
			$chunk_details = self::get_video_details( $chunk, $api_key );
			$video_details = array_merge( $video_details, $chunk_details );
		}

		// Format video data with additional sorting info
		$videos = [];
		foreach ( $all_items as $item ) {
			$video_id = $item['contentDetails']['videoId'];
			$snippet = $item['snippet'];

			$video_data = [
				'video_title' => $snippet['title'],
				'youtube_url' => [
					'url' => 'https://www.youtube.com/watch?v=' . $video_id,
				],
				'video_description' => $snippet['description'],
				'video_duration' => isset( $video_details[ $video_id ] ) ? $video_details[ $video_id ]['duration'] : '',
				'thumbnail' => isset( $snippet['thumbnails']['medium']['url'] ) ? $snippet['thumbnails']['medium']['url'] : '',
				'published_at' => isset( $snippet['publishedAt'] ) ? $snippet['publishedAt'] : '',
			];

			$videos[] = $video_data;
		}

		// Apply sorting if not default
		if ( $orderby !== 'default' ) {
			$videos = self::sort_playlist_videos( $videos, $orderby );
		}

		// Limit results to requested amount
		$videos = array_slice( $videos, 0, $max_results );

		// Cache for 1 hour by default
		set_transient( $cache_key, $videos, HOUR_IN_SECONDS );

		return $videos;
	}

	/**
	 * Get videos from YouTube channel (uploads playlist)
	 *
	 * @param string $channel_id YouTube channel ID
	 * @param string $api_key YouTube API key
	 * @param int $max_results Maximum number of videos to fetch (1-50)
	 * @return array|WP_Error Array of video data or WP_Error on failure
	 */
	public static function get_channel_videos( $channel_id, $api_key, $max_results = 10 ) {
		if ( empty( $channel_id ) || empty( $api_key ) ) {
			return new \WP_Error( 'missing_params', __( 'Channel ID and API Key are required.', 'mn-elements' ) );
		}

		// Check cache first
		$cache_key = 'mn_youtube_channel_' . md5( $channel_id . $max_results );
		$cached_data = get_transient( $cache_key );
		
		if ( false !== $cached_data ) {
			return $cached_data;
		}

		// Get channel's uploads playlist ID
		$url = self::API_BASE_URL . 'channels';
		$args = [
			'part' => 'contentDetails',
			'id' => $channel_id,
			'key' => $api_key,
		];

		$response = wp_remote_get( add_query_arg( $args, $url ) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( isset( $data['error'] ) ) {
			return new \WP_Error( 
				'api_error', 
				isset( $data['error']['message'] ) ? $data['error']['message'] : __( 'YouTube API error', 'mn-elements' )
			);
		}

		if ( ! isset( $data['items'][0]['contentDetails']['relatedPlaylists']['uploads'] ) ) {
			return new \WP_Error( 'no_uploads', __( 'Could not find channel uploads playlist.', 'mn-elements' ) );
		}

		$uploads_playlist_id = $data['items'][0]['contentDetails']['relatedPlaylists']['uploads'];

		// Get videos from uploads playlist
		return self::get_playlist_videos( $uploads_playlist_id, $api_key, $max_results );
	}

	/**
	 * Get video details (duration, etc.)
	 *
	 * @param array $video_ids Array of video IDs
	 * @param string $api_key YouTube API key
	 * @return array Array of video details indexed by video ID
	 */
	public static function get_video_details( $video_ids, $api_key ) {
		if ( empty( $video_ids ) || empty( $api_key ) ) {
			return [];
		}

		$url = self::API_BASE_URL . 'videos';
		$args = [
			'part' => 'contentDetails',
			'id' => implode( ',', $video_ids ),
			'key' => $api_key,
		];

		$response = wp_remote_get( add_query_arg( $args, $url ) );

		if ( is_wp_error( $response ) ) {
			return [];
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( ! isset( $data['items'] ) ) {
			return [];
		}

		$details = [];
		foreach ( $data['items'] as $item ) {
			$video_id = $item['id'];
			$duration = isset( $item['contentDetails']['duration'] ) ? $item['contentDetails']['duration'] : '';
			
			$details[ $video_id ] = [
				'duration' => self::format_duration( $duration ),
			];
		}

		return $details;
	}

	/**
	 * Format ISO 8601 duration to readable format (MM:SS or HH:MM:SS)
	 *
	 * @param string $duration ISO 8601 duration (e.g., PT4M13S)
	 * @return string Formatted duration
	 */
	private static function format_duration( $duration ) {
		if ( empty( $duration ) ) {
			return '00:00';
		}

		// Parse ISO 8601 duration
		preg_match( '/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/', $duration, $matches );

		$hours = isset( $matches[1] ) ? (int) $matches[1] : 0;
		$minutes = isset( $matches[2] ) ? (int) $matches[2] : 0;
		$seconds = isset( $matches[3] ) ? (int) $matches[3] : 0;

		if ( $hours > 0 ) {
			return sprintf( '%02d:%02d:%02d', $hours, $minutes, $seconds );
		} else {
			return sprintf( '%02d:%02d', $minutes, $seconds );
		}
	}

	/**
	 * Validate YouTube API key
	 *
	 * @param string $api_key YouTube API key
	 * @return bool|WP_Error True if valid, WP_Error on failure
	 */
	public static function validate_api_key( $api_key ) {
		if ( empty( $api_key ) ) {
			return new \WP_Error( 'empty_key', __( 'API Key is required.', 'mn-elements' ) );
		}

		// Test API key with a simple request
		$url = self::API_BASE_URL . 'videos';
		$args = [
			'part' => 'id',
			'id' => 'dQw4w9WgXcQ', // Test video ID
			'key' => $api_key,
		];

		$response = wp_remote_get( add_query_arg( $args, $url ) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( isset( $data['error'] ) ) {
			return new \WP_Error( 
				'invalid_key', 
				isset( $data['error']['message'] ) ? $data['error']['message'] : __( 'Invalid API Key', 'mn-elements' )
			);
		}

		return true;
	}

	/**
	 * Clear cached data for a specific playlist or channel
	 *
	 * @param string $id Playlist or channel ID
	 * @param string $type 'playlist' or 'channel'
	 * @return bool True on success
	 */
	public static function clear_cache( $id, $type = 'playlist' ) {
		$prefix = $type === 'channel' ? 'mn_youtube_channel_' : 'mn_youtube_playlist_';
		
		// Increment cache version to force refresh of all cache keys
		if ( $type === 'playlist' ) {
			$version_key = 'mn_youtube_playlist_cache_version_' . $id;
			$current_version = get_option( $version_key, 1 );
			update_option( $version_key, $current_version + 1 );
		}
		
		// We need to clear all possible cache keys for this ID
		// Since we don't know the max_results or orderby used, we'll clear common values
		$common_limits = [ 5, 10, 15, 20, 25, 30, 40, 50 ];
		$orderby_options = [ 'default', 'title', 'title_desc', 'published', 'published_desc' ];
		
		foreach ( $common_limits as $limit ) {
			foreach ( $orderby_options as $orderby ) {
				// Clear old cache keys (without version)
				$cache_key = $prefix . md5( $id . $limit . $orderby );
				delete_transient( $cache_key );
				
				// Also clear versioned cache keys
				for ( $v = 1; $v <= 10; $v++ ) {
					$versioned_key = $prefix . md5( $id . $limit . $orderby . $v );
					delete_transient( $versioned_key );
				}
			}
		}

		return true;
	}

	/**
	 * Sort playlist videos by specified criteria
	 *
	 * @param array $videos Array of video data
	 * @param string $orderby Sort order (title, title_desc, published, published_desc)
	 * @return array Sorted videos
	 */
	private static function sort_playlist_videos( $videos, $orderby ) {
		if ( empty( $videos ) || $orderby === 'default' ) {
			return $videos;
		}

		usort( $videos, function( $a, $b ) use ( $orderby ) {
			switch ( $orderby ) {
				case 'title':
					// Sort by title A-Z
					return strcasecmp( $a['video_title'], $b['video_title'] );
				
				case 'title_desc':
					// Sort by title Z-A
					return strcasecmp( $b['video_title'], $a['video_title'] );
				
				case 'published':
					// Sort by published date (newest first)
					$a_time = ! empty( $a['published_at'] ) ? strtotime( $a['published_at'] ) : 0;
					$b_time = ! empty( $b['published_at'] ) ? strtotime( $b['published_at'] ) : 0;
					return $b_time - $a_time;
				
				case 'published_desc':
					// Sort by published date (oldest first)
					$a_time = ! empty( $a['published_at'] ) ? strtotime( $a['published_at'] ) : 0;
					$b_time = ! empty( $b['published_at'] ) ? strtotime( $b['published_at'] ) : 0;
					return $a_time - $b_time;
				
				default:
					return 0;
			}
		} );

		return $videos;
	}

	/**
	 * Get cache duration options
	 *
	 * @return array Cache duration options
	 */
	public static function get_cache_duration_options() {
		return [
			'3600' => __( '1 Hour', 'mn-elements' ),
			'21600' => __( '6 Hours', 'mn-elements' ),
			'43200' => __( '12 Hours', 'mn-elements' ),
			'86400' => __( '24 Hours', 'mn-elements' ),
		];
	}
}
