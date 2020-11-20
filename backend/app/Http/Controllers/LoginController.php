<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\MasterDataService;
use App\UserProfile;
use App\UserLogin;
use App\MasterLoginItem;
use App\UserPresent;

class LoginController extends Controller
{
	public function Login(Request $request)
	{
		$client_master_version = $request->client_master_version;
		$user_id = $request->user_id;

		//マスターデータチェック
		if (!MasterDataService::CheckMasterDataVersion($client_master_version)) {
			return config('error.ERROR_MASTER_DATA_UPDATE');
		}

		//user_profileテーブルのレコードを取得
		$user_profile = UserProfile::where('user_id', $user_id)->first();
		//レコード存在チェック
		if (!$user_profile) {
			return config('error.ERROR_INVALID_DATA');
		}

		//ログインボーナステーブルのレコードを取得
		$user_login = UserLogin::where('user_id', $user_id)->first();
		if (!$user_login) {
			//初期値設定
			$user_login = new UserLogin;
			$user_login->user_id = $user_id;
			$user_login->login_day = 0;
			$last_login_at = date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, 2000));
			$user_login->last_login_at = $last_login_at;
		}

		//日付の比較
		$today = date('Y-m-d');
		$last_login_day = date('Y-m-d', strtotime($user_login->last_login_at));


		$user_present = new UserPresent;
		if (strtotime($today) !== strtotime($last_login_day)) {
			$user_login->login_day += 1;
			$master_login_item = MasterLoginItem::GetMasterLoginItemByLoginDay($user_login->login_day);


			if (!is_null($master_login_item)) {
				//プレゼント作成
				$user_present->user_id = $user_id;
				$user_present->item_type = $master_login_item->item_type;
				$user_present->item_count = $master_login_item->item_count;
				$user_present->description = 'Loginbonus';
				//30日後まで受け取りOK
				$user_present->limited_at = date('Y-m-d', (time() + (60 * 60 * 24 * 30)));
			}
		}

		//ログイン時刻の更新
		$user_login->last_login_at = date("Y-m-d H:i:s");

		//データの書き込み
		try {
			if (isset($user_present->user_id)) {
				$user_present->save();
			}
			$user_login->save();
		} catch (\PDOException $e) {
			return config('error.ERROR_DB_UPDATE');
		}
		//user_presentテーブルからレコード取得
		$user_present_list = UserPresent::where('user_id', $user_id)->get();

		//クライアントへのレスポンス
		$response = array(
			"user_login" => $user_login,
			"user_present" => $user_present_list,
		);

		return json_encode($response);
	}
}