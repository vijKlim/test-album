<?php

use Codeception\Util\HttpCode;
use Faker\Factory;

class PhotoCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
    }

    public function getAllPhotos(ApiTester $I) {
        $I->sendGet('photos');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsValidOnJsonSchemaString('{"type":"array"}');
        $validResponseJsonSchema = json_encode(
            [
                'properties' => [
                    'id'         => ['type' => 'integer'],
                    'album_id'         => ['type' => 'integer'],
                    'title'       => ['type' => 'string'],
                    'url'       => ['type' => 'string'],
                ]
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
    }

    public function getPhoto(ApiTester $I) {

        $I->sendGet('photos/1');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsValidOnJsonSchemaString('{"type":"object"}');
        $validResponseJsonSchema = json_encode(
            [
                'properties' => [
                    'id'         => ['type' => 'integer'],
                    'album_id'       => ['type' => 'integer'],
                    'title'       => ['type' => 'string'],
                    'url'       => ['type' => 'string'],
                ]
            ]
        );
        $I->seeResponseIsValidOnJsonSchemaString($validResponseJsonSchema);
    }

    public function createNewPhoto(ApiTester $I) {

        $faker = Factory::create();
        $I->sendPost(
            'photos',
            [
                'album_id'       => 1,
                'title'       => $faker->name,
                'url'       => '/assets/images/download'.rand(1, 4) .'.jpeg'
            ]
        );
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(
            [
                'id'         => 'integer',
                'title' => 'string',
                'url' => 'string',
            ]
        );
    }

    public function updatePhoto(ApiTester $I) {

        $faker = Factory::create();
        $newUrl = '/assets/images/download'.rand(1, 4) .'.jpeg';
        $I->sendPatch(
            'photos/1',
            [
                'url' => $newUrl
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['url' => $newUrl]);
    }

    public function deletePhoto(ApiTester $I) {

        $I->sendDelete('photos/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
        //try to get deleted user
        $I->sendGet('photos/1');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
