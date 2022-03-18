<?php

namespace app\commands;

use app\models\Album;
use app\models\Photo;
use app\models\User;
use Yii;
use yii\console\Controller;

class SeedController extends Controller
{
    public function actionIndex()
    {

        $faker = \Faker\Factory::create();

        $user = new User();
        $album = new Album();
        $photo = new Photo();
        foreach (range(0, 10) as $number)
        {
            $user->setIsNewRecord(true);

            $user->id = null;

            $user->first_name = $faker->firstName;
            $user->last_name = $faker->lastName;
            $user->password_hash = \Yii::$app->security->generatePasswordHash(getenv('USER_PASSWORD'));
            if ( $user->save() )
            {
                foreach (range(0, 10) as $number2)
                {

                    $album->setIsNewRecord(true);
                    $album->id = null;

                    $album->user_id = $user->id;
                    $album->title = 'album'.$number2;
                    if($album->save()){
                        foreach (range(0, 10) as $number3)
                        {
                            $photo->setIsNewRecord(true);
                            $photo->id = null;

                            $photo->album_id = $album->id;
                            $photo->title = 'photo'.$number3;
                            $photo->url = '/assets/images/download'.rand(1, 4) .'.jpeg';
                            $photo->save();
                        }
                    }


                }

            }
        }

    }
}