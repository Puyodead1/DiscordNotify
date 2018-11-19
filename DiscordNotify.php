<?php

global $global;
require_once $global['systemRootPath'] . 'plugin/Plugin.abstract.php';

class DiscordNotify extends PluginAbstract
{
    
    public function getDescription()
    {
        return "Send video upload notifications to discord webhook";
    }
    
    public function getName()
    {
        return "DiscordNotify";
    }
    
    public function getUUID()
    {
        return "cf145581-7d5e-4bb6-8c12-848a19j1564g";
    }
    public function getTags()
    {
        return array(
            'free',
            'notify',
            'webhook'
        );
    }
   
    public function afterNewVideo($videos_id)
    {
		$users_id = Video::getOwner($videos_id);
		$user = new User($users_id);
		$username = $user->getNameIdentification();
		$channelName = $user->getChannelName();
		$video = new Video("","",$videos_id);
		$videoName = $video->getTitle();
		$images = Video::getImageFromFilename($video->getFilename());
		$videoThumbs = $images->thumbsJpg;
                $videoLink = Video::getPermaLink($videos_id);
		$videoDuration = $video->getDuration();
		$videoDescription = $video->getDescription();
		$url = "https://ptb.discordapp.com/api/webhooks/506907055442100230/DxI5fhYz0KcvRfN354mg87rbHCcT2Uzg9vF6mU-Kpad_KZsukNyW65VfbSD4RLSFIi9L";

$hookObject = json_encode([
    /*
     * The general "message" shown above your embeds
     */
    "content" => "",
    /*
     * The username shown in the message
     */
    "username" => "PuyoTube",
    /*
     * The image location for the senders image
     */
    "avatar_url" => "https://puyodead1-development.me/view/img/favicon.png",
    /*
     * Whether or not to read the message in Text-to-speech
     */
    "tts" => false,
    /*
     * File contents to send to upload a file
     */
    // "file" => "",
    /*
     * An array of Embeds
     */
    "embeds" => [
        /*
         * Our first embed
         */
        [
            // Set the title for your embed
            "title" => $username . " just uploaded a video",

            // The type of your embed, will ALWAYS be "rich"
            "type" => "rich",

            // A description for your embed
            "description" => "",

            // The URL of where your title will be a link to
            "url" => "https://puyodead1-development.me/" . $channelName,

            /* A timestamp to be displayed below the embed, IE for when an an article was posted
             * This must be formatted as ISO8601
             */
            "timestamp" => "",

            // The integer color to be used on the left side of the embed
            "color" => hexdec( "FF0000" ),

            // Footer object
            "footer" => [
                "text" => "PuyoTube",
                "icon_url" => "https://puyodead1-development.me/videos/userPhoto/logo.png"
            ],

            "image" => [
                "url" => $videoThumbs,
            ],

            //"thumbnail" => [
             //   "url" => $userThumbnail
            //],

            // Field array of objects
            "fields" => [
                // Field 1
                [
                    "name" => "Video Name",
                    "value" => $videoName,
                    "inline" => true
                ],
                // Field 2
                [
                    "name" => "Video Link",
                    "value" => $videoLink,
                    "inline" => true
                ],
                // Field 3
                [
                    "name" => "Video Duration",
                    "value" => $videoDuration,
                    "inline" => true
                ],
				[
                    "name" => "Video Description",
                    "value" => "N/A",
                    "inline" => true
                ]
            ]
        ]
    ]

], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

$ch = curl_init();

curl_setopt_array( $ch, [
    CURLOPT_URL => $url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $hookObject,
    CURLOPT_HTTPHEADER => [
        "Length" => strlen( $hookObject ),
        "Content-Type" => "application/json"
    ]
]);

return curl_exec( $ch );
    }
}
