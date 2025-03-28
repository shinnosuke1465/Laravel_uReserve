<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use App\Models\User;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'information',
        'max_people',
        'start_date',
        'end_date',
        'is_visible'
    ];

    //イベント日時
    protected function eventDate(): Attribute
    {
        return new Attribute(
            //eventモデルのstart_dateの内容を年月日の形で表示する
            get: fn() => Carbon::parse($this->start_date)->format('Y年m月d日')
        );
    }

    //edit用のイベント日時
    protected function editEventDate(): Attribute
    {
        return new Attribute(
            //eventモデルのstart_dateの内容を年月日の形で表示する
            get: fn() => Carbon::parse($this->start_date)->format('Y-m-d')
        );
    }
    //イベント開始時間
    protected function startTime(): Attribute
    {
        return new Attribute(
            get: fn() => Carbon::parse($this->start_date)->format('H時i分')
        );
    }

    //イベント終了時間
    protected function endTime(): Attribute
    {
        return new Attribute(
            //eventモデルのstart_dateの内容を年月日の形で表示する
            get: fn() => Carbon::parse($this->end_date)->format('H時i分')
        );
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'reservations')
            ->withPivot('id', 'number_of_people', 'canceled_date');
    }
}
