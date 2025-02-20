<?php

namespace App\Infrastructure\Mappers;

use App\Domain\Models\Share;
use App\Infrastructure\Models\EloquentShare;

class ShareMapper
{
    public static function toDomain(EloquentShare $eloquentShare): Share
    {
        $share = new Share(
            $eloquentShare->user_id,
            $eloquentShare->title,
            $eloquentShare->description,
            $eloquentShare->content_type,
            $eloquentShare->content,
            $eloquentShare->file_url,
            $eloquentShare->short_code
        );
        $share->setId($eloquentShare->id);
        $share->setCreatedAt(new \DateTime($eloquentShare->created_at));
        $share->setUpdatedAt(new \DateTime($eloquentShare->updated_at));
        return $share;
    }
}
