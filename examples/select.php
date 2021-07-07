<?php

require_once __DIR__ . '/../vendor/autoload.php';

use QueryBuilder\Builder;

$pdo = new PDO('sqlite:Chinook_Sqlite.sqlite');
$builder = new Builder($pdo);

// obtiene listado de artistas
$artist = $builder->select('ArtistId', 'Name')
    ->from('Artist')
    ->get();

# print_r($artist);

// utiliza AS para renombrar una columna, además, colocar los nombres en minúsculas también sirve
$artist = $builder->select('artistid as id', 'name')
    ->from('artist')
    ->get();

# print_r($artist);

// cuenta la cantidad de pistas disponibles
$tracks = $builder->count('name as name')->from('track')->get();

# print_r($tracks);

// obtiene la primera pista junto al álbum y su artista
$tracks = $builder->select(
        'Track.Name as track_name',
        'Album.Title as album_title',
        'Artist.Name as artist_name'
    )
    ->from('Track')
    ->join('Album', 'Track.AlbumId = Album.AlbumId')
    ->join('Artist', 'Album.ArtistId = Artist.ArtistId')
    ->first();

# print_r($tracks);

// fija el modo de obtener el objeto (según PDO)
$album = $builder->select('Track.Name')->from('Track')->first(PDO::FETCH_OBJ);

# print_r($album);
