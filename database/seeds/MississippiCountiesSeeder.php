<?php

use App\MsCounty;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class MississippiCountiesSeeder extends DatabaseSeeder
{

    public function run()
    {
        DB::table('ms_counties')->delete();

        MsCounty::create(["county_id"=>"ADAMS", "name" => "ADAMS"]);
        MsCounty::create(["county_id"=>"ALCORN", "name" => "ALCORN"]);
        MsCounty::create(["county_id"=>"AMITE", "name" => "AMITE"]);
        MsCounty::create(["county_id"=>"ATTALA", "name" => "ATTALA"]);
        MsCounty::create(["county_id"=>"BENTON", "name" => "BENTON"]);
        MsCounty::create(["county_id"=>"BOLIVAR", "name" => "BOLIVAR"]);
        MsCounty::create(["county_id"=>"CALHOUN", "name" => "CALHOUN"]);
        MsCounty::create(["county_id"=>"CARROLL", "name" => "CARROLL"]);
        MsCounty::create(["county_id"=>"CHICKASAW", "name" => "CHICKASAW"]);
        MsCounty::create(["county_id"=>"CHOCTAW", "name" => "CHOCTAW"]);
        MsCounty::create(["county_id"=>"CLAIBORNE", "name" => "CLAIBORNE"]);
        MsCounty::create(["county_id"=>"CLARKE", "name" => "CLARKE"]);
        MsCounty::create(["county_id"=>"CLAY", "name" => "CLAY"]);
        MsCounty::create(["county_id"=>"COAHOMA", "name" => "COAHOMA"]);
        MsCounty::create(["county_id"=>"COPIAH", "name" => "COPIAH"]);
        MsCounty::create(["county_id"=>"COVINGTON", "name" => "COVINGTON"]);
        MsCounty::create(["county_id"=>"DESOTO", "name" => "DESOTO"]);
        MsCounty::create(["county_id"=>"FORREST", "name" => "FORREST"]);
        MsCounty::create(["county_id"=>"FRANKLIN", "name" => "FRANKLIN"]);
        MsCounty::create(["county_id"=>"GEORGE", "name" => "GEORGE"]);
        MsCounty::create(["county_id"=>"GREENE", "name" => "GREENE"]);
        MsCounty::create(["county_id"=>"GRENADA", "name" => "GRENADA"]);
        MsCounty::create(["county_id"=>"HANCOCK", "name" => "HANCOCK"]);
        MsCounty::create(["county_id"=>"HARRISON", "name" => "HARRISON"]);
        MsCounty::create(["county_id"=>"HINDS", "name" => "HINDS"]);
        MsCounty::create(["county_id"=>"HOLMES", "name" => "HOLMES"]);
        MsCounty::create(["county_id"=>"HUMPHREYS", "name" => "HUMPHREYS"]);
        MsCounty::create(["county_id"=>"ISSAQUENA", "name" => "ISSAQUENA"]);
        MsCounty::create(["county_id"=>"ITAWAMBA", "name" => "ITAWAMBA"]);
        MsCounty::create(["county_id"=>"JACKSON", "name" => "JACKSON"]);
        MsCounty::create(["county_id"=>"JASPER", "name" => "JASPER"]);
        MsCounty::create(["county_id"=>"JEFFERSON", "name" => "JEFFERSON"]);
        MsCounty::create(["county_id"=>"JEFFERSON DAVIS", "name" => "JEFFERSON DAVIS"]);
        MsCounty::create(["county_id"=>"JONES", "name" => "JONES"]);
        MsCounty::create(["county_id"=>"KEMPER", "name" => "KEMPER"]);
        MsCounty::create(["county_id"=>"LAFAYETTE", "name" => "LAFAYETTE"]);
        MsCounty::create(["county_id"=>"LAMAR", "name" => "LAMAR"]);
        MsCounty::create(["county_id"=>"LAUDERDALE", "name" => "LAUDERDALE"]);
        MsCounty::create(["county_id"=>"LAWRENCE", "name" => "LAWRENCE"]);
        MsCounty::create(["county_id"=>"LEAKE", "name" => "LEAKE"]);
        MsCounty::create(["county_id"=>"LEE", "name" => "LEE"]);
        MsCounty::create(["county_id"=>"LEFLORE", "name" => "LEFLORE"]);
        MsCounty::create(["county_id"=>"LINCOLN", "name" => "LINCOLN"]);
        MsCounty::create(["county_id"=>"LOWNDES", "name" => "LOWNDES"]);
        MsCounty::create(["county_id"=>"MADISON", "name" => "MADISON"]);
        MsCounty::create(["county_id"=>"MARION", "name" => "MARION"]);
        MsCounty::create(["county_id"=>"MARSHALL", "name" => "MARSHALL"]);
        MsCounty::create(["county_id"=>"MONROE", "name" => "MONROE"]);
        MsCounty::create(["county_id"=>"MONTGOMERY", "name" => "MONTGOMERY"]);
        MsCounty::create(["county_id"=>"NESHOBA", "name" => "NESHOBA"]);
        MsCounty::create(["county_id"=>"NEWTON", "name" => "NEWTON"]);
        MsCounty::create(["county_id"=>"NOXUBEE", "name" => "NOXUBEE"]);
        MsCounty::create(["county_id"=>"OKTIBBEHA", "name" => "OKTIBBEHA"]);
        MsCounty::create(["county_id"=>"PANOLA", "name" => "PANOLA"]);
        MsCounty::create(["county_id"=>"PEARL RIVER", "name" => "PEARL RIVER"]);
        MsCounty::create(["county_id"=>"PERRY", "name" => "PERRY"]);
        MsCounty::create(["county_id"=>"PIKE", "name" => "PIKE"]);
        MsCounty::create(["county_id"=>"PONTOTOC", "name" => "PONTOTOC"]);
        MsCounty::create(["county_id"=>"PRENTISS", "name" => "PRENTISS"]);
        MsCounty::create(["county_id"=>"QUITMAN", "name" => "QUITMAN"]);
        MsCounty::create(["county_id"=>"RANKIN", "name" => "RANKIN"]);
        MsCounty::create(["county_id"=>"SCOTT", "name" => "SCOTT"]);
        MsCounty::create(["county_id"=>"SHARKEY", "name" => "SHARKEY"]);
        MsCounty::create(["county_id"=>"SIMPSON", "name" => "SIMPSON"]);
        MsCounty::create(["county_id"=>"SMITH", "name" => "SMITH"]);
        MsCounty::create(["county_id"=>"STONE", "name" => "STONE"]);
        MsCounty::create(["county_id"=>"SUNFLOWER", "name" => "SUNFLOWER"]);
        MsCounty::create(["county_id"=>"TALLAHATCHIE", "name" => "TALLAHATCHIE"]);
        MsCounty::create(["county_id"=>"TATE", "name" => "TATE"]);
        MsCounty::create(["county_id"=>"TIPPAH", "name" => "TIPPAH"]);
        MsCounty::create(["county_id"=>"TISHOMINGO", "name" => "TISHOMINGO"]);
        MsCounty::create(["county_id"=>"TUNICA", "name" => "TUNICA"]);
        MsCounty::create(["county_id"=>"UNION", "name" => "UNION"]);
        MsCounty::create(["county_id"=>"UNKNOWN", "name" => "UNKNOWN"]);
        MsCounty::create(["county_id"=>"WALTHALL", "name" => "WALTHALL"]);
        MsCounty::create(["county_id"=>"WARREN", "name" => "WARREN"]);
        MsCounty::create(["county_id"=>"WASHINGTON", "name" => "WASHINGTON"]);
        MsCounty::create(["county_id"=>"WAYNE", "name" => "WAYNE"]);
        MsCounty::create(["county_id"=>"WEBSTER", "name" => "WEBSTER"]);
        MsCounty::create(["county_id"=>"WILKINSON", "name" => "WILKINSON"]);
        MsCounty::create(["county_id"=>"WINSTON", "name" => "WINSTON"]);
        MsCounty::create(["county_id"=>"YALOBUSHA", "name" => "YALOBUSHA"]);
        MsCounty::create(["county_id"=>"YAZOO", "name" => "YAZOO"]);

       }

}
