<?php
declare(strict_types=1);

namespace App\Models\Meta;

use Corcel\Model\Meta\PostMeta;

class ItemMeta extends PostMeta
{
    /**
     * @inheritDoc
     *
     * @var  string
     */
    protected $table = 'order_itemmeta';

    /**
     * @inheritDoc
     *
     * @var  string[]
     */
    protected $fillable = [
        'order_item_id',
        'meta_key',
        'meta_value',
    ];
}