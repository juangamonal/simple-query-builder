<?php

require_once __DIR__ . '/../vendor/autoload.php';

use QueryBuilder\Builder;

$pdo = new PDO('sqlite:Chinook_Sqlite.sqlite');
$builder = new Builder($pdo);

// ...
$tracks = $builder->select(
        'Track.Name as track_name',
        'Album.Title as album_title',
        'Artist.Name as artist_name'
    )
    ->from('Track')
    ->join('Album', 'Track.AlbumId = Album.AlbumId')
    ->join('Artist', 'Album.ArtistId = Artist.ArtistId')
    ->where()
    ->execute();

print_r($tracks);
