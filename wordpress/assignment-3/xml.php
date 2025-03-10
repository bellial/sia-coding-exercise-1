<?php
namespace SportsAPI;

use PDO;
use SimpleXMLElement;

abstract class APIClient {
    protected string $apiUrl;
    protected PDO $db;

    public function __construct(string $apiUrl, PDO $db) {
        $this->apiUrl = $apiUrl;
        $this->db = $db;
    }

    abstract public function fetchData(): SimpleXMLElement;
}

class SportsDataFetcher extends APIClient {
    public function fetchData(): SimpleXMLElement {
        $xmlData = file_get_contents('data.xml');
        return new SimpleXMLElement($xmlData);
    }
}

class SportsDataProcessor {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function processAndStore(SimpleXMLElement $xml): void {
        foreach ($xml->{'team-sport-content'} as $sportContent) {
            $sport = $sportContent->sport;
            $sportId = $this->storeSport((string)$sport->id, (string)$sport->name);
            
            foreach ($sportContent->{'league-content'} as $leagueContent) {
                $league = $leagueContent->league;
                $leagueId = $this->storeLeague((string)$league->id, (string)$league->name, (string)$league->{'name'}[1] ?? '');
                
                foreach ($leagueContent->{'season-content'} as $seasonContent) {
                    $season = $seasonContent->season;
                    $seasonId = $this->storeSeason((string)$season->id, (string)$season->name, (string)$season->details->{'start-date'}, (string)$season->details->{'end-date'});
                    
                    foreach ($seasonContent->competition as $competition) {
                        $competitionId = $this->storeCompetition((string)$competition->id, (string)$competition->{'start-date'}, (string)$competition->name, (string)$competition->{'result-scope'}->{'competition-status'});
                    }
                }
            }
        }
    }

    private function storeSport(string $id, string $name): int {
        $stmt = $this->db->prepare("INSERT INTO sports (id, name) VALUES (?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name)");
        $stmt->execute([$id, $name]);
        return $this->db->lastInsertId();
    }

    private function storeLeague(string $id, string $name, string $nickname): int {
        $stmt = $this->db->prepare("INSERT INTO leagues (id, name, nickname) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name), nickname=VALUES(nickname)");
        $stmt->execute([$id, $name, $nickname]);
        return $this->db->lastInsertId();
    }

    private function storeSeason(string $id, string $name, string $startDate, string $endDate): int {
        $stmt = $this->db->prepare("INSERT INTO seasons (id, name, start_date, end_date) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name), start_date=VALUES(start_date), end_date=VALUES(end_date)");
        $stmt->execute([$id, $name, $startDate, $endDate]);
        return $this->db->lastInsertId();
    }

    private function storeCompetition(string $id, string $startDate, string $name, string $status): int {
        $stmt = $this->db->prepare("INSERT INTO competitions (id, start_date, name, status) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name), status=VALUES(status)");
        $stmt->execute([$id, $startDate, $name, $status]);
        return $this->db->lastInsertId();
    }
}

// Example usage
$pdo = new PDO("mysql:host=localhost;dbname=sports_db", "root", "password", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$fetcher = new SportsDataFetcher("https://api.example.com/feeds", $pdo);
$processor = new SportsDataProcessor($pdo);

$xmlData = $fetcher->fetchData();
$processor->processAndStore($xmlData);
