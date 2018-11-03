<?php

/**
 * Description of UserService
 *
 * @author johannes
 */
class UserService {

    /** @var object $cnx - The database connection reference */
    protected $cnx;

    /**
     * Default constructor
     */
    public function __construct($cnx = null) {
        if (isset($cnx)) {
            $this->cnx = $cnx;
        }
    }

    /**
     * registerUser - register user account service
     * @param array $data - input data
     * @return array - json encoded array
     */
    public function registerUser($data){
        try {
            $this->cnx->beginTransaction();

            $user_model = new UsersModel($this->cnx);
            $user_profile_model = new UserProfileModel($this->cnx);
            $api_autho_keys_model = new ApiAuthoKeysModel($this->cnx);
            $secure_hash = new SecurePasswordHash();

            $user = $user_model->getByEmail($data["email"]);
            if ($user) {
                Utils::userLogEntry($user->user_id, "Could not register, user exists", $this->cnx);
                $this->cnx->commit();
                return Utils::response([
                    "message" => "Could not register user {$data["first_name"]} {$data["last_name"]}, User exist",
                    "status" => "501"
                ]);
            }

            $password_hash = $secure_hash->hash($data["password"]);
            $user_id = hash(PBKDF2_HASH_ALGORITHM, $user_model->generateUserId());
            $api_autho_key = $api_autho_keys_model->generateAuthoKey();

            $api_autho_keys_model->insert([
                "autho_key" => $api_autho_key,
                "device" => Utils::getUserAgent(),
                "device_id" => $data["device_id"],
            ]);

            $user_model->insert([
                "user_id" => $user_id,
                "user_type" => $data["user_type"],
                "email" => $data["email"],
                "hash_value" => $password_hash,
                "date_added" => Utils::getDateTime()
            ]);

            $path = UPLOADS_PATH . "users" . DS . $user_id;
            Utils::makeDirectory($path);
            Utils::changeDirectoryPermissions($path, 0777);

            $user_profile_model->insert([
                "user_id" => $user_id,
                "first_name" => $data["first_name"],
                "last_name" => $data["last_name"]
            ]);

            $subject = "Activate Your Account";
            $body = "
                <p>Hi {$data["first_name"]},</p>
                <p>Welcome to " . APP_NAME . ".</p>
                <br/>
                <p>Regards</p>
                <p>" . APP_NAME . " Support Team</p>
            ";

            if (MAILER) {
                $mailer = new Mailer($data["email"], $data["first_name"]);
                $mailer->sendMail($subject, $body);
            }

            Utils::userLogEntry($user_id, "New user registration", $this->cnx);
            $this->cnx->commit();
            return Utils::response([

            ]);
        } catch (Exception $ex) {
            $this->cnx->rollBack();
            Utils::systemLogEntry("Register error: " . $ex->getMessage(), $this->cnx);
            return Utils::response([
                "message" => "Unsuccessful",
                "status" => "501"
            ]);
        }
    }

    /**
     * loginUser - Login user service
     * @param array $data - input data
     * @return array - json encoded array
     */
    public function loginUser($data) {
        try {
            $this->cnx->beginTransaction();
            $user_model = new UsersModel($this->cnx);
            $user_profile_model = new UserProfileModel($this->cnx);
            $secure_hash = new SecurePasswordHash();

            $user = $user_model->getByEmail($data["email"]);
            if (!$user) {
                Utils::systemLogEntry("Could not login, invalid login email {$data["email"]}", $this->cnx);
                $this->cnx->commit();
                return Utils::response([
                    "message" => "Could not login, invalid login details",
                    "status" => "501"
                ]);
            }
            if ($user->is_suspended) {
                Utils::userLogEntry($user->user_id, "Could not login, account suspended", $this->cnx);
                $this->cnx->commit();
                return Utils::response([
                    "message" => "Could not login. Your account is suspended, please contact your employer or support <a href='" . SUPPORT_EMAIL . "'>" . SUPPORT_EMAIL . "</a> for help.",
                    "status" => "501"
                ]);
            }
            if (!$secure_hash->verify($data["password"], $user->hash_value)) {
                Utils::userLogEntry($user->user_id, "Could not login, invalid login password: {$data["password"]}", $this->cnx);
                $this->cnx->commit();
                return Utils::response([
                    "message" => "Could not login, invalid login details",
                    "status" => "501"
                ]);
            }
            $user_model->update(["id" => $user->id, "is_online" => 1]);
            $user_profile = $user_profile_model->getByUserId($user->user_id);
            Utils::userLogEntry($user->user_id, "User login success", $this->cnx);
            $this->cnx->commit();
            return Utils::response([
                "user" => $user,
                "profile" => $user_profile,
                "status" => "200"
            ]);
        } catch (Exception $ex) {
            $this->cnx->rollBack();
            Utils::systemLogEntry("Login error: " . $ex->getMessage(), $this->cnx);
            return Utils::response([
                "message" => "Unsuccessful",
                "status" => "501"
            ]);
        }
    }

