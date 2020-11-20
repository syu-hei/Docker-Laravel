<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class MasterGachaCharacter extends Model
{
	protected $table = 'master_gacha_character';
	protected $primaryKey = 'gacha_id';


	public static function GetMasterGachaCharacterByGachaId($gacha_id)
	{
		$master_data_list = MasterDataService::GetMasterData('master_gacha_character');

		$master_gacha_character_list = array();
		foreach ($master_data_list as $master_data) {
			if ($gacha_id == $master_data['gacha_id']) {
				$master_gacha_character = new MasterGachaCharacter;
				$master_gacha_character->gacha_id = $master_data['gacha_id'];
				$master_gacha_character->character_id = $master_data['character_id'];
				$master_gacha_character->weight = $master_data['weight'];
				array_push($master_gacha_character_list, $master_gacha_character);
			}
		}

		return $master_gacha_character_list;
	}
}
