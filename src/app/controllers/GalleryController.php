<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Paginator;
use App\Models\GalleryImage;

class GalleryController extends Controller
{
    private const PER_PAGE = 24;
    private const ALBUM_MAX_LENGTH = 100;

    public function index(): void
    {
        $galleryImages = new GalleryImage();
        $albumQuery = $this->query('album');
        $album = is_string($albumQuery) ? mb_substr(trim($albumQuery), 0, self::ALBUM_MAX_LENGTH) : '';
        $selectedAlbum = $album !== '' ? $album : null;
        $pageQuery = $this->query('page', '1');
        $requestedPage = is_string($pageQuery) ? (int) $pageQuery : 1;

        $pager = Paginator::make($galleryImages->countActiveByAlbum($selectedAlbum), $requestedPage, self::PER_PAGE);

        $this->view('public/gallery', [
            'title' => 'Đời sống học sinh',
            'images' => $galleryImages->activeByAlbum($selectedAlbum, $pager['limit'], $pager['offset']),
            'albums' => $this->albumOptions($galleryImages->albums(true)),
            'selectedAlbum' => $selectedAlbum,
            'pager' => $pager,
            'paginationPath' => '/hinh-anh',
        ]);
    }

    private function albumOptions(array $albums): array
    {
        $options = [];
        foreach ($albums as $album) {
            $options[$album] = GalleryImage::albumLabel($album);
        }

        return $options;
    }
}
