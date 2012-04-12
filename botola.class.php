<?php

class GNF {

    private $Equipes = null;
    private $GNF = null;

    function GNF($l) {

        switch ($l) {
            case 1:$url = "http://www.arryadia.com/index.php?option=com_joomleague&view=ranking&p=1&type=0&Itemid=27";
                break;
            case 2:$url = "http://www.arryadia.com/index.php?option=com_joomleague&view=ranking&p=4&type=0&Itemid=32";
                break;
            default : throw new Exception("GNF" . $l . " Not Found");
        }

        $this->GNF = $l;

        $h = curl_init($url);
        curl_setopt($h, CURLOPT_HEADER, 0);
        curl_setopt($h, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($h, CURLOPT_NOBODY, FALSE);
        $ree = curl_exec($h);
        curl_close($h);
        $this->Equipes = array();
        preg_match_all("/class=\"jl_rankingheader\">Class.<\/a>([^`]*?)<\/table>/", $ree, $f);
        preg_match_all("/<tr class=\"sectiontableentry[\d]*\">([^`]*?)<\/tr>/", $f[1][0], $ListeEn);

        foreach ($ListeEn[1] as $sEq) {
            $info = array();
            preg_match_all("/<td class=\"rankingrow_rank\"[^>]*>([^<]*)<\/td>[^`]*<img src=\"([^\"]*)[^`]*<td class=\"rankingrow_team\"[^>]*>([^<]*)[^`]*<td class=\"rankingrow_points\"[^>]*>(\d*)<\/td>[^`]*<td class=\"rankingrow_played\"[^>]*>([^<]*)[^`]*<td class=\"rankingrow\"[^>]*>(\d*)<\/td>[^`]*<td class=\"rankingrow\"[^>]*>(\d*)<\/td>[^`]*<td class=\"rankingrow\"[^>]*>(\d*)<\/td>[^`]*<td class=\"rankingrow\"[^>]*>([^<]*)<\/td>[^`]*<td class=\"rankingrow\"[^>]*>(\d*)<\/td>[^`]*<td class=\"rankingrow\"[^>]*>([^<]*)<\/td>/", $sEq, $R);


            $info['Classement'] = $R[1][0];
            $info['LogoLink'] = 'http://www.arryadia.com' . $R[2][0];
            $info['Nom'] = $R[3][0];
            $info['PTS'] = $R[4][0];
            $info['J'] = $R[5][0];
            $info['G'] = $R[6][0];
            $info['N'] = $R[7][0];
            $info['P'] = $R[8][0];
            $info['BP'] = $R[9][0];
            $info['BC'] = $R[10][0];
            $info['DIFF'] = $R[11][0];
            ;
            $this->Equipes[] = $info;
        }
    }

    function GNFEquipe($Classement) {
        return $this->Equipes[($Classement - 1)];
    }

    function GNFMatches($j) {
        switch ($this->GNF) {
            case 2:$url = "http://www.arryadia.com/index.php?option=com_joomleague&view=results&p=4&r=" . ($j + 62);
                break;
            case 1:$url = "http://www.arryadia.com/index.php?option=com_joomleague&view=results&p=1&r=" . $j;
                break;
            default: throw new Exception("GNF" . $l . " Not Found");
        }

        $h = curl_init($url);
        curl_setopt($h, CURLOPT_HEADER, 0);
        curl_setopt($h, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($h, CURLOPT_NOBODY, FALSE);
        $ree = curl_exec($h);
        curl_close($h);

        preg_match_all("/<div id=\"jlg_ranking_table\" align=\"center\">([^`]*?)<\/div>/", $ree, $f);
        preg_match_all("/<!-- DATE HEADER -->([^`]*?)<!-- GAMES END -->/", $f[1][0], $R);

        $Res = array();
        foreach ($R[1] as $sJour) {
            $Info = array();
            preg_match_all("/<th colspan=\"12\">([^<]*)[^`]*<\/th>/", $sJour, $F);
            $Info['Date'] = trim($F[1][0]);

            preg_match_all("/<tr class=\"result[^>]*>([^`]*?)<\/tr>/", $sJour, $F);

            foreach ($F[1] as $sM) {
                preg_match_all("/<td width='20'><img src=\"([^\"]*)\" alt=\"([^\"]*)\"[^`]*<td>([^<]*)<\/td>[^`]*<img src=\"([^\"]*)\" alt=\"([^\"]*)\"[^`]*<td>([^<]*)<\/td>[^`]*<td width='10' class='score' nowrap='nowrap'>([^<]*)<\/td>/", $sM, $sF);

                $Info['Match']['Equipe1'] = array();
                $Info['Match']['Equipe1']['Logo'] = "http://www.arryadia.com" . $sF[1][0];
                $Info['Match']['Equipe1']['ABV'] = trim($sF[2][0]);
                $Info['Match']['Equipe1']['Nom'] = trim($sF[3][0]);

                $Info['Match']['Equipe2'] = array();
                $Info['Match']['Equipe2']['Logo'] = "http://www.arryadia.com" . trim($sF[4][0]);
                $Info['Match']['Equipe2']['ABV'] = trim($sF[5][0]);
                $Info['Match']['Equipe2']['Nom'] = trim($sF[6][0]);

                $Sc = explode("-", $sF[7][0]);

                $Info['Match']['Equipe1']['Score'] = preg_replace("/[^0-9]/", '', $Sc[0]);
                $Info['Match']['Equipe2']['Score'] = preg_replace("/[^0-9]/", '', $Sc[1]);
            }


            $Res[] = $Info;
        }

        return $Res;
    }

    public function getEquipes() {
        return $this->Equipes;
    }

    public function getGNF() {
        return $this->GNF;
    }

}

?>