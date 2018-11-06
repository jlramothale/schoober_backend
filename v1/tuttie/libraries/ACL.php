<?php

/**
 * Description of ACL
 *
 * @author jramothale
 */
final class ACL {

    /**
     * A list of permissions for this user. Will be filled
     * by the first call to the constructor method.
     */
    private $permissions;

    /**
     * A list of permission values for this user. Will be filled
     * by the first call to the constructor method.
     */
    private $values;

    /**
     * Create new {@link ACL} with default properties set.
     */
    public function __construct($permissions) {
        $this->permissions = $permissions;
    }

    /**
     * Returns true if the user represented by this object can do the action
     * given as a param.
     * @param permission String holding a permission name
     * @return boolean, true if the user has access, false if he has no access
     */
    public function hasAccess($permission) {
        if (isset($permission)) {
            if ($this->permissions && is_array($this->permissions)) {
                foreach ($this->permissions as $key => $value) {
                    if ($key === $permission && (int) $value === 1) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

}
