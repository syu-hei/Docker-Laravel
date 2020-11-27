<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\MasterDataService;
use App\UserProfile;
use App\UserQuest;
use App\MasterQuest;

class QuestController extends Controller
{
	public function Tutorial(Request $request)
	{
		$user_id = $request->user_id;

		$user_profile = UserProfile::where('user_id', $user_id)->first();

		if (!$user_profile) {
			return config('error.ERROR_INVALID_DATA');
		}

		if (config('constants.TUTORIAL_QUEST') <= $user_profile->tutorial_progress) {
			return config('error.ERROR_INVALID_DATA');
		}

		$user_profile->tutorial_progress = config('constants.TUTORIAL_QUEST');

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
	public function Start(Request $request)
	{
		$client_master_version = $request->client_master_version;
		$user_id = $request->user_id;
		$quest_id = $request->quest_id;

		if (!MasterDataService::CheckMasterDataVersion($client_master_version)) {
			return config('error.ERROR_MASTER_DATA_UPDATE');
		}

		$user_profile = UserProfile::where('user_id', $user_id)->first();

		if (!$user_profile) {
			return config('error.ERROR_INVALID_DATA');
		}

		$master_quest = MasterQuest::GetMasterQuestByQuestId($quest_id);
		if (is_null($master_quest)) {
			return config('error.ERROR_INVALID_DATA');
		}

		if (time() < strtotime($master_quest->open_at)) {
			return config('error.ERROR_INVALID_SCHEDULE');
		}
		if (strtotime($master_quest->close_at) < time()) {
			return config('error.ERROR_INVALID_SCHEDULE');
		}

		$user_quest = UserQuest::where('user_id', $user_id)->where('quest_id', $quest_id)->first();
		if (!$user_quest) {
			$user_quest = new UserQuest;
			$user_quest->user_id = $user_id;
			$user_quest->quest_id = $quest_id;
			$user_quest->status = config('constants.QUEST_START');
		}

		try {
			$user_quest->save();
		} catch (\PDOException $e) {
			return config('error.ERROR_DB_UPDATE');
		}

		$user_quest_list = UserQuest::where('user_id', $user_id)->get();
		$response = array(
			'user_quest' => $user_quest_list,
		);

		return json_encode($response);
	}

	public function End(Request $request)
	{
		$user_id = $request->user_id;
		$quest_id = (int)$request->quest_id; //1
		$score = (int)$request->score; //654321
		$clear_time = (int)$request->clear_time; //150

		$master_quest = MasterQuest::GetMasterQuestByQuestId($quest_id);
		if (is_null($master_quest)) {
			return config('error.ERROR_INVALID_DATA');
		}

		if (time() < strtotime($master_quest->open_at)) {
			return config('error.ERROR_INVALID_SCHEDULE');
		}
		if (strtotime($master_quest->close_at) < time()) {
			return config('error.ERROR_INVALID_SCHEDULE');
		}

		if ($score <= 0 || 1000000 < $score) {
			return config('error.ERROR_INVALID_DATA');
		}
		if ($clear_time <= 10 || 1000000 < $clear_time) {
			return config('error.ERROR_INVALID_DATA');
		}

		$user_quest = UserQuest::where('user_id', $user_id)->where('quest_id', $quest_id)->first();
		if (!$user_quest) {
			return config('error.ERROR_INVALID_DATA');
		}

		$user_profile = UserProfile::where('user_id', $user_id)->first();
		if (!$user_profile) {
			return config('error.ERROR_INVALID_DATA');
		}

		if ($user_quest->status != config('constants.QUEST_CLEAR')) {
			switch ($master_quest->item_type) {
				case config('constants.ITEM_TYPE_CRYSTAL'):
					$user_profile->crystal += $master_quest->item_count;
					break;
				case config('constants.ITEM_TYPE_CRYSTAL_FREE'):
					$user_profile->crystal_free += $master_quest->item_count;
					break;
				case config('constants.ITEM_TYPE_FRIEND_COIN'):
					$user_profile->friend_coin += $master_quest->item_count;
					break;
				default:
					break;
			}
		}

		$user_quest->status = config('constants.QUEST_CLEAR');
		$user_quest->score = $score;
		$user_quest->clear_time = $clear_time;

		try {
			$user_profile->save();
			$user_quest->save();
		} catch (\PDOException $e) {
			return config('error.ERROR_DB_UPDATE');
		}

		$user_quest_list = UserQuest::where('user_id', $user_id)->get();
		$response = array(
			'user_profile' => $user_profile,
			'user_quest' => $user_quest_list,
		);

		return json_encode($response);
	}
}
