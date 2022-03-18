<?php

use Codeception\Util\HttpCode;
use Faker\Factory;

class AlbumCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
    }

    public function getAllAlbums(ApiTester $I) {
        $I->sendGet('albums');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsValidOnJsonSchemaString('{"type":"array"}');
        $validResponseJsonSchema = json_encode(
            [
                'properties' => [
                    'id'         => ['type' => 'integer'],
                    'user_id'       => ['type' => 'integer'],
                    'title'       => ['type' => 'string'],
                ]
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
    }

    public function getAlbum(ApiTester $I) {

        $I->sendGet('albums/1');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsValidOnJsonSchemaString('{"type":"object"}');
        $validResponseJsonSchema = json_encode(
            [
                'properties' => [
                    'id'         => ['type' => 'integer'],
                    'user_id'       => ['type' => 'integer'],
                    'title'       => ['type' => 'string'],
                    'photos'       => ['type' => 'array'],
                ]
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
    }

    public function createNewAlbum(ApiTester $I) {

        $faker = Factory::create();
        $I->sendPost(
            'albums',
            [
                'user_id'       => 1,
                'title'       => $faker->name,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(
            [
                'id'         => 'integer',
                'title' => 'string',
            ]
        );
    }

    public function updateAlbum(ApiTester $I) {

        $faker = Factory::create();
        $newTitle = $faker->name;
        $I->sendPatch(
            'albums/1',
            [
                'title' => $newTitle
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['title' => $newTitle]);
    }

    public function deleteAlbum(ApiTester $I) {

        $I->sendDelete('albums/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
        //try to get deleted user
        $I->sendGet('albums/1');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
