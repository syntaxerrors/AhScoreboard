<?php

class HomeController extends Core_HomeController {

    public function getIndex()
    {
        $developer = $this->hasPermission('DEVELOPER');

        $videos = Video::orderBy('date', 'desc')->paginate(10);

        // foreach ($videos as $video) {
        //     if (!File::exists(public_path() .'/img/youtube/'. $video->id .'.jpg')) {
        //         try {
        //             Image::make('http://img.youtube.com/vi/'. $video->link .'/hqdefault.jpg')->save(public_path() .'/img/youtube/'. $video->id .'.jpg');
        //         } catch (Exception $e) {
        //             continue;
        //         }
        //     }
        // }

        $this->setViewData('videos', $videos);
        $this->setViewData('developer', $developer);
    }

    public function getYoutube($id, $timestamp, $end)
    {
        $this->setViewData('id', $id);
        $this->setViewData('timestamp', $timestamp);
        $this->setViewData('end', $end);
    }

    public function getScores($videoId)
    {
        $video = Video::find($videoId);

        $this->setViewData('video', $video);
    }
}