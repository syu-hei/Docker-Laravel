<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\UserProfile;
use App\MasterShop;

class ShopController extends Controller
{
	public function BuyItem(Request $request)
	{
		$user_id = $request->user_id;
		$shop_id = $request->shop_id; //item_120

		//user_profileテーブルのレコードを取得
		$user_profile = UserProfile::where('user_id', $user_id)->first();
		//レコード存在チェック
		if (!$user_profile) {
			return config('error.ERROR_INVALID_DATA');
		}

		$master_shop = MasterShop::GetMasterShopByShopId($shop_id);
		//マスターデータ存在チェック
		if (is_null($master_shop)) {
			return config('error.ERROR_INVALID_DATA');
		}

		$user_profile->crystal += $master_shop->amount;

		//データの書き込み
		try {
			$user_profile->save();
		} catch (\PDOException $e) {
			return config('error.ERROR_DB_UPDATE');
		}

		//クライアントへのレスポンス
		$user_profile = UserProfile::where('user_id', $user_id)->first();
		$response = array(
			'user_profile' => $user_profile,
		);

		return json_encode($response);
	}
}
