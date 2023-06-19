<?php 
namespace App\Traits;
use Carbon\Carbon;
use Carbon\CarbonImmutable;	
trait TimeZone{
    public function getCreatedAtAttribute($value){
		$date = Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $value, 'UTC');
		$date->setTimezone('Asia/Ho_Chi_Minh');
		$formattedDate = $date->format('Y-m-d H:i:s');
		return $formattedDate;
	}
	public function getUpdatedAtAttribute($value){
		$date = Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $value, 'UTC');
		$date->setTimezone('Asia/Ho_Chi_Minh');
		$formattedDate = $date->format('Y-m-d H:i:s');
		return $formattedDate;
	}
}
?>