<?php
namespace App\Http;
/**
 * base64Convert short summary.
 *
 * base64Convert description.
 *
 * @version 1.0
 * @author QJ
 */
class base64Convert
{
    public static function save_base64($base64_string, $output_file)
    {
        $ifp = fopen($output_file, 'w+');
        $data = explode( ',', $base64_string);
        fwrite( $ifp, base64_decode($data[1]));
        fclose( $ifp );
        return true;
    }
    public function getYouTubeVideoId($pageVideUrl) {
        $link = $pageVideUrl;
        $video_id = explode("embed/", $link);
        if (!isset($video_id[1])) {
            $video_id = explode("youtu.be/", $link);
        }
        //$youtubeID = $video_id[1];
        if (empty($video_id[1])) $video_id = explode("/v/", $link);
        $video_id = explode("&", $video_id[1]);
        $youtubeVideoID = $video_id[0];
        if ($youtubeVideoID) {
            return $youtubeVideoID;
        } else {
            return false;
        }
    }
    public function scrapper($video_url,$sermon_id)
    {
        $youtubeID = $this->getYouTubeVideoId($video_url);
        $imageUrl = 'http://img.youtube.com/vi/'.$youtubeID.'/sddefault.jpg';


        $url = $imageUrl;

        $img = $sermon_id.'.png';
        file_put_contents(public_path().'/img/'.$img, file_get_contents($url));
        //file_put_contents('images/sermons/'.$img, file_get_contents($url));

    }
}
