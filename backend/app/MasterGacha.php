<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class MasterGacha extends Model
{
	protected $table = 'master_gacha';
	protected $primaryKey = 'gacha_id';

	public static function GetMasterGacha()
	{
		$master_data_list = MasterDataService::GetMasterData('master_gacha');
		return $master_data_list;
	}

	public static function GetMasterGachaByGachaId($gacha_id)
	{
		$master_data_list = self::GetMasterGacha();
		foreach ($master_data_list as $master_data) {
			$master_gacha = new MasterGacha;
			$master_gacha->gacha_id = $master_data['gacha_id'];
			$master_gacha->banner_id = $master_data['banner_id'];
			$master_gacha->cost_type = $master_data['cost_type'];
			$master_gacha->cost_amount = $master_data['cost_amount'];
			$master_gacha->draw_count = $master_data['draw_count'];
			$master_gacha->open_at = $master_data['open_at'];
			$master_gacha->close_at = $master_data['close_at'];
			$master_gacha->description = $master_data['description'];
			if ($gacha_id == $master_gacha->gacha_id) {
				return $master_gacha;
			}
		}

		return null;
	}
}
