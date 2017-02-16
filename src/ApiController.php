<?php

namespace Cowaboo\Api;
use App\Http\Controllers\Controller;
use Auth;
use Cowaboo\Models\Dictionary;
use Cowaboo\Models\Entry;
use Exception;
use Illuminate\Http\Request;
use IPFS;
use Response;
use Stellar;
use TagList;
use UserList;

const ERROR_CODE = 500;
const NO_PARAM_CODE = 403;
const UNKNOWN_USER_CODE = 401;
const ALREADY_REGISTERED_CODE = 409;
const UNKNOWN_OBSERVATORY_CODE = 404;

class ApiController extends Controller {
	/**
	 * @param Request $request
	 */
	public function addConfigurationToEntry(Request $request) {
		$input = $request->all();
		if (!isset($input['secretKey']) || !$input['secretKey']) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}
		$secretKey = $input['secretKey'];

		if (!Auth::attempt(['secretKey' => $secretKey], true)) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}

		if (!isset($input['observatoryId']) || !$input['observatoryId']) {
			return self::json("unknown observatory", UNKNOWN_OBSERVATORY_CODE);
		}
		$dictionary = Dictionary::getCurrentFromId($input['observatoryId']);
		if (!$dictionary) {
			return self::json("unknown observatory", UNKNOWN_OBSERVATORY_CODE);
		}

		if (!isset($input['hash']) || !$input['hash']) {
			return self::json("Entrée inconnue", UNKNOWN_OBSERVATORY_CODE);
		}
		$oldEntry = Entry::createFromHash($input['hash']);
		if (!$oldEntry) {
			return self::json("Entrée inconnue", UNKNOWN_OBSERVATORY_CODE);
		}

		if (!isset($input['metadata_name']) || !$input['metadata_name']) {
			return self::json("No metadata name", ERROR_CODE);
		}
		$name = $input['metadata_name'];
		$value = isset($input['metadata_value']) ? $input['metadata_value'] : false;

		$newEntry = $oldEntry->createNewVersion();
		$newEntry->addConf($name, $value);
		$newHash = $newEntry->save();

		$dictionary = $dictionary->createNewVersion();
		$dictionary->removeEntry($oldEntry);
		$dictionary->addEntry($newEntry);
		$dictionary->save();

		return self::json(($newHash));
	}

	/**
	 * @param Request $request
	 */
	public function addConfigurationToObservatory(Request $request) {
		$input = $request->all();
		if (!isset($input['secretKey']) || !$input['secretKey']) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}
		$secretKey = $input['secretKey'];

		if (!Auth::attempt(['secretKey' => $secretKey], true)) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}

		if (!isset($input['observatoryId']) || !$input['observatoryId']) {
			return self::json("unknown observatory", UNKNOWN_OBSERVATORY_CODE);
		}

		$dictionary = Dictionary::getCurrentFromId($input['observatoryId']);
		if (!$dictionary) {
			return self::json("unknown observatory", UNKNOWN_OBSERVATORY_CODE);
		}

		if (!isset($input['metadata_name']) || !$input['metadata_name']) {
			return self::json("No metadata name", ERROR_CODE);
		}
		$name = $input['metadata_name'];
		$value = isset($input['metadata_value']) ? $input['metadata_value'] : false;

		$newDictionary = $dictionary->createNewVersion();
		$newDictionary->addConf($name, $value);

		$hash = $newDictionary->save();

		return self::json($hash);
	}

	/**
	 * @param Request $request
	 */
	public function createEntry(Request $request) {
		$input = $request->all();
		if (!isset($input['secretKey']) || !$input['secretKey']) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}
		$secretKey = $input['secretKey'];

		if (!Auth::attempt(['secretKey' => $secretKey], true)) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}

		if (!isset($input['observatoryId']) || !$input['observatoryId']) {
			return self::json("unknown observatory", UNKNOWN_OBSERVATORY_CODE);
		}

		$dictionary = Dictionary::getCurrentFromId($input['observatoryId']);
		if (!$dictionary) {
			return self::json("unknown observatory", UNKNOWN_OBSERVATORY_CODE);
		}
		$dictionary = $dictionary->createNewVersion();

		if (!isset($input['tags']) || !$input['tags']) {
			throw new Exception("Création d'une entrée sans tags !");
		}

		if (!isset($input['value']) || !$input['value']) {
			throw new Exception("Création d'une entrée sans valeur !");
		}

		$entry = new Entry();
		$entry->tags = $input['tags'];
		$entry->value = $input['value'];
		$hash = $entry->save();
		$dictionary->addEntry($entry);
		$dictionary->save();
		return self::json(($hash));
	}

	/**
	 * @param Request $request
	 */
	public function createObservatory(Request $request) {
		$input = $request->all();
		if (!isset($input['secretKey']) || !$input['secretKey']) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}

		$secretKey = $input['secretKey'];

		if (!Auth::attempt(['secretKey' => $secretKey], true)) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}

		if (!isset($input['observatoryId']) || !$input['observatoryId']) {
			throw new Exception("Création d'observatoire sans nom d'observatoire !");
		}

		$observatoryId = $input['observatoryId'];
		return self::json(Dictionary::createNew($observatoryId));
	}

	/**
	 * @param Request $request
	 */
	public function createUser(Request $request) {
		$input = $request->all();
		if (!isset($input['email']) || !$input['email']) {
			return self::json("no email given", NO_PARAM_CODE);
		}
		$email = $input['email'];

		$email = strtolower($email);
		$exist = UserList::userExists($email);

		if ($exist) {
			return self::json("user already exists", ALREADY_REGISTERED_CODE);
		}

		$keyPair = Stellar::getNewKeyPair();

		if (!Stellar::getInitAccount($keyPair->public_address, $keyPair->secret_key)) {
			return self::json("problem while creating the user", ERROR_CODE);
		}

		$userListHash = UserList::addUser($email, $keyPair->public_address);

		$mail = str_replace(
			array('%PUBLICADDRESS%', '%SECRETKEY%'),
			array($keyPair->public_address, $keyPair->secret_key),
			"email à créer, '%PUBLICADDRESS%', '%SECRETKEY%', '%PUBLICADDRESS%', '%SECRETKEY%'"
		);

		$to = $email;

		$subject = 'Welcome to the CoWaBoo project';

		$headers = "From: " . strip_tags('do-no-reply@cowaboo.net') . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		mail($to, $subject, $mail, $headers);

		return self::json(("User created, an email has been sent to you with your new secret key"));
	}

	/**
	 * @param Request $request
	 */
	public function deleteEntry(Request $request) {
		$input = $request->all();
		if (!isset($input['secretKey']) || !$input['secretKey']) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}
		$secretKey = $input['secretKey'];

		if (!Auth::attempt(['secretKey' => $secretKey], true)) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}

		if (!isset($input['observatoryId']) || !$input['observatoryId']) {
			return self::json("unknown observatory", UNKNOWN_OBSERVATORY_CODE);
		}
		$dictionary = Dictionary::getCurrentFromId($input['observatoryId']);
		if (!$dictionary) {
			return self::json("unknown observatory", UNKNOWN_OBSERVATORY_CODE);
		}

		$dictionary = $dictionary->createNewVersion();

		if (!isset($input['hash']) || !$input['hash']) {
			throw new Exception("Modification d'une entrée sans hash !");
		}
		$oldEntry = Entry::createFromHash($input['hash']);
		if (!$oldEntry) {
			throw new Exception("Modification d'une entrée inexistante !");
		}

		$dictionary->removeEntry($oldEntry);
		$newHash = $dictionary->save();

		return self::json(($newHash));
	}

	/**
	 * @param Request $request
	 */
	public function deleteObservatory(Request $request) {
		$input = $request->all();
		if (!isset($input['secretKey']) || !$input['secretKey']) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}

		$secretKey = $input['secretKey'];

		if (!Auth::attempt(['secretKey' => $secretKey], true)) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}

		if (!isset($input['observatoryId']) || !$input['observatoryId']) {
			throw new Exception("Création d'observatoire sans nom d'observatoire !");
		}
		$observatoryId = $input['observatoryId'];

		$tagList = TagList::getCurrent();
		$hash = $tagList->removeDictionaryId($observatoryId);

		return self::json($hash);
	}

	/**
	 * @param Request $request
	 */
	public function getObservatory(Request $request) {
		$input = $request->all();
		if (!isset($input['observatoryId']) || !$input['observatoryId']) {
			return self::json("unknown observatory", UNKNOWN_OBSERVATORY_CODE);
		}

		$dictionary = Dictionary::getCurrentFromId($input['observatoryId']);
		if (!$dictionary) {
			return self::json("unknown observatory", UNKNOWN_OBSERVATORY_CODE);
		}

		$entries = [];
		foreach ($dictionary->entries as $entryHash) {
			$entries[$entryHash] = IPFS::get($entryHash)->entry;
		}

		$dictionary = json_encode($dictionary);
		$dictionary = json_decode($dictionary);
		$dictionary->dictionary->entries = $entries;

		return self::json(($dictionary));
	}

	/**
	 * @param Request $request
	 */
	public function getUser(Request $request) {
		$input = $request->all();
		if (!isset($input['secretKey']) || !$input['secretKey']) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}
		$secretKey = $input['secretKey'];

		if (!Auth::attempt(['secretKey' => $secretKey], true)) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}

		$user = Auth()->user();
		return self::json(($user));
	}

	/**
	 * @param Request $request
	 */
	public function getUserObservatories(Request $request) {
		$input = $request->all();
		if (!isset($input['secretKey']) || !$input['secretKey']) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}
		$secretKey = $input['secretKey'];

		if (!Auth::attempt(['secretKey' => $secretKey], true)) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}

		$user = Auth()->user();
		return self::json(($user->dictionaries));
	}

	/**
	 * @param Request $request
	 */
	public function getUserUnsubscribedObservatories(Request $request) {
		$input = $request->all();
		if (!isset($input['secretKey']) || !$input['secretKey']) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}
		$secretKey = $input['secretKey'];

		if (!Auth::attempt(['secretKey' => $secretKey], true)) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}

		$user = Auth()->user();
		return self::json(($user->other_dictionaries));
	}

	/**
	 * @param Request $request
	 */
	public function modifyEntry(Request $request) {
		$input = $request->all();
		if (!isset($input['secretKey']) || !$input['secretKey']) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}
		$secretKey = $input['secretKey'];

		if (!Auth::attempt(['secretKey' => $secretKey], true)) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}

		if (!isset($input['observatoryId']) || !$input['observatoryId']) {
			return self::json("unknown observatory", UNKNOWN_OBSERVATORY_CODE);
		}
		$dictionary = Dictionary::getCurrentFromId($input['observatoryId']);
		if (!$dictionary) {
			return self::json("unknown observatory", UNKNOWN_OBSERVATORY_CODE);
		}

		$dictionary = $dictionary->createNewVersion();

		if (!isset($input['hash']) || !$input['hash']) {
			throw new Exception("Modification d'une entrée sans hash !");
		}
		$oldEntry = Entry::createFromHash($input['hash']);
		if (!$oldEntry) {
			throw new Exception("Modification d'une entrée inexistante !");
		}

		if (!isset($input['newValue']) || !$input['newValue']) {
			throw new Exception("Modification d'une entrée sans nouvelle valeur !");
		}

		$newEntry = $oldEntry->createNewVersion();
		$newEntry->value = $input['newValue'];
		$newHash = $newEntry->save();

		$dictionary->removeEntry($oldEntry);
		$dictionary->addEntry($newEntry);

		$dictionary->save();

		return self::json(($newHash));
	}

	/**
	 * @param Request $request
	 */
	public function suscribe(Request $request) {
		$input = $request->all();
		if (!isset($input['secretKey']) || !$input['secretKey']) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}
		$secretKey = $input['secretKey'];

		if (!Auth::attempt(['secretKey' => $secretKey], true)) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}

		if (!isset($input['observatoryId']) || !$input['observatoryId']) {
			return self::json("unknown observatory", UNKNOWN_OBSERVATORY_CODE);
		}

		$dictionary = Dictionary::getCurrentFromId($input['observatoryId']);
		if (!$dictionary) {
			return self::json("unknown observatory", UNKNOWN_OBSERVATORY_CODE);
		}
		$dictionary = $dictionary->createNewVersion();

		$dictionary->member_list = array_unique(array_merge((array) $dictionary->member_list, array(Auth()->user()->email)));
		$dictionary->save();

		return self::json(true);
	}

	/**
	 * @param Request $request
	 */
	public function unsuscribe(Request $request) {
		$input = $request->all();
		if (!isset($input['secretKey']) || !$input['secretKey']) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}
		$secretKey = $input['secretKey'];

		if (!Auth::attempt(['secretKey' => $secretKey], true)) {
			return self::json("unknown user", UNKNOWN_USER_CODE);
		}

		if (!isset($input['observatoryId']) || !$input['observatoryId']) {
			return self::json("unknown observatory", UNKNOWN_OBSERVATORY_CODE);
		}

		$dictionary = Dictionary::getCurrentFromId($input['observatoryId']);
		if (!$dictionary) {
			return self::json("unknown observatory", UNKNOWN_OBSERVATORY_CODE);
		}
		$dictionary = $dictionary->createNewVersion();

		$email = Auth()->user()->email;
		$list = (array) $dictionary->member_list;
		$key = array_search($email, $list);
		$list = array();
		if (false !== $key) {
			unset($list[$key]);
			array_unique(array_merge($list, []));
		}
		$dictionary->member_list = $list;

		$dictionary->save();

		return self::json(true);
	}

	/**
	 * @param  $content
	 * @return mixed
	 */
	private static function json($content, $status = 200) {
		if (!is_string($content) || !json_decode($content)) {
			$content = json_encode($content);
		}
		$response = Response::make($content, $status);
		$response->header('Content-Type', 'application/json');
		return $response;
	}
}
