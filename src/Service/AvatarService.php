<?php

declare(strict_types=1);

namespace App\Service;

use Intervention\Image\Gd\Font;
use Intervention\Image\Gd\Shapes\CircleShape;
use Intervention\Image\ImageManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class AvatarService
{
    private const WIDTH = 100;
    private const HEIGHT = 100;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir
    ) {
    }

    public function make(string $firstName, string $lastName = null): string
    {
        return (new ImageManager())
            ->canvas(self::WIDTH, self::HEIGHT)
            ->circle(self::WIDTH, self::WIDTH / 2, self::WIDTH / 2, $this->circle(...))
            ->text($this->getText($firstName, $lastName), self::WIDTH / 2, self::WIDTH / 2, $this->text(...))
            ->save($this->projectDir.'/public/src/img/'.md5(microtime()).'.jpg', 100)
            ->basename;
    }

    private function circle(CircleShape $draw): void
    {
        $draw->background('#'.str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT));
    }

    private function getText(string $firstName, string $lastName = null): string
    {
        return mb_strtoupper(mb_substr($firstName, 0, 1).mb_substr($lastName ?? '', 0, 1));
    }

    private function text(Font $font): void
    {
        $fontFile = $this->projectDir.'/public/src/webfonts/Inter-Regular.ttf';

        $font->align('center')->valign('middle')->file($fontFile)->size(52)->color('#fff');
    }
}
