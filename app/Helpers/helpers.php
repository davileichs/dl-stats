<?php

/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('time2string')) {
    function time2string(string $time, array $fields =['d','h', 'm', 's']): string
    {
        $d = floor($time/86400);
        $d = str_pad($d, 2, '0', STR_PAD_LEFT);

        $h = floor(($time-$d*86400)/3600);
        $h= str_pad($h, 2, '0', STR_PAD_LEFT);

        $m = floor(($time-($d*86400+$h*3600))/60);
        $m = str_pad($m, 2, '0', STR_PAD_LEFT);

        $s = $time-($d*86400+$h*3600+$m*60);
        $s = str_pad($s, 2, '0', STR_PAD_LEFT);

        $time_str = '';
        foreach($fields as $field) {
            $time_str .= $$field . ''.$field .' ' ;
        }

        return $time_str;
    }
}

if (! function_exists('timeago2string')) {
    function timeago2string(string $time, int $parts = 2): string
    {

        $start = \Carbon\Carbon::createFromTimestamp($time);
        $end = \Carbon\Carbon::now();
        return str_replace('before', 'ago', $start->diffForHumans($end, [
            'parts' => $parts,
            'join' => true, // join with natural syntax as per current locale
        ]));

    }
}

if (! function_exists('getSteamProfileId')) {
    function getSteamProfileId(string $steamId): string
    {
        $aux = explode(':', $steamId);
        return $aux[0] + '76561197960265728' + ($aux[1]*2);

    }
}

if (! function_exists('getSteamAvatar')) {
    function getSteamAvatar(string $steamId): string
       {
        $steamUrl = "http://steamcommunity.com/profiles/".$steamId;

        $page = Http::get($steamUrl);
        $html = $page->body();

        preg_match('/\<link rel=\"image_src\" href=\"(.*)\"\>/', $html, $match);

        if(!empty($match[1])) {
            return $match[1];
       } else {
            return '/images/unknown.jpg';
       }
    }
}

if (! function_exists('getDaysLastMonth')) {
    function getDaysLastMonth(): array
    {
        $days = 30;
        for($i=0;$i<30;$i++) {
            $key = \Carbon\Carbon::now()->subDays($i)->toDateString();
            $dateKey = substr($key,-5);
            $inverse = explode('-', $dateKey);
            $newOrder = implode('-', array_reverse($inverse));
            $date[] = $newOrder;
        }

        return array_reverse($date);
    }
}

if (! function_exists('slug')) {
    function slug(string $text, $divider = '-'): string
    {
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $divider);
        $text = preg_replace('~-+~', $divider, $text);
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}

if (! function_exists('percent')) {
    function percent(int $value, int $total, string $class = 'round'): int
    {
        if ($total == 0) return 0;
        $result = $class(($value / $total) * 100);
        return $result;
    }
}

if (! function_exists('percent_inverse')) {
    function percent_inverse(int $value, int $total, string $class = 'round'): int
    {
        return (100 - percent($value, $total));
    }
}
