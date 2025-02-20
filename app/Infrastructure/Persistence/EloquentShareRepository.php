<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Models\Share;
use App\Domain\Repositories\ShareRepositoryInterface;
use App\Infrastructure\Models\EloquentShare;
use App\Infrastructure\Mappers\ShareMapper;

class EloquentShareRepository implements ShareRepositoryInterface
{
    public function save(Share $share): Share
    {
        $eloquentShare = new EloquentShare();
        $eloquentShare->user_id = $share->getUserId();
        $eloquentShare->title = $share->getTitle();
        $eloquentShare->description = $share->getDescription();
        $eloquentShare->content_type = $share->getContentType();
        $eloquentShare->content = $share->getContent();
        $eloquentShare->file_url = $share->getFileUrl();
        $eloquentShare->short_code = $share->getShortCode();
        $eloquentShare->save();

        $share->setId($eloquentShare->id);
        return $share;
    }

    public function find(int $id): ?Share
    {
        $eloquentShare = EloquentShare::find($id);
        return $eloquentShare ? ShareMapper::toDomain($eloquentShare) : null;
    }

    public function getAll(): array
    {
        $eloquentShares = EloquentShare::all();
        $shares = [];
        foreach ($eloquentShares as $eShare) {
            $shares[] = ShareMapper::toDomain($eShare);
        }
        return $shares;
    }

    public function delete(int $id): void
    {
        $eloquentShare = EloquentShare::find($id);
        if ($eloquentShare) {
            $eloquentShare->delete();
        }
    }
}
