<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class personalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i=0; $i < 20; $i++) {
            \DB::table('tblPersonal')->insert(array(
                   'expediente' => $faker->ean8,
                   'nombre'  => $faker->name,
                   'email'  => $faker->email,
                   'huella'  => $faker->randomElement(['0100101010','0001110001','1110001100']),
                   'nombramiento'  => $faker->randomElement(['determinado','indeterminado']),
                   'jornada'  => $faker->randomElement(['horas','tiempo completo','medio tiempo','confianza']),
                   'created_at' => date('Y-m-d H:m:s'),
                   'updated_at' => date('Y-m-d H:m:s')
            ));
        }
    }
}
