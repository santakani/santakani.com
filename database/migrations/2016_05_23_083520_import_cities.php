<?php

/*
 * This file is part of santakani.com
 *
 * (c) Guo Yunhe <guoyunhebrave@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Carbon\Carbon;
use Cocur\Slugify\Slugify;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * ImportCities
 *
 * Database migration to import city data from cities15000.txt.
 *
 * @author Guo Yunhe <guoyunhebrave@gmail.com>
 * @see https://github.com/santakani/santakani.com/wiki/City
 */
class ImportCities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        echo "Import cities start\n";

        $slugify = new Slugify();

        $handle = fopen(base_path("database/sources/cities15000.txt"), "r");
        if ($handle) {
            while (($city = fgetcsv($handle, 0, "\t")) !== false) {

                if (count($city) !== 19) {
                    echo "\tSkip: $city[1]\n";
                    continue;
                }

                $country = DB::table('country')->where('code', $city[8])->first();
                if (isset($country->id)) {
                    $country_id = $country->id;
                } else {
                    echo "\tSkip: $city[1]\n";
                    continue;
                }

                $slug = $this->slug2($slugify->slugify($city[2]), $country_id);

                $id = DB::table('city')->insertGetId([
                    'slug' => $slug,
                    'country_id' => $country_id,
                    'latitude' => $city[4],
                    'longitude' => $city[5],
                    'timezone' => $city[17],
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);

                DB::table('city_translation')->insert([
                    'city_id' => $id,
                    'locale' => 'en',
                    'name' => $city[1],
                    'content' => $city[3],
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        } else {
            // error opening the file.
            echo "\tError: Cannot open file 'database/sources/cities15000.txt'\n";
        }

        echo "Import cities end\n";
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Cannot undo
    }

    /**
     * Check unique slug
     *
     * @param string $slug
     * @param int $country_id
     * @param int $n
     * @return string
     */
    public function slug2($slug, $country_id, $n = 0)
    {
        if ($n === 0) {
            $cities = DB::table('city')->where([
                ['slug', $slug],
                ['country_id', $country_id],
            ])->get();
        } else {
            $cities = DB::table('city')->where([
                ['slug', $slug . '-' . $n],
                ['country_id', $country_id],
            ])->get();
        }

        if (count($cities)) {
            $n++;
            return $this->slug2($slug, $country_id, $n);
        } elseif ($n === 0) {
            return $slug;
        } else {
            return $slug . '-' . $n;
        }
    }
}
