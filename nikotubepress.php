<?php 
 /* 
 Plugin Name: nikotube-press
 Plugin URI: http://blog.uklab.jp/web/wordpress-youtube-nicovideo/
 Description: This plugin append video tag of Yourube and nikoniko video
 Author: uklab
 Version: 0.1 
 Author URI: http://blog.uklab.jp/
 */ 

function get_video_tag($video_option , $video = null)
{
	$width = null;
	$height = null;
	$vq = null;
	$rel = null;
	$video_tag = null;
	$res = array();
	if(!empty($video))
	{
		extract(shortcode_atts(array(
			'width' => 682,
			'height' => 384,
			'vq' => 360,
			'rel' => 0
		), $video_option));

		if($width < 200)
		{
			$width = 200;
		}
		if($height < 200)
		{
			$height = 200;
		}
		if(!in_array($vq,array(240,360,480,720,1080)))
		{
			$vq = 360;
		}
		switch($vq)
		{
			// 240p
			case '240':
				$vq = 'small';
			break;
			// 360p
			case '360':
				$vq = 'medium';
			break;
			// 480p
			case '480':
				$vq = 'large';
			break;
			// HD720p
			case '720':
				$vq = 'hd720';
			break;
			// HD1080p
			case '1080':
				$vq = 'hd1080';
			break;
		}
		if($rel != 0 && $rel != 1)
		{
			$rel = 0;
		}

		$nico_pattern = '/watch\/(sm[\d]*)/';
		$youtube_pattern = '/v=([\w-]*)/';
		
		if(preg_match($nico_pattern, $video,$res))
		{
			$video_info = simplexml_load_file('http://ext.nicovideo.jp/api/getthumbinfo/'.$res[1]);
			$title = $video_info->thumb->title;
			$video_tag = '<script type="text/javascript" src="http://ext.nicovideo.jp/thumb_watch/'.$res[1].'?w='.$width.'&h='.$height.'"></script><noscript><a href="http://www.nicovideo.jp/watch/'.$res[1].'">'.$title.'</a></noscript>';
		}
		elseif(preg_match($youtube_pattern,$video,$res))
		{
			$video_tag = '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$res[1].'?wmode=transparent&vq='.$vq.'&rel='.$rel.'" frameborder="0" allowfullscreen></iframe>';
		}
		else
		{
			$video_tag = '<iframe width="'.$width.'" height="'.$height.'" src="'.$video.'" frameborder="0" allowfullscreen></iframe>';
		}

		return $video_tag;
	}
	else
	{
		return null;
	}
}
add_shortcode('video', 'get_video_tag');
?>
