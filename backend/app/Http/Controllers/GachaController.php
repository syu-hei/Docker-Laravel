<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Libs\MasterDataService;
use App\UserProfile;
use App\UserCharacter;
use App\MasterGacha;
use App\MasterGachaCharacter;


class GachaController extends Controller
{
	public function DrawGacha(Request $request)
	{
		$client_master_version = $request->client_master_version;
		$user_id = $request->user_id;
		$gacha_id = $request->gacha_id; //1

		//マスターデータチェック
		if (!MasterDataService::CheckMasterDataVersion($client_master_version)) {
			return config('error.ERROR_MASTER_DATA_UPDATE');
		}

		//user_profileテーブルからレコードを取得
		$user_profile = UserProfile::where('user_id', $user_id)->first();

		//レコード存在チェック
		if (!$user_profile) {
			return config('error.ERROR_INVALID_DATA');
		}

		//ガチャマスターデータを取得
		$master_gacha = MasterGacha::GetMasterGachaByGachaId($gacha_id);
		//マスターデータ存在チェック
		if (is_null($master_gacha)) {
			return config('error.ERROR_INVALID_DATA');
		}

		//値の妥当性の検証
		$this->validation($user_profile, $master_gacha);

		//ガチャ商品マスターデータを取得
		$master_gacha_character_list = MasterGachaCharacter::GetMasterGachaCharacterByGachaId($gacha_id);
		//マスターデータ存在チェック
		if (is_null($master_gacha_character_list)) {
			return config('error.ERROR_INVALID_DATA');
		}

		$user_characters = array(); //取得キャラクターのリスト
		for ($i =0; $i < $master_gacha->draw_count; $i++) {
			$character_id = 0;
			$weight_sum = 0;
			foreach ($master_gacha_character_list as $master_gacha_character) {
				//ウエイトの合計値を計算
				$weight_sum += $master_gacha_character->weight;
			}
			//ウエイトの合計値に対して乱数を設定
			$random = mt_rand(1, $weight_sum);
			$sum = 0;
			foreach ($master_gacha_character_list as $master_gacha_character) {
				$sum += $master_gacha_character->weight;
				if ($random <= $sum) {
					//キャラクターIDが決定したらループ終了
					$character_id = $master_gacha_character->character_id;

					break;
				}
			}
			$user_character = new UserCharacter;
			$user_character->user_id = $user_id;
			$user_character->character_id = $character_id;
			array_push($user_characters, $user_character);
		}

		//コストの減算
		if ($master_gacha->cost_type == config('constants.GACHA_COST_TYPE_CRYSTAL')) {
			$user_profile->crystal -= $master_gacha->cost_amount;
		} else if ($master_gacha->cost_type == config('constants.GACHA_COST_TYPE_CRYSTAL_FREE')) {
			if ($master_gacha->cost_amount <= $user_profile->crystal_free) {
				$user_profile->crystal_free -= $master_gacha->cost_amount;
			} else {
				$user_profile->crystal_free = 0;
				$user_profile->crystal -= ($master_gacha->cost_amount - $user_profile->crystal_free);
			}
		} else if ($master_gacha->cost_type == config('constants.GACHA_COST_TYPE_FRIEND_COIN')) {
			$user_profile->friend_coin -= $master_gacha->cost_amount;
		}

		//テーブルへの挿入
		try {
			foreach ($user_characters as $user_character) {
				$user_character->save();
			}
			$user_profile->save();
		} catch (\PDOException $e) {
			return config('error.ERROR_DB_UPDATE');
		}

		//クライアントへのレスポンス
		$user_profile = UserProfile::where('user_id', $user_id)->first();
		$user_character_list = UserCharacter::where('user_id', $user_id)->get();
		$response = array(
			"user_profile" => $user_profile,
			"user_character" => $user_character_list,
			"gacha_result" => $user_characters,
		);

		return json_encode($response);
	}

	private function validation($user_profile, $master_gacha)
	{
		//スケジュールチェック
		if (time() < strtotime($master_gacha->open_at)) {
			return config('error.ERROR_INVALID_SCHEDULE');
		}
		if (strtotime($master_gacha->close_at) < time()) {
			return config('error.ERROR_INVALID_SCHEDULE');
		}

		//所持通貨チェック
		if ($master_gacha->cost_type == config('constants.GACHA_COST_TYPE_CRYSTAL')) {
			if ($user_profile->crystal < $master_gacha->cost_amount) {
				return config('error.ERROR_COST_SHORTAGE');
			}
		} else if ($master_gacha->cost_type == config('constants.GACHA_COST_TYPE_CRYSTAL_FREE')) {
			if ($user_profile->crystal + $user_profile->crystal_free < $master_gacha->cost_amount) {
				return config('error.ERROR_COST_SHORTAGE');
			}
		} else if ($master_gacha->cost_type == config('constants.GACHA_COST_TYPE_FRIEND_COIN')) {
			if ($user_profile->friend_coin < $master_gacha->cost_amount) {
				return config('error.ERROR_COST_SHORTAGE');
			}
		}
	}
}
