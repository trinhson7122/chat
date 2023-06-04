<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'avatar',
        'user_id',
    ];

    public function getShortName(): string
    {
        $arr = explode("-", convert_name($this->name));
        $shortName = $arr[0][0] . $arr[count($arr) - 1][0];
        return $shortName;
    }

    public function getGroupWithShortName(): array
    {
        $arr = $this->toArray();
        $arr['short_name'] = $this->getShortName();
        return $arr;
    }
}
