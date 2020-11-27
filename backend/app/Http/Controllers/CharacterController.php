<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\MasterDataService;
use App\UserProfile;
use App\UserCharacter;
use App\MasterCharacter;

class CharacterController extends Controller
{
    public function GetCharacterList(Request $request)
	{
		$client_master_version = $request->client_master_version;
		$user_id = $request->user_id;

		if (!MasterDataService::CheckMasterDataVersion($client_master_version)) {
			return config('error.ERROR_MASTER_DATA_UPDATE');
		}

		$user_profile = UserProfile::where('user_id', $user_id)->first();
		if (!$user_profile) {
			return config('error.ERROR_INVALID_DATA');
		}

		$user_character_list = UserCharacter::where('user_id', $user_id)->get();

		$response = array(
			'user_character' => $user_character_list,
		);

		return json_encode($response);
	}

	public function SellCharacter(Request $request)
	{
		$client_master_version = $request->client_master_version;
		$user_id = $request->user_id;
		$id = $request->id;

		if (!MasterDataService::CheckMasterDataVersion($client_master_version)) {
			return config('error.ERROR_MASTER_DATA_UPDATE');
		}

		$user_profile = UserProfile::where('user_id', $user_id)->first();
		if (!$user_profile) {
			return config('error.ERROR_INVALID_DATA');
		}

		$user_character = UserCharacter::where('user_id', $user_id)->where('id', $id)->first();
		if (!$user_character) {
			return config('error.ERROR_INVALID_DATA');
		}

		$master_character = MasterCharacter::GetMasterCharacterByCharacterId($user_character->character_id);
		if (is_null($master_character)) {
			return config('error.ERROR_INVALID_DATA');
		}

		$user_profile->friend_coin += $master_character->sell_point;

		try {
			$user_profile->save();
			$user_character->delete();
		} catch (\PDOException $e) {
			config('error.ERROR_DB_UPDATE');
		}

		$user_profile = UserProfile::where('user_id', $user_id)->first();
		$user_character_list = UserCharacter::where('user_id', $user_id)->get();
		$response = array(
			'user_profile' => $user_profile,
			'user_character' => $user_character_list,
		);

		return json_encode($response);
	}
}