    public function completeRegistration($data) {
        try {
            $this->cnx->beginTransaction();
            $user_profile_model = new UserProfileModel($this->cnx);
            $user_profile = $user_profile_model->getByUserId($data["user_id"]);

            $user_profile_model->update([
                "id" => $user_profile->id,
                "gender" => $data["gender"],
                "cell_number" => $data["cell_number"],
                "street_address" => $data["street_address"],
                "town" => $data["town"],
                "province" => $data["province"],
                "code" => $data["code"],
            ]);

            $user_profile = $user_profile_model->getByUserId($data["user_id"]);
            Utils::userLogEntry($data["user_id"], "User login success", $this->cnx);
            $this->cnx->commit();
            return Utils::response([
                "profile" => $user_profile,
                "status" => "200"
            ]);
        } catch (Exception $ex) {
            $this->cnx->rollBack();
            Utils::systemLogEntry("Complete registration error: " . $ex->getMessage(), $this->cnx);
            return Utils::response([
                "message" => "Unsuccessful",
                "status" => "501"
            ]);
        }
    }

    public function updateUserProfile($data) {
        try {
            $this->cnx->beginTransaction();
            $user_profile_model = new UserProfileModel($this->cnx);
            $user_profile = $user_profile_model->getByUserId($data["user_id"]);

            $user_profile_model->update([
                "id" => $user_profile->id,
                "first_name" => $data["first_name"],
                "last_name" => $data["last_name"],
                "gender" => $data["gender"],
                "cell_number" => $data["cell_number"],
                "street_address" => $data["street_address"],
                "town" => $data["town"],
                "province" => $data["province"],
                "code" => $data["code"],
            ]);

            $user_profile = $user_profile_model->getByUserId($data["user_id"]);
            Utils::userLogEntry($data["user_id"], "User updated profile", $this->cnx);
            $this->cnx->commit();
            return Utils::response([
                "profile" => $user_profile,
                "status" => "200"
            ]);
        } catch (Exception $ex) {
            $this->cnx->rollBack();
            Utils::systemLogEntry("Update profile error: " . $ex->getMessage(), $this->cnx);
            return Utils::response([
                "message" => "Unsuccessful",
                "status" => "501"
            ]);
        }
    }

    public function resetPasswordLink($data){
        try {
            $this->cnx->beginTransaction();
            $user_model = new UsersModel($this->cnx);
            $user_profile_model = new UserProfileModel($this->cnx);

            $user = $user_model->getByEmail($data["email"]);
            if (!$user) {
                Utils::systemLogEntry("Invalid user email: {$data["email"]}", $this->cnx);
                $this->cnx->commit();
                return Utils::response([
                    "message" => "Invalid user email: {$data["email"]}",
                    "status" => "501"
                ]);
            }
            $user_profile = $user_profile_model->getByUserId($user->user_id);
            $subject = "Password Reset Code";
            $body = "
                <p>Hi $user_profile->first_name,</p>
                <p>Here is your password reset Code: " . Utils::randomString() . "</p>
                <br/>
                <p>Kind Regards,</p>
                <p>The " . APP_NAME . " Support Team</p>
                <p>Contact us anytime at " . SUPPORT_EMAIL . "</p>
            ";
            if (MAILER) {
                $mailer = new Mailer($user->email, $user_profile->first_name);
                $mailer->sendMail($subject, $body);
            }
            Utils::userLogEntry($user->user_id, "Password reset code sent", $this->cnx);
            $this->cnx->commit();
            return Utils::response([
                "profile" => $user_profile,
                "status" => "200"
            ]);
        } catch (Exception $ex) {
            $this->cnx->rollBack();
            Utils::systemLogEntry("Update profile error: " . $ex->getMessage(), $this->cnx);
            return Utils::response([
                "message" => "Unsuccessful",
                "status" => "501"
            ]);
        }
    }

}
