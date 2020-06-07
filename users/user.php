
<?php 

class User {
    protected   $id,
                $username,
                $passhash,
                $full_name,
                $lastseen,
                $macaddress,
                $access_level,
                $ip,
                $email,
                $token;

    function __construct($username, $password, $full_name, $email, $r_question, $r_answer) {
        // if user exists then return null
        // else create a new user and ask for email confirmation by generating a tok
    }

    protected function find($args) {
        switch ($args[0]) {
            case 'Email':
            break;
            case 'Username':
            break;
            case 'LastSeen':
            break;
            case 'Token':
            break;
            default:
        }
    }
    
    // this is a regex match for retrieval functions
    protected function __callStatic($method, $args) {
        if(preg_match('/^findBy(.+)$/', $method, $matches)) {
            return this::find(array('key' => $matches[1],
                                    'value' => $args[0]));
        }
    }
}


?>