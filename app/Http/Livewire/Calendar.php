<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\CarbonImmutable;
use App\Services\EventService;

class Calendar extends Component
{
    public $currentDate;
    public $day;
    public $checkDay; // 日付判定用
    public $dayOfWeek; // 曜日
    public $currentWeek;
    public $sevenDaysLater;
    public $events;

    //画面を表示した時の初期表示
    public function mount()
    {
        //クラスの中の変数を指定
        //現在の日付
        $this->currentDate = CarbonImmutable::today();
        $this->sevenDaysLater = $this->currentDate->addDays(7);
        $this->currentWeek = [];

        $this->events = EventService::getWeekEvents(
            $this->currentDate->format('Y-m-d'),
            $this->sevenDaysLater->format('Y-m-d')
        );

        //現在の日付から7日以降分の日付を取得
        for ($i = 0; $i < 7; $i++) {
            $this->day = CarbonImmutable::today()->addDays($i)->format('m月d日');
            $this->checkDay = CarbonImmutable::today()->addDays($i)->format('Y-m-d');
            $this->dayOfWeek = CarbonImmutable::today()->addDays($i)->dayName;
            array_push($this->currentWeek, [ // 連想配列に変更
                'day' => $this->day, // カレンダー表示用 (○月△日)
                'checkDay' => $this->checkDay, // 判定用 (○○○○-△△-□□)
                'dayOfWeek' => $this->dayOfWeek // 曜日
            ]);
        }
    }

    //現在の日付を変更した場合、その変更した日付以降の7日分の日付取得
    public function getDate($date)
    {
        $this->currentDate = $date; //文字列
        $this->currentWeek = [];
        $this->sevenDaysLater = CarbonImmutable::parse($this->currentDate)->addDays(7);

        $this->events = EventService::getWeekEvents(
            $this->currentDate,
            $this->sevenDaysLater->format('Y-m-d')
        );
        for ($i = 0; $i < 7; $i++) {
            $this->day = CarbonImmutable::parse($this->currentDate)->addDays($i)->format('m月d日'); // parseでCarbonインスタンスに変換後 日付を加算
            $this->CheckDay = CarbonImmutable::parse($this->currentDate)->addDays($i)->format('Y-m-d');
            $this->dayOfWeek = CarbonImmutable::parse($this->currentDate)->addDays($i)->dayName;
            array_push($this->currentWeek, [
                'day' => $this->day,
                'checkDay' => $this->CheckDay,
                'dayOfWeek' => $this->dayOfWeek,
            ]);
        }
    }

    // dd($this->currentWeek);
    public function render()
    {
        return view('livewire.calendar');
    }
}
