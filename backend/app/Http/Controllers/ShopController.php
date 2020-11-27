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
		$shop_id = $request->shop_id;

		$user_profile = UserProfile::where('user_id', $user_id)->first();

		if (!$user_profile) {
			return config('error.ERROR_INVALID_DATA');
		}

		$master_shop = MasterShop::GetMasterShopByShopId($shop_id);

		if (is_null($master_shop)) {
			return config('error.ERROR_INVALID_DATA');
		}

		$user_profile->crystal += $master_shop->amount;

		try {
			$user_profile->save();
		} catch (\PDOException $e) {
			return config('error.ERROR_DB_UPDATE');
		}

		$user_profile = UserProfile::where('user_id', $user_id)->first();
		$response = array(
			'user_profile' => $user_profile,
		);

		return json_encode($response);
	}
}
