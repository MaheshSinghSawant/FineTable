<?php
require_once('../../lib/User.class.php');
// create some testing hash array with random data
// keys has to be exactly the same with the column names in related table

// test for User::create,  sign-up-user
$fields = array(
    'username' => 'Alex008',
    'firstname' => 'Alex',
    'lastname' => 'Coco',
    'email' => 'testfun234234@mail.com',
    'password' => md5('123456'),
    'phone_number' => '4157564823'
);
// use try and catch block
// exceptions might be thrown with in the object
// any operation need a $success and $message
try {
    $user1 = new User();
    $user1->create($fields);
    $success = true;
    $message = 'Create new user complete';
} catch (Exception $e) {
    $success = false;
    $message = $e->getMessage();
    echo "Creating a new user : " . $message . "<br>";
}

// test for User::update, update user info in database
echo "Tesing update a exist user's information in database: <br>";
$fields = array(
    'user_id' => '1',
    'email' => 'test_update000@finatable.com',
    'phone_number' => '4444444444'
);
try {
    $user2 = new User();
    $user2->update($fields);
    $success = true;
    $message = 'Update user information complete';
} catch (Exception $e) {
    $success = false;
    $message = $e->getMessage();
    echo "Update a exsit user's information : " . $message . "<br>";
}

echo "Testing find a user by user id: <br>";
$user_to_find = '2'; // testing find a user by id
$user3 = new User();
echo "If find a user in databse by id :" . $user3->find($user_to_find) . "<br>";
print_r($user3->get_user_info());
echo $user3->get_user_info_json();


echo "Testing find a user by username: <br>";
$user_to_find = 'testuser3';
$user4 = new User();
echo "If find a user in databse by id :" . $user4->find($user_to_find) . "<br>";
print_r($user4->get_user_info());
echo $user4->get_user_info_json();

echo "Testing for delete a user : <br>";
$user_to_delete = 15;
$user5 = new User();
echo "Testing delete a user from database by user name : " . $user5->delete($user_to_delete);
?>
