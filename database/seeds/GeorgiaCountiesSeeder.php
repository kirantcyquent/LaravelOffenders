<?php

use App\GaCounty;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class GeorgiaCountiesSeeder extends DatabaseSeeder
{

    public function run()
    {
        DB::table('ga_counties')->delete();

        GaCounty::create(["county_id" => "44", "name" => "APPLING", 'pages' => 4]);
        GaCounty::create(["county_id" => "50", "name" => "ATKINSON", 'pages' => 3]);
        GaCounty::create(["county_id" => "51", "name" => "BACON", 'pages' => 4]);
        GaCounty::create(["county_id" => "52", "name" => "BAKER", 'pages' => 2]);
        GaCounty::create(["county_id" => "53", "name" => "BALDWIN", 'pages' => 10]);
        GaCounty::create(["county_id" => "54", "name" => "BANKS", 'pages' => 5]);
        GaCounty::create(["county_id" => "55", "name" => "BARROW", 'pages' => 15]);
        GaCounty::create(["county_id" => "56", "name" => "BARTOW", 'pages' => 21]);
        GaCounty::create(["county_id" => "57", "name" => "BEN HILL", 'pages' => 7]);
        GaCounty::create(["county_id" => "58", "name" => "BERRIEN", 'pages' => 7]);
        GaCounty::create(["county_id" => "59", "name" => "BIBB", 'pages' => 28]);
        GaCounty::create(["county_id" => "60", "name" => "BLECKLEY", 'pages' => 4]);
        GaCounty::create(["county_id" => "61", "name" => "BRANTLEY", 'pages' => 6]);
        GaCounty::create(["county_id" => "62", "name" => "BROOKS", 'pages' => 5]);
        GaCounty::create(["county_id" => "63", "name" => "BRYAN", 'pages' => 5]);
        GaCounty::create(["county_id" => "64", "name" => "BULLOCH", 'pages' => 8]);
        GaCounty::create(["county_id" => "65", "name" => "BURKE", 'pages' => 9]);
        GaCounty::create(["county_id" => "66", "name" => "BUTTS", 'pages' => 6]);
        GaCounty::create(["county_id" => "67", "name" => "CALHOUN", 'pages' => 3]);
        GaCounty::create(["county_id" => "68", "name" => "CAMDEN", 'pages' => 8]);
        GaCounty::create(["county_id" => "69", "name" => "CANDLER", 'pages' => 6]);
        GaCounty::create(["county_id" => "70", "name" => "CARROLL", 'pages' => 26]);
        GaCounty::create(["county_id" => "71", "name" => "CATOOSA", 'pages' => 14]);
        GaCounty::create(["county_id" => "72", "name" => "CHARLTON", 'pages' => 3]);
        GaCounty::create(["county_id" => "73", "name" => "CHATHAM", 'pages' => 46]);
        GaCounty::create(["county_id" => "74", "name" => "CHATTAHOOCHEE", 'pages' => 2]);
        GaCounty::create(["county_id" => "75", "name" => "CHATTOOGA", 'pages' => 10]);
        GaCounty::create(["county_id" => "76", "name" => "CHEROKEE", 'pages' => 22]);
        GaCounty::create(["county_id" => "77", "name" => "CLARKE", 'pages' => 14]);
        GaCounty::create(["county_id" => "78", "name" => "CLAY", 'pages' => 1]);
        GaCounty::create(["county_id" => "79", "name" => "CLAYTON", 'pages' => 44]);
        GaCounty::create(["county_id" => "80", "name" => "CLINCH", 'pages' => 2]);
        GaCounty::create(["county_id" => "45", "name" => "COBB", 'pages' => 47]);
        GaCounty::create(["county_id" => "81", "name" => "COFFEE", 'pages' => 9]);
        GaCounty::create(["county_id" => "82", "name" => "COLQUITT", 'pages' => 12]);
        GaCounty::create(["county_id" => "83", "name" => "COLUMBIA", 'pages' => 9]);
        GaCounty::create(["county_id" => "84", "name" => "COOK", 'pages' => 7]);
        GaCounty::create(["county_id" => "85", "name" => "COWETA", 'pages' => 16]);
        GaCounty::create(["county_id" => "86", "name" => "CRAWFORD", 'pages' => 4]);
        GaCounty::create(["county_id" => "87", "name" => "CRISP", 'pages' => 6]);
        GaCounty::create(["county_id" => "88", "name" => "DADE", 'pages' => 4]);
        GaCounty::create(["county_id" => "89", "name" => "DAWSON", 'pages' => 4]);
        GaCounty::create(["county_id" => "90", "name" => "DECATUR", 'pages' => 10]);
        GaCounty::create(["county_id" => "47", "name" => "DEKALB", 'pages' => 91]);
        GaCounty::create(["county_id" => "91", "name" => "DODGE", 'pages' => 8]);
        GaCounty::create(["county_id" => "92", "name" => "DOOLY", 'pages' => 4]);
        GaCounty::create(["county_id" => "93", "name" => "DOUGHERTY", 'pages' => 27]);
        GaCounty::create(["county_id" => "94", "name" => "DOUGLAS", 'pages' => 20]);
        GaCounty::create(["county_id" => "95", "name" => "EARLY", 'pages' => 7]);
        GaCounty::create(["county_id" => "96", "name" => "ECHOLS", 'pages' => 1]);
        GaCounty::create(["county_id" => "97", "name" => "EFFINGHAM", 'pages' => 10]);
        GaCounty::create(["county_id" => "98", "name" => "ELBERT", 'pages' => 6]);
        GaCounty::create(["county_id" => "99", "name" => "EMANUEL", 'pages' => 7]);
        GaCounty::create(["county_id" => "100", "name" => "EVANS", 'pages' => 3]);
        GaCounty::create(["county_id" => "101", "name" => "FANNIN", 'pages' => 5]);
        GaCounty::create(["county_id" => "102", "name" => "FAYETTE", 'pages' => 4]);
        GaCounty::create(["county_id" => "103", "name" => "FLOYD", 'pages' => 23]);
        GaCounty::create(["county_id" => "104", "name" => "FORSYTH", 'pages' => 12]);
        GaCounty::create(["county_id" => "105", "name" => "FRANKLIN", 'pages' => 6]);
        GaCounty::create(["county_id" => "48", "name" => "FULTON", 'pages' => 151]);
        GaCounty::create(["county_id" => "106", "name" => "GILMER", 'pages' => 11]);
        GaCounty::create(["county_id" => "107", "name" => "GLASCOCK", 'pages' => 1]);
        GaCounty::create(["county_id" => "108", "name" => "GLYNN", 'pages' => 11]);
        GaCounty::create(["county_id" => "109", "name" => "GORDON", 'pages' => 12]);
        GaCounty::create(["county_id" => "110", "name" => "GRADY", 'pages' => 7]);
        GaCounty::create(["county_id" => "111", "name" => "GREENE", 'pages' => 4]);
        GaCounty::create(["county_id" => "46", "name" => "GWINNETT", 'pages' => 48]);
        GaCounty::create(["county_id" => "112", "name" => "HABERSHAM", 'pages' => 8]);
        GaCounty::create(["county_id" => "113", "name" => "HALL", 'pages' => 24]);
        GaCounty::create(["county_id" => "114", "name" => "HANCOCK", 'pages' => 3]);
        GaCounty::create(["county_id" => "115", "name" => "HARALSON", 'pages' => 9]);
        GaCounty::create(["county_id" => "116", "name" => "HARRIS", 'pages' => 5]);
        GaCounty::create(["county_id" => "117", "name" => "HART", 'pages' => 5]);
        GaCounty::create(["county_id" => "118", "name" => "HEARD", 'pages' => 5]);
        GaCounty::create(["county_id" => "119", "name" => "HENRY", 'pages' => 19]);
        GaCounty::create(["county_id" => "120", "name" => "HOUSTON", 'pages' => 26]);
        GaCounty::create(["county_id" => "121", "name" => "IRWIN", 'pages' => 4]);
        GaCounty::create(["county_id" => "122", "name" => "JACKSON", 'pages' => 14]);
        GaCounty::create(["county_id" => "123", "name" => "JASPER", 'pages' => 3]);
        GaCounty::create(["county_id" => "124", "name" => "JEFF DAVIS", 'pages' => 4]);
        GaCounty::create(["county_id" => "125", "name" => "JEFFERSON", 'pages' => 6]);
        GaCounty::create(["county_id" => "126", "name" => "JENKINS", 'pages' => 2]);
        GaCounty::create(["county_id" => "127", "name" => "JOHNSON", 'pages' => 4]);
        GaCounty::create(["county_id" => "128", "name" => "JONES", 'pages' => 5]);
        GaCounty::create(["county_id" => "129", "name" => "LAMAR", 'pages' => 3]);
        GaCounty::create(["county_id" => "130", "name" => "LANIER", 'pages' => 4]);
        GaCounty::create(["county_id" => "131", "name" => "LAURENS", 'pages' => 14]);
        GaCounty::create(["county_id" => "132", "name" => "LEE", 'pages' => 4]);
        GaCounty::create(["county_id" => "133", "name" => "LIBERTY", 'pages' => 13]);
        GaCounty::create(["county_id" => "134", "name" => "LINCOLN", 'pages' => 3]);
        GaCounty::create(["county_id" => "135", "name" => "LONG", 'pages' => 5]);
        GaCounty::create(["county_id" => "136", "name" => "LOWNDES", 'pages' => 21]);
        GaCounty::create(["county_id" => "137", "name" => "LUMPKIN", 'pages' => 6]);
        GaCounty::create(["county_id" => "140", "name" => "MACON", 'pages' => 3]);
        GaCounty::create(["county_id" => "141", "name" => "MADISON", 'pages' => 7]);
        GaCounty::create(["county_id" => "142", "name" => "MARION", 'pages' => 4]);
        GaCounty::create(["county_id" => "138", "name" => "MCDUFFIE", 'pages' => 7]);
        GaCounty::create(["county_id" => "139", "name" => "MCINTOSH", 'pages' => 4]);
        GaCounty::create(["county_id" => "143", "name" => "MERIWETHER", 'pages' => 8]);
        GaCounty::create(["county_id" => "144", "name" => "MILLER", 'pages' => 2]);
        GaCounty::create(["county_id" => "145", "name" => "MITCHELL", 'pages' => 9]);
        GaCounty::create(["county_id" => "146", "name" => "MONROE", 'pages' => 5]);
        GaCounty::create(["county_id" => "147", "name" => "MONTGOMERY", 'pages' => 3]);
        GaCounty::create(["county_id" => "148", "name" => "MORGAN", 'pages' => 3]);
        GaCounty::create(["county_id" => "149", "name" => "MURRAY", 'pages' => 10]);
        GaCounty::create(["county_id" => "150", "name" => "MUSCOGEE", 'pages' => 40]);
        GaCounty::create(["county_id" => "151", "name" => "NEWTON", 'pages' => 20]);
        GaCounty::create(["county_id" => "152", "name" => "OCONEE", 'pages' => 2]);
        GaCounty::create(["county_id" => "153", "name" => "OGLETHORPE", 'pages' => 4]);
        GaCounty::create(["county_id" => "154", "name" => "PAULDING", 'pages' => 21]);
        GaCounty::create(["county_id" => "155", "name" => "PEACH", 'pages' => 5]);
        GaCounty::create(["county_id" => "156", "name" => "PICKENS", 'pages' => 6]);
        GaCounty::create(["county_id" => "157", "name" => "PIERCE", 'pages' => 3]);
        GaCounty::create(["county_id" => "158", "name" => "PIKE", 'pages' => 3]);
        GaCounty::create(["county_id" => "159", "name" => "POLK", 'pages' => 11]);
        GaCounty::create(["county_id" => "160", "name" => "PULASKI", 'pages' => 3]);
        GaCounty::create(["county_id" => "161", "name" => "PUTNAM", 'pages' => 5]);
        GaCounty::create(["county_id" => "162", "name" => "QUITMAN", 'pages' => 2]);
        GaCounty::create(["county_id" => "163", "name" => "RABUN", 'pages' => 3]);
        GaCounty::create(["county_id" => "164", "name" => "RANDOLPH", 'pages' => 3]);
        GaCounty::create(["county_id" => "165", "name" => "RICHMOND", 'pages' => 43]);
        GaCounty::create(["county_id" => "166", "name" => "ROCKDALE", 'pages' => 10]);
        GaCounty::create(["county_id" => "167", "name" => "SCHLEY", 'pages' => 2]);
        GaCounty::create(["county_id" => "168", "name" => "SCREVEN", 'pages' => 5]);
        GaCounty::create(["county_id" => "169", "name" => "SEMINOLE", 'pages' => 3]);
        GaCounty::create(["county_id" => "170", "name" => "SPALDING", 'pages' => 17]);
        GaCounty::create(["county_id" => "171", "name" => "STEPHENS", 'pages' => 8]);
        GaCounty::create(["county_id" => "172", "name" => "STEWART", 'pages' => 3]);
        GaCounty::create(["county_id" => "173", "name" => "SUMTER", 'pages' => 8]);
        GaCounty::create(["county_id" => "174", "name" => "TALBOT", 'pages' => 2]);
        GaCounty::create(["county_id" => "175", "name" => "TALIAFERRO", 'pages' => 1]);
        GaCounty::create(["county_id" => "176", "name" => "TATTNALL", 'pages' => 7]);
        GaCounty::create(["county_id" => "177", "name" => "TAYLOR", 'pages' => 3]);
        GaCounty::create(["county_id" => "178", "name" => "TELFAIR", 'pages' => 4]);
        GaCounty::create(["county_id" => "179", "name" => "TERRELL", 'pages' => 4]);
        GaCounty::create(["county_id" => "180", "name" => "THOMAS", 'pages' => 13]);
        GaCounty::create(["county_id" => "181", "name" => "TIFT", 'pages' => 5]);
        GaCounty::create(["county_id" => "182", "name" => "TOOMBS", 'pages' => 6]);
        GaCounty::create(["county_id" => "183", "name" => "TOWNS", 'pages' => 2]);
        GaCounty::create(["county_id" => "184", "name" => "TREUTLEN", 'pages' => 3]);
        GaCounty::create(["county_id" => "185", "name" => "TROUP", 'pages' => 24]);
        GaCounty::create(["county_id" => "186", "name" => "TURNER", 'pages' => 4]);
        GaCounty::create(["county_id" => "187", "name" => "TWIGGS", 'pages' => 3]);
        GaCounty::create(["county_id" => "188", "name" => "UNION", 'pages' => 4]);
        GaCounty::create(["county_id" => "189", "name" => "UPSON", 'pages' => 12]);
        GaCounty::create(["county_id" => "190", "name" => "WALKER", 'pages' => 14]);
        GaCounty::create(["county_id" => "191", "name" => "WALTON", 'pages' => 17]);
        GaCounty::create(["county_id" => "192", "name" => "WARE", 'pages' => 10]);
        GaCounty::create(["county_id" => "193", "name" => "WARREN", 'pages' => 2]);
        GaCounty::create(["county_id" => "194", "name" => "WASHINGTON", 'pages' => 5]);
        GaCounty::create(["county_id" => "195", "name" => "WAYNE", 'pages' => 7]);
        GaCounty::create(["county_id" => "196", "name" => "WEBSTER", 'pages' => 1]);
        GaCounty::create(["county_id" => "197", "name" => "WHEELER", 'pages' => 4]);
        GaCounty::create(["county_id" => "198", "name" => "WHITE", 'pages' => 6]);
        GaCounty::create(["county_id" => "199", "name" => "WHITFIELD", 'pages' => 22]);
        GaCounty::create(["county_id" => "200", "name" => "WILCOX", 'pages' => 4]);
        GaCounty::create(["county_id" => "201", "name" => "WILKES", 'pages' => 3]);
        GaCounty::create(["county_id" => "202", "name" => "WILKINSON", 'pages' => 4]);
        GaCounty::create(["county_id" => "203", "name" => "WORTH", 'pages' => 7]);
    }

}
