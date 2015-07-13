<?php

use App\Sexoffender;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SexoffenderSeeder extends DatabaseSeeder
{

    public function run()
    {
        DB::table('sexoffenders')->delete();

        Sexoffender::create(['state_name' => "Alaska", "state_code" => "AK", "state_url" => "http://www.dps.state.ak.us/Sorweb/Search.aspx"]);
        Sexoffender::create(['state_name' => "Alabama", "state_code" => "AL", "state_url" => "http://app.alea.gov/Community/wfSexOffenderSearch.aspx"]);
        Sexoffender::create(['state_name' => "Arkansas", "state_code" => "AR", "state_url" => "http://acic.org/offender-search/index.php"]);
        Sexoffender::create(['state_name' => "Arizona", "state_code" => "AZ", "state_url" => "https://az.gov/app/sows/home.xhtml"]);
        Sexoffender::create(['state_name' => "California", "state_code" => "CA", "state_url" => "http://www.meganslaw.ca.gov/disclaimer.aspx?lang=ENGLISH"]);
        Sexoffender::create(['state_name' => "Colorado", "state_code" => "CO", "state_url" => "https://www.colorado.gov/apps/cdps/sor/?SOR=offender.list&category=SVP"]);
        Sexoffender::create(['state_name' => "Connecticut", "state_code" => "CT", "state_url" => "http://www.communitynotification.com/cap_office_disclaimer.php?office=54567"]);
        Sexoffender::create(['state_name' => "DC", "state_code" => "DC", "state_url" => "http://sexoffender.dc.gov/getOffenders.aspx?type=list"]);
        Sexoffender::create(['state_name' => "Delaware", "state_code" => "DE", "state_url" => "https://sexoffender.dsp.delaware.gov/", "crawl_state" => "stopped"]);
        Sexoffender::create(['state_name' => "Florida", "state_code" => "FL", "state_url" => "http://offender.fdle.state.fl.us/offender/offenderSearchNav.do?link=advanced"]);
        Sexoffender::create(['state_name' => "Georgia", "state_code" => "GA", "state_url" => "http://state.sor.gbi.ga.gov/Sort_Public/", "crawl_state" => "stopped"]);
        Sexoffender::create(['state_name' => "Hawaii", "state_code" => "HI", "state_url" => "http://sexoffenders.ehawaii.gov/sexoffender/welcome.html;jsessionid=8C31909DC5D71B2844208D16A2179595.liona"]);
        Sexoffender::create(['state_name' => "Iowa", "state_code" => "IA", "state_url" => "http://www.iowasexoffender.com/search"]);
        Sexoffender::create(['state_name' => "Idaho", "state_code" => "ID", "state_url" => "http://www.isp.idaho.gov/sor_id/search.html"]);
        Sexoffender::create(['state_name' => "Illinois", "state_code" => "IL", "state_url" => "http://www.isp.state.il.us/sor/sor.cfm"]);
        Sexoffender::create(['state_name' => "Indiana", "state_code" => "IN", "state_url" => "http://www.icrimewatch.net/index.php?AgencyID=54663"]);
        Sexoffender::create(['state_name' => "Kansas", "state_code" => "KS", "state_url" => "http://www.kbi.ks.gov/registeredoffender/", "crawl_state" => "stopped"]);
        Sexoffender::create(['state_name' => "Kentucky", "state_code" => "KY", "state_url" => "http://kspsor.state.ky.us/sor/html/SORSearch.htm"]);
        Sexoffender::create(['state_name' => "Louisiana", "state_code" => "LA", "state_url" => "http://www.icrimewatch.net/index.php?AgencyID=54450"]);
        Sexoffender::create(['state_name' => "Massachusetts", "state_code" => "MA", "state_url" => "http://sorb.chs.state.ma.us/"]);
        Sexoffender::create(['state_name' => "Maryland", "state_code" => "MD", "state_url" => "http://www.dpscs.state.md.us/sorSearch/search.do"]);
        Sexoffender::create(['state_name' => "Maine", "state_code" => "ME", "state_url" => "http://sor.informe.org/cgi-bin/sor/index.pl"]);
        Sexoffender::create(['state_name' => "Michigan", "state_code" => "MI", "state_url" => "http://www.mipsor.state.mi.us/"]);
        Sexoffender::create(['state_name' => "Minnesota", "state_code" => "MN", "state_url" => "http://www.doc.state.mn.us/level3/search.asp"]);
        Sexoffender::create(['state_name' => "Missouri", "state_code" => "MO", "state_url" => "http://www.mshp.dps.mo.gov/CJ38/searchRegistry.jsp"]);
        Sexoffender::create(['state_name' => "Mississippi", "state_code" => "MS", "state_url" => "http://state.sor.dps.ms.gov/ConditionsOfUse.aspx", "crawl_state" => "stopped"]);
        Sexoffender::create(['state_name' => "Montana", "state_code" => "MT", "state_url" => "http://svcalt.mt.gov/svor/search.asp"]);
        Sexoffender::create(['state_name' => "North Carolina", "state_code" => "NC", "state_url" => "http://sexoffender.ncdoj.gov/disclaimer.aspx"]);
        Sexoffender::create(['state_name' => "North Dakota", "state_code" => "ND", "state_url" => "http://www.sexoffender.nd.gov/"]);
        Sexoffender::create(['state_name' => "Nebraska", "state_code" => "NE", "state_url" => "https://sor.nebraska.gov/"]);
        Sexoffender::create(['state_name' => "New Hampshire", "state_code" => "NH", "state_url" => "http://www4.egov.nh.gov/nsor/"]);
        Sexoffender::create(['state_name' => "New Jersey", "state_code" => "NJ", "state_url" => "https://www16.state.nj.us/LPS_spoff/SetSession"]);
        Sexoffender::create(['state_name' => "New Mexico", "state_code" => "NM", "state_url" => "http://sheriffalerts.com/cap_office_disclaimer.php?office=55290&fwd=aHR0cDovL2NvbW11bml0eW5vdGlmaWNhdGlvbi5jb20vY2FwX21haW4ucGhwP29mZmljZT01NTI5MA=="]);
        Sexoffender::create(['state_name' => "Nevada", "state_code" => "NV", "state_url" => "http://www.nvsexoffenders.gov/Search.aspx"]);
        Sexoffender::create(['state_name' => "New York", "state_code" => "NY", "state_url" => "http://www.criminaljustice.ny.gov/SomsSUBDirectory/search_index.jsp"]);
        Sexoffender::create(['state_name' => "Ohio", "state_code" => "OH", "state_url" => "http://www.icrimewatch.net/index.php?AgencyID=55149"]);
        Sexoffender::create(['state_name' => "Oklahoma", "state_code" => "OK", "state_url" => "http://sors.doc.state.ok.us/svor/f?p=106:1:0::NO:::"]);
        Sexoffender::create(['state_name' => "Oregon", "state_code" => "OR", "state_url" => "http://sexoffenders.oregon.gov/SorPublic/Web.dll/main?S=29897005425&cmd=SHOW_BULLETIN&I=4956"]);
        Sexoffender::create(['state_name' => "Pennsylvania", "state_code" => "PA", "state_url" => "http://www.pameganslaw.state.pa.us/EntryPage.aspx?returnstate_url=~/SearchCounty.aspx"]);
        Sexoffender::create(['state_name' => "Rhode Island", "state_code" => "RI", "state_url" => "http://www.paroleboard.ri.gov/sexoffender/agree.php"]);
        Sexoffender::create(['state_name' => "South Carolina", "state_code" => "SC", "state_url" => "http://www.icrimewatch.net/index.php?AgencyID=54575"]);
        Sexoffender::create(['state_name' => "South Dakota", "state_code" => "SD", "state_url" => "http://sor.sd.gov/"]);
        Sexoffender::create(['state_name' => "Tennessee", "state_code" => "TN", "state_url" => "http://www.tbi.tn.gov/sorint/somainpg.aspx"]);
        Sexoffender::create(['state_name' => "Texas", "state_code" => "TX", "state_url" => "https://records.txdps.state.tx.us/SexOffender/index.aspx"]);
        Sexoffender::create(['state_name' => "Utah", "state_code" => "UT", "state_url" => "http://sheriffalerts.com/cap_office_disclaimer.php?office=54438"]);
        Sexoffender::create(['state_name' => "Virginia", "state_code" => "VA", "state_url" => "http://sex-offender.vsp.virginia.gov/sor/policy.html?original_requeststate_url=http%3A%2F%2Fsex-offender.vsp.virginia.gov%2Fsor%2FzipSearch.html&original_request_method=GET&original_request_parameters="]);
        Sexoffender::create(['state_name' => "Vermont", "state_code" => "VT", "state_url" => "http://www.communitynotification.com/cap_office_disclaimer.php?office=55275"]);
        Sexoffender::create(['state_name' => "Washington", "state_code" => "WA", "state_url" => "http://www.icrimewatch.net/index.php?AgencyID=54528"]);
        Sexoffender::create(['state_name' => "Wisconsin", "state_code" => "WI", "state_url" => "http://offender.doc.state.wi.us/public/"]);
        Sexoffender::create(['state_name' => "West Virginia", "state_code" => "WV", "state_url" => "https://apps.wv.gov/StatePolice/SexOffender/Disclaimer?continueTostate_url=http%3A%2F%2Fapps.wv.gov%2FStatePolice%2FSexOffender"]);
        Sexoffender::create(['state_name' => "Wyoming", "state_code" => "WY", "state_url" => "http://wysors.dci.wyo.gov/sor/"]);
    }

}
