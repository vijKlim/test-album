<?php

use Codeception\Util\HttpCode;
use Faker\Factory;

class UserCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
    }

    public function getAllUsers(ApiTester $I) {
        $I->sendGet('users');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsValidOnJsonSchemaString('{"type":"array"}');
        $validResponseJsonSchema = json_encode(
            [
                'properties' => [
                    'id'         => ['type' => 'integer'],
                    'first_name'       => ['type' => 'string'],
                    'last_name'       => ['type' => 'string'],
                ]
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
    }

    public function getUser(ApiTester $I) {

        $I->sendGet('users/1');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsValidOnJsonSchemaString('{"type":"object"}');

        $validResponseJsonSchema = json_encode(
            [
                'properties' => [
                    'id'         => ['type' => 'integer'],
                    'first_name'       => ['type' => 'string'],
                    'last_name'       => ['type' => 'string'],
                    'albums'       => ['type' => 'array'],
                ]
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
    }

    public function createNewUser(ApiTester $I) {

        $faker = Factory::create();
        $I->sendPost(
            'users',
            [
                'first_name'       => $faker->firstName,
                'last_name'       => $faker->lastName,
                'password_hash' => \Yii::$app->security->generatePasswordHash('test')
            ]
        );
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(
            [
                'id'         => 'integer',
                'first_name' => 'string',
                'last_name' => 'string',
            ]
        );
    }

    public function updateUser(ApiTester $I) {

        $faker = Factory::create();
        $newName = $faker->name;
        $I->sendPatch(
            'users/1',
            [
                'first_name' => $newName
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['first_name' => $newName]);
    }

    public function deleteUser(ApiTester $I) {

        $I->sendDelete('users/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
        //try to get deleted user
        $I->sendGet('users/1');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
